@extends('layouts.admin')

@php
    use Illuminate\Support\Str;
@endphp

@section('content')
<div class="space-y-4 sm:space-y-5">

    <div>
        <h1 class="text-[26px] sm:text-[30px] font-bold text-[var(--text-primary)] tracking-[-0.03em]">Berita</h1>
        <p class="text-[13px] text-[var(--text-muted)] mt-1">Baca berita dan informasi terbaru dari UKM CSA.</p>
    </div>

    <form method="GET" class="csa-panel p-3 sm:p-4 grid grid-cols-1 sm:grid-cols-[1fr_auto_auto] gap-3">
        <div class="relative">
            <x-admin.icon name="search" :size="16" class="absolute left-3.5 top-1/2 -translate-y-1/2 text-[var(--text-muted)] pointer-events-none" />
            <input type="search" name="search" value="{{ request('search') }}" placeholder="Cari judul atau isi berita..." class="csa-input pl-10" aria-label="Cari berita">
        </div>
        <x-form.select name="category_id" :value="request('category_id')" onchange="this.form.submit()" class="sm:w-44" aria-label="Kategori"
            placeholder="Semua Kategori"
            :options="collect($categories)->mapWithKeys(fn ($c) => [$c->id => $c->name])->all()" />
        <x-form.select name="sort" :value="request('sort', 'published_desc')" onchange="this.form.submit()" class="sm:w-48" aria-label="Urutkan"
            :options="['published_desc' => 'Terbit terbaru', 'published_asc' => 'Terbit terlama', 'title_asc' => 'Judul A-Z', 'title_desc' => 'Judul Z-A']" />
    </form>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
        @forelse($news as $item)
            <article class="csa-panel csa-panel-hover overflow-hidden">
                <a href="{{ route('admin.news.show', $item) }}" class="block">
                    @if($item->thumbnail_url)
                        <div class="h-44 w-full bg-[var(--bg-muted)] overflow-hidden">
                            <img src="{{ $item->thumbnail_url }}" alt="{{ $item->title }}" class="w-full h-full object-cover" loading="lazy">
                        </div>
                    @else
                        <div class="h-24 w-full bg-[var(--bg-muted)] flex items-center justify-center text-[var(--text-muted)]">
                            <x-admin.icon name="newspaper" :size="26" />
                        </div>
                    @endif

                    <div class="p-4">
                        <p class="text-[11px] font-medium text-[var(--accent-bright)]">{{ $item->category?->name ?? 'Tanpa kategori' }}</p>
                        <h2 class="text-[14px] font-semibold text-[var(--text-primary)] mt-1.5 line-clamp-2">{{ $item->title }}</h2>
                        <p class="text-[12px] text-[var(--text-muted)] mt-2 line-clamp-2 leading-relaxed">{{ Str::limit(strip_tags($item->content), 120) }}</p>
                        <div class="mt-3.5 pt-3 border-t border-[var(--border-soft)] flex items-center justify-between text-[11px] text-[var(--text-muted)]">
                            <span>{{ $item->author?->name ?? '-' }}</span>
                            <span>{{ $item->published_at?->translatedFormat('d M Y') ?? $item->created_at->translatedFormat('d M Y') }}</span>
                        </div>
                    </div>
                </a>
            </article>
        @empty
            <div class="col-span-full csa-panel p-12 text-center">
                <x-admin.icon name="newspaper" :size="32" class="mx-auto text-[var(--text-muted)]" />
                <p class="text-[13px] font-medium text-[var(--text-secondary)] mt-3">Tidak ada berita ditemukan</p>
                <p class="text-[12px] text-[var(--text-muted)] mt-1">Coba ubah kriteria pencarian.</p>
            </div>
        @endforelse
    </div>

    @if ($news->hasPages())
        {{ $news->links() }}
    @endif
</div>
@endsection
