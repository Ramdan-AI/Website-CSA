@props([
    'id' => 'deleteModal',
    'title' => 'Hapus Data?',
    'message' => 'Tindakan ini tidak dapat dibatalkan.',
])

<div id="{{ $id }}"
    class="fixed inset-0 z-[9999] hidden items-center justify-center p-6 transition-opacity duration-300"
    style="backdrop-filter: blur(3px);" onclick="closeDeleteModal('{{ $id }}', event)">
    <div id="{{ $id }}Content" class="relative w-full max-w-md transform scale-95 transition-all duration-300"
        onclick="event.stopPropagation()">
        <div
            class="relative bg-white/90 backdrop-blur-xl rounded-[32px] p-8 border border-white/60 shadow-[0_25px_60px_rgba(0,0,0,0.15)]">
            <div
                class="absolute inset-0 bg-gradient-to-br from-red-400/5 to-rose-400/5 rounded-[32px] pointer-events-none">
            </div>
            <div class="relative text-center">
                <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-red-50 flex items-center justify-center">
                    <i class="fas fa-exclamation-triangle text-2xl text-red-500"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">{{ $title }}</h3>
                <p class="text-sm text-gray-500 mb-6">{{ $message }}</p>
                <div class="flex items-center justify-center gap-3">
                    <button onclick="closeDeleteModal('{{ $id }}')"
                        class="px-5 py-2.5 bg-gray-100 text-gray-700 text-sm font-semibold rounded-xl hover:bg-gray-200 transition-colors">
                        Batal
                    </button>
                    <button onclick="document.getElementById('{{ $id }}Form').submit()"
                        class="px-5 py-2.5 bg-red-500 text-white text-sm font-semibold rounded-xl hover:bg-red-600 shadow-[0_4px_16px_rgba(239,68,68,0.3)] transition-all">
                        Hapus
                    </button>
                </div>
                <form id="{{ $id }}Form" method="POST" class="hidden">
                    @csrf
                    @method('DELETE')
                </form>
            </div>
        </div>
    </div>
</div>