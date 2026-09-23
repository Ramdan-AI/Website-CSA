{{-- resources/views/pages/public/berita-show.blade.php --}}
@extends('layouts.landing')

@php
    $publishedLabel = $article->published_at?->translatedFormat('d M Y') ?? $article->created_at->translatedFormat('d M Y');
    $readMinutes = max(1, (int) ceil(str_word_count(strip_tags($article->content)) / 200));
    $shareUrl = url()->current();
    $gallery = collect($article->images ?? [])->filter()->values();
@endphp

@section('content')

<article style="background: var(--paper);">

    {{-- Header --}}
    <header class="px-5 sm:px-8 pt-24 sm:pt-28 pb-8">
        <div class="max-w-[860px] mx-auto">
            <nav class="flex items-center gap-2 text-[14px] mb-7">
                <a href="{{ route('berita') }}" class="transition-colors hover:underline" style="color: var(--ink-faint);">Berita</a>
                <span style="color: var(--line-strong);">/</span>
                @if ($article->category)
                    <a href="{{ route('berita', ['kategori' => $article->category->slug]) }}"
                        class="transition-colors hover:underline truncate" style="color: var(--ink-faint);">{{ $article->category->name }}</a>
                @else
                    <span class="truncate" style="color: var(--ink-faint);">Artikel</span>
                @endif
            </nav>

            <h1 class="text-[clamp(1.9rem,4.4vw,2.9rem)] font-bold leading-[1.18] tracking-[-0.035em]" style="color: var(--ink);">
                {{ $article->title }}
            </h1>

            <div class="flex flex-wrap items-center gap-x-4 gap-y-2 mt-7 text-[14px]" style="color: var(--ink-faint);">
                <span class="font-medium" style="color: var(--ink-soft);">{{ $article->author?->name ?? 'CSA' }}</span>
                <span>·</span>
                <span>{{ $publishedLabel }}</span>
                <span>·</span>
                <span>{{ $readMinutes }} menit baca</span>
            </div>
        </div>
    </header>

    {{-- Gambar utama --}}
    @if ($article->thumbnail_url)
        <div class="px-5 sm:px-8 pb-10 sm:pb-12">
            <div class="max-w-[1100px] mx-auto">
                <figure class="rounded-2xl overflow-hidden aspect-[16/9]" style="background: var(--paper-sunken);">
                    <img src="{{ $article->thumbnail_url }}" alt="{{ $article->title }}"
                        width="1600" height="900" decoding="async" class="w-full h-full object-cover">
                </figure>
            </div>
        </div>
    @endif

    {{-- Isi --}}
    <div class="px-5 sm:px-8 pb-14 sm:pb-20">
        <div class="max-w-[720px] mx-auto">
            <div class="article-body text-[16px] sm:text-[17px] leading-[1.85]" style="color: var(--ink-soft);">
                {!! $article->content !!}
            </div>

            @if ($gallery->count() > 1)
                <div class="mt-14 pt-10 border-t" style="border-color: var(--line);">
                    <h2 class="text-[18px] font-bold mb-5" style="color: var(--ink);">Galeri</h2>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
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

            {{-- Bagikan --}}
            <div class="mt-14 pt-8 border-t" style="border-color: var(--line);">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <p class="text-[14px] font-medium" style="color: var(--ink);">Bagikan artikel</p>
                    <div class="flex flex-wrap items-center gap-2">
                        <a href="https://wa.me/?text={{ urlencode($article->title . ' ' . $shareUrl) }}" target="_blank" rel="noopener"
                            class="inline-flex items-center gap-2 px-4 min-h-[42px] rounded-lg text-[14px] font-medium border transition-colors hover:border-[var(--line-strong)]"
                            style="color: var(--ink-soft); border-color: var(--line);">
                            <i class="fab fa-whatsapp text-[15px]"></i> WhatsApp
                        </a>
                        <a href="https://twitter.com/intent/tweet?text={{ urlencode($article->title) }}&url={{ urlencode($shareUrl) }}" target="_blank" rel="noopener"
                            class="inline-flex items-center gap-2 px-4 min-h-[42px] rounded-lg text-[14px] font-medium border transition-colors hover:border-[var(--line-strong)]"
                            style="color: var(--ink-soft); border-color: var(--line);">
                            <i class="fab fa-x-twitter text-[15px]"></i> Post
                        </a>
                        <button type="button" data-copy-link data-url="{{ $shareUrl }}"
                            class="inline-flex items-center gap-2 px-4 min-h-[42px] rounded-lg text-[14px] font-medium border transition-colors hover:border-[var(--line-strong)]"
                            style="color: var(--ink-soft); border-color: var(--line);">
                            <i class="fas fa-link text-[14px]"></i> <span data-copy-label>Salin tautan</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</article>

{{-- Berita terkait --}}
@if ($related->isNotEmpty())
    <section class="border-t" style="background: var(--paper-sunken); border-color: var(--line);">
        <div class="max-w-[1200px] mx-auto px-5 sm:px-8 py-14 sm:py-20">
            <div class="flex items-end justify-between gap-4 mb-8">
                <div>
                    <p class="text-[13px] font-medium mb-2" style="color: var(--accent);">Lanjut baca</p>
                    <h2 class="text-[22px] sm:text-[26px] font-bold tracking-[-0.02em]" style="color: var(--ink);">Berita terkait</h2>
                </div>
                <a href="{{ route('berita') }}" class="text-[15px] font-medium hover:underline shrink-0" style="color: var(--accent);">
                    Semua berita
                </a>
            </div>

            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach ($related as $item)
                    <a href="{{ route('berita.show', $item->slug) }}"
                        class="group flex flex-col rounded-xl border overflow-hidden transition-all duration-300 hover:-translate-y-1 hover:border-[var(--line-strong)]"
                        style="background: var(--paper); border-color: var(--line);">
                        <div class="aspect-[16/10] overflow-hidden" style="background: var(--paper-sunken);">
                            @if ($item->thumbnail_url)
                                <img src="{{ $item->thumbnail_url }}" alt="{{ $item->title }}"
                                    width="640" height="400" loading="lazy" decoding="async"
                                    class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-[1.03]">
                            @else
                                <div class="w-full h-full flex items-center justify-center" style="color: var(--ink-faint);">
                                    <i class="fas fa-newspaper text-2xl"></i>
                                </div>
                            @endif
                        </div>
                        <div class="p-5 flex flex-col flex-1">
                            @if ($item->category)
                                <span class="text-[12px] font-medium" style="color: var(--accent);">{{ $item->category->name }}</span>
                            @endif
                            <h3 class="text-[16px] font-semibold mt-2 leading-[1.45] line-clamp-2" style="color: var(--ink);">{{ $item->title }}</h3>
                            <p class="text-[13px] mt-auto pt-4" style="color: var(--ink-faint);">
                                {{ $item->published_at?->translatedFormat('d M Y') ?? $item->created_at->translatedFormat('d M Y') }}
                            </p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
@endif

@endsection

@push('styles')
<style>
    /* Tipografi artikel: skala baca nyaman, warna ikut tema. */
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
            if (label) label.textContent = 'Gagal menyalin';
        }
    });
</script>
@endpush
