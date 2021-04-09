<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use \PDF;

class CheckoutController extends Controller
{
    public function index()
    {
        $transactions = Transaction::all();

        return view('checkout.index', ['transactions' => $transactions]);
    }

    public function show(Transaction $transaction)
    {
        return view('checkout.show', ['transaction' => $transaction]);
    }

}
