@extends('layouts.master')

@section('content')

<div class="container py-5">

    <div class="row justify-content-center">

        <div class="col-lg-6">

            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">

                {{-- HEADER --}}
                <div class="p-4 text-white" style="background-color: #3b5d50;">

                    <h3 class="fw-bold mb-1">
                        Pembayaran Custom Order
                    </h3>

                    <p class="mb-0 opacity-75">
                        Selesaikan pembayaran furniture custom Anda
                    </p>

                </div>

                {{-- BODY --}}
                <div class="card-body p-5">

                    {{-- PRODUK --}}
                    <div class="text-center mb-4">

                        <div class="mb-3">

                            @if($custom->gambar)

                                <img src="{{ asset('storage/' . $custom->gambar) }}"
                                     class="img-fluid rounded-4 shadow-sm"
                                     style="height:220px; object-fit:cover;">

                            @else

                                <img src="https://cdn-icons-png.flaticon.com/512/679/679720.png"
                                     width="120">

                            @endif

                        </div>

                        <h4 class="fw-bold">
                            {{ $custom->jenis_furniture }}
                        </h4>

                        <p class="text-muted mb-1">
                            Jenis Kayu: {{ $custom->jenis_kayu }}
                        </p>

                        <p class="text-muted">
                            Ukuran: {{ $custom->ukuran }}
                        </p>

                    </div>

                    {{-- TOTAL --}}
                    <div class="bg-light rounded-4 p-4 mb-4">

                        <div class="d-flex justify-content-between">

                            <span class="fw-semibold">
                                Estimasi Harga
                            </span>

                            <span class="fw-bold text-success fs-5">
                                Rp {{ number_format($custom->estimasi_harga, 0, ',', '.') }}
                            </span>

                        </div>

                    </div>

                    {{-- BUTTON --}}
                    <button id="pay-button"
                            class="btn w-100 py-3 rounded-pill fw-bold text-white"
                            style="background-color:#3b5d50;">

                        Bayar Sekarang

                    </button>

                    <div class="text-center mt-3">

                        <small class="text-muted">
                            Mendukung QRIS, Bank Transfer, Gopay, ShopeePay, Dana, dll
                        </small>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

{{-- MIDTRANS SNAP --}}
<script src="https://app.sandbox.midtrans.com/snap/snap.js"
    data-client-key="{{ config('midtrans.client_key') }}">
</script>

<script>
document.getElementById('pay-button').onclick = function () {

    snap.pay('{{ $snapToken }}', {

        onSuccess: function(result) {
            alert("Pembayaran berhasil!");
            window.location.href = "/profile";
        },

        onPending: function(result) {
            alert("Menunggu pembayaran");
        },

        onError: function(result) {
            alert("Pembayaran gagal");
        },

        onClose: function() {
            alert("Popup pembayaran ditutup");
        }

    });

};
</script>

@endsection