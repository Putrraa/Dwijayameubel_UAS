@extends('layouts.master')

@section('content')
<div class="container py-5">

    <h3 class="mb-4">Riwayat Pembelian</h3>

    {{-- ================= PESANAN PRODUK ================= --}}
    <h5 class="mb-3">Pesanan Produk</h5>

    @forelse($riwayat_pesanan as $pesanan)
        <div class="card mb-3 p-3 shadow-sm">

            <div class="d-flex justify-content-between align-items-center">

                <div>
                    <div class="fw-bold">#DWJ-{{ $pesanan->id }}</div>
                    <div class="text-muted small">
                        {{ \Carbon\Carbon::parse($pesanan->tanggal)->format('d M Y') }}
                    </div>
                    <div class="fw-bold text-success">
                        Rp {{ number_format($pesanan->jumlah_harga,0,',','.') }}
                    </div>
                </div>

                <div class="text-end">

                    {{-- STATUS --}}
                    @if($pesanan->status == 1)
                        <span class="badge bg-warning text-dark">Diproses</span>
                    @elseif($pesanan->status == 2)
                        <span class="badge bg-primary">Dikirim</span>
                    @elseif($pesanan->status == 3)
                        <span class="badge bg-secondary">Selesai</span>
                    @endif

                    <br><br>

                    {{-- BUTTON DETAIL --}}
                    <button type="button"
                            class="btn btn-sm btn-dwj btn-detail-pesanan"
                            data-id="{{ $pesanan->id }}"
                            data-bs-toggle="modal"
                            data-bs-target="#modalDetailPesanan">
                        Detail
                    </button>
                     @if($pesanan->status == 2)
                    <form action="{{ route('pesanan.terima', $pesanan->id) }}"
                            method="POST"
                            onsubmit="return confirm('Konfirmasi barang sudah sampai?');">
                        @csrf
                        <button type="submit"
                                class="btn btn-success btn-sm w-100">
                            <i class="bi bi-check-circle me-1"></i>
                            Barang Sampai
                        </button>
                    </form>
                    @endif
                    
                </div>

            </div>
        </div>
    @empty
        <p class="text-muted">Belum ada pesanan.</p>
    @endforelse


    <hr>


    {{-- ================= CUSTOM ORDER ================= --}}
    <h5 class="mb-3">Custom Order</h5>

    @forelse($custom_orders as $custom)
        <div class="card mb-3 p-3 shadow-sm">

            <div class="fw-bold">{{ $custom->jenis_furniture }}</div>
            <div>Status: {{ ucfirst($custom->status) }}</div>

        </div>
    @empty
        <p class="text-muted">Belum ada custom order.</p>
    @endforelse

</div>


{{-- ================= MODAL DETAIL PESANAN ================= --}}
<div class="modal fade" id="modalDetailPesanan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">

        <div class="modal-content border-0 shadow">

            <div class="modal-header" style="background:#3b5d50;">
                <h5 class="modal-title text-white">Detail Pesanan</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <div id="loading" class="text-center py-4">
                    <div class="spinner-border text-success"></div>
                    <p>Memuat...</p>
                </div>

                <div id="content" class="d-none">

                    <div class="mb-3">
                        <div class="fw-bold" id="kode"></div>
                        <div id="tanggal"></div>
                        <div id="metode"></div>
                        <div class="text-success fw-bold">
                            Rp <span id="total"></span>
                        </div>
                    </div>

                    <hr>

                    <div id="alamat"></div>

                    <hr>

                    <table class="table">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th>Qty</th>
                                <th>Harga</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody id="produk"></tbody>
                    </table>

                </div>

                <div id="error" class="alert alert-danger d-none"></div>

            </div>

        </div>

    </div>
</div>


{{-- ================= SCRIPT AJAX ================= --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    document.querySelectorAll('.btn-detail-pesanan').forEach(btn => {
        btn.addEventListener('click', function () {

            let id = this.dataset.id;

            document.getElementById('loading').classList.remove('d-none');
            document.getElementById('content').classList.add('d-none');
            document.getElementById('error').classList.add('d-none');

            fetch('/profile/detail/' + id)
                .then(res => res.json())
                .then(data => {

                    if (data.error) throw data;

                    document.getElementById('kode').innerText = data.pesanan.kode;
                    document.getElementById('tanggal').innerText = data.pesanan.tanggal;
                    document.getElementById('metode').innerText = data.pesanan.metode_pembayaran;
                    document.getElementById('total').innerText = data.total;

                    let html = '';
                    data.details.forEach(item => {
                        html += `
                            <tr>
                                <td>${item.nama_barang}</td>
                                <td>${item.jumlah}</td>
                                <td>Rp ${item.harga}</td>
                                <td>Rp ${item.jumlah_harga}</td>
                            </tr>
                        `;
                    });

                    document.getElementById('produk').innerHTML = html;

                    document.getElementById('loading').classList.add('d-none');
                    document.getElementById('content').classList.remove('d-none');

                })
                .catch(err => {
                    document.getElementById('loading').classList.add('d-none');
                    document.getElementById('error').classList.remove('d-none');
                    document.getElementById('error').innerText =
                        err.error ?? 'Gagal mengambil data';
                });

        });
    });

});
</script>

@endsection