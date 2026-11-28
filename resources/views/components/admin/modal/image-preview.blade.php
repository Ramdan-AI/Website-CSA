@props([
    'id' => 'imagePreviewModal',
])

<div id="{{ $id }}"
    class="fixed inset-0 z-[9999] hidden items-center justify-center p-6 transition-opacity duration-300"
    style="background: rgba(0,0,0,.5); backdrop-filter: blur(3px);" onclick="closeImagePreview('{{ $id }}', event)">
    <div id="{{ $id }}Content" class="relative w-full max-w-3xl transform scale-95 transition-all duration-300"
        onclick="event.stopPropagation()">
        <div class="bg-[var(--bg-card)] rounded-2xl overflow-hidden shadow-2xl border border-[var(--border-strong)]">
            <button onclick="closeImagePreview('{{ $id }}')"
                class="absolute top-4 right-4 w-9 h-9 rounded-full bg-[var(--bg-muted)] border border-[var(--border-soft)] flex items-center justify-center text-[var(--text-secondary)] hover:bg-[var(--bg-hover)] transition-colors z-10">
                <x-admin.icon name="x" :size="16" />
            </button>
            <div class="p-6 bg-[var(--bg-muted)]">
                <img id="{{ $id }}Image" src="" alt=""
                    class="w-full max-h-[70vh] object-contain rounded-lg">
            </div>
            <div class="px-6 py-4 border-t border-[var(--border-soft)] flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-blue-500/10 flex items-center justify-center">
                        <x-admin.icon name="image" :size="16" class="text-blue-600 dark:text-blue-400" />
                    </div>
                    <p id="{{ $id }}Title" class="text-sm font-semibold text-[var(--text-primary)]"></p>
                </div>
                <a id="{{ $id }}Download" href="" download
                    class="inline-flex items-center gap-2 px-4 py-2 bg-[var(--text-primary)] text-[var(--bg-card)] text-xs font-semibold rounded-lg hover:opacity-90 transition-colors">
                    <x-admin.icon name="download" :size="12" />
                    Download
                </a>
            </div>
        </div>
    </div>
</div>
