@extends('layouts.admin')

@section('content')
<div class="space-y-8" x-data="divisionsPage()">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
        <div>
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest">Data Master</p>
            <h1 class="text-2xl font-bold text-gray-900 mt-1.5">Divisi</h1>
            <p class="text-sm text-gray-500 mt-1">{{ $divisions->total() }} divisi</p>
        </div>
        <a href="{{ route('admin.divisions.create') }}"
            class="inline-flex items-center gap-2 px-4 py-2.5 bg-gray-900 text-white text-sm font-semibold rounded-lg hover:bg-gray-800 active:bg-gray-700 transition-all duration-150 shrink-0">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            Tambah Divisi
        </a>
    </div>

    <!-- Toolbar -->
    <div class="flex flex-col sm:flex-row gap-3">
        <!-- Search -->
        <div class="relative flex-1 max-w-md">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <input type="text" x-model="search" @input.debounce="updateUrl"
                placeholder="Cari divisi..."
                class="w-full pl-10 pr-4 py-2.5 text-sm bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-900/10 focus:border-gray-900 placeholder:text-gray-400 transition-colors">
        </div>

        <!-- Status Filter -->
        <select x-model="status" @change="updateUrl"
            class="w-full sm:w-44 px-3 py-2.5 text-sm bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-900/10 focus:border-gray-900 transition-colors appearance-none bg-no-repeat bg-right pr-9 text-gray-700"
            style="background-image: url(\"data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e\"); background-size: 1.25rem 1.25rem;">
            <option value="">Semua Status</option>
            <option value="1">Aktif</option>
            <option value="0">Nonaktif</option>
        </select>

        <!-- Sort -->
        <select x-model="sort" @change="updateUrl"
            class="w-full sm:w-48 px-3 py-2.5 text-sm bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-900/10 focus:border-gray-900 transition-colors appearance-none bg-no-repeat bg-right pr-9 text-gray-700"
            style="background-image: url(\"data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e\"); background-size: 1.25rem 1.25rem;">
            <option value="name_asc">Nama A-Z</option>
            <option value="name_desc">Nama Z-A</option>
            <option value="members_desc">Anggota Terbanyak</option>
            <option value="members_asc">Anggota Tersedikit</option>
            <option value="newest">Terbaru</option>
            <option value="oldest">Terlama</option>
        </select>
    </div>

    <!-- Results Summary -->
    <div class="flex items-center justify-between" x-show="filteredCount !== undefined">
        <p class="text-sm text-gray-500" x-text="`Menampilkan ${filteredCount} divisi`"></p>
        <button x-show="search || status" @click="resetFilters"
            class="text-sm text-gray-500 hover:text-gray-900 underline underline-offset-2 transition-colors">
            Reset filter
        </button>
    </div>

    <!-- Loading State -->
    <div x-show="loading" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
        <template x-for="i in 6" :key="i">
            <div class="bg-white border border-gray-200 rounded-xl p-5 space-y-4 animate-pulse">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-xl bg-gray-100"></div>
                    <div class="space-y-2 flex-1">
                        <div class="h-4 bg-gray-100 rounded w-3/4"></div>
                        <div class="h-3 bg-gray-100 rounded w-1/2"></div>
                    </div>
                </div>
                <div class="space-y-2">
                    <div class="h-3 bg-gray-100 rounded w-1/3"></div>
                    <div class="h-3 bg-gray-100 rounded w-1/4"></div>
                </div>
                <div class="flex gap-2 pt-2">
                    <div class="h-9 bg-gray-100 rounded-lg w-24"></div>
                    <div class="h-9 bg-gray-100 rounded-lg w-9"></div>
                </div>
            </div>
        </template>
    </div>

    <!-- Empty State -->
    <div x-show="!loading && filteredCount === 0"
        class="text-center py-16">
        <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-gray-100 flex items-center justify-center">
            <svg class="w-8 h-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
            </svg>
        </div>
        <p class="text-base font-semibold text-gray-900" x-show="search || status">Tidak ada divisi yang cocok</p>
        <p class="text-sm text-gray-500 mt-1">
            <span x-show="search">Coba ubah kata kunci pencarian</span>
            <span x-show="status && !search">Coba ubah filter status</span>
        </p>
    </div>

    <!-- Cards Grid -->
    <div x-show="!loading" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
        @forelse($divisions as $division)
            <div class="group bg-white border border-gray-200 rounded-xl hover:shadow-md hover:border-gray-300 transition-all duration-200"
                data-division
                data-name="{{ $division->name }}"
                data-slug="{{ $division->slug }}"
                data-leader="{{ $division->leader_name ?? '' }}"
                data-active="{{ $division->is_active ? '1' : '0' }}"
                x-show="matchesFilters('{{ $division->name }}', '{{ $division->slug }}', '{{ addslashes($division->leader_name ?? '') }}', {{ $division->is_active ? 1 : 0 }})"
                style="display: none;">

                <div class="p-5">
                    <!-- Top Row: Avatar + Name -->
                    <div class="flex items-start gap-3.5">
                        <div class="shrink-0">
                            @if ($division->image)
                                <img src="{{ asset('storage/' . $division->image) }}"
                                    alt="{{ $division->name }}"
                                    class="w-12 h-12 rounded-xl object-cover bg-gray-50 ring-1 ring-black/5">
                            @else
                                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-gray-50 to-gray-100 ring-1 ring-black/5 flex items-center justify-center">
                                    <svg class="w-6 h-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                </div>
                            @endif
                        </div>

                        <div class="min-w-0 flex-1 pt-0.5">
                            <div class="flex items-center gap-2">
                                <h3 class="text-base font-semibold text-gray-900 truncate">{{ $division->name }}</h3>
                                @if ($division->is_active)
                                    <span class="shrink-0 w-2 h-2 rounded-full bg-emerald-500" title="Aktif"></span>
                                @else
                                    <span class="shrink-0 w-2 h-2 rounded-full bg-gray-300" title="Nonaktif"></span>
                                @endif
                            </div>
                            <p class="text-xs text-gray-400 font-mono mt-0.5">{{ $division->slug }}</p>
                        </div>
                    </div>

                    <!-- Badges Row -->
                    <div class="flex flex-wrap items-center gap-3 mt-4">
                        <div class="flex items-center gap-1.5 text-xs text-gray-500">
                            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                            </svg>
                            <span>{{ $division->leader_name ?? '—' }}</span>
                        </div>
                        <div class="flex items-center gap-1.5 text-xs text-gray-500">
                            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                            </svg>
                            <span>{{ $division->members_count ?? 0 }} anggota</span>
                        </div>
                        @if ($division->is_active)
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-100">
                                Aktif
                            </span>
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600 border border-gray-200">
                                Nonaktif
                            </span>
                        @endif
                    </div>

                    <!-- Description -->
                    @if ($division->description)
                        <p class="text-sm text-gray-500 mt-3 line-clamp-2 leading-relaxed">{{ $division->description }}</p>
                    @endif

                    <!-- Actions -->
                    <div class="flex items-center gap-2 mt-4 pt-4 border-t border-gray-100">
                        <a href="{{ route('admin.divisions.members', $division) }}"
                            class="inline-flex items-center gap-1.5 px-3.5 py-2 text-sm font-medium text-gray-700 bg-gray-50 rounded-lg hover:bg-gray-100 hover:text-gray-900 transition-colors duration-150">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                            </svg>
                            Anggota
                        </a>
                        <div class="relative ml-auto" x-data="{ open: false }">
                            <button @click="open = !open" @click.outside="open = false"
                                class="w-9 h-9 rounded-lg flex items-center justify-center text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-colors duration-150"
                                aria-label="Opsi">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v.01M12 12v.01M12 19v.01" />
                                </svg>
                            </button>
                            <div x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:leave="transition ease-in duration-75"
                                class="absolute right-0 top-full mt-1 w-40 bg-white border border-gray-200 rounded-lg shadow-lg py-1 z-20"
                                @click="open = false"
                                style="display: none;">
                                <a href="{{ route('admin.divisions.edit', $division) }}"
                                    class="flex items-center gap-2 px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    Edit
                                </a>
                                <form action="{{ route('admin.divisions.destroy', $division) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        onclick="return confirm('Hapus divisi {{ $division->name }}? Tindakan ini tidak dapat dibatalkan.')"
                                        class="w-full flex items-center gap-2 px-3 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                        <svg class="w-4 h-4 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-16">
                <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-gray-100 flex items-center justify-center">
                    <svg class="w-8 h-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
                <p class="text-base font-semibold text-gray-900">Belum ada divisi</p>
                <p class="text-sm text-gray-500 mt-1">Tambahkan divisi pertama untuk memulai struktur organisasi</p>
                <a href="{{ route('admin.divisions.create') }}"
                    class="inline-flex items-center gap-2 px-4 py-2.5 bg-gray-900 text-white text-sm font-semibold rounded-lg hover:bg-gray-800 active:bg-gray-700 transition-all duration-150 mt-6">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Divisi Pertama
                </a>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if ($divisions->hasPages())
        <div class="pt-2">
            {{ $divisions->appends(request()->query())->links() }}
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script defer>
document.addEventListener('alpine:init', () => {
    Alpine.data('divisionsPage', () => ({
        search: new URLSearchParams(window.location.search).get('search') || '',
        status: new URLSearchParams(window.location.search).get('status') || '',
        sort: new URLSearchParams(window.location.search).get('sort') || 'name_asc',
        loading: false,
        filteredCount: null,

        init() {
            this.calculateFilteredCount();
            this.$nextTick(() => {
                document.querySelectorAll('[data-division]').forEach(el => el.style.display = '');
                this.calculateFilteredCount();
            });
        },

        matchesFilters(name, slug, leader, active) {
            const q = this.search.toLowerCase();
            const matchesSearch = !q ||
                name.toLowerCase().includes(q) ||
                slug.toLowerCase().includes(q) ||
                leader.toLowerCase().includes(q);
            const matchesStatus = !this.status || active === parseInt(this.status);
            return matchesSearch && matchesStatus;
        },

        calculateFilteredCount() {
            const cards = document.querySelectorAll('[data-division]');
            let visible = 0;
            cards.forEach(card => {
                const name = card.dataset.name;
                const slug = card.dataset.slug;
                const leader = card.dataset.leader;
                const active = parseInt(card.dataset.active);
                if (this.matchesFilters(name, slug, leader, active)) {
                    visible++;
                }
            });
            this.filteredCount = visible;
        },

        updateUrl() {
            const params = new URLSearchParams();
            if (this.search) params.set('search', this.search);
            if (this.status) params.set('status', this.status);
            if (this.sort && this.sort !== 'name_asc') params.set('sort', this.sort);
            const qs = params.toString();
            const url = window.location.pathname + (qs ? '?' + qs : '');
            window.history.replaceState({}, '', url);
            this.loading = true;
            this.filteredCount = null;
            window.location.href = url;
        },

        resetFilters() {
            this.search = '';
            this.status = '';
            this.sort = 'name_asc';
            this.updateUrl();
        }
    }));
});
</script>
@endpush