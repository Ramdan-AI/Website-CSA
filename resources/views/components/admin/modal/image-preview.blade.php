@props([
    'id' => 'imagePreviewModal',
])

<div id="{{ $id }}"
    class="fixed inset-0 z-[9999] hidden items-center justify-center p-6 transition-opacity duration-300"
    style="backdrop-filter: blur(3px);" onclick="closeImagePreview('{{ $id }}', event)">
    <div id="{{ $id }}Content" class="relative w-full max-w-3xl transform scale-95 transition-all duration-300"
        onclick="event.stopPropagation()">
        <div class="bg-white rounded-2xl overflow-hidden shadow-md border border-gray-200">
            <button onclick="closeImagePreview('{{ $id }}')"
                class="absolute top-4 right-4 w-9 h-9 rounded-full bg-gray-100 border border-gray-200 flex items-center justify-center text-gray-500 hover:bg-gray-200 transition-colors z-10">
                <i class="fas fa-times text-sm"></i>
            </button>
            <div class="p-6 bg-gray-50">
                <img id="{{ $id }}Image" src="" alt=""
                    class="w-full max-h-[70vh] object-contain rounded-lg">
            </div>
            <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center">
                        <i class="fas fa-image text-blue-600 text-sm"></i>
                    </div>
                    <p id="{{ $id }}Title" class="text-sm font-semibold text-gray-900"></p>
                </div>
                <a id="{{ $id }}Download" href="" download
                    class="inline-flex items-center gap-2 px-4 py-2 bg-gray-900 text-white text-xs font-semibold rounded-lg hover:bg-gray-800 transition-colors">
                    <i class="fas fa-download text-[10px]"></i>
                    Download
                </a>
            </div>
        </div>
    </div>
</div>
