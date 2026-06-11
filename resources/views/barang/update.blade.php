<div class="modal fade" id="modalEditBarang{{ $data->id }}" tabindex="-1" aria-labelledby="modalEditBarangLabel{{ $data->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border-radius: 15px; border: none; overflow: hidden;">
            
            <!-- Header dengan warna Hijau Dwijaya Mebel -->
            <div class="modal-header" style="background-color: #3d5a4a; color: white;">
                <h5 class="modal-title" id="modalEditBarangLabel{{ $data->id }}">
                    <i class="fas fa-edit me-2"></i> Edit Data Produk
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="{{ route('barang.update', $data->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                {{-- Gunakan method PUT/PATCH jika route kamu menggunakan Route::put atau Route::patch --}}
                {{-- @method('PUT') --}}
                
                <div class="modal-body p-4">
                    <div class="row">
                        <!-- Baris 1: Nama & Kategori -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Nama Barang <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="nama_barang" value="{{ $data->nama_barang }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Kategori <span class="text-danger">*</span></label>
                            <select name="kategori_id" class="form-select" required>
                                <option value="" disabled>-- Pilih Kategori --</option>
                                @foreach($kategori as $k)
                                    <option value="{{ $k->id }}" {{ $data->kategori_id == $k->id ? 'selected' : '' }}>
                                        {{ $k->nama_kategori }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Baris 2: Harga & Stok -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Harga (Rp) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" class="form-control" name="harga" value="{{ $data->harga }}" required>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Stok <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" name="stok" value="{{ $data->stok }}" required>
                        </div>

                        <!-- Baris 3: Bahan (Dropdown) & Ukuran -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Bahan <span class="text-danger">*</span></label>
                            <select name="bahan_id" class="form-select" required>
                                <option value="" disabled>-- Pilih Bahan --</option>
                                @foreach($bahan as $b)
                                    <option value="{{ $b->id }}" {{ $data->bahan_id == $b->id ? 'selected' : '' }}>
                                        {{ $b->nama_bahan }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Ukuran <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="ukuran" value="{{ $data->ukuran }}" required>
                        </div>

                        <!-- baris 4: Deskripsi -->
                        <div class="col-12 mb-3">
                            <label class="form-label fw-bold">Deskripsi <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="deskripsi" rows="3" required>{{ $data->deskripsi }}</textarea>
                        </div>

                        <!-- Baris 5: Update Gambar -->
                        <div class="col-12 mb-3">
                            <label class="form-label fw-bold">Update Gambar Produk</label>
                            <div class="d-flex align-items-start gap-3">
                                @if($data->gambar)
                                    <div class="text-center">
                                        {{-- Perbaikan path storage agar gambar muncul --}}
                                        <img src="{{ asset('storage/barang/' . $data->gambar) }}" class="rounded border mb-1" width="80" height="80" style="object-fit: cover;">
                                        <div class="small text-muted">Foto Lama</div>
                                    </div>
                                @endif
                                <div class="flex-grow-1">
                                    <input type="file" class="form-control" name="gambar" accept="image/*">
                                    <div class="form-text text-muted small">Kosongkan jika tidak ingin mengganti gambar.</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer" style="background-color: #f8f9fa;">
                    <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning px-4 fw-bold text-white shadow-sm">
                        <i class="fas fa-save me-1"></i> Update Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>