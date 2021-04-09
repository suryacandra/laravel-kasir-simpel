<?php

namespace App\Http\Livewire;

use App\Models\Cart;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;

class Cashier extends Component
{
    public $products,
        $tempCart,
        $cart,
        $total,
        $customerName,
        $payment,
        $change;

    public function saveCart(Product $product)
    {
        $cart = Cart::query()->where('product_id', $product->id)->first();

        $quantity = &$this->tempCart[$product->id];

        if ($cart) {
            if ($quantity === "") $quantity = 0;

            if (($product->quantity + $cart->quantity) - $quantity < 0) {
                $this->tempCart[$product->id] = ($product->quantity + $cart->quantity);
                return;
            }

            if ($quantity > 0) {
                $cart->quantity = $quantity;

                $cart->save();
            } else {
                $this->tempCart[$product->id] = 0;
                $cart->delete();
            }
        } else {
            if ($product->quantity - $quantity < 0) {
                $this->tempCart[$product->id] = 0;
                return;
            }

            if ($quantity > 0) {
                $cart = new Cart();

                $cart->product_id = $product->id;
                $cart->quantity = $quantity;
                $cart->price = $product->price;

                $cart->save();
            } else {
                $this->tempCart[$product->id] = 0;
            }
        }
    }

    public function validateCheckout()
    {
        $this->payment = (float) $this->payment;

        $this->validate(
            ['customerName' => 'required', 'payment' => 'required|numeric|min:' . $this->total],
            [
                'customerName.required' => 'Tolong isi nama customer sebelum checkout',
                'payment.required' => 'Tolong isi nominal pembayaran sebelum checkout',
                'payment.numeric' => 'Nominal pembayaran yang dimasukkan harus berupa angka',
                'payment.min' => 'Nominal pembayaran yang dimasukkan kurang dari total pembelian',
            ]
        );
    }

    public function reduceProductStock(Product $product, $quantity)
    {
        if ($quantity > $product->quantity) {
            return false;
        }

        $product->quantity -= $quantity;

        $product->save();
    }

    public function checkout()
    {
        $this->validateCheckout();

        DB::beginTransaction();

        $transaction = new Transaction();

        $transaction->customer_name = $this->customerName;
        $transaction->total = $this->total;
        $transaction->payment = $this->payment;

        $transaction->save();

        $cart = Cart::all();

        Cart::query()->delete();

        foreach ($cart as $item) {
            $transactionDetail = TransactionDetail::query()->create([
                'transaction_id' => $transaction->id,
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'price' => $item->price,
            ]);

            $this->reduceProductStock(
                Product::query()->find($transactionDetail->product_id),
                $transactionDetail->quantity
            );
        }

        $this->customerName = null;
        $this->payment = null;
        $this->change = null;
        $this->tempCart = null;

        DB::commit();

        return redirect('/checkout/' . $transaction->id);
    }

    /**
     * Dijalankan pertama kali dan hanya satu kali
     *
     * @return void
     */
    public function mount()
    {
        $this->change = 0;
    }

    /**
     * Dijalankan setiap ada perubahan data
     *
     * @return void
     */
    public function render()
    {
        $this->cart = Cart::with('product')->get();
        $this->total = 0;

        $productsInCart = [];

        /**
         * Menambah total pembelian
         */
        foreach ($this->cart as $cart) {
            $this->tempCart[$cart->product_id] = $this->tempCart[$cart->product_id] ?? $cart->quantity;

            if (isset($this->tempCart[$cart->product_id]) && $this->tempCart[$cart->product_id] > 0)
                $productsInCart[] = $cart->product_id;

            $this->total += $cart->quantity * $cart->price;
        }

        $this->products = Product::query()->whereIn('id', $productsInCart)->orWhere('quantity', '>', 0)->get();

        $this->payment = preg_replace('/[^0-9]/', '', $this->payment);

        if ($this->payment > $this->total)
            $this->change = $this->payment - $this->total;
        else
            $this->change = 0;

        return view('livewire.cashier');
    }

    public function clearCart()
    {
        $this->tempCart = [];
        $this->cart = [];
        Cart::query()->delete();
    }
}
