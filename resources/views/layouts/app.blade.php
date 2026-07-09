<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'CSA STMIKMI'))</title>
    <meta name="description" content="@yield('meta_description', 'Creative Student Association STMIKMI — Komunitas inovasi dan kreativitas mahasiswa.')">

    <link rel="icon" href="{{ asset('storage/system/logos/csa.png') }}" type="image/png">
    <link rel="preconnect" href="https://fonts.bunny.net">
    {{-- Geist for UI, JetBrains Mono for data — anti-AI-slop typography --}}
    <link href="https://fonts.bunny.net/css?family=geist:300,400,500,600,700&family=jetbrains-mono:400,500,600&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body class="min-h-screen bg-zinc-950 text-zinc-100 font-sans antialiased">

    {{-- ══════════════════════════════
         NAVIGATION
         Fixed header, no glow/gradient.
         Depth established by 1px border only.
         ══════════════════════════════ --}}
    <header
        class="fixed inset-x-0 top-0 z-50 h-14 flex items-center border-b border-zinc-800/80 bg-zinc-950/90 backdrop-blur-md"
        x-data="{ mobileOpen: false }"
    >
        <nav class="w-full max-w-7xl mx-auto px-6 flex items-center justify-between">

            {{-- Brand --}}
            <a href="{{ url('/') }}" class="flex items-center gap-3 group shrink-0 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 rounded">
                <img
                    src="{{ asset('storage/system/logos/csa.png') }}"
                    alt="CSA STMIKMI"
                    class="h-8 w-auto object-contain transition-opacity group-hover:opacity-80"
                    loading="eager"
                >
                <span class="hidden sm:inline text-sm font-semibold text-zinc-200 tracking-tight">
                    CSA <span class="text-zinc-500 font-normal">STMIKMI</span>
                </span>
            </a>

            {{-- Desktop auth links --}}
            <div class="hidden sm:flex items-center gap-2">
                @guest
                    @if (Route::has('login'))
                        <a href="{{ route('login') }}"
                           class="h-8 px-4 inline-flex items-center text-sm font-medium text-zinc-400 hover:text-zinc-100 rounded transition-colors duration-100 focus:outline-none focus-visible:ring-2 focus-visible:ring-zinc-500">
                            Masuk
                        </a>
                    @endif
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}"
                           class="h-8 px-4 inline-flex items-center text-sm font-semibold text-white bg-blue-600 hover:bg-blue-500 rounded transition-colors duration-100 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-400">
                            Daftar
                        </a>
                    @endif
                @else
                    {{-- Authenticated dropdown --}}
                    <div class="relative" x-data="{ open: false }">
                        <button
                            @click="open = !open"
                            @keydown.escape="open = false"
                            class="h-8 px-3 inline-flex items-center gap-2 text-sm font-medium text-zinc-300 hover:text-zinc-100 bg-zinc-900 border border-zinc-800 hover:border-zinc-700 rounded transition-colors duration-100 focus:outline-none focus-visible:ring-2 focus-visible:ring-zinc-500"
                        >
                            <span class="h-5 w-5 rounded-full bg-blue-600 flex items-center justify-center text-[10px] font-bold text-white uppercase shrink-0">
                                {{ Str::substr(Auth::user()->name, 0, 1) }}
                            </span>
                            <span class="max-w-[120px] truncate">{{ Auth::user()->name }}</span>
                            <svg class="w-3 h-3 text-zinc-500 transition-transform duration-100" :class="open ? 'rotate-180' : ''" viewBox="0 0 16 16" fill="currentColor">
                                <path fill-rule="evenodd" d="M4.22 6.22a.75.75 0 0 1 1.06 0L8 8.94l2.72-2.72a.75.75 0 1 1 1.06 1.06l-3.25 3.25a.75.75 0 0 1-1.06 0L4.22 7.28a.75.75 0 0 1 0-1.06Z"/>
                            </svg>
                        </button>

                        <div
                            x-show="open"
                            x-transition:enter="transition duration-100 ease-out"
                            x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
                            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                            x-transition:leave="transition duration-75 ease-in"
                            x-transition:leave-start="opacity-100 scale-100"
                            x-transition:leave-end="opacity-0 scale-95"
                            @click.outside="open = false"
                            class="absolute right-0 mt-2 top-full w-48 rounded-lg border border-zinc-800 bg-zinc-900 shadow-xl shadow-zinc-950/50 py-1 z-50"
                            style="display: none;"
                        >
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                        class="w-full text-left px-4 py-2 text-sm text-zinc-400 hover:text-zinc-100 hover:bg-zinc-800 transition-colors focus:outline-none focus-visible:bg-zinc-800">
                                    Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                @endguest
            </div>

            {{-- Mobile burger —  Alpine.js toggle, no inline onclick --}}
            <button
                class="sm:hidden h-8 w-8 flex items-center justify-center text-zinc-400 hover:text-zinc-100 hover:bg-zinc-900 rounded transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-zinc-500"
                @click="mobileOpen = !mobileOpen"
                :aria-expanded="mobileOpen.toString()"
                aria-label="Toggle navigation"
            >
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                    <path x-show="!mobileOpen" stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                    <path x-show="mobileOpen" stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" style="display:none;"/>
                </svg>
            </button>
        </nav>

        {{-- Mobile nav drawer --}}
        <div
            x-show="mobileOpen"
            x-transition:enter="transition duration-150 ease-out"
            x-transition:enter-start="opacity-0 -translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition duration-100 ease-in"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="absolute inset-x-0 top-full border-b border-zinc-800 bg-zinc-950 px-6 py-4 sm:hidden space-y-1"
            style="display: none;"
        >
            @guest
                @if (Route::has('login'))
                    <a href="{{ route('login') }}" class="block py-2 text-sm font-medium text-zinc-400 hover:text-zinc-100 transition-colors">Masuk</a>
                @endif
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="block py-2 text-sm font-semibold text-blue-400 hover:text-blue-300 transition-colors">Daftar</a>
                @endif
            @else
                <p class="py-2 text-sm font-medium text-zinc-300">{{ Auth::user()->name }}</p>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="block py-2 text-sm text-zinc-500 hover:text-zinc-300 transition-colors">Keluar</button>
                </form>
            @endguest
        </div>
    </header>

    {{-- Page content — offset for fixed header --}}
    <main class="pt-14">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="border-t border-zinc-900 py-8 mt-auto">
        <div class="max-w-7xl mx-auto px-6 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-zinc-600">
            <div class="flex items-center gap-3">
                <img src="{{ asset('storage/system/logos/csa.png') }}" alt="" class="h-5 w-auto opacity-40" aria-hidden="true">
                <span class="font-mono">&copy; {{ date('Y') }} CSA STMIKMI. All rights reserved.</span>
            </div>
            <div class="flex items-center gap-6 font-mono">
                <a href="#" class="hover:text-zinc-400 transition-colors">Privacy</a>
                <a href="#" class="hover:text-zinc-400 transition-colors">Terms</a>
            </div>
        </div>
    </footer>

    {{-- Alpine.js CDN — lightweight, no Vue/React overhead --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @stack('scripts')
</body>
</html>
