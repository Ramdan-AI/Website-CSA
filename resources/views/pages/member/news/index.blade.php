@extends('layouts.admin')

@section('content')
<div class="space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
        <div>
            <p class="text-xs font-semibold text-teal-400 uppercase tracking-widest">Publikasi</p>
            <h1 class="text-3xl font-bold text-gray-900 mt-1">Berita & Artikel</h1>
            <p class="text-sm text-gray-400 mt-1.5 font-medium">Baca berita dan informasi terbaru dari UKM CSA.</p>
        </div>
    </div>

    <form method="GET" class="bg-white border border-gray-200 rounded-2xl p-4 shadow-sm mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3">
            <div class="relative lg:col-span-2">
                <input type="search" name="search" value="{{ request('search') }}" placeholder="Cari judul atau isi berita..."
                    class="w-full h-11 pl-10 pr-4 rounded-xl border border-gray-200 text-sm bg-gray-50 focus:bg-white focus:outline-none focus:border-gray-400 transition-all">
                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
            </div>
            <select name="category_id" onchange="this.form.submit()" class="h-11 px-3 rounded-xl border border-gray-200 text-sm bg-gray-50 focus:bg-white focus:outline-none focus:border-gray-400 text-gray-600">
                <option value="">Semua Kategori</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" @selected(request('category_id') == $category->id)>{{ $category->name }}</option>
                @endforeach
            </select>
            <select name="sort" onchange="this.form.submit()" class="h-11 px-3 rounded-xl border border-gray-200 text-sm bg-gray-50 focus:bg-white focus:outline-none focus:border-gray-400 text-gray-600">
                <option value="published_desc" @selected(request('sort', 'published_desc') === 'published_desc')>Terbit terbaru</option>
                <option value="published_asc" @selected(request('sort') === 'published_asc')>Terbit terlama</option>
                <option value="title_asc" @selected(request('sort') === 'title_asc')>Judul A-Z</option>
                <option value="title_desc" @selected(request('sort') === 'title_desc')>Judul Z-A</option>
            </select>
        </div>
    </form>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
        @forelse($news as $item)
            <article class="group bg-white border border-gray-200 rounded-2xl shadow-sm hover:shadow-md hover:border-gray-300 transition-all overflow-hidden">
                <a href="{{ route('admin.news.show', $item) }}" class="block">
                    @if($item->thumbnail_url)
                        <div class="h-48 w-full bg-gray-100 overflow-hidden relative">
                            <img src="{{ $item->thumbnail_url }}" alt="{{ $item->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" loading="lazy">
                            @if(count($item->images ?? []) > 1)
                                <span class="absolute top-3 right-3 px-2 py-1 rounded-lg bg-black/60 text-white text-[11px] font-semibold">
                                    <i class="fas fa-images mr-1"></i>{{ count($item->images) }}
                                </span>
                            @endif
                        </div>
                    @else
                        <div class="h-28 w-full bg-gray-50 flex items-center justify-center text-gray-300 border-b border-gray-100">
                            <i class="fas fa-newspaper text-3xl"></i>
                        </div>
                    @endif

                    <div class="p-5">
                        <div class="flex items-center justify-between gap-2 mb-2">
                            <span class="px-2 py-0.5 rounded-lg bg-teal-50 text-teal-700 text-[11px] font-semibold">
                                {{ $item->category?->name ?? 'Tanpa kategori' }}
                            </span>
                            <span class="px-2 py-0.5 rounded-lg text-[11px] font-semibold bg-emerald-50 text-emerald-600">Terbit</span>
                        </div>

                        <h2 class="text-base font-bold text-gray-900 line-clamp-2 group-hover:text-teal-700 transition-colors">
                            {{ $item->title }}
                        </h2>
                        <p class="text-xs text-gray-500 line-clamp-2 mt-2 leading-relaxed">
                            {{ Str::limit(strip_tags($item->content), 130) }}
                        </p>

                        <div class="mt-4 flex items-center justify-between text-xs text-gray-400">
                            <span>{{ $item->author?->name ?? '-' }}</span>
                            <span>{{ $item->published_at?->format('d M Y') ?: $item->created_at->format('d M Y') }}</span>
                        </div>
                    </div>
                </a>
            </article>
        @empty
            <div class="col-span-full bg-white border border-gray-200 rounded-2xl p-12 text-center">
                <i class="fas fa-newspaper text-4xl text-gray-300 mb-3"></i>
                <p class="text-sm font-semibold text-gray-500">Tidak ada berita ditemukan</p>
                <p class="text-xs text-gray-400 mt-1">Coba ubah kriteria pencarian.</p>
            </div>
        @endforelse
    </div>

    {{ $news->links() }}

</div>
@endsection