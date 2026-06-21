@extends('layouts.master')

@section('content')
<div class="container py-5">

    <h3 class="mb-4">Riwayat Pembelian</h3>

    <h5 class="mb-3">Pesanan Produk</h5>

    @forelse($riwayat_pesanan as $pesanan)
        <div class="card mb-3 p-3">
            <div class="fw-bold">#DWJ-{{ $pesanan->id }}</div>
            <div>Tanggal: {{ $pesanan->tanggal }}</div>
            <div>Total: Rp {{ number_format($pesanan->jumlah_harga,0,',','.') }}</div>
        </div>
    @empty
        <p class="text-muted">Belum ada pesanan.</p>
    @endforelse

    <hr>

    <h5 class="mb-3">Custom Order</h5>

    @forelse($custom_orders as $custom)
        <div class="card mb-3 p-3">
            <div class="fw-bold">{{ $custom->jenis_furniture }}</div>
            <div>Status: {{ $custom->status }}</div>
        </div>
    @empty
        <p class="text-muted">Belum ada custom order.</p>
    @endforelse

</div>
@endsection