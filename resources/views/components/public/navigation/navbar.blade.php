@php
    $menus = [
        ['label' => 'Beranda', 'url' => route('home')],
        ['label' => 'Tentang', 'url' => url('/') . '#tentang'],
        ['label' => 'Divisi', 'url' => url('/') . '#divisi'],
        ['label' => 'Berita', 'url' => route('berita')],
        ['label' => 'Anggota', 'url' => route('anggota')],
        ['label' => 'Cerita', 'url' => url('/') . '#cerita'],
    ];
    $regOpen = Cache::remember('setting.registration_open', 60, fn () => \App\Models\Setting::first()?->registration_open ?? false);
@endphp

<header x-data="{
    mobileOpen: false,
    isDark: document.documentElement.classList.contains('dark'),
    toggle() {
        this.isDark = !this.isDark;
        document.documentElement.classList.toggle('dark', this.isDark);
        localStorage.setItem('theme', this.isDark ? 'dark' : 'light');
    }
}" class="fixed top-0 left-0 right-0 z-50 border-b"
    style="background: color-mix(in srgb, var(--paper) 88%, transparent); border-color: var(--line);">
    <div class="max-w-[1200px] mx-auto px-5 sm:px-8">
        <div class="flex items-center justify-between h-16">

            <a href="{{ route('home') }}" class="flex items-center gap-3 shrink-0">
                <img src="{{ asset('storage/system/logos/csa-logo.png') }}" alt="CSA" class="w-8 h-8 object-contain">
                <span class="hidden sm:block text-[15px] font-semibold tracking-[-0.01em]" style="color: var(--ink);">CSA</span>
            </a>

            <nav class="hidden lg:flex items-center gap-1">
                @foreach ($menus as $menu)
                    <a href="{{ $menu['url'] }}"
                        class="px-3 py-2 text-[14px] font-medium rounded-lg transition-colors hover:bg-[var(--paper-sunken)]"
                        style="color: var(--ink-soft);">
                        {{ $menu['label'] }}
                    </a>
                @endforeach
            </nav>

            <div class="hidden lg:flex items-center gap-2">
                <button type="button" @click="toggle()"
                    :aria-label="isDark ? 'Aktifkan tema terang' : 'Aktifkan tema gelap'"
                    class="w-10 h-10 flex items-center justify-center rounded-lg transition-colors hover:bg-[var(--paper-sunken)]"
                    style="color: var(--ink-soft);">
                    <i class="fas text-[15px]" :class="isDark ? 'fa-sun' : 'fa-moon'"></i>
                </button>

                @auth
                    <a href="{{ route('admin.dashboard', ['roleSlug' => auth()->user()->role->slug ?? 'anggota']) }}"
                        class="px-4 py-2 text-[14px] font-medium text-[var(--on-accent)] rounded-lg transition-opacity hover:opacity-90" style="background: var(--accent-solid);">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}"
                        class="px-4 py-2 text-[14px] font-medium rounded-lg transition-colors hover:bg-[var(--paper-sunken)]"
                        style="color: var(--ink-soft);">
                        Masuk
                    </a>
                    @if ($regOpen)
                        <a href="{{ route('register') }}"
                            class="px-4 py-2 text-[14px] font-medium text-[var(--on-accent)] rounded-lg transition-opacity hover:opacity-90" style="background: var(--accent-solid);">
                            Daftar
                        </a>
                    @else
                        <button type="button" onclick="window.dispatchEvent(new CustomEvent('open-register-modal'))"
                            class="px-4 py-2 text-[14px] font-medium rounded-lg cursor-pointer"
                            style="color: var(--ink-faint); background: var(--paper-sunken);">
                            Daftar
                        </button>
                    @endif
                @endauth
            </div>

            <div class="lg:hidden flex items-center gap-1">
                <button type="button" @click="toggle()"
                    :aria-label="isDark ? 'Aktifkan tema terang' : 'Aktifkan tema gelap'"
                    class="w-10 h-10 flex items-center justify-center rounded-lg"
                    style="color: var(--ink-soft);">
                    <i class="fas text-[15px]" :class="isDark ? 'fa-sun' : 'fa-moon'"></i>
                </button>
                <button type="button" @click="mobileOpen = !mobileOpen"
                    :aria-expanded="mobileOpen.toString()" aria-controls="mobile-menu" aria-label="Menu"
                    class="w-10 h-10 flex items-center justify-center rounded-lg"
                    style="color: var(--ink);">
                    <i class="fas text-[17px]" :class="mobileOpen ? 'fa-xmark' : 'fa-bars'"></i>
                </button>
            </div>
        </div>
    </div>

    <div id="mobile-menu" x-show="mobileOpen" x-cloak class="lg:hidden border-t" style="background: var(--paper); border-color: var(--line); display: none;">
        <nav class="max-w-[1200px] mx-auto px-5 sm:px-8 py-3">
            @foreach ($menus as $menu)
                @php $isActive = $menu['url'] === url()->current(); @endphp
                <a href="{{ $menu['url'] }}" @click="mobileOpen = false"
                    class="block px-3 py-3 rounded-lg text-[15px] font-medium transition-colors"
                    @if ($isActive)
                        style="color: var(--accent); background: var(--accent-soft);"
                    @else
                        style="color: var(--ink-soft);"
                    @endif>
                    {{ $menu['label'] }}
                </a>
            @endforeach

            <div class="mt-3 pt-3 border-t flex flex-col gap-2" style="border-color: var(--line);">
                @auth
                    <a href="{{ route('admin.dashboard', ['roleSlug' => auth()->user()->role->slug ?? 'anggota']) }}" @click="mobileOpen = false"
                        class="w-full text-center px-4 py-3 rounded-lg text-[15px] font-medium text-[var(--on-accent)]" style="background: var(--accent-solid);">
                        Buka Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" @click="mobileOpen = false"
                        class="w-full text-center px-4 py-3 rounded-lg text-[15px] font-medium border transition-colors"
                        style="color: var(--ink); border-color: var(--line-strong);">
                        Masuk
                    </a>
                    @if ($regOpen)
                        <a href="{{ route('register') }}" @click="mobileOpen = false"
                            class="w-full text-center px-4 py-3 rounded-lg text-[15px] font-medium text-[var(--on-accent)]" style="background: var(--accent-solid);">
                            Daftar
                        </a>
                    @else
                        <button type="button" @click="window.dispatchEvent(new CustomEvent('open-register-modal')); mobileOpen = false"
                            class="w-full text-center px-4 py-3 rounded-lg text-[15px] font-medium cursor-pointer"
                            style="color: var(--ink-faint); background: var(--paper-sunken);">
                            Pendaftaran ditutup
                        </button>
                    @endif
                @endauth
            </div>
        </nav>
    </div>
</header>
