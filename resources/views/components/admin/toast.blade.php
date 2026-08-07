@props([
    'id' => 'globalToast',
    'successIcon' => 'fa-check-circle',
    'errorIcon' => 'fa-exclamation-circle',
    'warningIcon' => 'fa-exclamation-triangle',
    'infoIcon' => 'fa-info-circle',
])

<div id="{{ $id }}"
    class="fixed bottom-8 right-8 z-[9999] hidden transform translate-y-4 opacity-0 transition-all duration-500 ease-[cubic-bezier(0.34,1.56,0.64,1)]">

    <div
        class="relative bg-white/90 backdrop-blur-xl rounded-2xl border border-white/60 shadow-[0_8px_32px_rgba(0,0,0,0.08)] overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-blue-400/5 to-purple-400/5 pointer-events-none"></div>

        <div class="relative flex items-center gap-4 px-5 py-4 pr-12">
            {{-- Icon --}}
            <div id="{{ $id }}Icon" class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0">
            </div>

            {{-- Content --}}
            <div class="min-w-0">
                <p id="{{ $id }}Title" class="text-sm font-bold text-gray-900"></p>
                <p id="{{ $id }}Message" class="text-xs text-gray-500 mt-0.5"></p>
            </div>

            {{-- Close --}}
            <button onclick="hideToast('{{ $id }}')"
                class="absolute right-3 top-3 w-6 h-6 rounded-lg flex items-center justify-center text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-colors">
                <i class="fas fa-times text-[10px]"></i>
            </button>
        </div>

        {{-- Progress bar --}}
        <div class="relative h-1 bg-gray-100">
            <div id="{{ $id }}Progress"
                class="absolute inset-y-0 left-0 bg-gradient-to-r from-blue-500 to-blue-600 rounded-full transition-all duration-300"
                style="width: 100%;"></div>
        </div>
    </div>
</div>

<script>
    function showToast(type = 'success', title = '', message = '') {
        const toast = document.getElementById('globalToast');
        const icon = document.getElementById('globalToastIcon');
        const titleEl = document.getElementById('globalToastTitle');
        const messageEl = document.getElementById('globalToastMessage');
        const progress = document.getElementById('globalToastProgress');

        // Reset progress
        progress.style.width = '100%';
        progress.style.transition = 'none';

        // Set icon & colors based on type
        const configs = {
            success: {
                icon: 'fa-check-circle',
                bg: 'bg-emerald-50',
                text: 'text-emerald-600',
                progress: 'from-emerald-500 to-emerald-600'
            },
            error: {
                icon: 'fa-exclamation-circle',
                bg: 'bg-red-50',
                text: 'text-red-600',
                progress: 'from-red-500 to-red-600'
            },
            warning: {
                icon: 'fa-exclamation-triangle',
                bg: 'bg-amber-50',
                text: 'text-amber-600',
                progress: 'from-amber-500 to-amber-600'
            },
            info: {
                icon: 'fa-info-circle',
                bg: 'bg-blue-50',
                text: 'text-blue-600',
                progress: 'from-blue-500 to-blue-600'
            }
        };

        const config = configs[type] || configs.success;

        icon.className = `w-10 h-10 rounded-xl flex items-center justify-center shrink-0 ${config.bg} ${config.text}`;
        icon.innerHTML = `<i class="fas ${config.icon}"></i>`;

        titleEl.textContent = title;
        messageEl.textContent = message;

        progress.className =
            `absolute inset-y-0 left-0 bg-gradient-to-r ${config.progress} rounded-full transition-all duration-300`;

        // Show toast
        toast.classList.remove('hidden');
        requestAnimationFrame(() => {
            toast.style.opacity = '1';
            toast.style.transform = 'translateY(0)';
        });

        // Start progress bar animation
        setTimeout(() => {
            progress.style.transition = 'width 4s linear';
            progress.style.width = '0%';
        }, 100);

        // Auto hide after 4 seconds
        clearTimeout(toast._timeout);
        toast._timeout = setTimeout(() => {
            hideToast('globalToast');
        }, 4000);
    }

    function hideToast(id) {
        const toast = document.getElementById(id);

        toast.style.opacity = '0';
        toast.style.transform = 'translateY(16px)';

        setTimeout(() => {
            toast.classList.add('hidden');
        }, 500);
    }

    // Flash message handler
    document.addEventListener('DOMContentLoaded', function() {
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
