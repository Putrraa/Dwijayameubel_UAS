@extends('layouts.master')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6">

            <div class="card shadow-sm border-0" style="border-radius:15px;">
                <div class="card-body p-5">

                    <h4 class="fw-bold mb-1">Pembayaran Custom Order</h4>
                    <p class="text-muted small mb-4">Selesaikan pembayaran Anda dengan aman.</p>
                    <hr>

                    <div class="mb-3">
                        <span class="text-muted small">Jenis Furniture</span>
                        <div class="fw-bold">{{ $custom->jenis_furniture }}</div>
                    </div>

                    <div class="mb-3">
                        <span class="text-muted small">Jenis Kayu</span>
                        <div class="fw-bold">{{ $custom->jenis_kayu }}</div>
                    </div>

                    <div class="mb-3">
                        <span class="text-muted small">Ukuran</span>
                        <div class="fw-bold">{{ $custom->ukuran }}</div>
                    </div>

                    <div class="mb-4">
                        <span class="text-muted small">Total Pembayaran</span>
                        <div class="fw-bold fs-5 text-success">
                            Rp {{ number_format($custom->estimasi_harga, 0, ',', '.') }}
                        </div>
                    </div>

                    {{-- Tombol Bayar Midtrans --}}
                    <button id="pay-button" class="btn btn-dwj w-100 py-3">
                        <i class="bi bi-credit-card me-2"></i>
                        Bayar Sekarang
                    </button>

                    <a href="{{ route('profile') }}" class="btn btn-outline-secondary w-100 mt-2">
                        Kembali
                    </a>

                </div>
            </div>

        </div>
    </div>
</div>

{{-- Midtrans Snap.js (Sandbox) --}}
<script src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('midtrans.client_key') }}"></script>

<script>
    document.getElementById('pay-button').onclick = function () {
        snap.pay('{{ $snapToken }}', {
            onSuccess: function (result) {
                window.location.href = '{{ route('payment.success', $custom->id) }}';
            },
            onPending: function (result) {
                alert('Menunggu pembayaran. Cek email Anda untuk instruksi.');
                window.location.href = '{{ route('profile') }}';
            },
            onError: function (result) {
                alert('Pembayaran gagal. Silakan coba lagi.');
                window.location.href = '{{ route('payment.failed', $custom->id) }}';
            },
            onClose: function () {
                alert('Anda menutup popup sebelum pembayaran selesai.');
            }
        });
    };
</script>

<style>
.btn-dwj {
    background-color: #3b5d50;
    color: white;
    border: none;
    border-radius: 10px;
    font-weight: 600;
}
.btn-dwj:hover {
    background-color: #2d463d;
    color: white;
}
</style>
@endsection