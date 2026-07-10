@props([
    'id' => 'imagePreviewModal',
])

<div id="{{ $id }}"
    class="fixed inset-0 z-[9999] hidden items-center justify-center p-6 transition-opacity duration-300"
    style="backdrop-filter: blur(3px);" onclick="closeImagePreview('{{ $id }}', event)">
    <div id="{{ $id }}Content" class="relative w-full max-w-3xl transform scale-95 transition-all duration-300"
        onclick="event.stopPropagation()">
        <div
            class="relative bg-white/90 backdrop-blur-xl rounded-[40px] overflow-hidden shadow-[0_25px_60px_rgba(0,0,0,0.2)] border border-white/20">
            <div class="absolute inset-0 bg-gradient-to-br from-blue-400/5 to-purple-400/5"></div>
            <button onclick="closeImagePreview('{{ $id }}')"
                class="absolute top-5 right-5 w-10 h-10 rounded-full bg-white/80 backdrop-blur-xl border border-white/60 flex items-center justify-center text-gray-500 hover:text-gray-900 hover:bg-white hover:shadow-lg transition-all z-10">
                <i class="fas fa-times text-sm"></i>
            </button>
            <div class="relative p-8">
                <img id="{{ $id }}Image" src="" alt=""
                    class="w-full max-h-[70vh] object-contain rounded-2xl">
            </div>
            <div class="relative px-8 pb-6 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div
                        class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center">
                        <i class="fas fa-image text-white text-xs"></i>
                    </div>
                    <p id="{{ $id }}Title" class="text-base font-bold text-gray-900"></p>
                </div>
                <a id="{{ $id }}Download" href="" download
                    class="inline-flex items-center gap-2 px-4 py-2.5 bg-gray-900 text-white text-xs font-semibold rounded-xl hover:bg-gray-800 transition-all">
                    <i class="fas fa-download text-[10px]"></i>
                    Download
                </a>
            </div>
        </div>
    </div>
</div>
