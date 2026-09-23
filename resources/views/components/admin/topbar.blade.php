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
    $newNews = \App\Models\News::where('status', 'published')->where('created_at', '>', now()->setTimestamp($lastSeen))->latest('published_at')->take(5)->get();
    foreach ($newNews as $news) {
        $notifications->push([
            'icon' => 'newspaper',
            'color' => 'bg-teal-500/15 text-teal-500 dark:text-teal-300',
            'title' => 'Berita Baru: ' . $news->title,
            'time' => $news->published_at?->diffForHumans() ?? $news->created_at->diffForHumans(),
            'link' => route('admin.news.show', $news),
        ]);
    }
    $newEvents = \App\Models\Event::where('status', 'published')->where('event_date', '>=', now()->toDateString())->where('created_at', '>', now()->setTimestamp($lastSeen))->latest('event_date')->take(5)->get();
    foreach ($newEvents as $event) {
        $notifications->push([
            'icon' => 'calendar-days',
            'color' => 'bg-cyan-500/15 text-cyan-600 dark:text-cyan-300',
            'title' => 'Acara: ' . $event->title,
            'time' => $event->event_date?->format('d M Y') ?? '-',
            'link' => route('admin.events.show', $event),
        ]);
    }
    $notifications = $notifications->take(10);
    $unreadCount = $notifications->count();

    // Ringkasan aktivitas sistem: hanya untuk pemegang izin log.
    $systemActivity = auth()->user()?->role?->hasPermission('view-activity-log')
        ? \App\Models\ActivityLog::with('user:id,name')->latestFirst()->take(6)->get()
        : collect();
@endphp

<header x-data="{
    notifOpen: false,
    userMenuOpen: false,
    sidebarExpanded: JSON.parse(localStorage.getItem('sidebar-expanded') ?? 'true'),
    init() {
        window.addEventListener('sidebar-toggled', (e) => { this.sidebarExpanded = e.detail.expanded; });
        window.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                this.notifOpen = false;
                this.userMenuOpen = false;
            }
        });
    }
}">
    <div class="csa-topbar fixed top-0 left-0 right-0 z-40 h-14 flex items-center px-4 sm:px-6"
        :class="sidebarExpanded ? 'lg:left-[240px]' : 'lg:left-[72px]'">

        <div class="min-w-0 ml-2 lg:ml-0">
            <h1 class="text-[15px] font-semibold text-[var(--text-primary)] tracking-tight truncate">
                {{ $pageTitle }}
                @if ($actionLabel)
                    <span class="text-[var(--text-muted)] font-medium text-[13px] ml-1.5">/ {{ $actionLabel }}</span>
                @endif
            </h1>
        </div>

        <div class="flex items-center gap-1 sm:gap-2 ml-auto shrink-0">
            <button @click="toggleTheme()" class="csa-icon-btn" :title="isDark ? 'Gunakan mode terang' : 'Gunakan mode gelap'" aria-label="Ubah tema">
                <span x-show="isDark"><x-admin.icon name="sun" :size="18" /></span>
                <span x-show="!isDark"><x-admin.icon name="moon" :size="18" /></span>
            </button>

            <div class="relative" @click.outside="notifOpen = false" x-data="{ tab: 'notif' }">
                <button @click="notifOpen = !notifOpen" class="csa-icon-btn relative" aria-label="Notifikasi">
                    <x-admin.icon name="bell" :size="18" />
                    <span class="absolute top-1.5 right-1.5 w-2 h-2 rounded-full bg-orange-500 border-2 border-[var(--topbar-bg)] {{ $unreadCount > 0 ? '' : 'hidden' }}"></span>
                </button>
                <div x-show="notifOpen" x-transition
                    class="absolute right-0 top-full mt-2 w-80 max-w-[calc(100vw-2rem)] bg-[var(--bg-elevated)] border border-[var(--border-strong)] rounded-2xl shadow-2xl overflow-hidden z-50"
                    style="display: none;">
                    <div class="p-4 border-b border-[var(--border-soft)] flex items-center justify-between">
                        <h3 class="text-sm font-semibold text-[var(--text-primary)]">
                            <span x-text="tab === 'notif' ? 'Notifikasi' : 'Aktivitas Sistem'"></span>
                        </h3>
                        @if($unreadCount > 0)
                            <span class="px-2 py-0.5 rounded-full bg-orange-500/15 text-orange-500 text-[10px] font-bold" x-show="tab === 'notif'">{{ $unreadCount }}</span>
                        @endif
                    </div>

                    @if ($systemActivity->isNotEmpty())
                        <div class="flex items-center gap-1 px-3 pt-3">
                            <button type="button" @click="tab = 'notif'"
                                class="flex-1 px-3 py-1.5 rounded-lg text-[12px] font-medium transition-colors"
                                :class="tab === 'notif' ? 'bg-[var(--accent-subtle)] text-[var(--accent-bright)]' : 'text-[var(--text-muted)]'">
                                Notifikasi
                            </button>
                            <button type="button" @click="tab = 'activity'"
                                class="flex-1 px-3 py-1.5 rounded-lg text-[12px] font-medium transition-colors"
                                :class="tab === 'activity' ? 'bg-[var(--accent-subtle)] text-[var(--accent-bright)]' : 'text-[var(--text-muted)]'">
                                Aktivitas
                            </button>
                        </div>
                    @endif

                    <div class="max-h-72 overflow-y-auto" x-show="tab === 'notif'">
                        @forelse($notifications as $notification)
                            <a href="{{ $notification['link'] }}" class="flex items-center gap-3 px-4 py-3 hover:bg-[var(--bg-hover)] transition-colors border-b border-[var(--border-soft)] last:border-0">
                                <div class="w-8 h-8 rounded-lg {{ $notification['color'] }} flex items-center justify-center shrink-0">
                                    <x-admin.icon :name="$notification['icon']" :size="15" />
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs font-medium text-[var(--text-primary)] truncate">{{ $notification['title'] }}</p>
                                    <p class="text-[10px] text-[var(--text-muted)] mt-0.5">{{ $notification['time'] }}</p>
                                </div>
                            </a>
                        @empty
                            <div class="py-10 text-center text-[var(--text-muted)]">
                                <x-admin.icon name="bell-off" :size="28" class="mx-auto mb-2" />
                                <p class="text-sm">Tidak ada notifikasi</p>
                            </div>
                        @endforelse
                    </div>

                    {{-- Aktivitas terbaru: hanya untuk pemegang izin log --}}
                    @if ($systemActivity->isNotEmpty())
                        <div class="max-h-72 overflow-y-auto" x-show="tab === 'activity'" x-cloak>
                            @foreach ($systemActivity as $log)
                                <div class="flex items-start gap-3 px-4 py-3 border-b border-[var(--border-soft)] last:border-0">
                                    <div class="w-8 h-8 rounded-lg bg-[var(--bg-muted)] flex items-center justify-center shrink-0">
                                        <x-admin.icon name="scroll-text" :size="15" class="text-[var(--text-muted)]" />
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-xs text-[var(--text-primary)] line-clamp-2">{{ $log->description }}</p>
                                        <p class="text-[10px] text-[var(--text-muted)] mt-0.5">
                                            {{ $log->user?->name ?? 'Sistem' }} · {{ $log->created_at?->diffForHumans() }}
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                            <a href="{{ route('admin.activity-log.index') }}" class="block px-4 py-3 text-center text-[12px] font-medium hover:underline" style="color: var(--accent);">
                                Lihat log lengkap
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <div class="relative ml-0.5" @click.outside="userMenuOpen = false">
                <button @click="userMenuOpen = !userMenuOpen" class="flex items-center gap-2 p-1 pr-1.5 min-h-[44px] rounded-full hover:bg-[var(--bg-hover)] transition-colors cursor-pointer">
                    <div class="w-8 h-8 rounded-full ring-2 ring-[var(--border-soft)] overflow-hidden">
                        <x-admin.avatar :size="32" class="w-full h-full" />
                    </div>
                    <x-admin.icon name="chevron-down" :size="14" class="text-[var(--text-muted)] transition-transform" x-bind:class="userMenuOpen ? 'rotate-180' : ''" />
                </button>
                <div x-show="userMenuOpen" x-transition
                    class="absolute right-0 top-full mt-2 w-64 bg-[var(--bg-elevated)] border border-[var(--border-strong)] rounded-2xl shadow-2xl overflow-hidden z-50"
                    style="display: none;">
                    <div class="px-4 pt-4 pb-3 border-b border-[var(--border-soft)]">
                        <p class="text-sm font-semibold text-[var(--text-primary)] truncate">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-[var(--text-muted)] truncate">{{ Auth::user()->email }}</p>
                        <span class="inline-block mt-1.5 px-2 py-0.5 rounded-full bg-[var(--bg-hover)] border border-[var(--border-soft)] text-[10px] font-medium text-[var(--text-secondary)] capitalize">{{ auth()->user()->role?->name }}</span>
                    </div>
                    <div class="p-1.5">
                        <a href="{{ route('admin.profile.edit') }}" class="flex min-h-[44px] items-center gap-2.5 px-3 py-2.5 rounded-xl text-[13px] text-[var(--text-secondary)] hover:bg-[var(--bg-hover)] transition-colors">
                            <x-admin.icon name="user-round" :size="16" /> Profile
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full min-h-[44px] flex items-center gap-2.5 px-3 py-2.5 rounded-xl text-[13px] text-red-500 hover:bg-red-500/10 transition-colors">
                                <x-admin.icon name="log-out" :size="16" /> Sign out
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

<div class="h-14"></div>
