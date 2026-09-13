@extends('layouts.admin')

@section('content')
<div class="space-y-6">

    <div>
        <a href="{{ route('admin.news-categories.index') }}" class="inline-flex items-center gap-2 text-sm text-gray-400 hover:text-gray-700 mb-3">
            <i class="fas fa-arrow-left text-xs"></i>
            Kembali ke Kategori
        </a>
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-teal-50 text-teal-600 flex items-center justify-center shrink-0">
                <i class="fas fa-tags text-lg"></i>
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest">Kategori Berita</p>
                <h1 class="text-3xl font-bold text-gray-900 mt-1">{{ $newsCategory->name }}</h1>
                <p class="text-sm text-gray-400 mt-1">{{ $newsCategory->description ?: 'Belum ada deskripsi.' }} · {{ $news->total() }} berita</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
        @forelse($news as $item)
            <article class="group bg-white border border-gray-200 rounded-2xl shadow-sm hover:shadow-md hover:border-gray-300 transition-all overflow-hidden">
                <a href="{{ route('admin.news.show', $item) }}" class="block">
                    @if($item->thumbnail_url)
                        <div class="h-44 w-full bg-gray-100 overflow-hidden">
                            <img src="{{ $item->thumbnail_url }}" alt="{{ $item->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" loading="lazy">
                        </div>
                    @else
                        <div class="h-24 w-full bg-gray-50 flex items-center justify-center text-gray-300 border-b border-gray-100">
                            <i class="fas fa-newspaper text-3xl"></i>
                        </div>
                    @endif
                    <div class="p-5">
                        <h2 class="text-base font-bold text-gray-900 line-clamp-2 group-hover:text-teal-700 transition-colors">{{ $item->title }}</h2>
                        <p class="text-xs text-gray-400 mt-2">{{ $item->published_at?->format('d M Y') ?: $item->created_at->format('d M Y') }} · {{ ucfirst($item->status) }}</p>
                    </div>
                </a>
            </article>
        @empty
            <div class="col-span-full bg-white border border-gray-200 rounded-2xl p-12 text-center">
                <i class="fas fa-newspaper text-4xl text-gray-300 mb-3"></i>
                <p class="text-sm font-semibold text-gray-500">Belum ada berita dalam kategori ini.</p>
            </div>
        @endforelse
    </div>

    {{ $news->links() }}

</div>
@endsection
