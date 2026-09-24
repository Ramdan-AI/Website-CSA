{{-- resources/views/pages/public/berita-show.blade.php --}}
@extends('layouts.landing')

@php
    $publishedLabel = $article->published_at?->translatedFormat('d M Y') ?? $article->created_at->translatedFormat('d M Y');
    $readMinutes = max(1, (int) ceil(str_word_count(strip_tags($article->content)) / 200));
    $shareUrl = url()->current();
    $gallery = collect($article->images ?? [])->filter()->values();
    $fmt = fn ($n) => $n->published_at?->translatedFormat('d M Y') ?? $n->created_at->translatedFormat('d M Y');

    // Lead paragraph: ambil paragraf pertama dari konten untuk gaya opening.
    preg_match('/<p[^>]*>(.*?)<\/p>/is', $article->content, $m);
    $leadParagraph = isset($m[1]) ? trim(strip_tags($m[1])) : null;
    $remainingContent = $leadParagraph
        ? preg_replace('/<p[^>]*>' . preg_quote($m[1], '/') . '<\/p>/is', '', $article->content, 1)
        : $article->content;
@endphp

@section('content')

{{-- Hero full-bleed --}}
<section class="relative" style="background: var(--paper);">
    <div class="max-w-[1200px] mx-auto px-5 sm:px-8 pt-24 sm:pt-28">
        <nav class="flex items-center gap-2 text-[13px] mb-8" style="color: var(--ink-faint);" data-reveal="fade">
            <a href="{{ route('berita') }}" class="transition-colors hover:underline">Berita</a>
            <span style="color: var(--line-strong);">/</span>
            @if ($article->category)
                <a href="{{ route('berita', ['kategori' => $article->category->slug]) }}" class="transition-colors hover:underline truncate" style="color: var(--accent);">{{ $article->category->name }}</a>
            @else
                <span class="truncate">Artikel</span>
            @endif
        </nav>
    </div>

    @if ($article->thumbnail_url)
        <div class="px-5 sm:px-8">
            <div class="max-w-[1200px] mx-auto">
                <figure class="relative rounded-2xl sm:rounded-3xl overflow-hidden aspect-[16/10] sm:aspect-[21/9]" style="background: var(--paper-sunken);" data-reveal="zoom">
                    <img src="{{ $article->thumbnail_url }}" alt="{{ $article->title }}"
                        width="1600" height="900" fetchpriority="high" decoding="async" class="w-full h-full object-cover">
                    <div class="absolute inset-0" style="background: linear-gradient(to top, rgba(0,0,0,0.72), rgba(0,0,0,0.1) 55%, transparent);"></div>

                    <div class="absolute inset-x-0 bottom-0 p-6 sm:p-12">
                        <div class="max-w-[820px]">
                            <div class="flex items-center gap-3 text-[12px] text-white/80">
                                <span class="font-mono tracking-[0.15em] uppercase">{{ $article->category?->name ?? 'Artikel' }}</span>
                                <span>·</span>
                                <span>{{ $publishedLabel }}</span>
                                <span>·</span>
                                <span>{{ $readMinutes }} menit baca</span>
                            </div>
                            <h1 class="text-[clamp(1.8rem,4.4vw,3.2rem)] font-extrabold leading-[1.1] tracking-[-0.035em] text-white mt-4">
                                {{ $article->title }}
                            </h1>
                        </div>
                    </div>
                </figure>
            </div>
        </div>
    @endif
</section>

{{-- Judul (bila tanpa gambar) & meta --}}
<div class="max-w-[1200px] mx-auto px-5 sm:px-8">
    @unless ($article->thumbnail_url)
        <div class="pt-10 max-w-[820px]">
            <div class="flex items-center gap-3 text-[12px]">
                <span class="font-mono tracking-[0.15em] uppercase" style="color: var(--accent);">{{ $article->category?->name ?? 'Artikel' }}</span>
                <span class="w-1 h-1 rounded-full" style="background: var(--line-strong);"></span>
                <span style="color: var(--ink-faint);">{{ $publishedLabel }}</span>
            </div>
            <h1 class="text-[clamp(1.9rem,4.4vw,3rem)] font-extrabold leading-[1.12] tracking-[-0.035em] mt-4" style="color: var(--ink);" data-reveal="blurUp">
                {{ $article->title }}
            </h1>
        </div>
    @endunless

    <div class="grid lg:grid-cols-12 gap-10 lg:gap-16 pt-10 sm:pt-14 pb-16 sm:pb-24">

        {{-- Kolom baca --}}
        <div class="lg:col-span-8">
            @if ($leadParagraph)
                <p class="text-[20px] sm:text-[22px] leading-[1.6] font-medium tracking-[-0.01em] mb-10" style="color: var(--ink);" data-reveal="up">
                    {{ $leadParagraph }}
                </p>
            @endif

            <div class="article-body text-[16px] sm:text-[17px] leading-[1.85]" style="color: var(--ink-soft);" data-reveal="up" data-reveal-delay="0.1">
                {!! $remainingContent !!}
            </div>

            @if ($gallery->count() > 1)
                <div class="mt-14 pt-10 border-t" style="border-color: var(--line);">
                    <h2 class="text-[12px] font-mono tracking-[0.15em] uppercase mb-5" style="color: var(--ink-faint);">Galeri</h2>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3" data-reveal-stagger>
                        @foreach ($gallery as $image)
                            <a href="{{ asset('storage/' . $image) }}" target="_blank" rel="noopener"
                                class="group block aspect-square rounded-xl overflow-hidden" style="background: var(--paper-sunken);">
                                <img src="{{ asset('storage/' . $image) }}" alt="Foto kegiatan {{ $loop->iteration }}"
                                    width="480" height="480" loading="lazy" decoding="async"
                                    class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-[1.05]">
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        {{-- Sidebar meta sticky --}}
        <aside class="lg:col-span-4">
            <div class="lg:sticky lg:top-24 space-y-8">
                <div class="rounded-2xl border p-6" style="background: var(--paper-sunken); border-color: var(--line);">
                    <p class="text-[11px] font-mono tracking-[0.15em] uppercase" style="color: var(--ink-faint);">Penulis</p>
                    <div class="flex items-center gap-3 mt-3">
                        <span class="w-10 h-10 rounded-full flex items-center justify-center text-[14px] font-bold shrink-0" style="background: var(--accent-soft); color: var(--accent);">
                            {{ strtoupper(mb_substr($article->author?->name ?? 'C', 0, 1)) }}
                        </span>
                        <span class="text-[15px] font-semibold" style="color: var(--ink);">{{ $article->author?->name ?? 'CSA' }}</span>
                    </div>

                    <dl class="mt-6 pt-6 border-t space-y-3 text-[13px]" style="border-color: var(--line);">
                        <div class="flex items-center justify-between gap-3">
                            <dt style="color: var(--ink-faint);">Terbit</dt>
                            <dd style="color: var(--ink);">{{ $publishedLabel }}</dd>
                        </div>
                        <div class="flex items-center justify-between gap-3">
                            <dt style="color: var(--ink-faint);">Waktu baca</dt>
                            <dd style="color: var(--ink);">{{ $readMinutes }} menit</dd>
                        </div>
                        @if ($article->category)
                            <div class="flex items-center justify-between gap-3">
                                <dt style="color: var(--ink-faint);">Kategori</dt>
                                <dd style="color: var(--ink);">{{ $article->category->name }}</dd>
                            </div>
                        @endif
                    </dl>
                </div>

                {{-- Bagikan --}}
                <div>
                    <p class="text-[11px] font-mono tracking-[0.15em] uppercase mb-3" style="color: var(--ink-faint);">Bagikan</p>
                    <div class="flex flex-wrap items-center gap-2">
                        <a href="https://wa.me/?text={{ urlencode($article->title . ' ' . $shareUrl) }}" target="_blank" rel="noopener"
                            class="inline-flex items-center gap-2 px-4 min-h-[42px] rounded-full text-[14px] font-medium border transition-colors hover:border-[var(--line-strong)]" style="color: var(--ink-soft); border-color: var(--line);">
                            <i class="fab fa-whatsapp text-[15px]"></i> WhatsApp
                        </a>
                        <a href="https://twitter.com/intent/tweet?text={{ urlencode($article->title) }}&url={{ urlencode($shareUrl) }}" target="_blank" rel="noopener"
                            class="inline-flex items-center gap-2 px-4 min-h-[42px] rounded-full text-[14px] font-medium border transition-colors hover:border-[var(--line-strong)]" style="color: var(--ink-soft); border-color: var(--line);">
                            <i class="fab fa-x-twitter text-[15px]"></i> Post
                        </a>
                        <button type="button" data-copy-link data-url="{{ $shareUrl }}"
                            class="inline-flex items-center gap-2 px-4 min-h-[42px] rounded-full text-[14px] font-medium border transition-colors hover:border-[var(--line-strong)]" style="color: var(--ink-soft); border-color: var(--line);">
                            <i class="fas fa-link text-[14px]"></i> <span data-copy-label>Salin</span>
                        </button>
                    </div>
                </div>
            </div>
        </aside>
    </div>
</div>

{{-- Berita terkait --}}
@if ($related->isNotEmpty())
    <section class="border-t" style="background: var(--paper-sunken); border-color: var(--line);">
        <div class="max-w-[1200px] mx-auto px-5 sm:px-8 py-16 sm:py-24">
            <div class="flex items-end justify-between gap-4 mb-12">
                <div>
                    <p class="text-[12px] font-mono tracking-[0.15em] uppercase mb-2" style="color: var(--ink-faint);">Lanjut baca</p>
                    <h2 class="text-[clamp(1.6rem,3.4vw,2.2rem)] font-bold tracking-[-0.025em]" style="color: var(--ink);">Berita terkait</h2>
                </div>
                <a href="{{ route('berita') }}" class="text-[15px] font-medium hover:underline shrink-0" style="color: var(--accent);">Semua berita</a>
            </div>

            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-x-8 gap-y-12" data-reveal-stagger>
                @foreach ($related as $item)
                    <article class="group flex flex-col">
                        <a href="{{ route('berita.show', $item->slug) }}" class="block aspect-[16/10] rounded-xl overflow-hidden" style="background: var(--paper);">
                            @if ($item->thumbnail_url)
                                <img src="{{ $item->thumbnail_url }}" alt="{{ $item->title }}"
                                    width="640" height="400" loading="lazy" decoding="async"
                                    class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-[1.04]">
                            @else
                                <div class="w-full h-full flex items-center justify-center" style="color: var(--ink-faint);">
                                    <i class="fas fa-newspaper text-2xl"></i>
                                </div>
                            @endif
                        </a>
                        <div class="flex items-center gap-3 mt-5 text-[12px]">
                            <span class="font-mono tracking-[0.12em] uppercase" style="color: var(--accent);">{{ $item->category?->name ?? 'Artikel' }}</span>
                            <span class="w-1 h-1 rounded-full" style="background: var(--line-strong);"></span>
                            <span style="color: var(--ink-faint);">{{ $fmt($item) }}</span>
                        </div>
                        <h3 class="text-[19px] font-bold leading-[1.3] tracking-[-0.015em] mt-3 line-clamp-2">
                            <a href="{{ route('berita.show', $item->slug) }}" class="group-hover:text-[var(--accent)] transition-colors" style="color: var(--ink);">{{ $item->title }}</a>
                        </h3>
                    </article>
                @endforeach
            </div>
        </div>
    </section>
@endif

@endsection

@push('styles')
<style>
    .article-body > * + * { margin-top: 1.4em; }
    .article-body p { margin: 0; }
    .article-body h2 {
        font-size: clamp(1.25rem, 2.4vw, 1.5rem);
        font-weight: 700;
        line-height: 1.35;
        letter-spacing: -0.02em;
        color: var(--ink);
        margin-top: 2em;
    }
    .article-body h3 {
        font-size: clamp(1.1rem, 2vw, 1.25rem);
        font-weight: 700;
        line-height: 1.4;
        color: var(--ink);
        margin-top: 1.7em;
    }
    .article-body strong { color: var(--ink); font-weight: 600; }
    .article-body a {
        color: var(--accent);
        text-decoration: underline;
        text-underline-offset: 3px;
        text-decoration-thickness: 1px;
    }
    .article-body ul { list-style: disc; padding-left: 1.4em; }
    .article-body ol { list-style: decimal; padding-left: 1.4em; }
    .article-body li + li { margin-top: 0.5em; }
    .article-body img { border-radius: 12px; max-width: 100%; height: auto; }
    .article-body blockquote {
        border-left: 3px solid var(--accent);
        padding: 0.2em 0 0.2em 1.2em;
        color: var(--ink);
        font-size: 1.05em;
        font-style: italic;
    }
    .article-body figure { margin: 1.8em 0; }
    .article-body figcaption {
        font-size: 0.85rem;
        color: var(--ink-faint);
        margin-top: 0.7em;
        text-align: center;
    }
    .article-body hr { border: 0; border-top: 1px solid var(--line); margin: 2.2em 0; }
    .article-body table { width: 100%; border-collapse: collapse; font-size: 0.92em; }
    .article-body th, .article-body td { border: 1px solid var(--line); padding: 0.65em 0.85em; text-align: left; }
    .article-body th { color: var(--ink); font-weight: 600; }
</style>
@endpush

@push('scripts')
<script>
    document.querySelector('[data-copy-link]')?.addEventListener('click', async (event) => {
        const button = event.currentTarget;
        const label = button.querySelector('[data-copy-label]');
        const url = button.dataset.url || window.location.href;
        try {
            await navigator.clipboard.writeText(url);
            if (label) {
                const original = label.textContent;
                label.textContent = 'Tersalin';
                setTimeout(() => { label.textContent = original; }, 1800);
            }
        } catch (e) {
            if (label) label.textContent = 'Gagal';
        }
    });
</script>
@endpush
