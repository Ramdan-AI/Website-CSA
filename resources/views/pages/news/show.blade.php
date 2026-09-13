@extends('layouts.admin')

@section('content')
<div class="space-y-6">

    <div>
        <a href="{{ route('admin.news.index') }}" class="inline-flex items-center gap-2 text-sm text-gray-400 hover:text-gray-700 mb-3">
            <i class="fas fa-arrow-left text-xs"></i>
            Kembali ke Berita
        </a>
        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
            <div>
                <span class="px-2.5 py-0.5 rounded-lg bg-teal-50 text-teal-700 text-xs font-semibold mb-1 inline-block">
                    {{ $news->category?->name ?? 'Tanpa Kategori' }}
                </span>
                <h1 class="text-3xl font-bold text-gray-900 leading-tight">{{ $news->title }}</h1>
                <div class="flex flex-wrap items-center gap-4 mt-3 text-sm text-gray-500">
                    <span class="flex items-center gap-1">
                        <i class="fas fa-user-circle"></i>{{ $news->author?->name ?? '-' }}
                    </span>
                    <span class="flex items-center gap-1">
                        <i class="fas fa-calendar"></i>{{ $news->published_at?->format('d M Y') ?: $news->created_at->format('d M Y') }}
                    </span>
                    <span class="px-2 py-1 rounded-lg text-xs font-semibold {{ $news->status === 'published' ? 'bg-emerald-50 text-emerald-600' : 'bg-amber-50 text-amber-600' }}">
                        {{ ucfirst($news->status) }}
                    </span>
                </div>
            </div>
            <div class="flex items-center gap-2 shrink-0">
                <a href="{{ route('admin.news.edit', $news) }}" class="px-4 py-2 bg-gray-100 text-gray-700 text-xs font-semibold rounded-xl hover:bg-gray-200 transition-colors">
                    <i class="fas fa-pen mr-1"></i> Edit
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            @if($news->thumbnail_url)
                <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
                    <img src="{{ $news->thumbnail_url }}" alt="{{ $news->title }}" class="w-full h-auto object-cover">
                </div>
            @endif

            <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Konten Berita</h2>
                <div class="prose prose-teal max-w-none text-gray-700 leading-relaxed">
                    {!! $news->content !!}
                </div>
            </div>

            @if($news->images && count($news->images) > 1)
                <div class="bg-white border border-gray-200 rounded-2xl shadow-sm">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-base font-bold text-gray-900">Galeri Gambar ({{ count($news->images) }})</h3>
                    </div>
                    <div class="p-6 grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                        @foreach($news->images as $image)
                            <a href="{{ asset('storage/' . $image) }}" target="_blank" class="group block aspect-square bg-gray-100 rounded-xl overflow-hidden border border-gray-100 hover:shadow-lg transition-shadow">
                                <img src="{{ asset('storage/' . $image) }}" alt="Gallery" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            @if($relatedNews->isNotEmpty())
                <div class="bg-white border border-gray-200 rounded-2xl shadow-sm">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-base font-bold text-gray-900">Berita Terkait</h3>
                    </div>
                    <div class="divide-y divide-gray-100">
                        @foreach($relatedNews as $related)
                            <a href="{{ route('admin.news.show', $related) }}" class="block p-4 hover:bg-gray-50 transition-colors">
                                <div class="flex items-center gap-3">
                                    @if($related->thumbnail_url)
                                        <img src="{{ $related->thumbnail_url }}" alt="" class="w-14 h-14 rounded-lg object-cover border border-gray-100">
                                    @else
                                        <div class="w-14 h-14 rounded-lg bg-gray-100 flex items-center justify-center text-gray-300">
                                            <i class="fas fa-newspaper"></i>
                                        </div>
                                    @endif
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-semibold text-gray-900 truncate">{{ $related->title }}</p>
                                        <p class="text-xs text-gray-400">{{ $related->published_at?->format('d M Y') }}</p>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <div class="space-y-6">
            <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-5">
                <h3 class="text-base font-bold text-gray-900 mb-4">Info Publikasi</h3>
                <dl class="grid grid-cols-2 gap-4 text-sm">
                    <dt class="text-gray-400">Judul</dt>
                    <dd class="font-semibold text-gray-900">{{ $news->title }}</dd>
                    <dt class="text-gray-400">Slug</dt>
                    <dd class="font-mono text-gray-500 break-all">{{ $news->slug }}</dd>
                    <dt class="text-gray-400">Kategori</dt>
                    <dd class="font-semibold text-gray-900">{{ $news->category?->name ?? 'Tanpa Kategori' }}</dd>
                    <dt class="text-gray-400">Penulis</dt>
                    <dd class="font-semibold text-gray-900">{{ $news->author?->name ?? '-' }}</dd>
                    <dt class="text-gray-400">Status</dt>
                    <dd class="font-semibold text-gray-900">
                        <span class="px-2 py-0.5 rounded-lg {{ $news->status === 'published' ? 'bg-emerald-50 text-emerald-600' : 'bg-amber-50 text-amber-600' }}">
                            {{ ucfirst($news->status) }}
                        </span>
                    </dd>
                    <dt class="text-gray-400">Tanggal Terbit</dt>
                    <dd class="font-semibold text-gray-900">{{ $news->published_at?->format('d M Y H:i') ?: $news->created_at->format('d M Y H:i') }}</dd>
                    <dt class="text-gray-400">Dibuat</dt>
                    <dd class="font-semibold text-gray-900">{{ $news->created_at->format('d M Y H:i') }}</dd>
                    <dt class="text-gray-400">Diperbarui</dt>
                    <dd class="font-semibold text-gray-900">{{ $news->updated_at->format('d M Y H:i') }}</dd>
                    <dt class="text-gray-400">Gambar Utama</dt>
                    <dd class="font-semibold text-gray-900">{{ $news->thumbnail ? 'Ada' : 'Tidak' }}</dd>
                    <dt class="text-gray-400">Galeri Gambar</dt>
                    <dd class="font-semibold text-gray-900">{{ count($news->images ?? []) }} file</dd>
                </dl>
            </div>
        </div>
    </div>

</div>
@endsection