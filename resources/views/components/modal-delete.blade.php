<div class="modal fade" id="globalDeleteModal" tabindex="-1" aria-hidden="true" 
     style="position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; z-index: 99999 !important;">
    
    <div class="modal-backdrop-blur" 
         style="position: absolute; inset: 0; bg-color: rgba(15, 23, 42, 0.15); backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px); pointer-events: none;">
    </div>

    <div class="modal-dialog modal-dialog-centered" 
         style="margin: 0 auto; min-height: 100vh; display: flex; align-items: center; justify-content: center; max-width: 500px; width: 90%; position: relative; z-index: 2;">
        
        <div class="modal-content"
             style="border-radius: 24px; border: 1px solid rgba(255, 255, 255, 0.7); background: rgba(255, 255, 255, 0.95); backdrop-blur: xl; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15); width: 100%;">
            
            <div class="modal-body p-6 p-md-8 text-center">
                {{-- Icon Warning dengan Animasi Pulse Halus --}}
                <div style="width: 80px; height: 80px; background: #FEF2F2; border-radius: 24px; display: flex; align-items: center; justify-content: center; margin: 0 auto 24px; color: #EF4444; font-size: 2rem; box-shadow: 0 10px 15px -3px rgba(239, 68, 68, 0.1);">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                
                <h4 style="font-weight: 800; color: #1e293b; font-size: 1.35rem; margin-bottom: 12px; tracking: -0.02em;">
                    Konfirmasi Penghapusan
                </h4>
                
                <p style="color: #64748b; font-size: 0.95rem; line-height: 1.6; margin-bottom: 32px; padding: 0 10px;">
                    Tindakan ini bersifat permanen dan tidak dapat dibatalkan. Apakah Anda yakin ingin menghapus data ini dari sistem?
                </p>
                
                <div class="d-flex justify-content-center gap-3">
                    <button type="button" 
                            class="px-5 py-3 bg-gray-100 text-gray-700 text-sm font-semibold rounded-2xl hover:bg-gray-200 transition-all duration-300 border-0 outline-none cursor-pointer" 
                            data-bs-dismiss="modal">
                        Batalkan
                    </button>
                    <button type="button" 
                            class="px-5 py-3 bg-gradient-to-r from-red-500 to-red-600 text-white text-sm font-semibold rounded-2xl shadow-[0_4px_20px_rgba(239,68,68,0.25)] hover:shadow-[0_4px_25px_rgba(239,68,68,0.4)] hover:scale-105 transition-all duration-300 border-0 outline-none cursor-pointer flex items-center gap-2" 
                            id="confirmDeleteBtn">
                        <i class="fas fa-trash text-xs"></i> Ya, Hapus Data
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Hilangkan backdrop bawaan Bootstrap agar tidak terjadi tabrakan double-darkening */
    .modal-backdrop {
        display: none !important;
    }
    
    /* Efek transisi halus saat modal memudar muncul */
    #globalDeleteModal.fade {
        transition: opacity 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        background: rgba(15, 23, 42, 0.2);
        backdrop-filter: blur(0px);
        -webkit-backdrop-filter: blur(0px);
    }
    #globalDeleteModal.show {
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
    }
    
    #globalDeleteModal .modal-dialog {
        transform: scale(0.95) translateY(10px);
        transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1);
    }
    #globalDeleteModal.show .modal-dialog {
        transform: scale(1) translateY(0);
    }
</style>