@props([
    'id' => 'globalToast',
])

@php
    // SVG inline agar ikon selalu tampil tanpa bergantung pada JS/lucide.
    $icons = [
        'success' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="m8.5 12 2.5 2.5 4.5-5"/></svg>',
        'error' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="m15 9-6 6M9 9l6 6"/></svg>',
        'warning' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.3 3.9 1.8 18a2 2 0 0 0 1.7 3h17a2 2 0 0 0 1.7-3L13.7 3.9a2 2 0 0 0-3.4 0Z"/><path d="M12 9v4M12 17h.01"/></svg>',
        'info' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 16v-4M12 8h.01"/></svg>',
    ];
@endphp

<div id="{{ $id }}"
    class="fixed bottom-6 right-6 z-[9999] hidden max-w-[calc(100vw-3rem)] transform translate-y-4 opacity-0 transition-all duration-500 ease-[cubic-bezier(0.34,1.56,0.64,1)]">

    <div class="relative csa-panel rounded-2xl border border-[var(--border-strong)] shadow-2xl overflow-hidden">
        <div class="flex items-center gap-3.5 px-4 py-3.5 pr-11">
            {{-- Icon (SVG inline, warna diatur JS) --}}
            <span id="{{ $id }}Icon" class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0 [&>svg]:w-[18px] [&>svg]:h-[18px]">
                {!! $icons['success'] !!}
            </span>

            {{-- Content --}}
            <div class="min-w-0">
                <p id="{{ $id }}Title" class="text-[13px] font-semibold text-[var(--text-primary)]"></p>
                <p id="{{ $id }}Message" class="text-[12px] text-[var(--text-muted)] mt-0.5"></p>
            </div>

            {{-- Close --}}
            <button type="button" onclick="hideToast('{{ $id }}')"
                class="absolute right-2.5 top-1/2 -translate-y-1/2 w-7 h-7 rounded-lg flex items-center justify-center text-[var(--text-muted)] hover:bg-[var(--bg-hover)] hover:text-[var(--text-primary)] transition-colors"
                aria-label="Tutup">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-[15px] h-[15px]"><path d="M18 6 6 18M6 6l12 12"/></svg>
            </button>
        </div>

        {{-- Progress bar --}}
        <div class="relative h-0.5 bg-[var(--border-soft)]">
            <div id="{{ $id }}Progress" class="absolute inset-y-0 left-0 rounded-full bg-[var(--accent)]" style="width: 100%;"></div>
        </div>
    </div>
</div>

<script>
    window.__toastIcons = @json($icons);

    function showToast(type = 'success', title = '', message = '') {
        const toast = document.getElementById('globalToast');
        if (!toast) return;

        const icon = document.getElementById('globalToastIcon');
        const titleEl = document.getElementById('globalToastTitle');
        const messageEl = document.getElementById('globalToastMessage');
        const progress = document.getElementById('globalToastProgress');

        progress.style.transition = 'none';
        progress.style.width = '100%';

        const configs = {
            success: { cls: 'bg-emerald-500/12 text-emerald-600 dark:text-emerald-400', bar: 'var(--green)' },
            error: { cls: 'bg-red-500/12 text-red-500', bar: 'var(--red)' },
            warning: { cls: 'bg-amber-500/12 text-amber-600 dark:text-amber-400', bar: 'var(--amber)' },
            info: { cls: 'bg-[var(--accent-subtle)] text-[var(--accent-bright)]', bar: 'var(--accent)' },
        };

        const config = configs[type] || configs.success;

        icon.className = `w-9 h-9 rounded-xl flex items-center justify-center shrink-0 [&>svg]:w-[18px] [&>svg]:h-[18px] ${config.cls}`;
        icon.innerHTML = window.__toastIcons[type] || window.__toastIcons.success;

        titleEl.textContent = title;
        messageEl.textContent = message;

        progress.style.background = config.bar;

        toast.classList.remove('hidden');
        requestAnimationFrame(() => {
            toast.style.opacity = '1';
            toast.style.transform = 'translateY(0)';
        });

        setTimeout(() => {
            progress.style.transition = 'width 4s linear';
            progress.style.width = '0%';
        }, 100);

        clearTimeout(toast._timeout);
        toast._timeout = setTimeout(() => hideToast('globalToast'), 4000);
    }

    function hideToast(id) {
        const toast = document.getElementById(id);
        if (!toast) return;

        toast.style.opacity = '0';
        toast.style.transform = 'translateY(16px)';

        setTimeout(() => toast.classList.add('hidden'), 500);
    }

    document.addEventListener('DOMContentLoaded', function () {
        @if (session('success'))
            showToast('success', 'Berhasil', @json(session('success')));
        @endif

        @if (session('error'))
            showToast('error', 'Gagal', @json(session('error')));
        @endif

        @if (session('warning'))
            showToast('warning', 'Perhatian', @json(session('warning')));
        @endif

        @if (session('info'))
            showToast('info', 'Informasi', @json(session('info')));
        @endif
    });
</script>
