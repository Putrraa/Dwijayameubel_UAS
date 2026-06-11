<!-- Modal Tambah Kategori -->
<div class="modal fade" id="modalTambahkategori" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border-radius: 15px; border: none; overflow: hidden;">

            <!-- Header -->
            <div class="modal-header" style="background-color: #3d5a4a; color: white;">
                <h5 class="modal-title">
                    <i class="fas fa-plus-circle me-2"></i> Tambah Kategori & Bahan Baru
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body p-4">

                <!-- NOTIF KATEGORI -->
                <div id="notif-kategori" class="alert d-none mb-3" role="alert"></div>

                <!-- FORM KATEGORI -->
                <form id="formKategori" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-4">
                        <label class="form-label fw-bold">Nama Kategori <span class="text-danger">*</span></label>
                        <input type="text" id="inputKategori" class="form-control"
                            name="nama_kategori" placeholder="Contoh: Lemari Pakaian">

                        <label class="form-label fw-bold mt-2">Gambar Kategori</label>
                        <input type="file" id="inputGambarKategori" class="form-control"
                            name="gambar" accept="image/*">

                        <div class="d-flex justify-content-end mt-2">
                            <button type="submit" id="btnKategori" class="btn text-white" style="background-color: #3d5a4a;">
                                <span id="loadingKategori" class="spinner-border spinner-border-sm d-none me-1"></span>
                                Simpan Kategori
                            </button>
                        </div>
                    </div>
                </form>

                <hr>

                <!-- NOTIF BAHAN -->
                <div id="notif-bahan" class="alert d-none mb-3" role="alert"></div>

                <!-- FORM BAHAN -->
                <form id="formBahan">
                    @csrf
                    <div>
                        <label class="form-label fw-bold">
                            Nama Bahan <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               id="inputBahan"
                               class="form-control"
                               name="nama_bahan"
                               placeholder="Contoh: Kayu Jati">
                        <div class="d-flex justify-content-end mt-2">
                            <button type="submit" id="btnBahan" class="btn text-white" style="background-color: #3d5a4a;">
                                <span id="loadingBahan" class="spinner-border spinner-border-sm d-none me-1"></span>
                                Simpan Bahan
                            </button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<script>
function showNotif(elId, type, message) {
    const el = document.getElementById(elId);
    el.className = `alert alert-${type}`;
    el.innerHTML = message;
    el.classList.remove('d-none');

    // Auto hilang setelah 4 detik
    setTimeout(() => {
        el.classList.add('d-none');
    }, 4000);
}

// SUBMIT KATEGORI
document.getElementById('formKategori').addEventListener('submit', function(e) {
    e.preventDefault();

    const btn     = document.getElementById('btnKategori');
    const loading = document.getElementById('loadingKategori');

    btn.disabled = true;
    loading.classList.remove('d-none');

    // ✅ Pakai FormData agar bisa kirim file
    const formData = new FormData(this);

    fetch("{{ route('kategori.store') }}", {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
        },
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            showNotif('notif-kategori', 'success', '✅ ' + data.message);
            document.getElementById('inputKategori').value = '';
            document.getElementById('inputGambarKategori').value = '';
        } else {
            showNotif('notif-kategori', 'danger', '❌ ' + data.message);
        }
    })
    .catch(() => showNotif('notif-kategori', 'danger', '❌ Terjadi kesalahan.'))
    .finally(() => {
        btn.disabled = false;
        loading.classList.add('d-none');
    });
});

// SUBMIT BAHAN
document.getElementById('formBahan').addEventListener('submit', function(e) {
    e.preventDefault();

    const input   = document.getElementById('inputBahan');
    const btn     = document.getElementById('btnBahan');
    const loading = document.getElementById('loadingBahan');

    btn.disabled = true;
    loading.classList.remove('d-none');

    fetch("{{ route('bahan.store') }}", {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ nama_bahan: input.value })
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            showNotif('notif-bahan', 'success', '✅ ' + data.message);
            input.value = '';
        } else {
            showNotif('notif-bahan', 'danger', '❌ ' + data.message);
        }
    })
    .catch(() => {
        showNotif('notif-bahan', 'danger', '❌ Terjadi kesalahan, coba lagi.');
    })
    .finally(() => {
        btn.disabled = false;
        loading.classList.add('d-none');
    });
});
</script>