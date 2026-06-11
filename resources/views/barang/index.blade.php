@extends('layouts.master_admin')
@section('title', 'Produk')

@section('content')
<div class="container mt-4">
    <div class="d-flex align-items-center justify-content-between mb-4">

        <h2 class="fw-bold mb-0" style="color: #1c1b1b;">
            Tabel Barang
        </h2>

        <!-- kumpulan tombol -->
        <div class="d-flex gap-2">

            <button class="btn shadow-sm text-white"
                    data-bs-toggle="modal"
                    data-bs-target="#modalTambahBarang"
                    style="background-color: #3d5a4a;">
                <i class="bi bi-plus-lg"></i> Tambah Data
            </button>

            <button class="btn shadow-sm text-white"
                    data-bs-toggle="modal"
                    data-bs-target="#modalTambahkategori"
                    style="background-color: #3d5a4a;">
                <i class="bi bi-plus-lg"></i> Tambah Data kategori & bahan
            </button>

        </div>
    </div>

    <div class="card shadow-sm border-0" style="border-radius: 12px;">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle" id="tabel-barang">
                    <thead style="background-color: #1c1b1b; color: white;">
                        <tr>
                            <th class="text-center" style="width:5%">No.</th>
                            <th>Nama Barang</th>
                            <th>Kategori</th>
                            <th>Harga</th>
                            <th>Bahan</th> <!-- Sekarang mengambil dari relasi -->
                            <th class="text-center">Stok</th>
                            <th class="text-center">Gambar</th>
                            <th class="text-center" style="width:15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($dataBarang as $data)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td class="fw-semibold">{{ $data->nama_barang }}</td>
                            <td>
                                <span class="badge bg-light text-dark border">
                                    {{ $data->kategori->nama_kategori ?? 'Tanpa Kategori' }}
                                </span>
                            </td>
                            <td class="fw-bold text-success">Rp {{ number_format($data->harga, 0, ',', '.') }}</td>
                            
                            <!-- PERBAIKAN: Menampilkan Nama Bahan dari Relasi -->
                            <td>
                                <span class="text-muted">
                                    {{ $data->bahan->nama_bahan ?? 'Tanpa Bahan' }}
                                </span>
                            </td>

                            <td class="text-center">
                                @if($data->stok <= 5)
                                    <span class="text-danger fw-bold">{{ $data->stok }} (Hampir Habis)</span>
                                @else
                                    {{ $data->stok }}
                                @endif
                            </td>
                            <td class="text-center">
                                @if($data->gambar)
                                    <img src="{{ asset('storage/barang/'.$data->gambar) }}" class="rounded shadow-sm border" width="60" height="60" style="object-fit: cover;">
                                @else
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center mx-auto" style="width: 60px; height: 60px;">
                                        <i class="bi bi-image text-muted"></i>
                                    </div>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <button class="btn btn-warning btn-sm fw-bold text-white shadow-sm" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#modalEditBarang{{ $data->id }}">
                                        Ubah
                                    </button>
                                    <button class="btn btn-danger btn-sm fw-bold shadow-sm" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#modalHapusBarang{{ $data->id }}">
                                        Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <!-- Kirim data bahan juga ke modal update -->
                        @include('barang.update', ['data' => $data, 'kategori' => $kategori, 'bahan' => $bahan])
                        @include('barang.delete', ['data' => $data])
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Kirim data bahan ke modal create -->
@include('barang.create', ['kategori' => $kategori, 'bahan' => $bahan])
@include('barang.tambah_cat')

@stop

@push('scripts')
<script>
    $(document).ready(function() {
        $('#tabel-barang').DataTable({
            "responsive": true,
            "autoWidth": false,
            "language": {
                "search": "Cari Produk:",
                "lengthMenu": "_MENU_ data per halaman",
                "zeroRecords": "Barang tidak ditemukan",
                "info": "Halaman _PAGE_ dari _PAGES_",
                "paginate": {
                    "next": "Next",
                    "previous": "Prev"
                }
            }
        });
    });
</script>
@endpush