@extends('layouts.master')
@section('title','Dwijaya Meubel')
@section('content')

<!-- Start Hero Section -->
@section('hero')
    <div class="hero">
        <div class="container">
            <div class="row justify-content-between">
                <div class="col-lg-5">
                    <div class="intro-excerpt">
                        <h1>Meubel Berkualitas<span class="d-block">Untuk Rumah Anda</span></h1>
                        <p class="mb-4">Kami menyediakan berbagai macam jenis meubel untuk kebutuhan rumah anda.</p>
                        <p><a href="{{ route('customer.shop')}}" class="btn btn-secondary me-2">Shop Now</a></p>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="hero-img-wrap">
                        <img src="template_customer/images/couch.png" class="img-fluid">
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
<!-- End Hero Section -->

<!-- Start Product Section -->
<div class="product-section">
    <div class="container">
        <div class="row">

            <!-- Start Column 1 -->
            <div class="col-md-12 col-lg-3 mb-5 mb-lg-0">
                <h2 class="mb-4 section-title">Berbagai Pilihan</h2>
                <p class="mb-4">Memiliki berbagai pilihan untuk anda, dengan kualitas yang terbaik.</p>
            </div>
            <!-- End Column 1 -->
@if(isset($bestSeller) && $bestSeller->count() > 0)
            <!-- Start Column 2 — Best Seller dengan Popup -->
            @foreach($bestSeller as $item)
            <div class="col-12 col-md-4 col-lg-3 mb-5 mb-md-0">
                <div class="product-item"
                     style="cursor: pointer;"
                     onclick="showProductModal(
                            '{{ addslashes($item->nama_barang) }}',
                            '{{ asset('storage/barang/' . $item->gambar)}}',
                            '{{ number_format($item->harga, 0, ',', '.') }}',
                            {{ $item->harga }},
                            '{{ addslashes($item->kategori->nama_kategori ?? 'Tanpa Kategori') }}', {{-- TAMBAH KATEGORI --}}
                            '{{ addslashes($item->bahan->nama_bahan ?? 'Tanpa Bahan') }}', {{-- PERBAIKI BAHAN --}}
                            '{{ addslashes($item->ukuran) }}',
                            {{ $item->stok }},
                            '{{ addslashes($item->deskripsi ?? '-') }}',
                            {{ $item->id }}
                        )">
                    <img src="{{ asset('storage/barang/'.$item->gambar)}}"
                        class="img-fluid product-thumbnail best-seller-img"
                        alt="{{ $item->nama_barang }}">
                    <h3 class="product-title">{{ $item->nama_barang }}</h3>
                    <strong class="product-price">Rp {{ number_format($item->harga, 0, ',', '.') }}</strong>
                    <p class="text-muted">Terjual {{ $item->total_terjual }}x</p>
                    <span class="icon-cross">
                        <img src="{{ asset('template_customer/images/cross.svg') }}" class="img-fluid">
                    </span>
                </div>
            </div>
            @endforeach
            <!-- End Column 2 -->
             @endif

        </div>
    </div>
</div>
<!-- End Product Section -->

<!-- Start Why Choose Us Section -->
<div class="why-choose-section">
    <div class="container">
        <div class="row justify-content-between">
            <div class="col-lg-6">
                <h2 class="section-title">Kenapa harus toko Meubel Dwijaya</h2>
                <p>Memiliki berbagai benefit untuk pelanggan kami.</p>
                <div class="row my-5">
                    <div class="col-6 col-md-6">
                        <div class="feature">
                            <div class="icon">
                                <img src="template_customer/images/truck.svg" alt="Image" class="imf-fluid">
                            </div>
                            <h3>Fast &amp; Free Shipping</h3>
                            <p>Cepat dan gratis pengiriman untuk setiap pembelian.</p>
                        </div>
                    </div>
                    <div class="col-6 col-md-6">
                        <div class="feature">
                            <div class="icon">
                                <img src="template_customer/images/bag.svg" alt="Image" class="imf-fluid">
                            </div>
                            <h3>Easy to Shop</h3>
                            <p>kemudahan berbelanja hanya dengan beberapa klik saja.</p>
                        </div>
                    </div>
                    <div class="col-6 col-md-6">
                        <div class="feature">
                            <div class="icon">
                                <img src="template_customer/images/support.svg" alt="Image" class="imf-fluid">
                            </div>
                            <h3>24/7 Support</h3>
                            <p>Konsultasi 24 Jam untuk produk dan layanan kami.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="img-wrap">
                    <img src="template_customer/images/why-choose-us-img.jpg" alt="Image" class="img-fluid">
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Why Choose Us Section -->

<!-- Start We Help Section -->
<div class="we-help-section">
    <div class="container">
        <div class="row justify-content-between">
            <div class="col-lg-7 mb-5 mb-lg-0">
                <div class="imgs-grid">
                    <div class="grid grid-1"><img src="template_customer/images/img-grid-1.jpg" alt="Untree.co"></div>
                    <div class="grid grid-2"><img src="template_customer/images/img-grid-2.jpg" alt="Untree.co"></div>
                    <div class="grid grid-3"><img src="template_customer/images/img-grid-3.jpg" alt="Untree.co"></div>
                </div>
            </div>
            <div class="col-lg-5 ps-lg-5">
                <h2 class="section-title mb-4">Kami membantu anda untuk mendesain rumah</h2>
                <p>Dengan produk dari kami, anda dapat membuat interior rumah anda sesuai dengan apa yang anda harapkan.</p>
                <p><a href="{{ route('customer.shop')}}" class="btn">Shop Now</a></p>
            </div>
        </div>
    </div>
</div>
<!-- End We Help Section -->

<!-- Start Testimonial Slider -->
<div class="testimonial-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-7 mx-auto text-center">
                <h2 class="section-title">Testimoni</h2>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="testimonial-slider-wrap text-center">
                    <div id="testimonial-nav">
                        <span class="prev" data-controls="prev"><span class="fa fa-chevron-left"></span></span>
                        <span class="next" data-controls="next"><span class="fa fa-chevron-right"></span></span>
                    </div>
                    <div class="testimonial-slider">
                        <div class="item">
                            <div class="row justify-content-center">
                                <div class="col-lg-8 mx-auto">
                                    <div class="testimonial-block text-center">
                                        <blockquote class="mb-5">
                                            <p>&ldquo;Mebel dengan kualitas baik dan sangat memuaskan, pelayanan terbaik dan sangat fast respon&rdquo;</p>
                                        </blockquote>
                                        <div class="author-info">
                                            <div class="author-pic">
                                                <img src="template_customer/images/person-1.png" alt="Jamal" class="img-fluid">
                                            </div>
                                            <h3 class="font-weight-bold">Jamal</h3>
                                            <span class="position d-block mb-3">CEO, Co-Founder, XYZ Inc.</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="item">
                            <div class="row justify-content-center">
                                <div class="col-lg-8 mx-auto">
                                    <div class="testimonial-block text-center">
                                        <blockquote class="mb-5">
                                            <p>&ldquo;Produk sangat berkualitas dan pengiriman cepat. Sangat puas dengan pelayanannya!&rdquo;</p>
                                        </blockquote>
                                        <div class="author-info">
                                            <div class="author-pic">
                                                <img src="template_customer/images/person-1.png" alt="Maria Jones" class="img-fluid">
                                            </div>
                                            <h3 class="font-weight-bold">Maria Jones</h3>
                                            <span class="position d-block mb-3">CEO, Co-Founder, XYZ Inc.</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Testimonial Slider -->

{{-- ===== MODAL POPUP DETAIL BARANG ===== --}}
<div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="border-radius: 16px; overflow: hidden; border: none;">

            <div class="modal-header" style="background: #3b5d50; border: none;">
                <h5 class="modal-title text-white fw-bold" id="productModalLabel">Detail Produk</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-0">
                <div class="row g-0">

                    <div class="col-md-5 d-flex align-items-center justify-content-center"
                         style="background: #f8f3ef; padding: 24px;">
                        <img id="modal-gambar" src="" alt="Gambar Produk"
                             style="max-width: 100%; max-height: 280px; object-fit: contain; border-radius: 10px;">
                    </div>

                    <div class="col-md-7 p-4 d-flex flex-column justify-content-between">
                        <div>
                            <h4 id="modal-nama" class="fw-bold mb-1" style="color: #3b2a1a;"></h4>
                            <p id="modal-harga" class="fw-bold fs-5 mb-3" style="color: #c0392b;"></p>
                            <hr style="border-color: #e0d6cc;">
                            <table class="table table-borderless mb-3" style="font-size: 0.93rem;">
                                <tbody>
                                    <tr>
                                        <td class="text-muted ps-0" style="width: 110px;">Kategori</td>
                                        <td class="fw-semibold" id="modal-kategori"></td> {{-- TAMBAHKAN INI --}}
                                    </tr>
                                    <tr>
                                        <td class="text-muted ps-0">Bahan</td>
                                        <td class="fw-semibold" id="modal-bahan"></td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted ps-0">Ukuran</td>
                                        <td class="fw-semibold" id="modal-ukuran"></td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted ps-0">Stok</td>
                                        <td id="modal-stok"></td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="mb-3">
                                <p class="text-muted mb-1" style="font-size: 0.88rem;">Deskripsi</p>
                                <p id="modal-deskripsi" style="font-size: 0.93rem; color: #3b2a1a; line-height: 1.6;"></p>
                            </div>
                        </div>

                        <div>
                            {{-- Input Quantity --}}
                            <div class="d-flex align-items-center justify-content-center gap-3 mb-2">
                                <button type="button" class="btn-qty" onclick="changeQty(-1)">&#8722;</button>
                                <input type="number" id="modal-qty" value="1" min="1"
                                       class="form-control text-center fw-bold qty-input"
                                       oninput="updateTotal()">
                                <button type="button" class="btn-qty" onclick="changeQty(1)">&#43;</button>
                            </div>

                            {{-- Total Harga --}}
                            <p class="text-center mb-3" style="font-size: 0.88rem; color: #666;">
                                Total: <strong id="modal-total" style="color: #c0392b;"></strong>
                            </p>

                            {{-- Form Cart --}}
                            <form id="modal-cart-form" action="{{ route('quick.add', 0) }}" method="POST" class="text-center">
                                @csrf
                                <input type="hidden" name="quantity" id="modal-qty-input" value="1">
                                <button type="submit" class="btn btn-add-cart w-100">ADD TO CART</button>
                            </form>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

@endsection

{{-- ===== STYLES ===== --}}
@push('styles')
<link rel="stylesheet" href="{{ asset('template_customer/css/style.css') }}">
@endpush

{{-- ===== SCRIPTS ===== --}}
@push('scripts')
<script>
    let modalHargaSatuan = 0;
    let modalMaxStok = 0;

    function showProductModal(nama, gambar, hargaFormatted, hargaAngka, kategori, bahan, ukuran, stok, deskripsi, id) {
        modalHargaSatuan = hargaAngka;
        modalMaxStok = stok;

        // Cek stok, jika > 0 set qty 1, jika 0 set qty 0
        let initialQty = stok > 0 ? 1 : 0;
        document.getElementById('modal-qty').value = initialQty;
        document.getElementById('modal-qty-input').value = initialQty;

        document.getElementById('modal-nama').textContent = nama.toUpperCase();
        document.getElementById('modal-gambar').src = gambar;
        document.getElementById('modal-harga').textContent = 'Rp ' + hargaFormatted;
        document.getElementById('modal-kategori').textContent = kategori;
        document.getElementById('modal-bahan').textContent = bahan;
        document.getElementById('modal-ukuran').textContent = ukuran;
        document.getElementById('modal-deskripsi').textContent = deskripsi;

        const stokEl = document.getElementById('modal-stok');
        const btnAddCart = document.querySelector('.btn-add-cart');
        const btnQtys = document.querySelectorAll('.btn-qty');

        // Logika untuk disable tombol saat stok habis
        if (stok > 0) {
            stokEl.innerHTML = `<span style="color: #27ae60; font-weight: 600;">✔ Tersedia (${stok})</span>`;
            btnAddCart.disabled = false;
            btnAddCart.style.opacity = '1';
            btnAddCart.textContent = "ADD TO CART";
            btnQtys.forEach(btn => btn.disabled = false);
        } else {
            stokEl.innerHTML = `<span style="color: #e74c3c; font-weight: 600;">✘ Habis</span>`;
            btnAddCart.disabled = true; // Nonaktifkan tombol cart
            btnAddCart.style.opacity = '0.5';
            btnAddCart.textContent = "STOK HABIS";
            btnQtys.forEach(btn => btn.disabled = true); // Nonaktifkan tombol +/-
        }

        updateTotal();

        const form = document.getElementById('modal-cart-form');
        form.action = form.action.replace(/\/\d+$/, '/' + id);

        const modal = new bootstrap.Modal(document.getElementById('productModal'));
        modal.show();
    }

    function changeQty(delta) {
        if (modalMaxStok <= 0) return; // Jangan ubah jika stok kosong

        const input = document.getElementById('modal-qty');
        let val = parseInt(input.value) + delta;
        
        if (val < 1) val = 1;
        if (val > modalMaxStok) val = modalMaxStok;
        
        input.value = val;
        updateTotal();
    }

    function updateTotal() {
        let inputQty = parseInt(document.getElementById('modal-qty').value);
        
        // Jika input bukan angka (kosong), set berdasarkan stok
        if (isNaN(inputQty)) {
            inputQty = modalMaxStok > 0 ? 1 : 0;
        }

        const total = modalHargaSatuan * inputQty;
        document.getElementById('modal-total').textContent = 'Rp ' + total.toLocaleString('id-ID');
        document.getElementById('modal-qty-input').value = inputQty;
    }
</script>
@endpush