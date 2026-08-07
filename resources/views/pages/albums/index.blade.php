{{-- resources/views/pages/admin/albums/index.blade.php --}}
@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    
    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
        <div>
            <p class="text-xs font-semibold text-blue-400 uppercase tracking-widest">Media</p>
            <h1 class="text-3xl font-bold text-gray-900 mt-1">Album</h1>
            <p class="text-sm text-gray-400 mt-1.5 font-medium">Kelola album foto dan dokumentasi kegiatan CSA.</p>
        </div>
        <a href="{{ route('admin.albums.create') }}" 
           class="relative inline-flex items-center gap-2 px-5 py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white text-sm font-semibold rounded-2xl shadow-[0_4px_20px_rgba(59,130,246,0.3)] hover:shadow-[0_4px_25px_rgba(59,130,246,0.45)] hover:scale-105 transition-all duration-300 overflow-hidden group">
            <div class="absolute inset-0 bg-white/20 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
            <span class="relative flex items-center gap-2">
                <i class="fas fa-plus text-xs"></i>
                Tambah Album
            </span>
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
        @forelse($albums as $album)
            <div class="relative group/card">
                <div class="absolute -inset-[2px] bg-gradient-to-br from-blue-400/30 via-blue-300/20 to-cyan-300/30 rounded-[34px] blur-lg opacity-0 group-hover/card:opacity-100 transition-all duration-500"></div>
                
                <div class="relative bg-white/70 backdrop-blur-xl rounded-[30px] border border-white/50 shadow-[0_2px_16px_-4px_rgba(59,130,246,0.06)] group-hover/card:shadow-[0_8px_32px_-4px_rgba(59,130,246,0.12)] transition-all duration-500 h-full flex flex-col overflow-hidden">
                    
                    <div class="relative aspect-[16/10] overflow-hidden bg-gray-100">
                        @if($album->cover_image)
                            <img src="{{ asset('storage/' . $album->cover_image) }}" 
                                 alt="{{ $album->title }}" 
                                 class="w-full h-full object-cover group-hover/card:scale-105 transition-transform duration-500">
                        @elseif($album->galleries->first())
                            <img src="{{ asset('storage/' . $album->galleries->first()->image) }}" 
                                 alt="{{ $album->title }}" 
                                 class="w-full h-full object-cover group-hover/card:scale-105 transition-transform duration-500">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-gray-50">
                                <i class="fas fa-images text-3xl text-gray-300"></i>
                            </div>
                        @endif
                        <div class="absolute top-3 right-3">
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-black/40 backdrop-blur-md text-white text-[10px] font-medium">
                                <i class="fas fa-image text-[8px]"></i>
                                {{ $album->galleries_count ?? $album->galleries->count() }}
                            </span>
                        </div>
                    </div>

                    <div class="relative p-4 flex-1 flex flex-col">
                        <div class="flex items-start justify-between gap-3 mb-2">
                            <div class="min-w-0 flex-1">
                                <h3 class="text-sm font-bold text-gray-900 truncate">{{ $album->title }}</h3>
                                <p class="text-[11px] text-gray-400 mt-0.5 line-clamp-2">{{ $album->description ?: 'Tidak ada deskripsi.' }}</p>
                            </div>
                            <div class="flex items-center gap-0.5 shrink-0">
                                <a href="{{ route('admin.albums.edit', $album) }}" 
                                   class="w-8 h-8 rounded-xl flex items-center justify-center text-gray-400 hover:text-blue-600 hover:bg-blue-50 transition-all duration-200">
                                    <i class="fas fa-pen text-[10px]"></i>
                                </a>
                                <button onclick="openDeleteModal('deleteModal', '{{ route('admin.albums.destroy', $album) }}')"
                                        class="w-8 h-8 rounded-xl flex items-center justify-center text-gray-400 hover:text-red-500 hover:bg-red-50 transition-all duration-200">
                                    <i class="fas fa-trash text-[10px]"></i>
                                </button>
                            </div>
                        </div>

                        <div class="mt-auto pt-3 flex items-center justify-between border-t border-gray-100/60">
                            <span class="text-[10px] text-gray-400">{{ $album->created_at?->diffForHumans() }}</span>
                            <a href="{{ route('admin.galleries.index', ['album_id' => $album->id]) }}" 
                               class="inline-flex items-center gap-1.5 text-[11px] font-medium text-blue-500 hover:text-blue-600 transition-colors">
                                <span>Lihat Foto</span>
                                <i class="fas fa-arrow-right text-[9px]"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full">
                <div class="relative">
                    <div class="absolute -inset-[2px] bg-gradient-to-br from-blue-400/20 via-blue-300/15 to-cyan-300/20 rounded-[34px] blur-md"></div>
                    <div class="relative bg-white/70 backdrop-blur-xl rounded-[30px] border border-white/50 shadow-[0_2px_16px_-4px_rgba(59,130,246,0.06)] overflow-hidden">
                        <div class="relative py-16 text-center">
                            <div class="w-14 h-14 rounded-2xl bg-blue-50 flex items-center justify-center mx-auto mb-4 shadow-[0_2px_8px_-2px_rgba(59,130,246,0.1)]">
                                <i class="fas fa-folder-open text-xl text-blue-400"></i>
                            </div>
                            <p class="text-sm font-bold text-gray-900 mb-1">Belum ada album</p>
                            <p class="text-xs text-gray-400 mb-5">Tambahkan album pertama untuk mengelompokkan foto</p>
                            <a href="{{ route('admin.albums.create') }}" 
                               class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-blue-500 to-blue-600 text-white text-xs font-semibold rounded-xl shadow-[0_4px_12px_-2px_rgba(59,130,246,0.3)] hover:shadow-[0_8px_20px_-4px_rgba(59,130,246,0.4)] hover:scale-105 transition-all duration-300">
                                <i class="fas fa-plus text-[10px]"></i>
                                Tambah Album
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    @if($albums->hasPages())
        <div class="relative bg-white/70 backdrop-blur-xl rounded-[30px] border border-white/50 px-6 py-4 shadow-[0_2px_16px_-4px_rgba(59,130,246,0.06)]">
            {{ $albums->links() }}
        </div>
    @endif
</div>
@endsection