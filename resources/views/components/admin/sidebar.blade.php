@php
    $userRole = auth()->user()->role->slug;

    $menuGroups = [
        [
            'id' => 'dashboard',
            'label' => 'Utama',
            'color' => 'blue',
            'roles' => ['super-admin', 'sekretaris', 'bendahara', 'kepala-divisi', 'ketua'],
            'items' => [
                ['icon' => 'fa-chart-pie', 'label' => 'Dashboard', 'route' => 'admin.dashboard', 'pattern' => 'admin.dashboard'],
            ],
        ],
        [
            'id' => 'master-data',
            'label' => 'Data Master',
            'color' => 'purple',
            'roles' => ['super-admin'],
            'items' => [
                ['icon' => 'fa-building', 'label' => 'Divisi', 'route' => 'admin.divisions.index', 'pattern' => 'admin.divisions.*'],
                ['icon' => 'fa-briefcase', 'label' => 'Jabatan', 'route' => 'admin.positions.index', 'pattern' => 'admin.positions.*'],
            ],
        ],
        [
            'id' => 'operational',
            'label' => 'Keanggotaan',
            'color' => 'orange',
            'roles' => ['super-admin', 'kepala-divisi', 'ketua'],
            'items' => [
                ['icon' => 'fa-users', 'label' => 'Anggota', 'route' => 'admin.members.index', 'pattern' => 'admin.members.*'],
                ['icon' => 'fa-user-check', 'label' => 'Kehadiran', 'route' => 'admin.attendances.index', 'pattern' => 'admin.attendances.*'],
            ],
        ],
        [
            'id' => 'publication',
            'label' => 'Publikasi',
            'color' => 'teal',
            'roles' => ['super-admin', 'sekretaris', 'ketua'],
            'items' => [
                ['icon' => 'fa-tags', 'label' => 'Kategori', 'route' => 'admin.news-categories.index', 'pattern' => 'admin.news-categories.*'],
                ['icon' => 'fa-newspaper', 'label' => 'Berita', 'route' => 'admin.news.index', 'pattern' => 'admin.news.*'],
                ['icon' => 'fa-folder-open', 'label' => 'Dokumen', 'route' => 'admin.documents.index', 'pattern' => 'admin.documents.*'],
            ],
        ],
        [
            'id' => 'events',
            'label' => 'Kegiatan',
            'color' => 'cyan',
            'roles' => ['super-admin', 'kepala-divisi', 'ketua'],
            'items' => [
                ['icon' => 'fa-calendar-alt', 'label' => 'Acara', 'route' => 'admin.events.index', 'pattern' => 'admin.events.*'],
                ['icon' => 'fa-clipboard-list', 'label' => 'Pendaftaran', 'route' => 'admin.event-registrations.index', 'pattern' => 'admin.event-registrations.*'],
            ],
        ],
        [
            'id' => 'finance',
            'label' => 'Keuangan',
            'color' => 'emerald',
            'roles' => ['super-admin', 'bendahara', 'ketua'],
            'items' => [
                ['icon' => 'fa-arrow-trend-up', 'label' => 'Pemasukan', 'route' => 'admin.incomes.index', 'pattern' => 'admin.incomes.*'],
                ['icon' => 'fa-arrow-trend-down', 'label' => 'Pengeluaran', 'route' => 'admin.expenses.index', 'pattern' => 'admin.expenses.*'],
            ],
        ],
        [
            'id' => 'media',
            'label' => 'Media',
            'color' => 'pink',
            'roles' => ['super-admin', 'ketua'],
            'items' => [
                ['icon' => 'fa-images', 'label' => 'Album', 'route' => 'admin.albums.index', 'pattern' => 'admin.albums.*'],
                ['icon' => 'fa-photo-video', 'label' => 'Galeri', 'route' => 'admin.galleries.index', 'pattern' => 'admin.galleries.*'],
            ],
        ],
    ];

    // Color palette for active states — one per group
    $colorMap = [
        'blue'    => ['bg' => 'from-blue-500 to-blue-600', 'glow' => 'rgba(59,130,246,0.25)', 'soft' => 'bg-blue-50 text-blue-600', 'ring' => 'ring-blue-500/20'],
        'purple'  => ['bg' => 'from-purple-500 to-purple-600', 'glow' => 'rgba(139,92,246,0.25)', 'soft' => 'bg-purple-50 text-purple-600', 'ring' => 'ring-purple-500/20'],
        'orange'  => ['bg' => 'from-orange-500 to-orange-600', 'glow' => 'rgba(249,115,22,0.25)', 'soft' => 'bg-orange-50 text-orange-600', 'ring' => 'ring-orange-500/20'],
        'teal'    => ['bg' => 'from-teal-500 to-teal-600', 'glow' => 'rgba(20,184,166,0.25)', 'soft' => 'bg-teal-50 text-teal-600', 'ring' => 'ring-teal-500/20'],
        'cyan'    => ['bg' => 'from-cyan-500 to-cyan-600', 'glow' => 'rgba(6,182,212,0.25)', 'soft' => 'bg-cyan-50 text-cyan-600', 'ring' => 'ring-cyan-500/20'],
        'emerald' => ['bg' => 'from-emerald-500 to-emerald-600', 'glow' => 'rgba(16,185,129,0.25)', 'soft' => 'bg-emerald-50 text-emerald-600', 'ring' => 'ring-emerald-500/20'],
        'pink'    => ['bg' => 'from-pink-500 to-pink-600', 'glow' => 'rgba(236,72,153,0.25)', 'soft' => 'bg-pink-50 text-pink-600', 'ring' => 'ring-pink-500/20'],
    ];

    $filteredGroups = array_values(array_filter($menuGroups, fn($group) => in_array($userRole, $group['roles'])));
    $isActive = fn($pattern) => request()->routeIs($pattern);
@endphp

<aside
    x-data="{
        expanded: JSON.parse(localStorage.getItem('sidebar-expanded') ?? 'true'),
        hoveredItem: null,
        toggle() {
            this.expanded = !this.expanded;
            localStorage.setItem('sidebar-expanded', this.expanded);
            this.$dispatch('sidebar-toggled', { expanded: this.expanded });
            document.documentElement.classList.toggle('sidebar-collapsed', !this.expanded);
        }
    }"
    class="csa-sidebar-root fixed left-4 top-4 h-[calc(100vh-32px)] z-50 flex flex-col gap-3"
    :class="expanded ? 'w-[264px]' : 'w-[80px]'"
    x-effect="localStorage.setItem('sidebar-expanded', expanded)">

    {{-- Toggle button: subtle pill --}}
    <button
        @click="toggle()"
        class="absolute top-1/2 -translate-y-1/2 -right-3 w-6 h-12 rounded-full bg-white/90 backdrop-blur-xl border border-black/5 flex items-center justify-center cursor-pointer z-50 shadow-sm hover:shadow-md hover:bg-white transition-all duration-300 focus:outline-none group/toggle">
        <span class="w-1 h-3 rounded-full bg-gray-300 group-hover/toggle:bg-gray-500 group-hover/toggle:h-4 transition-all duration-300"></span>
    </button>

    {{-- Brand --}}
    <div class="relative shrink-0 rounded-[22px] bg-white/80 backdrop-blur-xl border border-white/60 shadow-[0_4px_24px_rgba(0,0,0,0.04)] h-[72px] flex items-center overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-blue-400/[0.04] to-purple-400/[0.04] pointer-events-none"></div>
        <a href="{{ route('admin.dashboard') }}" class="relative flex items-center gap-3.5 px-4 w-full">
            <div class="w-10 h-10 flex items-center justify-center shrink-0 rounded-xl bg-white/70 shadow-sm">
                <img src="{{ asset('storage/system/logos/csa-logo.png') }}" alt="CSA" class="w-7 h-7 object-contain">
            </div>
            <div class="csa-sidebar-hideable overflow-hidden whitespace-nowrap"
                x-show="expanded" x-transition:enter="transition delay-75" x-transition:leave="transition duration-150">
                <span class="font-extrabold text-[15px] text-gray-900 tracking-tight block leading-none">CSA</span>
                <span class="text-[10px] text-gray-400 font-medium">Panel Admin</span>
            </div>
        </a>
    </div>

    {{-- Navigation --}}
    <nav
        id="csa-sidebar-nav"
        onscroll="sessionStorage.setItem('csa_sidebar_scroll', this.scrollTop)"
        class="relative flex-1 overflow-y-auto no-scrollbar rounded-[22px] bg-white/80 backdrop-blur-xl border border-white/60 shadow-[0_4px_24px_rgba(0,0,0,0.04)] p-3">

        <div class="absolute inset-0 bg-gradient-to-br from-blue-400/[0.03] to-purple-400/[0.03] rounded-[22px] pointer-events-none"></div>

        <div class="relative z-10 flex flex-col gap-5 py-1">
            @foreach ($filteredGroups as $group)
                @php $palette = $colorMap[$group['color']]; @endphp
                <div>
                    {{-- Section label --}}
                    <div class="csa-sidebar-nav-header px-3 h-5 overflow-hidden mb-1.5"
                        x-show="expanded" x-transition:enter="transition delay-75">
                        <span class="text-[10px] font-bold uppercase tracking-[0.15em] text-gray-400 select-none">
                            {{ $group['label'] }}
                        </span>
                    </div>

                    @foreach ($group['items'] as $item)
                        @php $active = $isActive($item['pattern']); @endphp
                        <a
                            href="{{ route($item['route']) }}"
                            @mouseenter="hoveredItem = '{{ $group['id'] }}-{{ $loop->index }}'"
                            @mouseleave="hoveredItem = null"
                            class="relative h-11 rounded-[14px] flex items-center gap-3.5 px-3 transition-all duration-300 outline-none mb-0.5 group/nav"
                            :class="expanded ? '' : 'justify-center px-0 w-11 mx-auto'">
                            
                            {{-- Active background --}}
                            @if($active)
                                <span class="absolute inset-0 rounded-[14px] bg-gradient-to-r {{ $palette['bg'] }} opacity-90"
                                    style="box-shadow: 0 4px 16px {{ $palette['glow'] }};"></span>
                            @endif
                            
                            {{-- Hover background (when not active) --}}
                            <span class="absolute inset-0 rounded-[14px] bg-gray-50 opacity-0 group-hover/nav:opacity-100 transition-opacity duration-200 {{ $active ? '!opacity-0' : '' }}"></span>

                            {{-- Icon --}}
                            <span class="relative w-5 flex items-center justify-center shrink-0 z-10">
                                <i class="fas {{ $item['icon'] }} text-[15px] transition-all duration-300
                                    {{ $active ? 'text-white drop-shadow-sm' : 'text-gray-400 group-hover/nav:text-gray-700' }}"></i>
                            </span>

                            {{-- Label --}}
                            <span
                                class="relative z-10 font-semibold text-[13px] whitespace-nowrap transition-all duration-300
                                {{ $active ? 'text-white' : 'text-gray-600 group-hover/nav:text-gray-900' }}"
                                x-show="expanded" x-transition:enter="transition delay-75">
                                {{ $item['label'] }}
                            </span>

                            {{-- Active dot indicator (collapsed mode) --}}
                            @if($active)
                                <span
                                    class="absolute -right-1 top-1/2 -translate-y-1/2 w-1.5 h-1.5 rounded-full bg-white/90 shadow-sm"
                                    x-show="!expanded"></span>
                            @endif
                        </a>
                    @endforeach
                </div>
            @endforeach
        </div>
    </nav>

    {{-- Settings (super-admin only) --}}
    @if(auth()->user()->role->slug === 'super-admin')
        @php $settingsActive = request()->routeIs('admin.settings.*'); @endphp
        <div class="relative shrink-0 rounded-[22px] bg-white/80 backdrop-blur-xl border border-white/60 shadow-[0_4px_24px_rgba(0,0,0,0.04)] h-[60px] flex items-center overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-blue-400/[0.04] to-purple-400/[0.04] pointer-events-none"></div>
            <a
                href="{{ route('admin.settings.edit') }}"
                class="relative h-11 w-full rounded-[14px] flex items-center gap-3.5 px-3 transition-all duration-300 outline-none group/nav mx-2"
                :class="expanded ? '' : 'justify-center w-11 mx-auto'">
                
                @if($settingsActive)
                    <span class="absolute inset-0 rounded-[14px] bg-gradient-to-r from-gray-500 to-gray-600 opacity-90"
                        style="box-shadow: 0 4px 16px rgba(107,114,128,0.2);"></span>
                @endif
                
                <span class="absolute inset-0 rounded-[14px] bg-gray-50 opacity-0 group-hover/nav:opacity-100 transition-opacity duration-200 {{ $settingsActive ? '!opacity-0' : '' }}"></span>

                <span class="relative w-5 flex items-center justify-center shrink-0 z-10">
                    <i class="fas fa-cog text-[15px] transition-all duration-300
                        {{ $settingsActive ? 'text-white drop-shadow-sm' : 'text-gray-400 group-hover/nav:text-gray-700' }}"></i>
                </span>
                
                <span
                    class="relative z-10 font-semibold text-[13px] whitespace-nowrap transition-all duration-300
                    {{ $settingsActive ? 'text-white' : 'text-gray-600 group-hover/nav:text-gray-900' }}"
                    x-show="expanded" x-transition:enter="transition delay-75">
                    Pengaturan
                </span>
            </a>
        </div>
    @endif
</aside>

{{-- Scroll position restore --}}
<script>
    (() => {
        const nav = document.getElementById('csa-sidebar-nav');
        if (!nav) return;
        const pos = sessionStorage.getItem('csa_sidebar_scroll');
        if (pos !== null) nav.scrollTop = parseInt(pos, 10);
    })();
</script>

<style>
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    .no-scrollbar::-webkit-scrollbar { display: none; }

    .csa-sidebar-root {
        transition: width 0.35s cubic-bezier(0.22, 1, 0.36, 1);
    }
</style>