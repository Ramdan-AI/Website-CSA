@php
    $userRole = auth()->user()->role->slug;

    $menuGroups = [
        [
            'id' => 'dashboard',
            'label' => 'Utama',
            'color' => 'blue',
            'roles' => ['super-admin', 'sekretaris', 'bendahara', 'kepala-divisi', 'ketua'],
            'items' => [
                [
                    'icon' => 'fa-chart-pie',
                    'label' => 'Dashboard',
                    'route' => 'admin.dashboard',
                    'pattern' => 'admin.dashboard',
                ],
            ],
        ],
        [
            'id' => 'master-data',
            'label' => 'Data Master',
            'color' => 'purple',
            'roles' => ['super-admin'],
            'items' => [
                [
                    'icon' => 'fa-building',
                    'label' => 'Divisi',
                    'route' => 'admin.divisions.index',
                    'pattern' => 'admin.divisions.*',
                ],
                [
                    'icon' => 'fa-briefcase',
                    'label' => 'Jabatan',
                    'route' => 'admin.positions.index',
                    'pattern' => 'admin.positions.*',
                ],
            ],
        ],
        [
            'id' => 'operational',
            'label' => 'Keanggotaan',
            'color' => 'orange',
            'roles' => ['super-admin', 'kepala-divisi'],
            'items' => [
                [
                    'icon' => 'fa-users',
                    'label' => 'Anggota',
                    'route' => 'admin.members.index',
                    'pattern' => 'admin.members.*',
                ],
                [
                    'icon' => 'fa-user-check',
                    'label' => 'Kehadiran',
                    'route' => 'admin.attendances.index',
                    'pattern' => 'admin.attendances.*',
                ],
            ],
        ],
        [
            'id' => 'publication',
            'label' => 'Publikasi',
            'color' => 'teal',
            'roles' => ['super-admin', 'sekretaris'],
            'items' => [
                [
                    'icon' => 'fa-tags',
                    'label' => 'Kategori',
                    'route' => 'admin.news-categories.index',
                    'pattern' => 'admin.news-categories.*',
                ],
                [
                    'icon' => 'fa-newspaper',
                    'label' => 'Berita',
                    'route' => 'admin.news.index',
                    'pattern' => 'admin.news.*',
                ],
                [
                    'icon' => 'fa-folder-open',
                    'label' => 'Dokumen',
                    'route' => 'admin.documents.index',
                    'pattern' => 'admin.documents.*',
                ],
            ],
        ],
        [
            'id' => 'events',
            'label' => 'Kegiatan',
            'color' => 'cyan',
            'roles' => ['super-admin', 'kepala-divisi'],
            'items' => [
                [
                    'icon' => 'fa-calendar-alt',
                    'label' => 'Acara',
                    'route' => 'admin.events.index',
                    'pattern' => 'admin.events.*',
                ],
                [
                    'icon' => 'fa-clipboard-list',
                    'label' => 'Pendaftaran',
                    'route' => 'admin.event-registrations.index',
                    'pattern' => 'admin.event-registrations.*',
                ],
            ],
        ],
        [
            'id' => 'finance',
            'label' => 'Keuangan',
            'color' => 'green',
            'roles' => ['super-admin', 'bendahara'],
            'items' => [
                [
                    'icon' => 'fa-arrow-trend-up',
                    'label' => 'Pemasukan',
                    'route' => 'admin.incomes.index',
                    'pattern' => 'admin.incomes.*',
                ],
                [
                    'icon' => 'fa-arrow-trend-down',
                    'label' => 'Pengeluaran',
                    'route' => 'admin.expenses.index',
                    'pattern' => 'admin.expenses.*',
                ],
            ],
        ],
        [
            'id' => 'media',
            'label' => 'Media',
            'color' => 'pink',
            'roles' => ['super-admin', 'ketua'],
            'items' => [
                [
                    'icon' => 'fa-images',
                    'label' => 'Album',
                    'route' => 'admin.albums.index',
                    'pattern' => 'admin.albums.*',
                ],
                [
                    'icon' => 'fa-photo-video',
                    'label' => 'Galeri',
                    'route' => 'admin.galleries.index',
                    'pattern' => 'admin.galleries.*',
                ],
            ],
        ],
    ];

    $filteredGroups = array_filter($menuGroups, function ($group) use ($userRole) {
        return in_array($userRole, $group['roles']);
    });
    $isActive = function ($pattern) {
        return request()->routeIs($pattern);
    };
@endphp

<style>
    .no-scrollbar::-webkit-scrollbar {
        display: none;
    }

    .no-scrollbar {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }

    .glow-blue {
        box-shadow: 0 0 20px rgba(59, 130, 246, 0.3), 0 0 40px rgba(59, 130, 246, 0.1);
    }
</style>

<aside x-data="{
    expanded: JSON.parse(localStorage.getItem('sidebar-expanded') ?? 'true'),
    toggle() {
        this.expanded = !this.expanded;
        localStorage.setItem('sidebar-expanded', this.expanded);
        this.$dispatch('sidebar-toggled', { expanded: this.expanded });
        if (this.expanded) {
            document.documentElement.classList.remove('sidebar-collapsed');
        } else {
            document.documentElement.classList.add('sidebar-collapsed');
        }
    }
}" class="csa-sidebar-root fixed left-4 top-4 h-[calc(100vh-32px)] z-50 flex flex-col gap-3">

    <button @click="toggle()"
        class="absolute top-1/2 -translate-y-1/2 -right-4 w-8 h-8 bg-white/95 backdrop-blur-xl border border-gray-200/60 rounded-full flex items-center justify-center cursor-pointer z-50 shadow-[0_4px_12px_rgba(0,0,0,0.05)] hover:shadow-[0_4px_16px_rgba(59,130,246,0.2)] hover:bg-blue-50 transition-all duration-300 transform hover:scale-110 focus:outline-none"
        title="Perbesar/Perkecil">
        <i class="fas text-xs text-blue-600 transition-transform duration-500"
            :class="expanded ? 'fa-chevron-left' : 'fa-chevron-right'"></i>
    </button>

    <div
        class="relative bg-white/80 backdrop-blur-xl rounded-3xl p-4 shadow-[0_8px_32px_rgba(59,130,246,0.05)] border border-white/60 shrink-0 h-[80px] overflow-hidden flex items-center">
        <div class="absolute inset-0 bg-gradient-to-br from-blue-400/5 to-purple-400/5 pointer-events-none"></div>
        <a href="{{ route('admin.dashboard') }}" class="relative flex items-center gap-4 group w-full outline-none">
            <div
                class="w-12 h-12 flex items-center justify-center shrink-0 rounded-2xl bg-white/60 shadow-sm group-hover:shadow-md transition-all duration-300">
                <img src="{{ asset('storage/system/logos/csa-logo.png') }}" alt="CSA"
                    class="w-8 h-8 object-contain group-hover:scale-110 transition-transform duration-300">
            </div>
            <div class="csa-sidebar-hideable whitespace-nowrap transition-all duration-300">
                <span
                    class="font-extrabold text-lg bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent block leading-tight">CSA</span>
                <p class="text-xs text-gray-500 font-medium">Panel Admin</p>
            </div>
        </a>
    </div>

    <nav id="csa-sidebar-nav" onscroll="sessionStorage.setItem('csa_sidebar_scroll', this.scrollTop)"
        class="relative bg-white/80 backdrop-blur-xl rounded-3xl p-3 flex-1 flex flex-col overflow-y-auto no-scrollbar shadow-[0_8px_32px_rgba(59,130,246,0.05)] border border-white/60">

        <div
            class="absolute inset-0 bg-gradient-to-br from-blue-400/5 to-purple-400/5 rounded-3xl pointer-events-none fixed">
        </div>

        <div class="relative z-10 flex flex-col space-y-4 py-1">
            @foreach ($filteredGroups as $group)
                <div>
                    <div class="csa-sidebar-nav-header px-4 overflow-hidden h-6 transition-all duration-300"
                        :class="expanded ? 'opacity-100' : 'opacity-0'">
                        <span
                            class="text-[10px] font-bold uppercase tracking-widest text-{{ $group['color'] }}-400/80">{{ $group['label'] }}</span>
                    </div>
                    @foreach ($group['items'] as $item)
                        @php $active = $isActive($item['pattern']); @endphp
                        <a href="{{ route($item['route']) }}"
                            class="relative h-12 rounded-2xl flex items-center gap-4 px-3.5 transition-all duration-300 group mb-1 outline-none
                                  {{ $active ? 'bg-gradient-to-r from-blue-500 to-blue-600 text-white glow-blue' : 'text-gray-500 hover:text-blue-600 hover:bg-white/80' }}">
                            <div class="w-5 flex items-center justify-center shrink-0">
                                <i
                                    class="fas {{ $item['icon'] }} text-lg transition-transform duration-300 {{ $active ? 'text-white' : 'group-hover:scale-110 group-hover:text-blue-500' }}"></i>
                            </div>
                            <span
                                class="csa-sidebar-hideable font-semibold text-sm whitespace-nowrap transition-all duration-300"
                                :class="expanded ? 'opacity-100 translate-x-0' :
                                    'opacity-0 -translate-x-4 absolute pointer-events-none'">{{ $item['label'] }}</span>
                            @if ($active)
                                <div class="absolute right-3 w-1.5 h-1.5 rounded-full bg-white/80 animate-pulse transition-opacity duration-300"
                                    :class="expanded ? 'opacity-100' : 'opacity-0'"></div>
                            @endif
                        </a>
                    @endforeach
                </div>
            @endforeach
        </div>
    </nav>

    <script>
        (function() {
            const nav = document.getElementById('csa-sidebar-nav');
            if (nav) {
                const scrollPos = sessionStorage.getItem('csa_sidebar_scroll');
                if (scrollPos !== null) {
                    nav.scrollTop = parseInt(scrollPos, 10);
                }
            }
        })();
    </script>

    <div
        class="relative bg-white/80 backdrop-blur-xl rounded-3xl p-3 shadow-[0_8px_32px_rgba(59,130,246,0.05)] border border-white/60 shrink-0 h-[72px] flex items-center">
        <div class="absolute inset-0 bg-gradient-to-br from-blue-400/5 to-purple-400/5 pointer-events-none"></div>
        @php $settingsActive = request()->routeIs('admin.settings.*'); @endphp
        <a href="{{ route('admin.settings.edit') }}"
            class="relative h-12 w-full rounded-2xl flex items-center gap-4 px-3.5 transition-all duration-300 group outline-none
                  {{ $settingsActive ? 'bg-gradient-to-r from-blue-500 to-blue-600 text-white glow-blue' : 'text-gray-500 hover:text-blue-600 hover:bg-white/80' }}">
            <div class="w-5 flex items-center justify-center shrink-0">
                <i
                    class="fas fa-cog text-lg transition-all duration-500 {{ $settingsActive ? 'text-white group-hover:rotate-180' : 'group-hover:rotate-90 group-hover:text-blue-500 group-hover:scale-110' }}"></i>
            </div>
            <span class="csa-sidebar-hideable font-semibold text-sm whitespace-nowrap transition-all duration-300"
                :class="expanded ? 'opacity-100 translate-x-0' : 'opacity-0 -translate-x-4 absolute pointer-events-none'">Pengaturan</span>
            @if ($settingsActive)
                <div class="absolute right-3 w-1.5 h-1.5 rounded-full bg-white/80 animate-pulse transition-opacity duration-300"
                    :class="expanded ? 'opacity-100' : 'opacity-0'"></div>
            @endif
        </a>
    </div>
</aside>
