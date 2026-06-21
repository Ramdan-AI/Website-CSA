<div class="modal fade" id="globalDeleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content"
                style="border-radius: 16px; border: none; box-shadow: 0 20px 40px rgba(0,0,0,0.1);">
                <div class="modal-body p-4 p-md-5 text-center">
                    <div
                        style="width: 72px; height: 72px; background: #FEF2F2; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; color: #EF4444; font-size: 1.8rem;">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <h4 style="font-weight: 800; color: var(--text-main); margin-bottom: 12px;">Konfirmasi Penghapusan
                    </h4>
                    <p style="color: var(--text-muted); font-size: 0.95rem; margin-bottom: 32px;">
                        Tindakan ini bersifat permanen dan tidak dapat dibatalkan. Apakah Anda yakin ingin menghapus
                        data ini dari sistem?
                    </p>
                    <div class="d-flex justify-content-center gap-3">
                        <button type="button" class="btn-solid-light m-0" data-bs-dismiss="modal">Batalkan</button>
                        <button type="button" class="btn-csa-primary m-0"
                            style="background-color: #EF4444; box-shadow: none;" id="confirmDeleteBtn">
                            <i class="fas fa-trash me-2"></i> Ya, Hapus Data
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>