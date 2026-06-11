@extends('layouts.master')
@section('title', $slug)

@section('content')
<div class="untree_co-section product-section before-footer-section">
    <div class="container">
        <div class="row">

            {{-- ===== KIRI: PRODUK ===== --}}
            <div class="col-lg-9">

                {{-- Judul Kategori --}}
                <h2 class="kategori-title">{{ ucwords(str_replace('-', ' ', $slug)) }}</h2>
                <p class="text-muted mb-3">{{ ucwords(str_replace('-', ' ', $slug)) }} – Toko Furniture Kami</p>

                {{-- Info & Sort --}}
                <div class="d-flex flex-wrap align-items-center justify-content-between mb-4">
                    <p class="text-muted mb-0">
                        Showing all {{ $products->total() }} results
                    </p>
                    <form method="GET" action="{{ route('customer.kategori', $slug) }}">
                        <select name="sort" class="form-select sort-select" onchange="this.form.submit()">
                            <option value=""       {{ request('sort') == ''       ? 'selected' : '' }}>Default sorting</option>
                            <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Sort by latest</option>
                            <option value="low"    {{ request('sort') == 'low'    ? 'selected' : '' }}>Sort by price: low to high</option>
                            <option value="high"   {{ request('sort') == 'high'   ? 'selected' : '' }}>Sort by price: high to low</option>
                        </select>
                    </form>
                </div>

                {{-- Grid Produk --}}
                <div class="row g-4">
                    @forelse ($products as $item)
                    <div class="col-6 col-md-4 col-lg-3">
                        <div class="product-card">
                            {{-- Klik Gambar/Info untuk Buka Modal --}}
                            <div class="product-img-wrapper" 
                                style="cursor: pointer;"
                                onclick="showProductModal(
                                    '{{ addslashes($item->nama_barang) }}',
                                    '{{ asset($item->gambar) }}',
                                    '{{ number_format($item->harga, 0, ',', '.') }}',
                                    {{ $item->harga }},
                                    '{{ addslashes($item->kategori->nama_kategori ?? 'Tanpa Kategori') }}',
                                    '{{ addslashes($item->bahan->nama_bahan ?? 'Tanpa Bahan') }}',
                                    '{{ addslashes($item->ukuran) }}',
                                    {{ $item->stok }},
                                    '{{ addslashes($item->deskripsi ?? '-') }}',
                                    {{ $item->id }}
                                )">
                                <img src="{{ asset($item->gambar) }}"
                                    class="product-img" alt="{{ $item->nama_barang }}">
                            </div>

                            <div class="product-info text-center mt-2">
                                <h6 class="product-name">{{ strtoupper($item->nama_barang) }}</h6>
                                <p class="product-price">Rp {{ number_format($item->harga, 0, ',', '.') }}</p>
                            </div>

                            {{-- Tombol Add to Cart Membuka Modal --}}
                            <button type="button" class="btn btn-add-cart w-100"
                                onclick="showProductModal(
                                    '{{ addslashes($item->nama_barang) }}',
                                    '{{ asset($item->gambar) }}',
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
                    <div class="col-12 text-center text-muted py-5">Tidak ada produk di kategori ini.</div>
                    @endforelse
                </div>

                {{-- Pagination --}}
                <div class="d-flex justify-content-center mt-5">
                    {{ $products->appends(request()->query())->links('pagination::bootstrap-5') }}
                </div>

            </div>

            {{-- ===== KANAN: SIDEBAR ===== --}}
            <div class="col-lg-3">
                {{-- Search --}}
                <div class="sidebar-box mb-4">
                    <h5 class="sidebar-title">Search Product</h5>
                    <form method="GET" action="{{ route('customer.kategori', $slug) }}" class="d-flex">
                        <input type="text" name="search" value="{{ request('search') }}"
                               class="form-control" placeholder="Search Products...">
                        <button type="submit" class="btn btn-search ms-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398l3.85 3.85a1 1 0 0 0 1.415-1.415l-3.868-3.833zm-5.242 1.156a5.5 5.5 0 1 1 0-11 5.5 5.5 0 0 1 0 11z"/>
                            </svg>
                        </button>
                    </form>
                </div>


                {{-- Daftar Kategori --}}
                <div class="sidebar-box">
                    <h5 class="sidebar-title">Product Categories</h5>
                    <ul class="sidebar-cat-list">

                        @php
                            $urutanKategori = [
                                'meja', 'kursi', 'lemari', 'tempat tidur',
                                'sofa', 'rak', 'kitchen set',
                            ];

                            $kategoriSorted = $kategoriList->sortBy(function($cat) use ($urutanKategori) {
                                $index = array_search(strtolower($cat->nama_kategori), $urutanKategori);
                                return $index !== false ? $index : 999;
                            });
                        @endphp

                        @foreach ($kategoriSorted as $cat)
                        @php $catSlug = Str::slug($cat->nama_kategori); @endphp
                        <li class="{{ $slug == $catSlug ? 'active' : '' }}">
                            <a href="{{ route('customer.kategori', $catSlug) }}">
                                {{ $cat->nama_kategori }}
                            </a>
                        </li>
                        @endforeach

                        {{-- Perabot Lainnya selalu di paling bawah --}}
                        <li class="{{ $slug == 'perabot-lainnya' ? 'active' : '' }}">
                            <a href="{{ route('customer.kategori', 'perabot-lainnya') }}">
                                Perabot Lainnya
                            </a>
                        </li>

                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ===== MODAL POPUP (IDENTIK DENGAN SHOP) ===== --}}
<div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="border-radius: 16px; overflow: hidden; border: none;">

            <div class="modal-header" style="background: #3b5d50; border: none;">
                <h5 class="modal-title text-white fw-bold" id="productModalLabel">Detail Produk</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-0">
                <div class="row g-0">
                    {{-- Sisi Kiri: Gambar --}}
                    <div class="col-md-5 d-flex align-items-center justify-content-center" 
                         style="background: #f8f3ef; padding: 24px;">
                        <img id="modal-gambar" src="" alt="Gambar Produk" 
                             style="max-width: 100%; max-height: 280px; object-fit: contain; border-radius: 10px;">
                    </div>

                    {{-- Sisi Kanan: Detail --}}
                    <div class="col-md-7 p-4 d-flex flex-column justify-content-between">
                        <div>
                            <h4 id="modal-nama" class="fw-bold mb-1" style="color: #3b2a1a;"></h4>
                            <p id="modal-harga" class="fw-bold fs-5 mb-3" style="color: #c0392b;"></p>
                            
                            <hr style="border-color: #e0d6cc;">

                            <table class="table table-borderless mb-3" style="font-size: 0.93rem;">
                                <tbody>
                                    <tr>
                                        <td class="text-muted ps-0" style="width: 110px;">Kategori</td>
                                        <td class="fw-semibold" id="modal-kategori"></td>
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
                            <div class="d-flex align-items-center justify-content-center gap-3 mb-2">
                                <button type="button" class="btn-qty" onclick="changeQty(-1)">&#8722;</button>
                                <input type="number" id="modal-qty" value="1" min="1" 
                                       class="form-control text-center fw-bold qty-input" 
                                       oninput="updateTotal()">
                                <button type="button" class="btn-qty" onclick="changeQty(1)">&#43;</button>
                            </div>

                            <p class="text-center mb-3" style="font-size: 0.88rem; color: #666;">
                                Total: <strong id="modal-total" style="color: #c0392b;"></strong>
                            </p>

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