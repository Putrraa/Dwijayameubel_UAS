<div class="modal fade" id="modalHapusBarang{{ $data->id }}" tabindex="-1"
    aria-labelledby="modalHapusBarangLabel{{ $data->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered"> <!-- Tambahkan centered supaya di tengah layar -->
        <div class="modal-content" style="border-radius: 15px; border: none; overflow: hidden;">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="modalHapusBarangLabel{{ $data->id }}">
                    <i class="fas fa-exclamation-triangle me-2"></i> Konfirmasi Hapus
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body p-4 text-center">
                <i class="fas fa-trash-alt fa-3x text-danger mb-3"></i>
                <p class="mb-0">Apakah Anda yakin ingin menghapus produk:</p>
                <h5 class="fw-bold text-dark mt-2">{{ $data->nama_barang }}?</h5>
                <p class="text-muted small mt-2">Data yang sudah dihapus tidak dapat dikembalikan.</p>
            </div>
            <div class="modal-footer" style="background-color: #f8f9fa; border: none;">
                <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Batal</button>
                <form action="{{ route('barang.destroy', $data->id ) }}" method="POST" style="display:inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger px-4 shadow-sm">
                        Ya, Hapus Sekarang
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>