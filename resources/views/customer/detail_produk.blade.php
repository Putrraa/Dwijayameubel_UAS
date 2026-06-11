@extends('layouts.master')

{{-- Variabel $data kini tersedia di sini --}}
@section('title', $data->nama_barang . ' Detail') 

@section('content')
<div class="container" style="padding-top: 50px; padding-bottom: 50px;">
    
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="row">
        
        <div class="col-lg-6">
            <img src="{{ asset('storage/' . $data->gambar) }}" class="img-fluid" alt="{{ $data->nama_barang }}">
            <h1 class="mt-3">{{ $data->nama_barang }}</h1>
            <p class="lead">Rp. {{ number_format($data->harga, 0, ',', '.') }}</p>
            <p>Stok Tersedia: <strong>{{ $data->stok }}</strong></p>
        </div>

        <div class="col-lg-6">
            <h3 class="mb-4">Pilih Jumlah dan Pesan</h3>
            
            @if ($data->stok > 0)
                {{-- Form POST ke route customer.pesan --}}
               <form action="{{ route('pesan', $data->id) }}" method="POST">
                @csrf
                <input type="number"
                    name="jumlah_pesan"
                    min="1"
                    max="{{ $data->stok }}"
                    class="form-control"
                    value="1"
                    required>

                <button type="submit" class="btn btn-success mt-2">
                    Masukkan Keranjang
                </button>
            </form>

            @else
                <p class="text-danger">Stok habis.</p>
            @endif
            
            <a href="{{ route('customer.shop') }}" class="btn btn-secondary mt-5">Kembali ke Shop</a>
        </div>
    </div>
</div>
@endsection