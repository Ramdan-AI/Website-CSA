{{-- resources/views/pages/admin/galleries/index.blade.php --}}
@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    
    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
        <div>
            <p class="text-xs font-semibold text-blue-400 uppercase tracking-widest">Media</p>
            <h1 class="text-3xl font-bold text-gray-900 mt-1">Galeri</h1>
            <p class="text-sm text-gray-400 mt-1.5 font-medium">Kelola koleksi foto dan dokumentasi kegiatan CSA.</p>
        </div>
        <a href="{{ route('admin.galleries.create') }}" 
           class="relative inline-flex items-center gap-2 px-5 py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white text-sm font-semibold rounded-2xl shadow-[0_4px_20px_rgba(59,130,246,0.3)] hover:shadow-[0_4px_25px_rgba(59,130,246,0.45)] hover:scale-105 transition-all duration-300 overflow-hidden group">
            <div class="absolute inset-0 bg-white/20 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
            <span class="relative flex items-center gap-2">
                <i class="fas fa-plus text-xs"></i>
                Tambah Foto
            </span>
        </a>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
        @forelse($galleries as $gallery)
            <div class="relative group/card">
                <div class="absolute -inset-[2px] bg-gradient-to-br from-blue-400/30 via-blue-300/20 to-cyan-300/30 rounded-[28px] blur-lg opacity-0 group-hover/card:opacity-100 transition-all duration-500"></div>
                
                <div class="relative bg-white/70 backdrop-blur-xl rounded-[24px] border border-white/50 shadow-[0_2px_16px_-4px_rgba(59,130,246,0.06)] group-hover/card:shadow-[0_8px_32px_-4px_rgba(59,130,246,0.12)] transition-all duration-500 overflow-hidden">
                    
                    <div class="aspect-square overflow-hidden cursor-pointer" onclick="openImagePreview('imagePreviewModal', '{{ asset('storage/' . $gallery->image) }}', '{{ $gallery->caption ?? 'Galeri CSA' }}')">
                        <img src="{{ asset('storage/' . $gallery->image) }}" 
                             alt="{{ $gallery->caption ?? 'Foto' }}" 
                             class="w-full h-full object-cover group-hover/card:scale-110 transition-transform duration-500"
                             loading="lazy">
                    </div>
                    
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover/card:opacity-100 transition-opacity duration-300 rounded-[24px] pointer-events-none"></div>
                    
                    <div class="absolute bottom-3 left-3 right-3 opacity-0 group-hover/card:opacity-100 transition-all duration-300 translate-y-2 group-hover/card:translate-y-0">
                        <div class="flex items-center justify-between">
                            <div class="min-w-0 flex-1 mr-2">
                                @if($gallery->caption)
                                    <p class="text-xs font-medium text-white truncate drop-shadow-lg">{{ $gallery->caption }}</p>
                                @endif
                                @if($gallery->album)
                                    <p class="text-[10px] text-white/70 truncate drop-shadow-lg">{{ $gallery->album->title }}</p>
                                @endif
                            </div>
                            <div class="flex items-center gap-1 shrink-0">
                                <a href="{{ route('admin.galleries.edit', $gallery) }}" 
                                   class="w-7 h-7 rounded-lg flex items-center justify-center bg-white/90 text-gray-700 hover:bg-blue-500 hover:text-white transition-all duration-200"
                                   onclick="event.stopPropagation()">
                                    <i class="fas fa-pen text-[9px]"></i>
                                </a>
                                <button onclick="event.stopPropagation(); openDeleteModal('deleteModal', '{{ route('admin.galleries.destroy', $gallery) }}')"
                                        class="w-7 h-7 rounded-lg flex items-center justify-center bg-white/90 text-red-500 hover:bg-red-500 hover:text-white transition-all duration-200">
                                    <i class="fas fa-trash text-[9px]"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full">
                <div class="relative">
                    <div class="absolute -inset-[2px] bg-gradient-to-br from-blue-400/20 via-blue-300/15 to-cyan-300/20 rounded-[28px] blur-md"></div>
                    <div class="relative bg-white/70 backdrop-blur-xl rounded-[24px] border border-white/50 shadow-[0_2px_16px_-4px_rgba(59,130,246,0.06)] overflow-hidden">
                        <div class="relative py-16 text-center">
                            <div class="w-14 h-14 rounded-2xl bg-blue-50 flex items-center justify-center mx-auto mb-4 shadow-[0_2px_8px_-2px_rgba(59,130,246,0.1)]">
                                <i class="fas fa-images text-xl text-blue-400"></i>
                            </div>
                            <p class="text-sm font-bold text-gray-900 mb-1">Belum ada foto</p>
                            <p class="text-xs text-gray-400 mb-5">Tambahkan foto pertama ke galeri</p>
                            <a href="{{ route('admin.galleries.create') }}" 
                               class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-blue-500 to-blue-600 text-white text-xs font-semibold rounded-xl shadow-[0_4px_12px_-2px_rgba(59,130,246,0.3)] hover:shadow-[0_8px_20px_-4px_rgba(59,130,246,0.4)] hover:scale-105 transition-all duration-300">
                                <i class="fas fa-plus text-[10px]"></i>
                                Tambah Foto
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    @if($galleries->hasPages())
        <div class="relative bg-white/70 backdrop-blur-xl rounded-[24px] border border-white/50 px-6 py-4 shadow-[0_2px_16px_-4px_rgba(59,130,246,0.06)]">
            {{ $galleries->links() }}
        </div>
    @endif
</div>
@endsection