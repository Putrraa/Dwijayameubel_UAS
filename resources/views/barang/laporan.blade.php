@extends('layouts.master_admin')
@section('title', 'Laporan')
@section('content')
<div class="container mt-4 laporan-page">

    <div class="d-flex justify-content-between align-items-center mb-3 no-print">
        <div>
            <h3 class="fw-bold">Laporan Semua Pesanan</h3>
            <p class="text-muted mb-0">
                Daftar semua pesanan reguler dan custom order yang telah diproses pembayarannya.
            </p>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('laporan.excel') }}"
            class="btn btn-success px-4">
                Export Excel
            </a>

            <button type="button"
                    onclick="window.print()"
                    class="btn btn-dark px-4">
                Cetak PDF
            </button>
        </div>
    </div>

    {{-- HEADER KHUSUS PRINT --}}
    <div class="print-header mb-4">
        <h3 class="fw-bold mb-1">Laporan Semua Pesanan</h3>
        <p class="mb-1">
            Daftar semua pesanan reguler dan custom order yang telah diproses pembayarannya.
        </p>
        <small>
            Dicetak pada: {{ \Carbon\Carbon::now()->format('d M Y, H:i') }}
        </small>
    </div>

    @if ($data->count() > 0)
        <div class="card border-0 shadow-sm laporan-card">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 laporan-table">
                    <thead>
                        <tr>
                            <th class="py-3">No</th>
                            <th class="py-3">Jenis Pesanan</th>
                            <th class="py-3">Kode Pesanan</th>
                            <th class="py-3">Tanggal</th>
                            <th class="py-3">Pembeli</th>
                            <th class="py-3 text-end">Total Harga</th>
                        </tr>
                    </thead>

                    <tbody>
                        @php
                            $grandTotal = 0;
                        @endphp

                        @foreach ($data as $item)
                            @php
                                $totalHarga = $item->jenis_pesanan == 'Reguler'
                                    ? $item->jumlah_harga
                                    : $item->estimasi_harga;

                                $grandTotal += $totalHarga;
                            @endphp

                            <tr>
                                <td>{{ $loop->iteration }}</td>

                                <td>
                                    {{ $item->jenis_pesanan == 'Reguler' ? 'Reguler' : 'Custom Order' }}
                                </td>

                                <td class="fw-semibold text-secondary">
                                    {{ $item->kode }}
                                </td>

                                <td>
                                    {{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y, H:i') }}
                                </td>

                                <td>
                                    {{ $item->user->name ?? '-' }}
                                </td>

                                <td class="fw-bold text-end">
                                    Rp {{ number_format($totalHarga, 0, ',', '.') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>

                    <tfoot>
                        <tr>
                            <th colspan="5" class="text-end py-3">
                                Total Keseluruhan
                            </th>
                            <th class="text-end py-3">
                                Rp {{ number_format($grandTotal, 0, ',', '.') }}
                            </th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    @else
        <div class="alert alert-info text-center py-4 laporan-alert">
            <i class="mb-2 fs-3 d-block">📥</i>
            Belum ada data pesanan yang memenuhi kriteria laporan.
        </div>
    @endif

</div>

<style>
    .laporan-card {
        border-radius: 0 !important;
        overflow: visible !important;
    }

    .laporan-alert {
        border-radius: 0 !important;
    }

    .laporan-table thead {
        background-color: #212529;
        color: #fff;
    }

    .laporan-table th,
    .laporan-table td {
        vertical-align: middle;
        white-space: nowrap;
    }

    .print-header {
        display: none;
    }

    @media print {
        .no-print,
        nav,
        .navbar,
        .sidebar,
        .dropdown,
        .btn {
            display: none !important;
        }

        .print-header {
            display: block !important;
        }

        body {
            background: #fff !important;
        }

        .container,
        .laporan-page {
            width: 100% !important;
            max-width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        .card,
        .laporan-card {
            box-shadow: none !important;
            border: none !important;
            border-radius: 0 !important;
        }

        .table-responsive {
            overflow: visible !important;
        }

        .laporan-table {
            width: 100% !important;
            border-collapse: collapse !important;
            font-size: 12px;
        }

        .laporan-table th,
        .laporan-table td {
            border: 1px solid #000 !important;
            padding: 8px !important;
            color: #000 !important;
        }

        .laporan-table thead {
            background: #eaeaea !important;
            color: #000 !important;
        }

        .laporan-table tfoot th {
            background: #f1f1f1 !important;
            color: #000 !important;
        }

        @page {
            size: A4 landscape;
            margin: 15mm;
        }
    }
</style>
@endsection