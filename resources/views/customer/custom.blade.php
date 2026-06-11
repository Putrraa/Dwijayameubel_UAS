@extends('layouts.master')
@section('title', 'Custom Furniture')

@section('styles')
<link rel="stylesheet" href="{{ asset('template_customer/css/style.css') }}">
@endsection

@section('content')

<div class="custom-wrapper">
    <div class="custom-container">

        {{-- Header --}}
        <div class="form-header">
            <span class="eyebrow">Pesan Sekarang</span>
            <h1>Custom Furniture</h1>
            <p>Ceritakan furnitur impian Anda — kami wujudkan dengan tangan.</p>
        </div>

        {{-- Alerts --}}
        @if ($errors->any())
            <div class="alert-error">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        {{-- Form Card --}}
        <div class="form-card">

            <form action="{{ route('custom-orders.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                {{-- Section: Spesifikasi Furniture --}}
                <p class="form-section-title">Spesifikasi Furniture</p>

                <div class="form-grid">

                {{-- Jenis Furniture --}}
                <div class="form-group">
                    <label for="jenis_furniture">
                        Jenis Furniture <span class="required">*</span>
                    </label>
                    <input type="text" name="jenis_furniture" id="jenis_furniture"
                        value="{{ old('jenis_furniture') }}"
                        placeholder="Contoh: Meja, Kursi, Lemari, dll."
                        class="{{ $errors->has('jenis_furniture') ? 'is-invalid' : '' }}"
                        required>
                    @error('jenis_furniture')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Jenis Kayu --}}
                <div class="form-group">
                    <label for="jenis_kayu">
                        Jenis Kayu <span class="required">*</span>
                    </label>
                    <input type="text" name="jenis_kayu" id="jenis_kayu"
                        value="{{ old('jenis_kayu') }}"
                        placeholder="Contoh: Jati, Mahoni, Pinus."
                        class="{{ $errors->has('jenis_kayu') ? 'is-invalid' : '' }}"
                        required>
                    @error('jenis_kayu')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                    {{-- Ukuran --}}
                    <div class="form-group full-width">
                        <label for="ukuran">
                            Ukuran <span class="required">*</span>
                        </label>
                        <input type="text" name="ukuran" id="ukuran"
                            value="{{ old('ukuran') }}"
                            placeholder="Contoh: 120×60×75 cm (P×L×T)"
                            class="{{ $errors->has('ukuran') ? 'is-invalid' : '' }}"
                            required>
                        <span class="ukuran-hint">Gunakan format: Panjang × Lebar × Tinggi dalam cm</span>
                        @error('ukuran')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                </div>

                <div class="divider"></div>

                {{-- Section: Referensi Desain --}}
                <p class="form-section-title">Referensi Desain</p>

                {{-- Gambar --}}
                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label for="gambar">Gambar Referensi</label>
                    <div class="upload-zone" id="uploadZone">
                        <input type="file" name="gambar" id="gambar"
                            accept="image/jpeg,image/png,image/webp,image/jpg"
                            onchange="handleFileChange(this)">
                        <span class="upload-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                <polyline points="17 8 12 3 7 8"/>
                                <line x1="12" y1="3" x2="12" y2="15"/>
                            </svg>
                        </span>
                        <span class="upload-label">Klik untuk unggah gambar referensi</span>
                        <span class="upload-hint">JPG, PNG, WEBP · Maks 2MB</span>
                    </div>
                    <div class="upload-preview" id="uploadPreview">
                        <img id="previewImg" src="" alt="Preview">
                        <span class="preview-name" id="previewName"></span>
                    </div>
                    @error('gambar')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Catatan --}}
                <div class="form-group">
                    <label for="catatan">Catatan Tambahan</label>
                    <textarea name="catatan" id="catatan"
                        placeholder="Tuliskan detail tambahan: warna finishing, motif ukiran, material khusus, atau kebutuhan lain...">{{ old('catatan') }}</textarea>
                    @error('catatan')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="divider"></div>

                {{-- Footer --}}
                <div class="form-footer">
                    <span class="footer-note">
                        Tim kami akan menghubungi Anda<br>untuk konfirmasi & estimasi harga.
                    </span>
                    <button type="submit" class="btn-submit">
                        <svg viewBox="0 0 24 24" stroke-width="2">
                            <path d="M5 12h14M12 5l7 7-7 7"/>
                        </svg>
                        Kirim Pesanan
                    </button>
                </div>

            </form>

        </div>{{-- end .form-card --}}

    </div>{{-- end .custom-container --}}
</div>{{-- end .custom-wrapper --}}

<script>
    function handleFileChange(input) {
        const preview = document.getElementById('uploadPreview');
        const previewImg = document.getElementById('previewImg');
        const previewName = document.getElementById('previewName');
        const zone = document.getElementById('uploadZone');

        if (input.files && input.files[0]) {
            const file = input.files[0];
            const reader = new FileReader();

            reader.onload = function(e) {
                previewImg.src = e.target.result;
                previewName.textContent = file.name;
                preview.style.display = 'flex';
                zone.style.borderColor = 'var(--wood-400)';
            };

            reader.readAsDataURL(file);
        } else {
            preview.style.display = 'none';
            zone.style.borderColor = 'var(--wood-200)';
        }
    }
</script>

@endsection