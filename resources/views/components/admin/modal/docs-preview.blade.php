<div id="documentPreviewModal" class="hidden fixed inset-0 z-[9999] items-center justify-center"
    style="opacity: 0; transition: opacity 0.3s ease;">

    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="closeDocumentPreview(event)"></div>

    <div id="documentPreviewModalContent"
        class="relative bg-white rounded-[28px] shadow-2xl w-full max-w-4xl max-h-[90vh] flex flex-col overflow-hidden mx-4"
        style="transform: scale(0.95); transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);">

        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-teal-100 flex items-center justify-center text-teal-600">
                    <i class="fas fa-file-alt"></i>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-gray-900" id="documentPreviewModalTitle">Judul Dokumen</h3>
                    <p class="text-[10px] text-gray-400 font-medium">Preview Dokumen</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <a href="#" id="documentPreviewModalDownload"
                    class="w-9 h-9 rounded-xl bg-teal-50 flex items-center justify-center text-teal-600 hover:bg-teal-500 hover:text-white transition-all duration-200"
                    title="Download">
                    <i class="fas fa-download text-sm"></i>
                </a>
                <button onclick="closeDocumentPreview()"
                    class="w-9 h-9 rounded-xl bg-gray-50 flex items-center justify-center text-gray-400 hover:bg-gray-100 hover:text-gray-600 transition-all duration-200">
                    <i class="fas fa-times text-sm"></i>
                </button>
            </div>
        </div>

        <div class="flex-1 overflow-auto p-4 bg-gray-50">
            <iframe id="documentPreviewFrame" src=""
                class="w-full h-[70vh] rounded-2xl border border-gray-200 bg-white" frameborder="0">
            </iframe>

            <div id="documentPreviewUnsupported" class="hidden flex items-center justify-center h-[70vh]">
                <div class="text-center">
                    <div class="w-20 h-20 rounded-full bg-teal-100 flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-file-alt text-3xl text-teal-500"></i>
                    </div>
                    <p class="text-sm font-semibold text-gray-900 mb-1">Preview Tidak Tersedia</p>
                    <p class="text-xs text-gray-400 mb-4">Format file tidak mendukung preview langsung.</p>
                    <a href="#" id="documentPreviewModalDownloadAlt"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-teal-500 to-teal-600 text-white text-sm font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all">
                        <i class="fas fa-download text-xs"></i>
                        Download File
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // ... existing functions (openDeleteModal, closeDeleteModal, etc.) ...

    function openDocumentPreview(fileUrl, title, downloadUrl) {
        const modal = document.getElementById('documentPreviewModal');
        const content = document.getElementById('documentPreviewModalContent');
        const frame = document.getElementById('documentPreviewFrame');
        const unsupported = document.getElementById('documentPreviewUnsupported');
        const titleEl = document.getElementById('documentPreviewModalTitle');
        const downloadLink = document.getElementById('documentPreviewModalDownload');
        const downloadLinkAlt = document.getElementById('documentPreviewModalDownloadAlt');

        titleEl.textContent = title;
        downloadLink.href = downloadUrl;
        downloadLinkAlt.href = downloadUrl;

        const ext = fileUrl.split('.').pop().toLowerCase();
        const previewableExts = ['pdf', 'jpg', 'jpeg', 'png', 'gif', 'webp', 'txt'];

        if (previewableExts.includes(ext)) {
            frame.src = fileUrl;
            frame.classList.remove('hidden');
            unsupported.classList.add('hidden');
        } else {
            frame.classList.add('hidden');
            unsupported.classList.remove('hidden');
        }

        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';

        requestAnimationFrame(() => {
            modal.style.opacity = '1';
            content.style.transform = 'scale(1)';
        });
    }

    function closeDocumentPreview(event) {
        if (event && event.target !== document.getElementById('documentPreviewModal')) return;

        const modal = document.getElementById('documentPreviewModal');
        const content = document.getElementById('documentPreviewModalContent');
        const frame = document.getElementById('documentPreviewFrame');

        modal.style.opacity = '0';
        content.style.transform = 'scale(0.95)';

        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.style.overflow = '';
            frame.src = ''; // Reset iframe
        }, 300);
    }

    // Update keyboard escape handler
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            document.querySelectorAll('[id$="Modal"]').forEach(modal => {
                if (!modal.classList.contains('hidden')) {
                    if (modal.id === 'deleteModal') {
                        closeDeleteModal('deleteModal');
                    } else if (modal.id === 'imagePreviewModal') {
                        closeImagePreview('imagePreviewModal');
                    } else if (modal.id === 'documentPreviewModal') {
                        closeDocumentPreview();
                    }
                }
            });
        }
    });
</script>
