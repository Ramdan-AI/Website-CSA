@props([
    'id' => 'deleteModal',
    'title' => 'Hapus Data?',
    'message' => 'Tindakan ini tidak dapat dibatalkan.',
])

<div id="{{ $id }}"
    class="fixed inset-0 z-[9999] hidden items-center justify-center p-6 transition-opacity duration-300"
    style="background: rgba(0,0,0,.5); backdrop-filter: blur(3px);" onclick="closeDeleteModal('{{ $id }}', event)">
    <div id="{{ $id }}Content" class="relative w-full max-w-md transform scale-95 transition-all duration-300"
        onclick="event.stopPropagation()">
        <div class="csa-panel p-6 border border-[var(--border-strong)] shadow-2xl">
            <div class="text-center">
                <div class="w-14 h-14 mx-auto mb-4 rounded-2xl bg-red-500/10 flex items-center justify-center">
                    <x-admin.icon name="triangle-alert" :size="24" class="text-red-500" />
                </div>
                <h3 class="text-[16px] font-semibold text-[var(--text-primary)] mb-2">{{ $title }}</h3>
                <p class="text-[13px] text-[var(--text-muted)] mb-6">{{ $message }}</p>
                <div class="flex items-center justify-center gap-3">
                    <button type="button" onclick="closeDeleteModal('{{ $id }}')" class="csa-btn csa-btn-ghost">
                        Batal
                    </button>
                    <button type="button" onclick="document.getElementById('{{ $id }}Form').submit()"
                        class="csa-btn csa-btn-danger">
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
