@extends('layouts.admin')

@section('content')
<div class="space-y-4 sm:space-y-5">
    <div>
        <a href="{{ route('admin.news-categories.index') }}" class="inline-flex items-center gap-1.5 text-[12px] text-[var(--text-muted)] hover:text-[var(--text-primary)] transition-colors">
            <x-admin.icon name="arrow-left" :size="14" /> Kembali ke Kategori
        </a>
        <div class="flex items-center gap-3 mt-2">
            <span class="w-12 h-12 rounded-xl bg-[var(--bg-muted)] text-[var(--text-secondary)] flex items-center justify-center shrink-0">
                <x-admin.icon name="tags" :size="20" />
            </span>
            <div>
                <h1 class="text-[22px] sm:text-[26px] font-bold text-[var(--text-primary)] tracking-[-0.03em]">{{ $newsCategory->name }}</h1>
                <p class="text-[13px] text-[var(--text-muted)] mt-0.5">{{ $newsCategory->description ?: 'Belum ada deskripsi.' }} · {{ $news->total() }} berita</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
        @forelse($news as $item)
            <article class="csa-panel csa-panel-hover overflow-hidden">
                <a href="{{ route('admin.news.show', $item) }}" class="block">
                    @if($item->thumbnail_url)
                        <div class="h-40 w-full bg-[var(--bg-muted)] overflow-hidden">
                            <img src="{{ $item->thumbnail_url }}" alt="{{ $item->title }}" class="w-full h-full object-cover" loading="lazy">
                        </div>
                    @else
                        <div class="h-20 w-full bg-[var(--bg-muted)] flex items-center justify-center text-[var(--text-muted)]">
                            <x-admin.icon name="newspaper" :size="24" />
                        </div>
                    @endif
                    <div class="p-4">
                        <h2 class="text-[14px] font-semibold text-[var(--text-primary)] line-clamp-2">{{ $item->title }}</h2>
                        <p class="text-[12px] text-[var(--text-muted)] mt-2">{{ $item->published_at?->translatedFormat('d M Y') ?? $item->created_at->translatedFormat('d M Y') }} · {{ ucfirst($item->status) }}</p>
                    </div>
                </a>
            </article>
        @empty
            <div class="col-span-full csa-panel p-12 text-center">
                <x-admin.icon name="newspaper" :size="32" class="mx-auto text-[var(--text-muted)]" />
                <p class="text-[13px] font-medium text-[var(--text-secondary)] mt-3">Belum ada berita dalam kategori ini</p>
            </div>
        @endforelse
    </div>

    @if ($news->hasPages())
        {{ $news->links() }}
    @endif
</div>
@endsection
