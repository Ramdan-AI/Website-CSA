<nav x-data="{ scrolled: false, mobileOpen: false }" x-init="window.addEventListener('scroll', () => { scrolled = window.scrollY > 20 })"
    class="fixed top-4 left-1/2 -translate-x-1/2 z-50 w-[calc(100%-32px)] max-w-6xl transition-all duration-700 ease-[cubic-bezier(0.22,1,0.36,1)]"
    :class="scrolled ? 'top-2' : 'top-4'">

    <div class="relative rounded-[28px] border border-white/20 bg-white/40 backdrop-blur-2xl shadow-[0_8px_32px_-8px_rgba(0,0,0,0.08),0_0_0_0.5px_rgba(255,255,255,0.4),inset_0_0.5px_0_rgba(255,255,255,0.6)] transition-all duration-700"
        :class="scrolled ?
            'bg-white/60 shadow-[0_16px_48px_-12px_rgba(0,0,0,0.12),0_0_0_0.5px_rgba(255,255,255,0.6),inset_0_0.5px_0_rgba(255,255,255,0.8)]' :
            ''">

        <div
            class="absolute inset-0 rounded-[28px] bg-gradient-to-b from-white/30 via-transparent to-transparent pointer-events-none">
        </div>

        <div class="relative flex items-center justify-between h-[60px] sm:h-[64px] px-4 sm:px-6">

            <a href="{{ route('home') }}" class="flex items-center gap-2.5 shrink-0 min-h-[44px]">
                <div
                    class="w-9 h-9 sm:w-10 sm:h-10 rounded-2xl bg-white/80 shadow-[0_4px_16px_-4px_rgba(0,0,0,0.06),inset_0_0.5px_0_rgba(255,255,255,0.8)] flex items-center justify-center">
                    <img src="{{ asset('storage/system/logos/csa-logo.png') }}" alt="CSA"
                        class="w-6 h-6 sm:w-7 sm:h-7 object-contain">
                </div>
                <div class="hidden sm:block leading-tight">
                    <span class="text-[15px] font-bold text-gray-900 tracking-tight">CSA</span>
                    <span class="block text-[9px] text-gray-400 font-medium -mt-0.5">STMIK Mardira Indonesia</span>
                </div>
            </a>

            <div class="hidden lg:flex items-center gap-6">
                @php
                    $menus = [
                        ['label' => 'Beranda', 'url' => route('home')],
                        ['label' => 'Tentang', 'url' => '#tentang'],
                        ['label' => 'Divisi', 'url' => '#divisi'],
                        ['label' => 'Kegiatan', 'url' => '#kegiatan'],
                        ['label' => 'Anggota', 'url' => route('anggota')],
                        ['label' => 'Kontak', 'url' => '#kontak'],
                    ];
                @endphp

                @foreach ($menus as $menu)
                    <a href="{{ $menu['url'] }}"
                        class="relative text-[13px] font-medium text-gray-500 hover:text-gray-900 transition-colors duration-200 py-2">
                        {{ $menu['label'] }}
                    </a>
                @endforeach
            </div>

            <div class="hidden lg:flex items-center gap-2">
                @auth
                    <a href="{{ route('admin.dashboard', ['roleSlug' => auth()->user()->role->slug ?? 'anggota']) }}"
                        class="px-5 py-2.5 bg-gray-900 text-white text-[13px] font-semibold rounded-2xl shadow-[0_4px_16px_-4px_rgba(0,0,0,0.15),0_0_0_0.5px_rgba(255,255,255,0.2)] hover:shadow-[0_8px_24px_-6px_rgba(0,0,0,0.25)] hover:scale-[1.02] active:scale-[0.98] transition-all duration-300">
                        Dashboard
                    </a>
                @else
                    @php
                    $regOpen = Cache::remember('setting.registration_open', 60, fn () => \App\Models\Setting::first()?->registration_open ?? false);
@endphp
                    @if ($regOpen)
                        <a href="{{ route('register') }}"
                            class="px-5 py-2.5 bg-gray-900 text-white text-[13px] font-semibold rounded-2xl shadow-[0_4px_16px_-4px_rgba(0,0,0,0.15),0_0_0_0.5px_rgba(255,255,255,0.2)] hover:shadow-[0_8px_24px_-6px_rgba(0,0,0,0.25)] hover:scale-[1.02] active:scale-[0.98] transition-all duration-300">
                            Bergabung
                        </a>
                    @else
                        <button @click="window.dispatchEvent(new CustomEvent('open-register-modal'))"
                            class="px-5 py-2.5 bg-gray-100 text-gray-400 text-[13px] font-semibold rounded-2xl border border-dashed border-gray-200 cursor-pointer hover:bg-gray-200/50 transition-all duration-300">
                            Bergabung
                        </button>
                    @endif
                    <a href="{{ route('login') }}"
                        class="px-5 py-2.5 bg-gray-900 text-white text-[13px] font-semibold rounded-2xl shadow-[0_4px_16px_-4px_rgba(0,0,0,0.15),0_0_0_0.5px_rgba(255,255,255,0.2)] hover:shadow-[0_8px_24px_-6px_rgba(0,0,0,0.25)] hover:scale-[1.02] active:scale-[0.98] transition-all duration-300">
                        Masuk
                    </a>
                @endauth
            </div>

            <button @click="mobileOpen = !mobileOpen"
                :aria-expanded="mobileOpen.toString()"
                aria-label="Buka menu navigasi"
                class="lg:hidden w-11 h-11 flex items-center justify-center rounded-2xl bg-white/60 shadow-sm hover:bg-white/80 active:scale-95 transition-all">
                <i class="fas text-sm text-gray-600" :class="mobileOpen ? 'fa-times' : 'fa-bars'"></i>
            </button>
        </div>
    </div>

    <div x-show="mobileOpen" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-2"
        class="mt-2 rounded-[28px] border border-white/20 bg-white/60 backdrop-blur-2xl shadow-[0_16px_48px_-12px_rgba(0,0,0,0.1)] p-3 lg:hidden"
        style="display: none;">
        <div class="space-y-1">
            @foreach ($menus as $menu)
                <a href="{{ $menu['url'] }}" @click="mobileOpen = false"
                    class="block px-4 py-3 min-h-[48px] flex items-center text-[14px] font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50/50 rounded-2xl transition-colors">
                    {{ $menu['label'] }}
                </a>
            @endforeach
            <div class="h-px bg-gray-100 my-2"></div>
            @auth
                <a href="{{ route('admin.dashboard', ['roleSlug' => auth()->user()->role->slug ?? 'anggota']) }}" @click="mobileOpen = false"
                    class="block px-4 py-3.5 min-h-[52px] flex items-center justify-center text-center text-[14px] font-semibold text-white bg-gray-900 rounded-2xl shadow-lg active:scale-[0.98] transition-transform">
                    Dashboard
                </a>
            @else
                @if ($regOpen)
                    <a href="{{ route('register') }}" @click="mobileOpen = false"
                        class="block px-4 py-3.5 min-h-[52px] flex items-center justify-center text-center text-[14px] font-semibold text-white bg-gray-900 rounded-2xl shadow-lg active:scale-[0.98] transition-transform">
                        Bergabung
                    </a>
                @else
                    <button @click="window.dispatchEvent(new CustomEvent('open-register-modal')); mobileOpen = false"
                        class="w-full block px-4 py-3.5 min-h-[52px] flex items-center justify-center text-center text-[14px] font-semibold text-gray-400 bg-gray-100 rounded-2xl border border-dashed border-gray-200 cursor-pointer active:scale-[0.98] transition-transform">
                        Bergabung
                    </button>
                @endif
                <a href="{{ route('login') }}" @click="mobileOpen = false"
                    class="block px-4 py-3.5 min-h-[52px] flex items-center justify-center text-center text-[14px] font-semibold text-white bg-gray-900 rounded-2xl shadow-lg active:scale-[0.98] transition-transform">
                    Masuk
                </a>
            @endauth
        </div>
    </div>
</nav>