@extends('layouts.app')

@section('content')
<div class="container">
    <a class="btn btn-primary mb-2" href="/">Kembali</a>
    <div class="card">
        <div class="card-body">
            <h4 class="my-auto">Riwayat Transaksi</h4>
            <hr>
            <table class="table table-bordered mb-0">
                <thead>
                    <tr>
                        <th>Nama Pembeli</th>
                        <th>Total</th>
                        <th>Pembayaran</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($transactions as $transaction)
                    <tr>
                        <td>{{ $transaction->customer_name }}</td>
                        <td>{{ number_format($transaction->total) }}</td>
                        <td>{{ number_format($transaction->payment) }}</td>
                        <td class="fit">
                            <a class="btn btn-primary" href="/checkout/{{ $transaction->id }}">Detil</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection