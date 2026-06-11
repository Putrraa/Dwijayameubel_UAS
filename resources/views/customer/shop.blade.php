@extends('layouts.master')
@section('title', 'Shop')

{{-- ===== SLIDER SECTION ===== --}}
@section('slider')
<div class="slider-wrapper">
    <div class="slider-container" id="sliderContainer">
        <div class="slide active" style="background-image: url('{{ asset('template_customer/images/slide1.png') }}')"></div>
        <div class="slide" style="background-image: url('{{ asset('template_customer/images/slide2.png') }}')"></div>
        <div class="slide" style="background-image: url('{{ asset('template_customer/images/slide3.png') }}')"></div>
        <div class="slide" style="background-image: url('{{ asset('template_customer/images/slide4.png') }}')"></div>
        <div class="slide" style="background-image: url('{{ asset('template_customer/images/slide5.png') }}')"></div>
    </div>
    <button class="slider-btn prev" id="prevBtn" aria-label="Previous slide">&#10094;</button>
    <button class="slider-btn next" id="nextBtn" aria-label="Next slide">&#10095;</button>
    <div class="slider-dots" id="sliderDots">
        <span class="dot active" data-index="0"></span>
        <span class="dot" data-index="1"></span>
        <span class="dot" data-index="2"></span>
        <span class="dot" data-index="3"></span>
        <span class="dot" data-index="4"></span>
    </div>
</div>
@endsection

@section('before_content')
<div class="section-divider">
    <h2>Categories</h2>
</div>
@endsection

{{-- ===== CONTENT SECTION ===== --}}
@section('content')
<div class="untree_co-section product-section before-footer-section">
    <div class="container">
        <div class="row g-4 justify-content-center">
            @foreach ($kategoriList as $cat)
            <div class="col-6 col-md-4 col-lg-3">
                <a class="category-card" href="{{ route('customer.kategori', Str::slug($cat->nama_kategori)) }}">
                    <div class="category-img-wrapper">
                        @php
                            // Jika ada gambar di DB pakai itu, jika tidak pakai default
                            $gambar = $cat->gambar
                                ? 'storage/' . $cat->gambar
                                : 'template_customer/images/asset_cat/img_default.jpg';
                        @endphp
                        <img src="{{ asset($gambar) }}" class="category-img" alt="{{ $cat->nama_kategori }}">
                        <div class="category-label">{{ $cat->nama_kategori }}</div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>  
</div>
@endsection

{{-- ===== ALL PRODUCTS SECTION ===== --}}
@section('after_content')

<div class="section-divider" id="all-products">
    <h2>All Products</h2>
</div>

<div class="untree_co-section product-section before-footer-section">
    <div class="container">

        {{-- Search & Sort Bar --}}
        <div class="d-flex flex-wrap align-items-center justify-content-between mb-4 gap-3">
            <div class="d-flex align-items-center gap-2">
                <h5 class="fw-bold text-brown mb-0">Find Your Favourite Products</h5>
            </div>
            <form method="GET" action="{{ route('customer.shop') }}" class="d-flex gap-2" id="searchForm">
                <input type="text" name="search" value="{{ request('search') }}"
                    class="form-control search-input" placeholder="Search products...">
                <button type="submit" class="btn btn-search">SEARCH</button>
            </form>
        </div>

        {{-- Info & Sort --}}
        <div class="d-flex flex-wrap align-items-center justify-content-between mb-3">
            <p class="text-muted mb-0">
                Showing {{ $products->firstItem() }}–{{ $products->lastItem() }} of {{ $products->total() }} results
            </p>
            <form method="GET" action="{{ route('customer.shop') }}" id="sortForm">
                @if(request('search'))
                    <input type="hidden" name="search" value="{{ request('search') }}">
                @endif
                <select name="sort" class="form-select sort-select" id="sortSelect">
                    <option value=""       {{ request('sort') == ''        ? 'selected' : '' }}>Default sorting</option>
                    <option value="latest" {{ request('sort') == 'latest'  ? 'selected' : '' }}>Sort by latest</option>
                    <option value="low"    {{ request('sort') == 'low'     ? 'selected' : '' }}>Sort by price: low to high</option>
                    <option value="high"   {{ request('sort') == 'high'    ? 'selected' : '' }}>Sort by price: high to low</option>
                </select>
            </form>
        </div>

        {{-- Product Grid --}}
        <div class="row g-4">
            @forelse ($products as $item)
            <div class="col-6 col-md-4 col-lg" style="flex: 0 0 20%; max-width: 20%;">
                <div class="product-card">
                    <div class="product-img-wrapper"
                        style="cursor: pointer;"
                        onclick="showProductModal(
                            '{{ addslashes($item->nama_barang) }}',
                            '{{ asset('storage/barang/' . $item->gambar) }}',
                            '{{ number_format($item->harga, 0, ',', '.') }}',
                            {{ $item->harga }},
                            '{{ addslashes($item->kategori->nama_kategori ?? 'Tanpa Kategori') }}', {{-- TAMBAH KATEGORI --}}
                            '{{ addslashes($item->bahan->nama_bahan ?? 'Tanpa Bahan') }}', {{-- PERBAIKI BAHAN --}}
                            '{{ addslashes($item->ukuran) }}',
                            {{ $item->stok }},
                            '{{ addslashes($item->deskripsi ?? '-') }}',
                            {{ $item->id }}
                        )">
                        <img src="{{ asset('storage/barang/' . $item->gambar) }}"
                            class="product-img" alt="{{ $item->nama_barang }}">
                    </div>

                    <div class="product-info text-center mt-2">
                        <h6 class="product-name">{{ strtoupper($item->nama_barang) }}</h6>
                        <p class="product-price">Rp {{ number_format($item->harga, 0, ',', '.') }}</p>
                    </div>

                    <button type="button" class="btn btn-add-cart w-100"
                            onclick="showProductModal(
                                '{{ addslashes($item->nama_barang) }}',
                                '{{ asset('storage/barang/' . $item->gambar) }}',
                                '{{ number_format($item->harga, 0, ',', '.') }}',
                                {{ $item->harga }},
                                '{{ addslashes($item->kategori->nama_kategori ?? 'Tanpa Kategori') }}',
                                '{{ addslashes($item->bahan->nama_bahan ?? 'Tanpa Bahan') }}',
                                '{{ addslashes($item->ukuran) }}',
                                {{ $item->stok }},
                                '{{ addslashes($item->deskripsi ?? '-') }}',
                                {{ $item->id }}
                            )">
                        ADD TO CART
                    </button>
                </div>
            </div>
            @empty
            <div class="col-12 text-center text-muted py-5">No products found.</div>
            @endforelse
        </div>

        {{-- Pagination --}}
        <div class="d-flex justify-content-center mt-5">
            @if ($products->hasPages())
            <ul class="pagination">

                {{-- Prev --}}
                @if ($products->onFirstPage())
                    <li class="page-item disabled"><span class="page-link">←</span></li>
                @else
                    <li class="page-item"><a class="page-link" href="{{ $products->previousPageUrl() }}&{{ http_build_query(request()->except('page')) }}">←</a></li>
                @endif

                {{-- Angka --}}
                @foreach ($products->appends(request()->query())->getUrlRange(1, $products->lastPage()) as $page => $url)
                    @if ($page == $products->currentPage())
                        <li class="page-item active"><span class="page-link">{{ $page }}</span></li>
                    @elseif ($page == 1 || $page == $products->lastPage() || abs($page - $products->currentPage()) <= 2)
                        <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                    @elseif (abs($page - $products->currentPage()) == 3)
                        <li class="page-item disabled"><span class="page-link">...</span></li>
                    @endif
                @endforeach

                {{-- Next --}}
                @if ($products->hasMorePages())
                    <li class="page-item"><a class="page-link" href="{{ $products->nextPageUrl() }}&{{ http_build_query(request()->except('page')) }}">→</a></li>
                @else
                    <li class="page-item disabled"><span class="page-link">→</span></li>
                @endif

            </ul>
            @endif
        </div>

    </div>
</div>

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
<link rel="stylesheet" href="{{ asset('template_customer/css/shop.css') }}">
@endpush

{{-- ===== SCRIPTS ===== --}}
@push('scripts')
<script>
    (function () {

        // ===== SLIDER =====
        const slides = document.querySelectorAll('.slide');
        const dots   = document.querySelectorAll('.dot');
        let current  = 0;
        let timer;

        function goTo(index) {
            slides[current].classList.remove('active');
            dots[current].classList.remove('active');
            current = (index + slides.length) % slides.length;
            slides[current].classList.add('active');
            dots[current].classList.add('active');
        }
        function autoPlay() { timer = setInterval(() => goTo(current + 1), 7000); }
        function resetTimer() { clearInterval(timer); autoPlay(); }

        document.getElementById('prevBtn').addEventListener('click', function () { goTo(current - 1); resetTimer(); });
        document.getElementById('nextBtn').addEventListener('click', function () { goTo(current + 1); resetTimer(); });
        dots.forEach(function (dot) {
            dot.addEventListener('click', function () { goTo(parseInt(this.dataset.index)); resetTimer(); });
        });
        autoPlay();

        // ===== SCROLL TO #all-products =====
        const searchForm = document.getElementById('searchForm');
        if (searchForm) {
            searchForm.addEventListener('submit', function () { sessionStorage.setItem('scrollToProducts', '1'); });
        }
        const sortSelect = document.getElementById('sortSelect');
        if (sortSelect) {
            sortSelect.addEventListener('change', function () {
                sessionStorage.setItem('scrollToProducts', '1');
                document.getElementById('sortForm').submit();
            });
        }
        window.addEventListener('load', function () {
            if (sessionStorage.getItem('scrollToProducts') === '1') {
                sessionStorage.removeItem('scrollToProducts');
                const el = document.getElementById('all-products');
                if (el) { setTimeout(function () { el.scrollIntoView({ behavior: 'smooth' }); }, 300); }
            }
        });

    })();

    // ===== VARIABEL GLOBAL =====
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
        
        // PERBAIKAN: Targetkan spesifik tombol di dalam modal agar tidak bentrok dengan tombol di card
        const btnAddCart = document.querySelector('#modal-cart-form .btn-add-cart');
        const btnQtys = document.querySelectorAll('#productModal .btn-qty');

        // Logika untuk disable tombol saat stok habis
        if (stok > 0) {
            stokEl.innerHTML = `<span style="color: #27ae60; font-weight: 600;">✔ Tersedia (${stok})</span>`;
            btnAddCart.disabled = false;
            btnAddCart.style.opacity = '1';
            btnAddCart.textContent = "ADD TO CART";
            btnQtys.forEach(btn => btn.disabled = false);
        } else {
            stokEl.innerHTML = `<span style="color: #e74c3c; font-weight: 600;">✘ Habis</span>`;
            btnAddCart.disabled = true; 
            btnAddCart.style.opacity = '0.5';
            btnAddCart.textContent = "STOK HABIS";
            btnQtys.forEach(btn => btn.disabled = true); 
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