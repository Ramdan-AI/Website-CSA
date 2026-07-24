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

        <div class="relative flex items-center justify-between h-[64px] px-6">

            <a href="{{ route('home') }}" class="flex items-center gap-2.5 shrink-0">
                <div
                    class="w-10 h-10 rounded-2xl bg-white/80 shadow-[0_4px_16px_-4px_rgba(0,0,0,0.06),inset_0_0.5px_0_rgba(255,255,255,0.8)] flex items-center justify-center">
                    <img src="{{ asset('storage/system/logos/csa-logo.png') }}" alt="CSA"
                        class="w-7 h-7 object-contain">
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
                        ['label' => 'Anggota', 'url' => '#anggota'],
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
                <a href="#kontak"
                    class="px-5 py-2.5 text-[13px] font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50/50 rounded-2xl transition-all duration-200">
                    Kontak
                </a>
                <a href="{{ route('register') }}"
                    class="px-5 py-2.5 bg-gray-900 text-white text-[13px] font-semibold rounded-2xl shadow-[0_4px_16px_-4px_rgba(0,0,0,0.15),0_0_0_0.5px_rgba(255,255,255,0.2)] hover:shadow-[0_8px_24px_-6px_rgba(0,0,0,0.25)] hover:scale-[1.02] active:scale-[0.98] transition-all duration-300">
                    Bergabung
                </a>
            </div>

            <button @click="mobileOpen = !mobileOpen"
                class="lg:hidden w-9 h-9 flex items-center justify-center rounded-2xl bg-white/60 shadow-sm hover:bg-white/80 transition-all">
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
                    class="block px-4 py-3 text-[14px] font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50/50 rounded-2xl transition-colors">
                    {{ $menu['label'] }}
                </a>
            @endforeach
            <div class="h-px bg-gray-100 my-2"></div>
            <a href="#kontak" @click="mobileOpen = false"
                class="block px-4 py-3 text-[14px] font-medium text-gray-600 hover:bg-gray-50/50 rounded-2xl">
                Kontak
            </a>
            <a href="{{ route('register') }}" @click="mobileOpen = false"
                class="block px-4 py-3.5 text-center text-[14px] font-semibold text-white bg-gray-900 rounded-2xl shadow-lg">
                Bergabung
            </a>
        </div>
    </div>
</nav>