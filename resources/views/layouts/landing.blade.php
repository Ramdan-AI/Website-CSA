<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#ffffff" media="(prefers-color-scheme: light)">
    <meta name="theme-color" content="#0a0a0b" media="(prefers-color-scheme: dark)">
    <title>Creative Student Association | Komunitas Teknologi STMIK Mardira Indonesia</title>
    <meta name="description" content="CSA adalah organisasi mahasiswa IT di STMIK Mardira Indonesia. Anggotanya belajar pemrograman dan multimedia lewat proyek nyata.">
    <link rel="icon" href="{{ asset('storage/system/logos/csa-logo.png') }}" type="image/png">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800|inter:400,500,600,700&family=jetbrains-mono:400,500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        // Tema diset sebelum paint agar tidak ada kedipan warna.
        (() => {
            const stored = localStorage.getItem('theme');
            const dark = stored === 'dark' || (!stored && window.matchMedia('(prefers-color-scheme: dark)').matches);
            document.documentElement.classList.toggle('dark', dark);
        })();
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/countup.js/2.8.0/countUp.umd.js"></script>
    @stack('styles')
    <style>
        :root {
            --paper: #ffffff;
            --paper-sunken: #f7f8fa;
            --ink: #0f1419;
            --ink-soft: #4b5563;
            --ink-faint: #8b929e;
            --line: #e5e7eb;
            --line-strong: #d1d5db;
            --accent: #1a73e8;
            --accent-soft: #e8f0fe;
            --accent-deep: #1557b0;
            --accent-solid: #1a73e8;
            --accent-solid-hover: #1557b0;
            --on-accent: #ffffff;
        }
        .dark {
            --paper: #0a0a0b;
            --paper-sunken: #111113;
            --ink: #f3f4f6;
            --ink-soft: #a1a5ad;
            --ink-faint: #6b7078;
            --line: #232326;
            --line-strong: #34343a;
            --accent: #8ab4f8;
            --accent-soft: #17253d;
            --accent-deep: #aecbfa;
            --accent-solid: #8ab4f8;
            --accent-solid-hover: #aecbfa;
            --on-accent: #0a0a0b;
        }
        html { color-scheme: light; }
        html.dark { color-scheme: dark; }
        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background: var(--paper);
            color: var(--ink);
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            transition: background-color .3s ease, color .3s ease;
        }
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Plus Jakarta Sans', 'Inter', system-ui, sans-serif;
        }
        .font-mono { font-family: 'JetBrains Mono', monospace; }

        ::selection { background: var(--accent-soft); color: var(--accent-deep); }

        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        .no-scrollbar::-webkit-scrollbar { display: none; }

        [data-slider-track] { touch-action: pan-x pan-y; -webkit-overflow-scrolling: touch; }
        [data-slider-track].is-dragging { cursor: grabbing; user-select: none; }
        [data-slider-track]:not(.is-dragging) { cursor: grab; }

        /* Strip foto: dua baris bergerak berlawanan arah. */
        [data-marquee] { overflow: hidden; }
        [data-marquee-track] { will-change: transform; }

        /* Judul hero: reveal per baris dengan clip-path, bukan fade. */
        .line-mask { display: block; overflow: hidden; }
        .line-mask > span {
            display: block;
            transform: translateY(105%);
            will-change: transform;
        }
        .js-ready .line-mask > span { transition: transform 1s cubic-bezier(.19, 1, .22, 1); }
        .js-ready .line-mask.is-in > span { transform: translateY(0); }

        @media (prefers-reduced-motion: reduce) {
            * { animation: none !important; transition: none !important; }
            .line-mask > span { transform: none !important; }
        }
    </style>
</head>
<body class="antialiased">
    <x-public.navigation.navbar />

    <main class="pt-16">
        @yield('content')
    </main>

    <x-public.footer />

    {{-- Modal pendaftaran ditutup --}}
    <div x-data="{ open: {{ (session('registration_closed') || (session('error') && str_contains(session('error'), 'ditutup'))) ? 'true' : 'false' }} }"
        x-init="window.addEventListener('open-register-modal', () => open = true)">
        <div x-show="open" x-cloak
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-[100] flex items-center justify-center p-6 bg-black/40 backdrop-blur-sm"
            style="display: none;">
            <div @click.away="open = false"
                class="w-full max-w-sm rounded-xl border p-8 text-center"
                style="background: var(--paper); border-color: var(--line);">
                <div class="w-14 h-14 rounded-full flex items-center justify-center mx-auto mb-5" style="background: var(--accent-soft);">
                    <i class="fas fa-circle-info text-[22px]" style="color: var(--accent);"></i>
                </div>
                <h3 class="text-[17px] font-bold mb-2" style="color: var(--ink);">Pendaftaran ditutup</h3>
                <p class="text-[14px] leading-relaxed mb-6" style="color: var(--ink-soft);">
                    Admin belum membuka pendaftaran. Cek kembali nanti atau hubungi pengurus CSA.
                </p>
                <button @click="open = false"
                    class="w-full py-3 text-white text-[14px] font-medium rounded-lg"
                    style="background: var(--accent);">
                    Mengerti
                </button>
            </div>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
