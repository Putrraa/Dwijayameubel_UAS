@extends('layouts.master')

@section('title','Keranjang')

@section('content')
<div class="checkout-page">

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if(request('payment') == 'success')
        <div class="alert alert-success">
            Pembayaran selesai. Pesanan akan diproses.
        </div>
    @endif

    @if(request('payment') == 'pending')
        <div class="alert alert-warning">
            Pesanan dibuat. Silakan selesaikan pembayaran agar pesanan diproses.
        </div>
    @endif

    @if ($pesanan && $pesanan->detail && $pesanan->detail->count() > 0)

        {{-- AREA TABEL --}}
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px; overflow: hidden;">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" style="font-size: 0.85rem; line-height: 1.6;">
                        <thead class="bg-light">
                            <tr style="border-bottom: 2px solid #eee;">
                                <th class="ps-4 py-3 text-uppercase fw-bold text-muted" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                                    Produk
                                </th>
                                <th class="py-3 text-uppercase fw-bold text-muted text-center" style="font-size: 0.75rem;">
                                    Harga
                                </th>
                                <th class="py-3 text-uppercase fw-bold text-muted text-center" style="font-size: 0.75rem; width: 120px;">
                                    Jumlah
                                </th>
                                <th class="py-3 text-uppercase fw-bold text-muted text-center" style="font-size: 0.75rem;">
                                    Subtotal
                                </th>
                                <th class="pe-4 py-3 text-uppercase fw-bold text-muted text-end" style="font-size: 0.75rem;">
                                    Aksi
                                </th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($pesanan->detail as $item)
                                <tr>
                                    <td class="ps-4 py-3">
                                        <div class="d-flex align-items-center gap-3">
                                            <img src="{{ asset($item->barang->gambar) }}"
                                                 width="60"
                                                 height="60"
                                                 class="rounded shadow-sm"
                                                 style="object-fit: cover;">

                                            <div style="line-height: 1.4;">
                                                <h6 class="mb-0 fw-bold text-dark" style="font-size: 0.9rem;">
                                                    {{ $item->barang->nama_barang }}
                                                </h6>
                                                <small class="text-muted" style="font-size: 0.75rem;">
                                                    Dwijaya Mebel Original
                                                </small>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="text-center text-secondary fw-medium">
                                        Rp {{ number_format($item->barang->harga, 0, ',', '.') }}
                                    </td>

                                    <td class="text-center">
                                        <div class="d-flex align-items-center justify-content-center gap-2">
                                            <form action="{{ route('checkout.kurang', $item->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn-qty-mini">-</button>
                                            </form>

                                            <span class="fw-bold" style="min-width: 20px;">
                                                {{ $item->jumlah }}
                                            </span>

                                            <form action="{{ route('checkout.tambah', $item->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn-qty-mini">+</button>
                                            </form>
                                        </div>
                                    </td>

                                    <td class="text-center fw-bold" style="color: #c0392b; font-size: 0.95rem;">
                                        Rp {{ number_format($item->jumlah_harga, 0, ',', '.') }}
                                    </td>

                                    <td class="pe-4 text-end">
                                        <form action="{{ route('checkout.hapus', $item->id) }}"
                                              method="POST"
                                              onsubmit="return confirm('Hapus barang?')">
                                            @csrf
                                            @method('DELETE')

                                            <button class="btn btn-dark btn-sm px-3 py-1"
                                                    style="border-radius: 20px; font-size: 0.75rem; font-weight: 500;">
                                                Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>

                    </table>
                </div>
            </div>
        </div>

        {{-- FOOTER --}}
        <div class="row align-items-center mb-5 px-2">
            <div class="col-md-6">
                <a href="{{ url('/shop') }}"
                   class="btn btn-dark px-4 py-2"
                   style="border-radius: 30px; font-size: 0.85rem; font-weight: 500;">
                    &larr; Kembali Belanja
                </a>
            </div>

            <div class="col-md-6 text-md-end mt-3 mt-md-0">
                <div class="d-inline-block text-end me-4 align-middle" style="line-height: 1.2;">
                    <small class="text-muted d-block mb-1" style="font-size: 0.75rem;">
                        Total Pembayaran:
                    </small>
                    <strong class="h4 fw-bold mb-0" style="color: #c0392b;">
                        Rp {{ number_format($pesanan->jumlah_harga, 0, ',', '.') }}
                    </strong>
                </div>

                <button type="button"
                        class="btn btn-success px-4 py-2 fw-bold shadow-sm"
                        style="border-radius: 30px; background-color: #3b5d50; border: none; font-size: 0.9rem; letter-spacing: 0.5px;"
                        data-bs-toggle="modal"
                        data-bs-target="#checkoutModal">
                    BAYAR SEKARANG
                </button>
            </div>
        </div>

    @else
        <p class="text-center">Keranjang masih kosong.</p>
    @endif

</div>


{{-- ================= MODAL CHECKOUT ================= --}}
@if ($pesanan && $pesanan->detail && $pesanan->detail->count() > 0)
<div class="modal fade" id="checkoutModal" tabindex="-1" aria-labelledby="checkoutModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content" style="border-radius: 16px; overflow: hidden; border: none;">

            <div class="modal-header" style="background: #3b5d50; border: none;">
                <h5 class="modal-title text-white fw-bold" id="checkoutModalLabel">
                    Konfirmasi Pembayaran
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body p-0">
                <div class="row g-0">

                    {{-- KIRI: RINGKASAN PESANAN --}}
                    <div class="col-md-5 d-flex flex-column"
                         style="background: #f8f3ef; padding: 28px; border-right: 1px solid #e0d6cc;">

                        <h6 class="fw-bold mb-3" style="color: #3b5d50;">
                            Ringkasan Pesanan
                        </h6>

                        <div class="flex-grow-1">
                            @foreach ($pesanan->detail as $item)
                                <div class="d-flex align-items-center gap-3 mb-3 pb-3"
                                     style="border-bottom: 1px dashed #d8cfc8;">

                                    <img src="{{ asset($item->barang->gambar) }}"
                                         width="60"
                                         height="60"
                                         class="rounded"
                                         style="object-fit: cover; flex-shrink: 0;">

                                    <div class="flex-grow-1">
                                        <div class="fw-semibold" style="font-size: 0.88rem; color: #3b2a1a;">
                                            {{ $item->barang->nama_barang }}
                                        </div>

                                        <div class="text-muted" style="font-size: 0.82rem;">
                                            {{ $item->jumlah }}x &nbsp;·&nbsp;
                                            Rp {{ number_format($item->barang->harga, 0, ',', '.') }}
                                        </div>
                                    </div>

                                    <div class="fw-bold" style="font-size: 0.88rem; color: #c0392b; flex-shrink: 0;">
                                        Rp {{ number_format($item->jumlah_harga, 0, ',', '.') }}
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-2 pt-3"
                             style="border-top: 2px solid #c8bfb5;">
                            <span class="fw-bold" style="color: #3b2a1a;">
                                Total Pembayaran
                            </span>

                            <span class="fw-bold fs-5" style="color: #c0392b;">
                                Rp {{ number_format($pesanan->jumlah_harga, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>

                    {{-- KANAN: FORM ALAMAT --}}
                    <div class="col-md-7 p-4">
                        <form id="form-checkout">
                            @csrf

                            <h6 class="fw-bold mb-3" style="color: #3b5d50;">
                                Alamat Pengiriman
                            </h6>

                            <div class="mb-3">
                                <label class="form-label fw-semibold" style="font-size:0.88rem;">
                                    Nama Penerima
                                </label>
                                <input type="text"
                                       name="nama_penerima"
                                       class="form-control form-control-sm"
                                       required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold" style="font-size:0.88rem;">
                                    Nomor Telepon
                                </label>
                                <input type="text"
                                       name="no_telepon"
                                       class="form-control form-control-sm"
                                       required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold" style="font-size:0.88rem;">
                                    Alamat Lengkap
                                </label>
                                <textarea name="alamat"
                                          rows="2"
                                          class="form-control form-control-sm"
                                          required></textarea>
                            </div>

                            <div class="row">
                                <div class="col-7 mb-3">
                                    <label class="form-label fw-semibold" style="font-size:0.88rem;">
                                        Kota
                                    </label>
                                    <input type="text"
                                           name="kota"
                                           class="form-control form-control-sm"
                                           required>
                                </div>

                                <div class="col-5 mb-3">
                                    <label class="form-label fw-semibold" style="font-size:0.88rem;">
                                        Kode Pos
                                    </label>
                                    <input type="text"
                                           name="kode_pos"
                                           class="form-control form-control-sm"
                                           required>
                                </div>
                            </div>

                            <div class="mb-2">
                                <label class="form-label fw-semibold" style="font-size:0.88rem;">
                                    Catatan (opsional)
                                </label>
                                <textarea name="catatan"
                                          rows="2"
                                          class="form-control form-control-sm"></textarea>
                            </div>

                        </form>
                    </div>

                </div>
            </div>

            <div class="modal-footer" style="background: #f8f3ef; border-top: 1px solid #e0d6cc;">
                <div class="d-flex justify-content-between align-items-center w-100">
                    <div>
                        <small class="text-muted">Total Pembayaran</small><br>
                        <strong class="fs-5" style="color: #c0392b;">
                            Rp {{ number_format($pesanan->jumlah_harga, 0, ',', '.') }}
                        </strong>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="button"
                                class="btn btn-outline-secondary btn-sm"
                                data-bs-dismiss="modal">
                            Batal
                        </button>

                        <button type="submit"
                                form="form-checkout"
                                id="btn-bayar-midtrans"
                                class="btn btn-success btn-sm px-4"
                                style="background-color: #3b5d50; border: none;">
                            Bayar dengan Midtrans
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endif

@endsection


@push('styles')
<link rel="stylesheet" href="{{ asset('template_customer/css/style.css') }}">
@endpush


@push('scripts')
<script src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('midtrans.client_key') }}">
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const formCheckout = document.getElementById('form-checkout');
    const btnBayar = document.getElementById('btn-bayar-midtrans');

    if (!formCheckout) {
        return;
    }

    formCheckout.addEventListener('submit', function (e) {
        e.preventDefault();

        btnBayar.disabled = true;
        btnBayar.innerText = 'Memproses...';

        const formData = new FormData(formCheckout);

        fetch("{{ route('checkout.bayar') }}", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                "Accept": "application/json"
            },
            body: formData
        })
        .then(async function (response) {
            const data = await response.json();

            if (!response.ok) {
                throw data;
            }

            return data;
        })
        .then(function (data) {
            if (data.snap_token) {
                snap.pay(data.snap_token, {
                    onSuccess: function (result) {
                        fetch("{{ route('checkout.finish') }}", {
                            method: "POST",
                            headers: {
                                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                                "Accept": "application/json",
                                "Content-Type": "application/json"
                            },
                            body: JSON.stringify({
                                order_id: result.order_id || data.kode,
                                payment_type: result.payment_type || 'midtrans',
                                transaction_id: result.transaction_id || null
                            })
                        })
                        .then(response => response.json())
                        .then(dataFinish => {
                            window.location.href = "{{ route('customer.checkout') }}?payment=success";
                        })
                        .catch(error => {
                            console.error(error);
                            window.location.href = "{{ route('customer.checkout') }}?payment=success";
                        });
                    },

                    onPending: function (result) {
                        window.location.href = "{{ route('customer.checkout') }}?payment=pending";
                    },
                    onError: function (result) {
                        alert('Pembayaran gagal. Silakan coba lagi.');
                        btnBayar.disabled = false;
                        btnBayar.innerText = 'Bayar dengan Midtrans';
                    },
                    onClose: function () {
                        alert('Popup pembayaran ditutup sebelum transaksi selesai.');
                        btnBayar.disabled = false;
                        btnBayar.innerText = 'Bayar dengan Midtrans';
                    }
                });
            } else {
                alert(data.message || 'Gagal membuat transaksi Midtrans.');
                btnBayar.disabled = false;
                btnBayar.innerText = 'Bayar dengan Midtrans';
            }
        })
        .catch(function (error) {
            console.error(error);

            let pesan = 'Terjadi kesalahan saat menghubungkan ke Midtrans.';

            if (error && error.message) {
                pesan = error.message;
            }

            alert(pesan);

            btnBayar.disabled = false;
            btnBayar.innerText = 'Bayar dengan Midtrans';
        });
    });
});
</script>
@endpush
@push('styles')
<link rel="stylesheet" href="{{ asset('template_customer/css/style.css') }}">

<style>
    .checkout-page {
        min-height: 60vh;
        padding-top: 20px;
        padding-bottom: 60px;
    }
</style>
@endpush