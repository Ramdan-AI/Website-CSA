<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Creative Student Association — Where Creators Meet Builders</title>
    <meta name="description" content="Organisasi berbasis IT di STMIK Mardira Indonesia. Pemrograman, Multimedia, dan segala hal tentang teknologi kreatif.">
    <link rel="icon" href="{{ asset('storage/system/logos/csa-logo.png') }}" type="image/png">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800|inter:400,500,600,700&family=jetbrains-mono:400,500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/countup.js/2.8.0/countUp.umd.js"></script>
    @stack('styles')
    <style>
        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background: #f8fafc;
            color: #0f172a;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Plus Jakarta Sans', 'Inter', system-ui, sans-serif;
        }
        .font-mono {
            font-family: 'JetBrains Mono', monospace;
        }
        .selection\:bg-blue-100 ::selection {
            background: rgba(59, 130, 246, 0.15);
        }
        html {
            scroll-behavior: smooth;
        }
        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        [data-slider-track] {
            touch-action: pan-x pan-y;
            -webkit-overflow-scrolling: touch;
        }
        [data-slider-track].is-dragging {
            cursor: grabbing;
            user-select: none;
        }
        [data-slider-track]:not(.is-dragging) {
            cursor: grab;
        }
        [data-marquee] {
            overflow: hidden;
        }
        [data-marquee-track] {
            will-change: transform;
        }
    </style>
</head>
<body class="antialiased">
    <x-public.navigation.navbar />

    <main class="pt-16">
        @yield('content')
    </main>

    <x-public.footer />

    {{-- Global "Registration Closed" modal (lives outside transformed navbar) --}}
    <div x-data="{ open: {{ (session('registration_closed') || (session('error') && str_contains(session('error'), 'ditutup'))) ? 'true' : 'false' }} }"
        x-init="window.addEventListener('open-register-modal', () => open = true)">
        <div x-show="open"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-[100] flex items-center justify-center p-6 bg-black/30 backdrop-blur-sm"
            style="display: none;">
            <div @click.away="open = false"
                class="w-full max-w-sm bg-white rounded-[28px] p-8 text-center shadow-2xl">
                <div class="w-16 h-16 rounded-full bg-amber-50 flex items-center justify-center mx-auto mb-5">
                    <i class="fas fa-user-plus text-2xl text-amber-400"></i>
                </div>
                <h3 class="text-[17px] font-bold text-gray-900 mb-2">Pendaftaran Ditutup</h3>
                <p class="text-[13px] text-gray-400 leading-relaxed mb-6">
                    Saat ini admin belum membuka pendaftaran. Silakan cek kembali nanti atau hubungi pengurus CSA.
                </p>
                <button @click="open = false"
                    class="w-full py-2.5 bg-gray-900 text-white text-[13px] font-semibold rounded-2xl hover:scale-[1.02] active:scale-[0.98] transition-all duration-300">
                    Mengerti
                </button>
            </div>
        </div>
    </div>

    @stack('scripts')
</body>
</html>