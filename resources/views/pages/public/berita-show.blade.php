{{-- resources/views/pages/public/berita-show.blade.php --}}
@extends('layouts.landing')

@php
    $publishedLabel = $article->published_at?->translatedFormat('d M Y') ?? $article->created_at->translatedFormat('d M Y');
    $readMinutes = max(1, (int) ceil(str_word_count(strip_tags($article->content)) / 200));
@endphp

@section('content')

<article class="bg-white">
    <header class="pt-28 sm:pt-32 pb-6">
        <div class="max-w-[720px] mx-auto px-5 sm:px-8">
            <nav class="flex items-center gap-2 text-[14px] mb-6">
                <a href="{{ route('berita') }}" class="text-gray-400 hover:text-[#1a73e8] transition-colors">Berita</a>
                <span class="text-gray-300">/</span>
                @if ($article->category)
                    <a href="{{ route('berita', ['kategori' => $article->category->slug]) }}" class="text-gray-400 hover:text-[#1a73e8] transition-colors truncate">{{ $article->category->name }}</a>
                @else
                    <span class="text-gray-400 truncate">Artikel</span>
                @endif
            </nav>

            <h1 class="text-[clamp(1.6rem,3.8vw,2.3rem)] font-bold text-gray-900 leading-[1.25] tracking-[-0.03em]">
                {{ $article->title }}
            </h1>

            <div class="flex flex-wrap items-center gap-x-5 gap-y-2 text-[14px] text-gray-400 mt-5">
                <span class="inline-flex items-center gap-2"><i class="fas fa-user-circle"></i> {{ $article->author?->name ?? 'CSA' }}</span>
                <span class="inline-flex items-center gap-2"><i class="fas fa-calendar"></i> {{ $publishedLabel }}</span>
                <span class="inline-flex items-center gap-2"><i class="fas fa-clock"></i> {{ $readMinutes }} menit baca</span>
            </div>
        </div>
    </header>

    @if ($article->thumbnail_url)
        <div class="max-w-[880px] mx-auto px-5 sm:px-8 mb-8 sm:mb-10">
            <figure class="rounded-xl overflow-hidden bg-[#e8f0fe] aspect-[16/9] max-h-[420px]">
                <img src="{{ $article->thumbnail_url }}" alt="{{ $article->title }}"
                    width="1200" height="675" decoding="async"
                    class="w-full h-full object-cover">
            </figure>
        </div>
    @endif

    <div class="max-w-[720px] mx-auto px-5 sm:px-8 pb-10 sm:pb-14">
        <div class="article-body text-[16px] sm:text-[17px] text-gray-700 leading-[1.8]">
            {!! $article->content !!}
        </div>

        @if ($article->images && count($article->images) > 1)
            <div class="mt-10 sm:mt-12">
                <h2 class="text-[17px] font-bold text-gray-900 mb-4">Galeri</h2>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                    @foreach ($article->images as $image)
                        <a href="{{ asset('storage/' . $image) }}" target="_blank" rel="noopener"
                            class="block aspect-square rounded-2xl overflow-hidden bg-[#e8f0fe]">
                            <img src="{{ asset('storage/' . $image) }}" alt="Foto kegiatan"
                                width="400" height="400" loading="lazy" decoding="async"
                                class="w-full h-full object-cover">
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="flex flex-wrap items-center gap-2.5 mt-10 pt-8 border-t border-gray-100">
            <span class="text-[14px] text-gray-400 mr-1">Bagikan</span>
            <a href="https://wa.me/?text={{ urlencode($article->title . ' ' . url()->current()) }}" target="_blank" rel="noopener"
                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-[#f1f3f4] text-[14px] font-medium text-gray-600 hover:bg-[#E8EEF7] transition-colors">
                <i class="fab fa-whatsapp"></i> WhatsApp
            </a>
            <button type="button" data-copy-link
                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-[#f1f3f4] text-[14px] font-medium text-gray-600 hover:bg-[#E8EEF7] transition-colors">
                <i class="fas fa-link"></i> <span data-copy-label>Salin tautan</span>
            </button>
        </div>
    </div>
</article>

@if ($related->isNotEmpty())
    <section class="py-14 sm:py-20 bg-[#f8f9fa] border-t border-gray-100">
        <div class="max-w-[1200px] mx-auto px-5 sm:px-8">
            <h2 class="text-[20px] sm:text-[24px] font-bold text-gray-900 tracking-[-0.02em] mb-8">Baca juga</h2>
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach ($related as $item)
                    <a href="{{ route('berita.show', $item->slug) }}"
                        class="group bg-white rounded-xl border border-gray-200/80 overflow-hidden hover:border-[#1a73e8]/30 transition-all duration-300">
                        <div class="aspect-[16/10] bg-[#e8f0fe] overflow-hidden">
                            @if ($item->thumbnail_url)
                                <img src="{{ $item->thumbnail_url }}" alt="{{ $item->title }}"
                                    width="640" height="400" loading="lazy" decoding="async"
                                    class="w-full h-full object-cover group-hover:scale-[1.03] transition-transform duration-500">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-[#1a73e8]/30">
                                    <i class="fas fa-newspaper text-2xl"></i>
                                </div>
                            @endif
                        </div>
                        <div class="p-5">
                            <h3 class="text-[15px] font-bold text-gray-900 leading-snug line-clamp-2">{{ $item->title }}</h3>
                            <p class="text-[13px] text-gray-400 mt-3">{{ $item->published_at?->translatedFormat('d M Y') }}</p>
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
    .article-body > * + * { margin-top: 1.15em; }
    .article-body p { margin: 0; }
    .article-body h2 { font-size: 1.3rem; font-weight: 700; color: #111827; letter-spacing: -0.02em; margin-top: 1.8em; }
    .article-body h3 { font-size: 1.12rem; font-weight: 700; color: #111827; margin-top: 1.5em; }
    .article-body a { color: #1a73e8; text-decoration: underline; text-underline-offset: 2px; }
    .article-body ul { list-style: disc; padding-left: 1.4em; }
    .article-body ol { list-style: decimal; padding-left: 1.4em; }
    .article-body li + li { margin-top: 0.4em; }
    .article-body img { border-radius: 16px; max-width: 100%; height: auto; }
    .article-body blockquote { border-left: 3px solid #1a73e8; padding-left: 1.1em; color: #475569; font-style: italic; }
    .article-body figure { margin: 1.6em 0; }
    .article-body figcaption { font-size: 0.85rem; color: #94a3b8; margin-top: 0.6em; text-align: center; }
    .article-body table { width: 100%; border-collapse: collapse; font-size: 0.9rem; }
    .article-body th, .article-body td { border: 1px solid #e5e7eb; padding: 0.6em 0.8em; text-align: left; }
</style>
@endpush

@push('scripts')
<script>
    document.querySelector('[data-copy-link]')?.addEventListener('click', async (event) => {
        const label = event.currentTarget.querySelector('[data-copy-label]');
        try {
            await navigator.clipboard.writeText(window.location.href);
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
