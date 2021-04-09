@extends('layouts.app')

@section('content')
<div class="container">
    <a class="btn btn-primary mb-2" href="/checkout">Riwayat Transaksi</a>
    <a class="btn btn-primary mb-2" href="/">Buat Transaksi Baru</a>
    <div class="card">
        <div class="card-body">
            <h4>Pembelian Berhasil</h4>
            <hr>
            <table class="mb-3">
                <tr>
                    <th>Nama Customer</th>
                    <th>:</th>
                    <td>{{ $transaction->customer_name }}</td>
                </tr>
                <tr>
                    <th>Tanggal Transaksi</th>
                    <th>:</th>
                    <td>{{ $transaction->created_at }}</td>
                </tr>
            </table>
            <table class="table table-bordered mb-0">
                <thead>
                    <tr>
                        <th>Gambar</th>
                        <th>Nama Item</th>
                        <th>Quantity</th>
                        <th class="text-right">Harga</th>
                        <th class="text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($transaction->details as $detail)
                    <tr>
                        <td class="fit">
                            @if (strlen($detail->product->image_url) > 0)
                            <img src="{{ asset('storage/' . $detail->product->image_url) }}" alt="">
                            @else
                            -
                            @endif
                        </td>
                        <td>{{ $detail->product->name }}</td>
                        <td>{{ $detail->quantity }}</td>
                        <td class="text-right">{{ number_format($detail->price) }}</td>
                        <td class="text-right">{{ number_format($detail->quantity * $detail->price) }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="h4">
                        <td class="text-right" colspan="4">Total Pembelian</td>
                        <td class="text-right">{{ number_format($transaction->total) }}</td>
                    </tr>
                    <tr>
                        <td class="text-right" colspan="4">Total Pembayaran</td>
                        <td class="text-right">{{ number_format($transaction->payment) }}</td>
                    </tr>
                    <tr>
                        <td class="text-right" colspan="4">Total Kembalian</td>
                        <td class="text-right">{{ number_format($transaction->payment - $transaction->total) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection