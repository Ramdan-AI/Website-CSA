@php
    $routeName = request()->route()->getName();
    $pageTitle = match (true) {
        str_contains($routeName, 'dashboard') => 'Dashboard',
        str_contains($routeName, 'divisions') => 'Divisi',
        str_contains($routeName, 'positions') => 'Jabatan',
        str_contains($routeName, 'members') => 'Anggota',
        str_contains($routeName, 'attendances') => 'Kehadiran',
        str_contains($routeName, 'news-categories') => 'Kategori Berita',
        str_contains($routeName, 'news') => 'Berita',
        str_contains($routeName, 'documents') => 'Dokumen',
        str_contains($routeName, 'events') => 'Acara',
        str_contains($routeName, 'event-registrations') => 'Pendaftaran',
        str_contains($routeName, 'incomes') => 'Pemasukan',
        str_contains($routeName, 'expenses') => 'Pengeluaran',
        str_contains($routeName, 'albums') => 'Album',
        str_contains($routeName, 'galleries') => 'Galeri',
        str_contains($routeName, 'settings') => 'Pengaturan',
        default => 'Dashboard',
    };

    $isCreate = request()->is('*/*/create') || str_contains($routeName, '.create');
    $isEdit = request()->is('*/*/edit') || str_contains($routeName, '.edit');
    $isShow = request()->is('*/*/*') && !$isCreate && !$isEdit && !str_contains($routeName, '.index');
    $actionLabel = match (true) {
        $isCreate => 'Tambah Data',
        $isEdit => 'Edit Data',
        $isShow => 'Detail',
        default => null,
    };

    // Role‑based gradient ring colours
    $ringMap = [
        'super-admin' => 'from-blue-400 via-purple-400 to-fuchsia-400',
        'ketua' => 'from-amber-400 via-orange-400 to-rose-400',
        'wakil-ketua' => 'from-yellow-400 via-amber-400 to-orange-400',
        'sekretaris' => 'from-teal-400 via-cyan-400 to-sky-400',
        'bendahara' => 'from-emerald-400 via-green-400 to-teal-400',
        'ketua-divisi' => 'from-violet-400 via-purple-400 to-indigo-400',
        'wakil-ketua-divisi' => 'from-indigo-400 via-blue-400 to-violet-400',
        'anggota' => 'from-gray-400 via-slate-400 to-zinc-400',
    ];
    $userRole = auth()->user()->role->slug ?? 'super-admin';
    $ringClass = $ringMap[$userRole] ?? 'from-gray-400 via-gray-500 to-gray-600';
    $hasAvatar = !empty(auth()->user()->avatar);
@endphp

<header x-data="{
    scrolled: false,
    sidebarExpanded: true, // will be synced with sidebar state
    searchOpen: false,
    notifOpen: false,
    userMenuOpen: false,
    query: '',

    init() {
        // Sync with sidebar
        this.sidebarExpanded = JSON.parse(localStorage.getItem('sidebar-expanded') ?? 'true');
        window.addEventListener('sidebar-toggled', (e) => {
            this.sidebarExpanded = e.detail.expanded;
        });

        // Scroll effect
        window.addEventListener('scroll', () => {
            this.scrolled = window.scrollY > 10;
        }, { passive: true });

        // Keyboard shortcuts
        window.addEventListener('keydown', (e) => {
            if ((e.metaKey || e.ctrlKey) && e.key === 'k') {
                e.preventDefault();
                this.searchOpen = !this.searchOpen;
                if (this.searchOpen) this.$nextTick(() => this.$refs.searchInput.focus());
            }
            if (e.key === 'Escape') {
                this.searchOpen = false;
                this.notifOpen = false;
                this.userMenuOpen = false;
            }
        });
    },

    get leftOffset() {
        const sidebarWidth = this.sidebarExpanded ? 320 : 120;
        const gap = 20;
        return sidebarWidth + gap;
    },

    search() {
        if (this.query.trim().length < 2) return;
        window.location.href = '{{ route('admin.dashboard') }}?search=' + encodeURIComponent(this.query);
    }
}" :style="{ left: leftOffset + 'px' }"
    class="fixed top-4 right-8 z-50 transition-all duration-500 "
    :class="scrolled ? 'top-2' : 'top-4'">

    {{-- Main surface – frosted glass with subtle noise texture overlay --}}
    <div class="relative rounded-[32px] border border-white/20 bg-white/40 backdrop-blur-2xl shadow-[0_12px_40px_-16px_rgba(0,0,0,0.12),inset_0_0_0_0.5px_rgba(255,255,255,0.6)] transition-shadow duration-500"
        :class="scrolled ? 'shadow-[0_20px_50px_-20px_rgba(0,0,0,0.18),inset_0_0_0_0.5px_rgba(255,255,255,0.8)]' : ''">

        <div class="absolute inset-0 rounded-[32px] opacity-[0.03] pointer-events-none"
            style="background-image: url('data:image/svg+xml,%3Csvg viewBox=%220 0 256 256%22 xmlns=%22http://www.w3.org/2000/svg%22%3E%3Cfilter id=%22n%22%3E%3CfeTurbulence type=%22fractalNoise%22 baseFrequency=%22.9%22 numOctaves=%224%22 stitchTiles=%22stitch%22/%3E%3C/filter%3E%3Crect width=%22100%25%22 height=%22100%25%22 filter=%22url(%23n)%22 opacity=%221%22/%3E%3C/svg%3E');">
        </div>

        <div class="relative flex items-center justify-between h-[66px] px-5">
            {{-- Left: page title with subtle coloured accent --}}
            <div class="flex items-center gap-4 min-w-0">
                <div class="min-w-0">
                    <h1 class="text-[15px] font-semibold text-gray-900 tracking-tight truncate">
                        {{ $pageTitle }}
                        @if ($actionLabel)
                            <span class="text-gray-400 font-medium text-[13px] ml-1.5">/ {{ $actionLabel }}</span>
                        @endif
                    </h1>
                </div>
            </div>

            {{-- Right cluster --}}
            <div class="flex items-center gap-1 shrink-0">
                {{-- Inline expandable search --}}
                <div class="relative flex items-center" @click.away="searchOpen = false">
                    <div class="flex items-center h-9 rounded-2xl transition-all duration-500 ease-[cubic-bezier(0.22,1,0.36,1)] overflow-hidden"
                        :class="searchOpen ? 'w-[270px] bg-gray-100/70 ring-1 ring-gray-200/50 shadow-sm' : 'w-9 bg-transparent'">
                        <button
                            @click="searchOpen = !searchOpen; if(searchOpen) $nextTick(() => $refs.searchInput.focus())"
                            class="w-9 h-9 shrink-0 flex items-center justify-center text-gray-500 hover:text-gray-700 transition-colors">
                            <i class="fas fa-search text-[13px]"></i>
                        </button>
                        <input x-ref="searchInput" x-model="query" type="text" placeholder="Search..."
                            class="h-full flex-1 bg-transparent text-[13px] text-gray-700 placeholder-gray-400 focus:outline-none pr-3"
                            :class="searchOpen ? 'opacity-100' : 'opacity-0 w-0'" @keydown.enter="search()">
                        <kbd class="hidden sm:flex items-center mr-2 px-1.5 h-5 text-[10px] font-mono text-gray-400 bg-gray-200/70 rounded-md shrink-0"
                            x-show="searchOpen">⌘K</kbd>
                    </div>
                </div>

                {{-- Notifications --}}
                <div class="relative" @click.away="notifOpen = false">
                    <button @click="notifOpen = !notifOpen"
                        class="relative w-9 h-9 rounded-2xl flex items-center justify-center text-gray-500 hover:text-gray-700 hover:bg-gray-100/50 transition-colors group">
                        <i class="far fa-bell text-[13px] group-hover:scale-110 transition-transform"></i>
                        <span class="absolute top-1.5 right-1.5 flex h-2 w-2">
                            <span
                                class="animate-ping absolute inline-flex h-full w-full rounded-full bg-orange-400 opacity-75"></span>
                            <span
                                class="relative inline-flex rounded-full h-2 w-2 bg-orange-500 border-2 border-white"></span>
                        </span>
                    </button>
                    <div x-show="notifOpen" x-transition
                        class="absolute right-0 top-full mt-3 w-80 bg-white/95 backdrop-blur-2xl rounded-3xl shadow-[0_24px_64px_-16px_rgba(0,0,0,0.15)] border border-white/20 overflow-hidden z-50"
                        style="display: none;">
                        <div class="p-5 border-b border-gray-100/50">
                            <h3 class="text-sm font-semibold text-gray-900">Notifications</h3>
                        </div>
                        <div class="py-10 text-center text-gray-400">
                            <i class="far fa-bell text-3xl mb-3"></i>
                            <p class="text-sm font-medium">No new notifications</p>
                        </div>
                    </div>
                </div>

                {{-- User avatar + dropdown --}}
                <div class="relative ml-2" @click.away="userMenuOpen = false">
                    <button @click="userMenuOpen = !userMenuOpen"
                        class="flex items-center gap-2.5 pl-1 pr-2 py-1 rounded-2xl hover:bg-gray-100/40 transition-colors cursor-pointer group">
                        {{-- Avatar with role gradient ring --}}
                        <div class="relative shrink-0">
                            <div
                                class="absolute -inset-[2px] rounded-full bg-gradient-to-br {{ $ringClass }} opacity-70 group-hover:opacity-100 transition-opacity">
                            </div>
                            <div
                                class="relative w-9 h-9 rounded-full bg-white flex items-center justify-center overflow-hidden ring-2 ring-white">
                                @if ($hasAvatar)
                                    <img src="{{ asset('storage/' . auth()->user()->avatar) }}"
                                        alt="{{ auth()->user()->name }}" class="w-full h-full object-cover">
                                @else
                                    <span
                                        class="text-sm font-bold text-gray-700">{{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="hidden sm:block text-left leading-tight">
                            <p class="text-[13px] font-medium text-gray-900">
                                {{ explode(' ', trim(Auth::user()->name))[0] }}</p>
                            <p class="text-[10px] text-gray-400 capitalize">{{ auth()->user()->role?->name }}</p>
                        </div>
                        <i class="fas fa-chevron-down text-[8px] text-gray-400 transition-transform duration-200"
                            :class="userMenuOpen ? 'rotate-180' : ''"></i>
                    </button>

                    {{-- Dropdown --}}
                    <div x-show="userMenuOpen" x-transition
                        class="absolute right-0 top-full mt-3 w-72 bg-white/95 backdrop-blur-2xl rounded-3xl shadow-[0_24px_64px_-16px_rgba(0,0,0,0.15)] border border-white/20 overflow-hidden z-50"
                        style="display: none;">
                        {{-- Profile header --}}
                        <div class="px-5 pt-5 pb-4">
                            <div class="flex items-center gap-4">
                                <div class="relative shrink-0">
                                    <div
                                        class="absolute -inset-[3px] rounded-full bg-gradient-to-br {{ $ringClass }} opacity-70">
                                    </div>
                                    <div
                                        class="relative w-14 h-14 rounded-full bg-white flex items-center justify-center overflow-hidden ring-3 ring-white">
                                        @if ($hasAvatar)
                                            <img src="{{ asset('storage/' . auth()->user()->avatar) }}"
                                                alt="{{ auth()->user()->name }}" class="w-full h-full object-cover">
                                        @else
                                            <span
                                                class="text-xl font-bold text-gray-700">{{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-gray-900 truncate">{{ Auth::user()->name }}
                                    </p>
                                    <p class="text-xs text-gray-400 truncate">{{ Auth::user()->email }}</p>
                                    <span
                                        class="inline-block mt-1.5 px-2 py-0.5 text-[10px] font-medium rounded-full bg-gray-100 text-gray-600 capitalize">{{ auth()->user()->role?->name }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="h-px bg-gray-100 mx-5"></div>
                        <div class="py-2 px-2">
                            <a href="{{ route('admin.settings.edit') }}"
                                class="flex items-center gap-3 px-3 py-2.5 rounded-2xl text-sm text-gray-700 hover:bg-gray-50 transition-colors group">
                                <div
                                    class="w-8 h-8 rounded-xl bg-gray-100 flex items-center justify-center group-hover:bg-gray-200">
                                    <i class="fas fa-user text-xs text-gray-500"></i>
                                </div>
                                <span>Profile</span>
                            </a>
                            <div class="h-px bg-gray-50 mx-3 my-1"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="flex items-center gap-3 px-3 py-2.5 rounded-2xl text-sm text-gray-600 hover:text-red-500 hover:bg-red-50 transition-colors w-full group">
                                    <div
                                        class="w-8 h-8 rounded-xl bg-gray-100 flex items-center justify-center group-hover:bg-red-100">
                                        <i
                                            class="fas fa-arrow-right-from-bracket text-xs text-gray-500 group-hover:text-red-400"></i>
                                    </div>
                                    Sign out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

{{-- Compensate fixed position --}}
<div class="h-[82px]"></div>
