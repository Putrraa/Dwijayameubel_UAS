@extends('layouts.master_kasir')

@section('title', 'Data Pesanan')

@section('content')
<div class="container py-5">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h2 class="fw-bold mb-1">Data Pesanan</h2>
            <p class="text-muted mb-0">Kelola pesanan reguler customer</p>
        </div>
    </div>

    {{-- ALERT --}}
    @if(session('success'))
        <div class="alert alert-success rounded-4 border-0 shadow-sm mb-4">
            {{ session('success') }}
        </div>
    @endif

    {{-- TABLE --}}
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table align-middle table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Pesanan</th>
                            <th>Tanggal</th>
                            <th>Penerima / Kontak</th>
                            <th>Alamat Pengiriman</th>
                            <th>Total Harga</th>
                            <th>Metode Bayar</th>
                            <th width="160px">Status</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($data_pesanan as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>

                                <td>
                                    <span class="badge bg-light text-dark border fw-bold">
                                        {{ $item->kode }}
                                    </span>
                                </td>

                                <td>
                                    <small class="text-muted">
                                        {{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y H:i') }}
                                    </small>
                                </td>

                                <td>
                                    <div class="fw-bold">
                                        {{ $item->nama_penerima }}
                                    </div>
                                    <small class="text-muted">
                                        {{ $item->no_telepon }}
                                    </small>
                                </td>

                                <td>
                                    <div class="text-truncate" style="max-width: 200px;" title="{{ $item->alamat }}">
                                        {{ $item->alamat }}
                                    </div>
                                    <small class="text-muted d-block">
                                        Kota: {{ $item->kota }} ({{ $item->kode_pos }})
                                    </small>
                                </td>

                                <td class="fw-bold text-success">
                                    Rp {{ number_format($item->jumlah_harga, 0, ',', '.') }}
                                </td>

                                <td>
                                    @php
                                        $metodeBayar = $item->metode_pembayaran 
                                            ? strtoupper(str_replace('_', ' ', $item->metode_pembayaran)) 
                                            : 'BELUM ADA';
                                    @endphp

                                    <span class="fw-semibold">
                                        {{ $metodeBayar }}
                                    </span>
                                </td>

                                <td>
                                    <form action="{{ route('pesanan.status', $item->id) }}" method="POST">
                                        @csrf

                                        <select name="status"
                                                onchange="this.form.submit()"
                                                class="form-select form-select-sm rounded-pill">
                                            <option value="1" {{ $item->status == 1 ? 'selected' : '' }}>
                                                Diproses
                                            </option>
                                            <option value="2" {{ $item->status == 2 ? 'selected' : '' }}>
                                                Dikirim
                                            </option>
                                            <option value="3" {{ $item->status == 3 ? 'selected' : '' }}>
                                                Selesai
                                            </option>
                                        </select>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">
                                    Belum ada data pesanan masuk.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>
        </div>
    </div>

</div>
@endsection