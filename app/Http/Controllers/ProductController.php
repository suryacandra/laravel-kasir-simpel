<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public $messages = [
        'name.required' => 'Tolong isi nama produk',
        'image.image' => 'Tolong pilih file berupa gambar dengan ekstensi: jpeg, png',
        'image.mimes' => 'Tolong pilih file berupa gambar dengan ekstensi: jpeg, png',
        'price.required' => 'Tolong isi harga produk',
        'price.numeric' => 'Nominal harga yang dimasukkan harus berupa angka',
        'quantity.required' => 'Tolong isi stok produk',
        'quantity.numeric' => 'Stok yang dimasukkan harus berupa angka',
    ];

    public function index()
    {
        $products = Product::all();

        return view('products.index', ['products' => $products]);
    }

    public function create()
    {
        return view('products.create');
    }

    public function edit(Product $product)
    {
        return view('products.edit', ['product' => $product]);
    }

    public function store(Request $request)
    {
        Validator::make($request->all(), [
            'name' => 'required',
            'image' => 'sometimes|image|mimes:jpeg,png',
            'price' => 'required|numeric',
            'quantity' => 'required|numeric',
        ], $this->messages)->validate();

        $product = new Product([
            'name' => $request->name,
            'price' => $request->price,
            'quantity' => $request->quantity,
        ]);

        if ($request->hasFile('image')) {
            $path = Storage::disk('public')->put('/products', $request->image);
            $product->image_url = $path;
        }

        $product->save();

        return redirect('/products');
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required',
            'image' => 'sometimes|mimes:jpg,png',
            'price' => 'required|numeric',
            'quantity' => 'required|numeric',
        ]);

        $product->name = $request->name;
        $product->price = $request->price;
        $product->price = $request->price;
        $product->quantity = $request->quantity;

        if ($request->hasFile('image')) {
            $path = Storage::disk('public')->put('/products', $request->image);
            $product->image_url = $path;
        }

        $product->save();

        return redirect('/products');
    }

    public function destroy(Product $product)
    {
        if (TransactionDetail::query()->where('product_id', $product->id)->first())
            return redirect('/products')->with('errorMessage', 'Produk tidak dapat dihapus karena sudah digunakan untuk transaksi');

        $product->delete();

        return redirect('/products');
    }
}
