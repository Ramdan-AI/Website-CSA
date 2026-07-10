@extends('layouts.admin')

@section('content')
    <div class="space-y-6">

        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
            <div>
                <p class="text-xs font-semibold text-blue-400 uppercase tracking-widest">Data Master</p>
                <h1 class="text-3xl font-bold text-gray-900 mt-1">Divisi</h1>
                <p class="text-sm text-gray-400 mt-1.5 font-medium">Kelola struktur dan informasi divisi dalam sistem
                    operasional CSA.</p>
            </div>
            <a href="{{ route('admin.divisions.create') }}"
                class="relative inline-flex items-center gap-2 px-5 py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white text-sm font-semibold rounded-2xl shadow-[0_4px_20px_rgba(59,130,246,0.3)] hover:shadow-[0_4px_25px_rgba(59,130,246,0.45)] hover:scale-105 transition-all duration-300 overflow-hidden group">
                <div
                    class="absolute inset-0 bg-white/20 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                </div>
                <span class="relative flex items-center gap-2">
                    <i class="fas fa-plus text-xs"></i>
                    Tambah Divisi
                </span>
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 2xl:grid-cols-4 gap-5">
            @forelse($divisions as $division)
                <div class="relative group/card">
                    <div
                        class="absolute -inset-1 bg-gradient-to-br from-blue-400/30 via-blue-500/20 to-purple-400/30 rounded-[32px] blur-lg opacity-0 group-hover/card:opacity-100 transition-opacity duration-500">
                    </div>

                    <div
                        class="relative bg-white/90 backdrop-blur-xl rounded-[28px] border border-white/60 shadow-[0_8px_32px_rgba(59,130,246,0.06)] overflow-hidden group-hover/card:shadow-[0_16px_48px_rgba(59,130,246,0.15)] transition-all duration-500">
                        <div
                            class="absolute inset-0 bg-gradient-to-br from-blue-400/5 to-purple-400/5 rounded-[28px] pointer-events-none">
                        </div>

                        <div class="relative">
                            <div class="block relative h-52 overflow-hidden cursor-pointer"
                                @if ($division->image) onclick="openImagePreview('imagePreviewModal', '{{ asset('storage/' . $division->image) }}', '{{ $division->name }}')"
                             @else
                                 onclick="window.location='{{ route('admin.divisions.members', $division) }}'" @endif>
                                @if ($division->image)
                                    <img src="{{ asset('storage/' . $division->image) }}" alt="{{ $division->name }}"
                                        class="w-full h-full object-cover group-hover/card:scale-110 transition-transform duration-700 ease-[cubic-bezier(0.4,0,0.2,1)]">
                                    <div
                                        class="absolute inset-0 bg-black/0 group-hover/card:bg-black/30 transition-colors duration-300 flex items-center justify-center">
                                        <div
                                            class="w-14 h-14 rounded-2xl bg-white/20 backdrop-blur-md flex items-center justify-center opacity-0 group-hover/card:opacity-100 transition-all duration-300 transform scale-50 group-hover/card:scale-100">
                                            <i class="fas fa-search-plus text-2xl text-white"></i>
                                        </div>
                                    </div>
                                @else
                                    <div
                                        class="w-full h-full bg-gradient-to-br from-blue-400 via-blue-500 to-purple-600 relative overflow-hidden">
                                        <div class="absolute -top-10 -right-10 w-40 h-40 bg-white/10 rounded-full blur-2xl">
                                        </div>
                                        <div
                                            class="absolute -bottom-10 -left-10 w-40 h-40 bg-white/5 rounded-full blur-2xl">
                                        </div>
                                        <div class="absolute inset-0 flex items-center justify-center">
                                            <i class="fas fa-building text-6xl text-white/20"></i>
                                        </div>
                                    </div>
                                @endif

                                <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent">
                                </div>

                                <div class="absolute bottom-4 left-4 right-4">
                                    <div class="flex items-end justify-between">
                                        <div>
                                            <h3 class="text-lg font-bold text-white drop-shadow-lg">{{ $division->name }}
                                            </h3>
                                            <p class="text-xs text-white/70 font-mono">{{ $division->slug }}</p>
                                        </div>
                                        @if ($division->is_active)
                                            <span
                                                class="shrink-0 inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-white/20 backdrop-blur-md text-[10px] font-semibold text-white border border-white/20">
                                                <span
                                                    class="w-1.5 h-1.5 rounded-full bg-emerald-400 shadow-[0_0_8px_rgba(52,211,153,0.6)]"></span>
                                                Aktif
                                            </span>
                                        @else
                                            <span
                                                class="shrink-0 inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-white/20 backdrop-blur-md text-[10px] font-semibold text-white/60 border border-white/20">
                                                <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span>
                                                Nonaktif
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="p-5 space-y-4">
                                <p class="text-sm text-gray-500 line-clamp-2 leading-relaxed">
                                    {{ $division->description ?: 'Belum ada deskripsi untuk divisi ini.' }}</p>

                                <div class="grid grid-cols-2 gap-3">
                                    <div
                                        class="relative bg-gradient-to-br from-blue-50 to-blue-100/50 rounded-2xl p-3 overflow-hidden group/stat hover:from-blue-100 hover:to-blue-200/50 transition-colors duration-300">
                                        <div
                                            class="absolute top-0 right-0 w-12 h-12 bg-blue-400/10 rounded-full blur-lg -translate-y-1/2 translate-x-1/2">
                                        </div>
                                        <div class="relative">
                                            <p class="text-[10px] text-blue-500 uppercase font-bold tracking-wider mb-1">
                                                Anggota</p>
                                            <p class="text-xl font-bold text-blue-700">{{ $division->members_count ?? 0 }}
                                            </p>
                                        </div>
                                    </div>
                                    <div
                                        class="relative bg-gradient-to-br from-purple-50 to-purple-100/50 rounded-2xl p-3 overflow-hidden group/stat hover:from-purple-100 hover:to-purple-200/50 transition-colors duration-300">
                                        <div
                                            class="absolute top-0 right-0 w-12 h-12 bg-purple-400/10 rounded-full blur-lg -translate-y-1/2 translate-x-1/2">
                                        </div>
                                        <div class="relative">
                                            <p class="text-[10px] text-purple-500 uppercase font-bold tracking-wider mb-1">
                                                Ketua</p>
                                            <p class="text-xs font-bold text-purple-700 truncate">
                                                {{ $division->leader_name ?? '-' }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex items-center gap-2 pt-1">
                                    <a href="{{ route('admin.divisions.members', $division) }}"
                                        class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-gradient-to-r from-blue-500 to-blue-600 text-white text-xs font-semibold rounded-xl shadow-[0_4px_12px_rgba(59,130,246,0.2)] hover:shadow-[0_4px_20px_rgba(59,130,246,0.4)] hover:scale-105 transition-all duration-300">
                                        <i class="fas fa-users text-[10px]"></i>
                                        Lihat Anggota
                                    </a>
                                    <div class="flex items-center gap-1">
                                        <a href="{{ route('admin.divisions.edit', $division) }}"
                                            class="w-9 h-9 rounded-xl flex items-center justify-center bg-gray-50 text-gray-400 hover:bg-blue-50 hover:text-blue-600 hover:shadow-[0_4px_12px_rgba(59,130,246,0.15)] transition-all duration-200">
                                            <i class="fas fa-pen text-xs"></i>
                                        </a>
                                        <button onclick="openDeleteModal('deleteModal', '{{ route('admin.divisions.destroy', $division) }}')"
        class="w-9 h-9 rounded-xl flex items-center justify-center bg-gray-50 text-gray-400 hover:bg-red-50 hover:text-red-500 hover:shadow-[0_4px_12px_rgba(239,68,68,0.15)] transition-all duration-200">
    <i class="fas fa-trash text-xs"></i>
</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full">
                    <div class="relative">
                        <div
                            class="absolute -inset-1 bg-gradient-to-br from-blue-400/20 via-blue-500/10 to-purple-400/20 rounded-[32px] blur-md">
                        </div>
                        <div
                            class="relative bg-white/80 backdrop-blur-xl rounded-[32px] border border-white/60 shadow-[0_8px_32px_rgba(59,130,246,0.05)] overflow-hidden">
                            <div
                                class="absolute inset-0 bg-gradient-to-br from-blue-400/5 to-purple-400/5 pointer-events-none">
                            </div>
                            <div class="relative py-24 text-center">
                                <div class="relative w-20 h-20 mx-auto mb-6">
                                    <div class="absolute inset-0 bg-blue-400/30 rounded-2xl blur-xl animate-pulse"></div>
                                    <div
                                        class="relative w-full h-full rounded-2xl bg-white/90 backdrop-blur-sm border border-white/60 flex items-center justify-center shadow-[0_8px_32px_rgba(59,130,246,0.1)]">
                                        <i class="fas fa-sitemap text-3xl text-blue-400"></i>
                                    </div>
                                </div>
                                <p class="text-base font-bold text-gray-900 mb-1">Belum ada divisi</p>
                                <p class="text-sm text-gray-400 mb-6">Tambahkan divisi pertama untuk memulai struktur
                                    organisasi</p>
                                <a href="{{ route('admin.divisions.create') }}"
                                    class="relative inline-flex items-center gap-2 px-5 py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white text-sm font-semibold rounded-xl shadow-[0_4px_16px_rgba(59,130,246,0.3)] hover:shadow-[0_4px_20px_rgba(59,130,246,0.45)] hover:scale-105 transition-all duration-300">
                                    <i class="fas fa-plus text-xs"></i>
                                    Tambah Divisi Pertama
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>

        @if ($divisions->hasPages())
            <div
                class="relative bg-white/80 backdrop-blur-xl rounded-[28px] border border-white/60 px-6 py-4 shadow-[0_8px_32px_rgba(59,130,246,0.05)]">
                {{ $divisions->links() }}
            </div>
        @endif
    </div>
@endsection
