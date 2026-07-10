<header class="fixed inset-x-0 top-4 z-50 px-4 sm:px-6 transition-all duration-300" x-data="{ mobileOpen: false, scrolled: false }"
    @scroll.window="scrolled = (window.pageYOffset > 10)">

    <!-- Senior Developer Update: Memindahkan 'border' dan 'ring-1' ke dalam kondisi dinamis saat 'scrolled' saja -->
    <nav :class="scrolled ?
        'bg-white/30 backdrop-blur-2xl border-white/50 ring-1 ring-gray-900/5 shadow-[0_8px_30px_rgb(0,0,0,0.04)] py-2.5' :
        'bg-white/10 backdrop-blur-md border-transparent ring-0 ring-transparent shadow-none py-3'"
        class="mx-auto flex max-w-5xl items-center justify-between rounded-full border px-6 transition-all duration-500 relative">

        <a href="{{ url('/') }}"
            class="flex items-center gap-3 group shrink-0 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 rounded-full">
            <img src="{{ asset('storage/system/logos/csa.png') }}" alt="csa logo"
                class="h-7 w-auto object-contain transition-transform duration-300 group-hover:scale-105" loading="eager">
        </a>

        <div :class="scrolled
            ?
            'bg-white/45 backdrop-blur-2xl border border-white/60 shadow-[0_10px_40px_rgba(255,255,255,0.18),0_8px_30px_rgba(15,23,42,0.08)]' :
            'bg-transparent border-transparent shadow-none'"
            class="hidden sm:flex items-center gap-1 p-1 rounded-full transition-all duration-500 relative overflow-hidden">

            <!-- Glass Highlight -->
            <div x-show="scrolled" x-transition.opacity.duration.500ms
                class="pointer-events-none absolute inset-0 rounded-full">
                <div class="absolute -top-6 left-8 h-16 w-40 rotate-12 bg-white/20 blur-2xl"></div>
            </div>

            @guest

                @if (Route::has('login'))
                    <a href="{{ route('login') }}"
                        class="group relative h-9 px-5 inline-flex items-center justify-center rounded-full text-sm font-semibold text-gray-700 transition-all duration-300 overflow-hidden hover:scale-[1.03] active:scale-[0.98]">

                        <!-- Hover Background -->
                        <span
                            class="absolute inset-0 rounded-full bg-gradient-to-b from-white/95 via-white/80 to-white/60 opacity-0 group-hover:opacity-100 transition-all duration-300"></span>

                        <!-- Hover Border -->
                        <span
                            class="absolute inset-0 rounded-full ring-1 ring-white/0 group-hover:ring-white/80 transition-all duration-300"></span>

                        <!-- Glow -->
                        <span
                            class="absolute inset-0 rounded-full opacity-0 group-hover:opacity-100 shadow-[0_0_18px_rgba(255,255,255,.6)] transition-all duration-300"></span>

                        <span class="relative z-10 group-hover:text-gray-900">
                            Masuk
                        </span>
                    </a>
                @endif

                @if (Route::has('register'))
                    <a href="{{ route('register') }}"
                        class="group relative h-9 px-6 inline-flex items-center justify-center rounded-full text-sm font-bold text-white bg-blue-600 hover:scale-[1.03] active:scale-[0.98] transition-all duration-300 overflow-hidden">
                        <span
                            class="absolute inset-0 bg-gradient-to-r from-transparent via-violet-100/30 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-700 ease-out">
                        </span>

                        <span class="relative z-10">
                            Daftar
                        </span>
                    </a>
                @endif
            @else
                {{-- Authenticated dropdown --}}
                <div class="relative" x-data="{ open: false }">

                    <button @click="open = !open" @keydown.escape="open = false"
                        class="h-9 pl-1 pr-3 inline-flex items-center gap-2 text-sm font-semibold text-gray-700 bg-white/70 hover:bg-white border border-white/70 rounded-full shadow-md transition-all duration-300 hover:shadow-lg hover:scale-[1.02]">

                        <span
                            class="h-7 w-7 rounded-full bg-gradient-to-tr from-blue-600 to-cyan-500 flex items-center justify-center text-[10px] font-bold text-white uppercase shadow-inner">
                            {{ Str::substr(Auth::user()->name, 0, 1) }}
                        </span>

                        <span class="max-w-[100px] truncate">
                            {{ explode(' ', Auth::user()->name)[0] }}
                        </span>

                        <svg class="w-3.5 h-3.5 text-gray-400 transition-transform duration-300"
                            :class="open ? 'rotate-180' : ''" viewBox="0 0 16 16" fill="currentColor">

                            <path fill-rule="evenodd"
                                d="M4.22 6.22a.75.75 0 0 1 1.06 0L8 8.94l2.72-2.72a.75.75 0 1 1 1.06 1.06l-3.25 3.25a.75.75 0 0 1-1.06 0L4.22 7.28a.75.75 0 0 1 0-1.06Z" />
                        </svg>

                    </button>

                    <div x-show="open" x-cloak x-transition:enter="transition duration-200 ease-out"
                        x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                        x-transition:leave="transition duration-150 ease-in"
                        x-transition:leave-start="opacity-100 scale-100"
                        x-transition:leave-end="opacity-0 scale-95 translate-y-2" @click.outside="open = false"
                        class="absolute right-0 mt-3 top-full w-48 rounded-2xl border border-white/60 bg-white/80 backdrop-blur-xl shadow-2xl p-1.5 z-50"
                        style="display:none;">

                        <div class="px-3 py-2 border-b border-gray-100/50 mb-1">
                            <p class="text-xs font-medium text-gray-500">Signed in as</p>
                            <p class="text-sm font-bold text-gray-900 truncate">
                                {{ Auth::user()->email }}
                            </p>
                        </div>

                        <a href="{{ route('pages.admin.dashboard') }}"
                            class="block px-3 py-2 text-sm font-medium text-gray-700 hover:text-blue-600 hover:bg-white rounded-xl transition-colors">
                            Dashboard
                        </a>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <button type="submit"
                                class="w-full text-left px-3 py-2 text-sm font-medium text-red-600 hover:text-red-700 hover:bg-red-50 rounded-xl transition-colors">
                                Sign out
                            </button>
                        </form>
                    </div>
                </div>
            @endguest
        </div>

        <button
            class="sm:hidden relative h-9 w-9 flex items-center justify-center text-gray-700 bg-white/50 backdrop-blur-sm border border-white/50 shadow-sm rounded-full transition-colors focus:outline-none"
            @click="mobileOpen = !mobileOpen" :aria-expanded="mobileOpen.toString()">
            <svg class="w-5 h-5 transition-transform duration-300"
                :class="mobileOpen ? 'rotate-90 scale-0' : 'rotate-0 scale-100'" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
            <svg class="w-5 h-5 absolute inset-0 m-auto transition-transform duration-300"
                :class="mobileOpen ? 'rotate-0 scale-100' : '-rotate-90 scale-0'" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </nav>

    <div x-show="mobileOpen" x-cloak x-transition:enter="transition duration-300 ease-out cubic-bezier(0.16, 1, 0.3, 1)"
        x-transition:enter-start="opacity-0 -translate-y-8 scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
        x-transition:leave="transition duration-200 ease-in" x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 -translate-y-4 scale-95"
        class="absolute inset-x-4 top-[4.5rem] sm:hidden origin-top" style="display: none;">

        <div class="rounded-3xl border border-white/60 bg-white/70 backdrop-blur-2xl shadow-2xl overflow-hidden p-2">
            @guest
                @if (Route::has('login'))
                    <a href="{{ route('login') }}"
                        class="block px-4 py-3 text-sm font-bold text-gray-700 hover:bg-white/80 rounded-2xl transition-colors">Masuk</a>
                @endif
                @if (Route::has('register'))
                    <a href="{{ route('register') }}"
                        class="block px-4 py-3 text-sm font-bold text-blue-600 hover:bg-blue-50/50 rounded-2xl transition-colors">Daftar
                        Akun Baru</a>
                @endif
            @else
                <div class="px-4 py-3 border-b border-gray-200/50">
                    <p class="text-xs font-medium text-gray-500">Masuk sebagai</p>
                    <p class="text-sm font-bold text-gray-900">{{ Auth::user()->name }}</p>
                </div>
                <a href="{{ route('pages.admin.dashboard') }}"
                    class="block px-4 py-3 text-sm font-bold text-gray-700 hover:bg-white/80 rounded-2xl transition-colors mt-1">Dashboard</a>
                <form method="POST" action="{{ route('logout') }}" class="mt-1">
                    @csrf
                    <button type="submit"
                        class="w-full text-left px-4 py-3 text-sm font-bold text-red-600 hover:bg-red-50 rounded-2xl transition-colors">Sign
                        out</button>
                </form>
            @endguest
        </div>
    </div>
</header>
