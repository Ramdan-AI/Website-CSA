@extends('layouts.admin')

@section('content')
<div class="space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
        <div>
            <p class="text-xs font-semibold text-teal-400 uppercase tracking-widest">Publikasi</p>
            <h1 class="text-3xl font-bold text-gray-900 mt-1">Kategori Berita</h1>
            <p class="text-sm text-gray-400 mt-1.5 font-medium">Klik kartu kategori untuk melihat seluruh berita di dalamnya.</p>
        </div>
        <a href="{{ route('admin.news-categories.create') }}"
           class="inline-flex items-center gap-2 px-5 py-2.5 bg-teal-600 text-white text-sm font-semibold rounded-xl hover:bg-teal-700 transition-colors shadow-sm">
            <i class="fas fa-plus text-xs"></i>
            Tambah Kategori
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
        @forelse($categories as $category)
            <article class="group bg-white border border-gray-200 rounded-2xl shadow-sm hover:shadow-md hover:border-gray-300 transition-all overflow-hidden">
                <a href="{{ route('admin.news-categories.show', $category) }}" class="block p-6">
                    <div class="flex items-start justify-between gap-3">
                        <div class="w-12 h-12 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center shrink-0">
                            <i class="fas fa-tags"></i>
                        </div>
                        <span class="px-2.5 py-1 rounded-full bg-gray-100 text-gray-600 text-xs font-semibold">
                            {{ $category->news_count }} berita
                        </span>
                    </div>

                    <h2 class="mt-4 text-lg font-bold text-gray-900 group-hover:text-teal-700 transition-colors">
                        {{ $category->name }}
                    </h2>
                    <p class="mt-1 text-sm text-gray-500 line-clamp-2">
                        {{ $category->description ?: 'Belum ada deskripsi kategori.' }}
                    </p>

                    <div class="mt-4 flex items-center gap-4 text-xs font-semibold">
                        <span class="text-emerald-600">{{ $category->published_news_count }} terbit</span>
                        <span class="text-amber-600">{{ $category->draft_news_count }} draf</span>
                    </div>

                    @if($category->news->isNotEmpty())
                        <div class="mt-4 pt-4 border-t border-gray-100 space-y-2">
                            @foreach($category->news as $latest)
                                <div class="text-xs text-gray-500 truncate">
                                    <span class="text-gray-300 mr-2">•</span>{{ $latest->title }}
                                </div>
                            @endforeach
                        </div>
                    @endif
                </a>

                <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-between">
                    <span class="text-xs text-gray-400">Lihat seluruh berita</span>
                    <div class="flex items-center gap-1">
                        <a href="{{ route('admin.news-categories.edit', $category) }}"
                           class="w-8 h-8 rounded-lg flex items-center justify-center bg-white border border-gray-200 text-gray-600 hover:bg-gray-100 transition-colors"
                           title="Edit">
                            <i class="fas fa-pen text-[10px]"></i>
                        </a>
                        <button onclick="openDeleteModal('deleteModal', '{{ route('admin.news-categories.destroy', $category) }}')"
                                class="w-8 h-8 rounded-lg flex items-center justify-center bg-white border border-gray-200 text-red-500 hover:bg-red-50 transition-colors"
                                title="Hapus">
                            <i class="fas fa-trash text-[10px]"></i>
                        </button>
                    </div>
                </div>
            </article>
        @empty
            <div class="col-span-full bg-white border border-gray-200 rounded-2xl p-12 text-center">
                <i class="fas fa-tags text-4xl text-gray-300 mb-3"></i>
                <p class="text-sm font-semibold text-gray-500">Belum ada kategori berita.</p>
            </div>
        @endforelse
    </div>

</div>
@endsection
