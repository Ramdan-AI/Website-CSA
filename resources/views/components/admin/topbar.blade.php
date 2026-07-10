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

    $isDetailPage = request()->is('*/*/*') && !request()->is('*/*/create') && !request()->is('*/*/edit');
@endphp

<header
    class="relative bg-white/80 backdrop-blur-xl rounded-3xl shadow-[0_8px_32px_rgba(59,130,246,0.05)] border border-white/60 mx-8 mt-4">
    <div class="absolute inset-0 bg-gradient-to-br from-blue-400/5 to-purple-400/5 rounded-3xl pointer-events-none">
    </div>

    <div class="relative flex items-center justify-between h-[72px] px-6">
        <div class="flex items-center gap-3 min-w-0">
            <div class="flex items-center gap-2.5">
                <div class="min-w-0">
                    <h1 class="text-lg font-bold text-gray-900 truncate">{{ $pageTitle }}</h1>
                    @if ($isDetailPage)
                        <p class="text-xs text-gray-400 font-medium">Detail Data</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="flex items-center gap-1.5 shrink-0">
            <div class="relative" x-data="{ open: false }" @click.away="open = false">
                <button @click="open = !open"
                    class="w-10 h-10 rounded-xl flex items-center justify-center text-gray-400 hover:text-blue-600 hover:bg-white/60 transition-all duration-300">
                    <i class="fas fa-search text-sm"></i>
                </button>
                <div x-show="open" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                    class="absolute right-0 top-full mt-2 w-80 bg-white/95 backdrop-blur-xl rounded-2xl shadow-[0_10px_40px_rgba(0,0,0,0.1)] border border-gray-100 p-3 z-50"
                    style="display: none;">
                    <div class="relative">
                        <i class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                        <input type="text" placeholder="Cari data..."
                            class="w-full h-11 pl-10 pr-4 rounded-xl bg-gray-50 border border-gray-100 text-sm focus:outline-none focus:border-blue-300 focus:bg-white transition-all">
                        <kbd
                            class="absolute right-3.5 top-1/2 -translate-y-1/2 px-2 py-0.5 text-[10px] font-mono text-gray-400 bg-gray-100 rounded-md">⌘K</kbd>
                    </div>
                </div>
            </div>

            <button
                class="relative w-10 h-10 rounded-xl flex items-center justify-center text-gray-400 hover:text-blue-600 hover:bg-white/60 transition-all duration-300">
                <i class="far fa-bell text-sm"></i>
                <span class="absolute top-2 right-2 w-2 h-2 bg-orange-400 rounded-full border-2 border-white"></span>
            </button>

            <div class="h-6 w-px bg-gray-200 mx-2"></div>

            <div class="relative" x-data="{ open: false }" @click.away="open = false">
                <button @click="open = !open"
                    class="flex items-center gap-2.5 pl-2 pr-3 py-2 rounded-2xl hover:bg-white/60 transition-all duration-300 cursor-pointer">
                    <div
                        class="w-9 h-9 rounded-xl bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white font-bold text-sm shrink-0 shadow-sm">
                        {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
                    </div>
                    <div class="hidden sm:block text-left">
                        <p class="text-[13px] font-semibold text-gray-900 leading-none">
                            {{ explode(' ', Auth::user()->name ?? 'Admin')[0] }}</p>
                        <p class="text-[10px] text-gray-400 font-medium leading-none mt-0.5">
                            {{ Auth::user()->role->name ?? 'Admin' }}</p>
                    </div>
                    <i class="fas fa-chevron-down text-[10px] text-gray-400 transition-transform duration-200"
                        :class="open ? 'rotate-180' : ''"></i>
                </button>

                <div x-show="open" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
                    x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                    class="absolute right-0 top-full mt-2 w-56 bg-white/95 backdrop-blur-xl rounded-2xl shadow-[0_10px_40px_rgba(0,0,0,0.1)] border border-gray-100 py-2 z-50"
                    style="display: none;">

                    <div class="px-4 py-2 border-b border-gray-50 mb-1">
                        <p class="text-[13px] font-semibold text-gray-900">{{ Auth::user()->name }}</p>
                        <p class="text-[11px] text-gray-400">{{ Auth::user()->email }}</p>
                    </div>

                    <a href="{{ route('admin.settings.edit') }}"
                        class="flex items-center gap-3 px-4 py-2.5 text-[13px] text-gray-600 hover:bg-gray-50 hover:text-gray-900 font-medium transition-colors">
                        <i class="fas fa-cog w-5 text-gray-400"></i>
                        Pengaturan
                    </a>

                    <div class="border-t border-gray-50 mt-1 pt-1">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="flex items-center gap-3 px-4 py-2.5 text-[13px] text-red-500 hover:bg-red-50 font-medium transition-colors w-full">
                                <i class="fas fa-sign-out-alt w-5"></i>
                                Keluar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
