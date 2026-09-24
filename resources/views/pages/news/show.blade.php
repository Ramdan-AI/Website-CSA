@extends('layouts.admin')

@section('content')
<div class="space-y-4 sm:space-y-5">
    <div>
        <a href="{{ route('admin.news.index') }}" class="inline-flex items-center gap-1.5 text-[12px] text-[var(--text-muted)] hover:text-[var(--text-primary)] transition-colors">
            <x-admin.icon name="arrow-left" :size="14" /> Kembali ke Berita
        </a>
        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between mt-2">
            <div>
                <span class="text-[11px] font-medium text-[var(--accent-bright)]">{{ $news->category?->name ?? 'Tanpa Kategori' }}</span>
                <h1 class="text-[22px] sm:text-[26px] font-bold text-[var(--text-primary)] tracking-[-0.03em] leading-tight mt-1">{{ $news->title }}</h1>
                <div class="flex flex-wrap items-center gap-4 mt-3 text-[12px] text-[var(--text-muted)]">
                    <span class="inline-flex items-center gap-1.5"><x-admin.icon name="user-round" :size="14" /> {{ $news->author?->name ?? '-' }}</span>
                    <span class="inline-flex items-center gap-1.5"><x-admin.icon name="calendar-days" :size="14" /> {{ $news->published_at?->translatedFormat('d M Y') ?? $news->created_at->translatedFormat('d M Y') }}</span>
                    <span class="csa-chip {{ $news->status === 'published' ? 'csa-chip-green' : 'csa-chip-amber' }}">{{ ucfirst($news->status) }}</span>
                </div>
            </div>
            <a href="{{ route('admin.news.edit', $news) }}" class="csa-btn csa-btn-ghost shrink-0">
                <x-admin.icon name="pen" :size="16" /> Edit
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <div class="lg:col-span-2 space-y-4">
            @if($news->thumbnail_url)
                <figure class="csa-panel overflow-hidden aspect-[16/9] max-h-[340px]">
                    <img src="{{ $news->thumbnail_url }}" alt="{{ $news->title }}" width="1200" height="675" class="w-full h-full object-cover">
                </figure>
            @endif

            <section class="csa-panel p-5">
                <h2 class="text-[14px] font-semibold text-[var(--text-primary)] mb-3">Konten Berita</h2>
                <div class="article-body text-[13px] text-[var(--text-secondary)] leading-relaxed">{!! $news->content !!}</div>
            </section>

            @if($news->images && count($news->images) > 1)
                <section class="csa-panel overflow-hidden">
                    <div class="px-5 py-3.5 border-b border-[var(--border-soft)]">
                        <h3 class="text-[14px] font-semibold text-[var(--text-primary)]">Galeri Gambar ({{ count($news->images) }})</h3>
                    </div>
                    <div class="p-5 grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                        @foreach($news->images as $image)
                            <a href="{{ asset('storage/' . $image) }}" target="_blank" rel="noopener" class="block aspect-square rounded-xl overflow-hidden bg-[var(--bg-muted)]">
                                <img src="{{ asset('storage/' . $image) }}" alt="Galeri" class="w-full h-full object-cover" loading="lazy">
                            </a>
                        @endforeach
                    </div>
                </section>
            @endif

            @if($relatedNews->isNotEmpty())
                <section class="csa-panel overflow-hidden">
                    <div class="px-5 py-3.5 border-b border-[var(--border-soft)]">
                        <h3 class="text-[14px] font-semibold text-[var(--text-primary)]">Berita Terkait</h3>
                    </div>
                    <div class="divide-y divide-[var(--border-soft)]">
                        @foreach($relatedNews as $related)
                            <a href="{{ route('admin.news.show', $related) }}" class="flex items-center gap-3 p-4 hover:bg-[var(--bg-hover)] transition-colors">
                                @if($related->thumbnail_url)
                                    <img src="{{ $related->thumbnail_url }}" alt="" class="w-12 h-12 rounded-lg object-cover shrink-0">
                                @else
                                    <span class="w-12 h-12 rounded-lg bg-[var(--bg-muted)] flex items-center justify-center text-[var(--text-muted)] shrink-0">
                                        <x-admin.icon name="newspaper" :size="18" />
                                    </span>
                                @endif
                                <span class="flex-1 min-w-0">
                                    <span class="block text-[13px] font-medium text-[var(--text-primary)] truncate">{{ $related->title }}</span>
                                    <span class="block text-[12px] text-[var(--text-muted)]">{{ $related->published_at?->translatedFormat('d M Y') }}</span>
                                </span>
                            </a>
                        @endforeach
                    </div>
                </section>
            @endif
        </div>

        <section class="csa-panel p-5 h-fit">
            <h3 class="text-[14px] font-semibold text-[var(--text-primary)] mb-4">Info Publikasi</h3>
            <dl class="space-y-3 text-[13px]">
                @foreach ([
                    ['Slug', $news->slug, 'font-mono text-[12px] break-all'],
                    ['Kategori', $news->category?->name ?? 'Tanpa Kategori', ''],
                    ['Penulis', $news->author?->name ?? '-', ''],
                    ['Tanggal Terbit', $news->published_at?->translatedFormat('d M Y H:i') ?: $news->created_at->translatedFormat('d M Y H:i'), ''],
                    ['Dibuat', $news->created_at->translatedFormat('d M Y H:i'), ''],
                    ['Diperbarui', $news->updated_at->translatedFormat('d M Y H:i'), ''],
                    ['Gambar Utama', $news->thumbnail ? 'Ada' : 'Tidak', ''],
                    ['Galeri Gambar', count($news->images ?? []) . ' file', ''],
                ] as [$label, $value, $extra])
                    <div class="flex justify-between gap-4">
                        <dt class="text-[var(--text-muted)] shrink-0">{{ $label }}</dt>
                        <dd class="text-[var(--text-primary)] text-right min-w-0 {{ $extra }}">{{ $value }}</dd>
                    </div>
                @endforeach
            </dl>
        </section>
    </div>
</div>
@endsection
