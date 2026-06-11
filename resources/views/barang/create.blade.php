<!-- Modal Tambah Barang -->
<div class="modal fade" id="modalTambahBarang" tabindex="-1" aria-labelledby="modalTambahBarangLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border-radius: 15px; border: none; overflow: hidden;">
            
            <!-- Header -->
            <div class="modal-header" style="background-color: #3d5a4a; color: white;">
                <h5 class="modal-title" id="modalTambahBarangLabel">
                    <i class="fas fa-plus-circle me-2"></i> Tambah Produk Baru
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="{{ route('barang.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4">
                    <div class="row">
                        <!-- Baris 1: Nama & Kategori -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Nama Barang <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="nama_barang" placeholder="Contoh: Kursi Jati Minimalis" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="kategori_id" class="form-label fw-bold">Kategori Produk <span class="text-danger">*</span></label>
                            <select name="kategori_id" id="kategori_id" class="form-select" required>
                                <option value="" selected disabled>-- Pilih Kategori --</option>
                                @foreach($kategori as $k)
                                    <option value="{{ $k->id }}">{{ $k->nama_kategori }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Baris 2: Harga & Stok -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Harga (Rp) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" class="form-control" name="harga" placeholder="1500000" required>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Stok <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" name="stok" placeholder="0" required>
                        </div>

                        <!-- Baris 3: Bahan (Dropdown) & Ukuran -->
                        <div class="col-md-6 mb-3">
                            <label for="bahan_id" class="form-label fw-bold">Bahan <span class="text-danger">*</span></label>
                            <select name="bahan_id" id="bahan_id" class="form-select" required>
                                <option value="" selected disabled>-- Pilih Bahan (Jati, Mahoni, dll) --</option>
                                @foreach($bahan as $b)
                                    <option value="{{ $b->id }}">{{ $b->nama_bahan }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Ukuran <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="ukuran" placeholder="Contoh: 120x60x75 cm" required>
                        </div>

                        <!-- Baris 4: Deskripsi -->
                        <div class="col-12 mb-3">
                            <label class="form-label fw-bold">Deskripsi Produk <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="deskripsi" rows="3" placeholder="Masukkan Deskripsi Produk" required></textarea>
                        </div>

                        <!-- Baris 5: Upload Gambar -->
                        <div class="col-12 mb-2">
                            <label class="form-label fw-bold">Gambar Produk <span class="text-danger">*</span></label>
                            <input type="file" class="form-control" name="gambar" id="gambar" accept="image/*" required>
                            <div class="form-text text-muted">Format: JPG, JPEG, PNG (Maks. 2MB)</div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer" style="background-color: #f8f9fa;">
                    <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-4" style="background-color: #3d5a4a; border: none;">
                        <i class="fas fa-save me-1"></i> Simpan Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>