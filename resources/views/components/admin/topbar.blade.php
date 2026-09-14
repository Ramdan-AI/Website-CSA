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

    $hasAvatar = !empty(auth()->user()->avatar);
    $notifications = collect();
    $lastSeen = session('notifications_seen_at', 0);
    $unreadCount = 0;
    $newNews = \App\Models\News::where('status', 'published')->where('created_at', '>', now()->setTimestamp($lastSeen))->latest('published_at')->take(5)->get();
    foreach ($newNews as $news) {
        $notifications->push([
            'icon' => 'fa-newspaper',
            'color' => 'bg-teal-50 text-teal-600',
            'title' => 'Berita Baru: ' . $news->title,
            'time' => $news->published_at?->diffForHumans() ?? $news->created_at->diffForHumans(),
            'link' => route('admin.news.show', $news),
        ]);
    }
    $newEvents = \App\Models\Event::where('status', 'published')->where('event_date', '>=', now()->toDateString())->where('created_at', '>', now()->setTimestamp($lastSeen))->latest('event_date')->take(5)->get();
    foreach ($newEvents as $event) {
        $notifications->push([
            'icon' => 'fa-calendar-alt',
            'color' => 'bg-cyan-50 text-cyan-600',
            'title' => 'Acara: ' . $event->title,
            'time' => $event->event_date?->format('d M Y') ?? '-',
            'link' => route('admin.events.show', $event),
        ]);
    }
    $notifications = $notifications->take(10);
    $unreadCount = $notifications->count();
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
        window.location.href = '{{ route('admin.search') }}?q=' + encodeURIComponent(this.query);
    }
}" :style="{ left: leftOffset + 'px' }"
    class="fixed top-4 right-8 z-50 transition-all duration-300 "
    :class="scrolled ? 'top-3' : 'top-4'">

    <div class="rounded-2xl border border-gray-200 bg-white shadow-sm transition-shadow duration-300"
        :class="scrolled ? 'shadow-md' : ''">
        <div class="flex items-center justify-between h-16 px-5">
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
                    <button @click="notifOpen = !notifOpen; localStorage.setItem('notifications-seen-at', Date.now())"
                        class="relative w-9 h-9 rounded-2xl flex items-center justify-center text-gray-500 hover:text-gray-700 hover:bg-gray-100/50 transition-colors group">
                        <i class="far fa-bell text-[13px] group-hover:scale-110 transition-transform"></i>

<span class="absolute top-1.5 right-1.5 flex h-2 w-2 {{ $unreadCount > 0 ? 'block' : 'hidden' }}">
                            <span
                                class="animate-ping absolute inline-flex h-full w-full rounded-full bg-orange-400 opacity-75"></span>
                            <span
                                class="relative inline-flex rounded-full h-2 w-2 bg-orange-500 border-2 border-white"></span>
                        </span>
                    </button>
                    <div x-show="notifOpen" x-transition
                        class="absolute right-0 top-full mt-3 w-80 bg-white/95 backdrop-blur-2xl rounded-3xl shadow-[0_24px_64px_-16px_rgba(0,0,0,0.15)] border border-white/20 overflow-hidden z-50"
                        style="display: none;">
                        <div class="p-5 border-b border-gray-100/50 flex items-center justify-between">
                            <h3 class="text-sm font-semibold text-gray-900">Notifications</h3>
                            @if($unreadCount > 0)
                                <span class="px-2 py-0.5 rounded-full bg-red-500 text-white text-[10px] font-bold">{{ $unreadCount }}</span>
                            @endif
                        </div>
                        @if($notifications->isNotEmpty())
                            <div class="max-h-80 overflow-y-auto">
                                @foreach($notifications as $notification)
                                    <a href="{{ $notification['link'] }}" class="flex items-center gap-3 px-5 py-3 hover:bg-gray-50 transition-colors border-b border-gray-50 last:border-0">
                                        <div class="w-9 h-9 rounded-xl {{ $notification['color'] }} flex items-center justify-center shrink-0">
                                            <i class="fas {{ $notification['icon'] }} text-sm"></i>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-xs font-semibold text-gray-900 truncate">{{ $notification['title'] }}</p>
                                            <p class="text-[10px] text-gray-400 mt-0.5">{{ $notification['time'] }}</p>
                                        </div>
                                        <span class="w-2 h-2 rounded-full bg-red-500 shrink-0" title="Belum dibaca"></span>
                                    </a>
                                @endforeach
                            </div>
                        @else
                            <div class="py-10 text-center text-gray-400">
                                <i class="far fa-bell text-3xl mb-3"></i>
                                <p class="text-sm font-medium">No new notifications</p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- User avatar + dropdown --}}
                <div class="relative ml-2" @click.away="userMenuOpen = false">
                    <button @click="userMenuOpen = !userMenuOpen"
                        class="flex items-center gap-2.5 pl-1 pr-2 py-1 rounded-2xl hover:bg-gray-100/40 transition-colors cursor-pointer group">
                        <div class="relative shrink-0">
                            <div
                                class="relative w-9 h-9 rounded-full bg-white flex items-center justify-center overflow-hidden ring-2 ring-gray-200">
                                @if ($hasAvatar)
                                    <img src="{{ asset('storage/' . auth()->user()->avatar) }}"
                                        alt="{{ auth()->user()->name }}" class="w-full h-full object-cover">
                                @else
                                    <img src="{{ asset('storage/profile/profile-placeholder.png') }}"
                                        alt="{{ auth()->user()->name }}" class="w-full h-full object-cover">
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
                                        class="relative w-14 h-14 rounded-full bg-white flex items-center justify-center overflow-hidden ring-2 ring-gray-200">
                                        @if ($hasAvatar)
                                            <img src="{{ asset('storage/' . auth()->user()->avatar) }}"
                                                alt="{{ auth()->user()->name }}" class="w-full h-full object-cover">
                                        @else
                                            <img src="{{ asset('storage/profile/profile-placeholder.png') }}"
                                                alt="{{ auth()->user()->name }}" class="w-full h-full object-cover">
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
                            <a href="{{ route('admin.profile.edit') }}"
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
