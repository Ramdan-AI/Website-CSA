@extends('layouts.admin')

@section('content')
<div class="space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
        <div>
            <p class="text-xs font-semibold text-pink-400 uppercase tracking-widest">Media</p>
            <h1 class="text-3xl font-bold text-gray-900 mt-1">Galeri & Album</h1>
            <p class="text-sm text-gray-400 mt-1.5 font-medium">Jelajahi momen-momen indah kegiatan UKM CSA.</p>
        </div>
    </div>

    <div class="space-y-6">
        {{-- Albums --}}
        @if($albums->count() > 0)
            <div>
                <h2 class="text-lg font-bold text-gray-900 mb-4">Album Foto</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
                    @foreach($albums as $album)
                        <a href="{{ route('admin.albums.show', $album) }}" class="group bg-white border border-gray-200 rounded-2xl shadow-sm hover:shadow-md hover:border-gray-300 transition-all overflow-hidden block">
                            @if($album->cover_image)
                                <div class="aspect-[4/3] overflow-hidden">
                                    <img src="{{ asset('storage/' . $album->cover_image) }}" alt="{{ $album->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" loading="lazy">
                                </div>
                            @else
                                <div class="aspect-[4/3] bg-gray-100 flex items-center justify-center text-gray-300">
                                    <i class="fas fa-images text-3xl"></i>
                                </div>
                            @endif
                            <div class="p-5">
                                <h3 class="text-base font-bold text-gray-900 line-clamp-1 group-hover:text-pink-600 transition-colors">{{ $album->title }}</h3>
                                <p class="text-xs text-gray-400 mt-1">{{ $album->galleries_count ?? 0 }} foto</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Hero Galleries --}}
        @if($heroGalleries->count() > 0)
            <div>
                <h2 class="text-lg font-bold text-gray-900 mb-4">Foto Unggulan</h2>
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-3">
                    @foreach($heroGalleries as $gallery)
                        <a href="{{ asset('storage/' . $gallery->image) }}" target="_blank" class="group relative bg-white border border-gray-200 rounded-2xl shadow-sm hover:shadow-md hover:border-gray-300 transition-all overflow-hidden aspect-square block">
                            <img src="{{ asset('storage/' . $gallery->image) }}" alt="{{ $gallery->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" loading="lazy">
                            <div class="absolute inset-0 bg-black/0 group-hover:bg-black/30 transition-colors flex items-center justify-center">
                                <i class="fas fa-search-plus text-white text-xl opacity-0 group-hover:opacity-100 transition-opacity"></i>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Recent Galleries --}}
        @if($recentGalleries->count() > 0)
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-bold text-gray-900">Foto Terbaru</h2>
                    <a href="{{ route('admin.galleries.index') }}" class="text-xs text-cyan-600 font-semibold hover:underline">Lihat Semua</a>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-3">
                    @foreach($recentGalleries as $gallery)
                        <a href="{{ asset('storage/' . $gallery->image) }}" target="_blank" class="group relative bg-white border border-gray-200 rounded-2xl shadow-sm hover:shadow-md hover:border-gray-300 transition-all overflow-hidden aspect-square block">
                            <img src="{{ asset('storage/' . $gallery->image) }}" alt="{{ $gallery->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" loading="lazy">
                            <div class="absolute inset-0 bg-black/0 group-hover:bg-black/30 transition-colors flex items-center justify-center">
                                <i class="fas fa-search-plus text-white text-xl opacity-0 group-hover:opacity-100 transition-opacity"></i>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    @if($albums->isEmpty() && $heroGalleries->isEmpty() && $recentGalleries->isEmpty())
        <div class="bg-white border border-gray-200 rounded-2xl p-12 text-center">
            <i class="fas fa-images text-4xl text-gray-300 mb-3"></i>
            <p class="text-sm font-semibold text-gray-500">Belum ada foto atau album</p>
            <p class="text-xs text-gray-400 mt-1">Album dan foto akan muncul di sini setelah ditambahkan oleh admin.</p>
        </div>
    @endif

</div>
@endsection