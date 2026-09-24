{{-- resources/views/pages/public/berita.blade.php --}}
@extends('layouts.landing')

@php
    $baseUrl = route('berita');
    $filtered = $q !== '' || $categorySlug !== '';
    $lead = $news->first();
    $rest = $news->skip(1);
    $fmt = fn ($n) => $n->published_at?->translatedFormat('d M Y') ?? $n->created_at->translatedFormat('d M Y');
@endphp

@section('content')

{{-- Header editorial --}}
<header class="relative overflow-hidden" style="background: var(--paper);">
    <div class="max-w-[1200px] mx-auto px-5 sm:px-8 pt-24 sm:pt-32 pb-12 sm:pb-16">
        <div class="flex items-center gap-3 mb-6" data-reveal="fade">
            <span class="text-[12px] font-mono tracking-[0.2em] uppercase" style="color: var(--ink-faint);">01 — Berita</span>
            <span class="h-px flex-1 max-w-[120px]" style="background: var(--line-strong);"></span>
        </div>

        <div class="grid lg:grid-cols-12 gap-8 items-end">
            <div class="lg:col-span-8">
                <h1 class="text-[clamp(2.4rem,6vw,4.4rem)] font-extrabold leading-[0.98] tracking-[-0.045em]"
                    style="color: var(--ink);" data-reveal="blurUp">
                    Kabar dari <span style="color: var(--accent);">CSA</span>
                </h1>
                <p class="text-[16px] sm:text-[18px] leading-[1.7] mt-6 max-w-[560px]" style="color: var(--ink-soft);" data-reveal="up" data-reveal-delay="0.15">
                    Catatan kegiatan, program kerja, dan pencapaian anggota Creative Student Association.
                </p>
            </div>
            <div class="lg:col-span-4 lg:text-right" data-reveal="up" data-reveal-delay="0.25">
                <p class="text-[64px] sm:text-[80px] font-extrabold leading-none tabular-nums tracking-[-0.04em]" style="color: var(--ink);">
                    {{ $news->total() }}
                </p>
                <p class="text-[13px] mt-2" style="color: var(--ink-faint);">berita diterbitkan</p>
            </div>
        </div>

        {{-- Pencarian + kategori --}}
        <div class="flex flex-col gap-5 mt-12" data-reveal="up" data-reveal-delay="0.35">
            <form method="GET" action="{{ $baseUrl }}" class="relative w-full sm:max-w-md" role="search">
                <i class="fas fa-search absolute left-5 top-1/2 -translate-y-1/2 text-[14px] pointer-events-none" style="color: var(--ink-faint);"></i>
                <input type="search" name="q" value="{{ $q }}" placeholder="Cari berita…"
                    class="w-full h-12 pl-12 pr-4 rounded-full text-[15px] border transition-colors focus:outline-none"
                    style="background: var(--paper-sunken); color: var(--ink); border-color: transparent;">
                @if ($categorySlug !== '')
                    <input type="hidden" name="kategori" value="{{ $categorySlug }}">
                @endif
            </form>

            @if ($categories->isNotEmpty())
                <div class="-mx-5 px-5 sm:mx-0 sm:px-0 overflow-x-auto no-scrollbar">
                    <div class="flex items-center gap-2 w-max sm:w-auto sm:flex-wrap">
                        <a href="{{ $baseUrl }}" class="px-4 py-2 rounded-full text-[13px] font-medium whitespace-nowrap transition-colors border"
                            style="{{ $categorySlug === '' ? 'background: var(--ink); color: var(--paper); border-color: var(--ink);' : 'background: transparent; color: var(--ink-soft); border-color: var(--line);' }}">
                            Semua
                        </a>
                        @foreach ($categories as $category)
                            <a href="{{ $baseUrl }}?kategori={{ $category->slug }}" class="px-4 py-2 rounded-full text-[13px] font-medium whitespace-nowrap transition-colors border"
                                style="{{ $categorySlug === $category->slug ? 'background: var(--ink); color: var(--paper); border-color: var(--ink);' : 'background: transparent; color: var(--ink-soft); border-color: var(--line);' }}">
                                {{ $category->name }}
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</header>

{{-- Daftar berita --}}
<section class="px-5 sm:px-8 pb-20 sm:pb-28" style="background: var(--paper);">
    <div class="max-w-[1200px] mx-auto">

        @if ($filtered)
            <div class="flex items-center justify-between gap-4 pb-5 mb-10 border-t pt-6" style="border-color: var(--line);">
                <p class="text-[14px]" style="color: var(--ink-soft);">
                    <span class="font-semibold" style="color: var(--ink);">{{ $news->total() }}</span> berita ditemukan
                </p>
                <a href="{{ $baseUrl }}" class="text-[14px] font-medium hover:underline" style="color: var(--accent);">Reset filter</a>
            </div>
        @endif

        @if ($news->isEmpty())
            <div class="rounded-2xl border py-24 text-center" style="background: var(--paper-sunken); border-color: var(--line);">
                <i class="fas fa-newspaper text-4xl" style="color: var(--line-strong);"></i>
                <p class="text-[16px] font-semibold mt-4" style="color: var(--ink-soft);">Belum ada berita ditemukan</p>
                <p class="text-[14px] mt-1" style="color: var(--ink-faint);">Coba ubah kata kunci atau filter kategori.</p>
            </div>
        @else
            {{-- Lead story: gambar full, teks di bawah, gaya majalah --}}
            <a href="{{ route('berita.show', $lead->slug) }}" class="group block" data-reveal="up">
                <div class="grid lg:grid-cols-12 gap-8 lg:gap-12 items-center">
                    <div class="lg:col-span-7 order-2 lg:order-1">
                        <div class="flex items-center gap-3 text-[12px]">
                            <span class="font-mono tracking-[0.15em] uppercase" style="color: var(--accent);">{{ $lead->category?->name ?? 'Artikel' }}</span>
                            <span class="w-1 h-1 rounded-full" style="background: var(--line-strong);"></span>
                            <span style="color: var(--ink-faint);">{{ $fmt($lead) }}</span>
                        </div>
                        <h2 class="text-[clamp(1.8rem,3.6vw,2.8rem)] font-bold leading-[1.12] tracking-[-0.03em] mt-4 group-hover:text-[var(--accent)] transition-colors" style="color: var(--ink);">
                            {{ $lead->title }}
                        </h2>
                        @if ($lead->excerpt)
                            <p class="text-[16px] sm:text-[17px] leading-[1.8] mt-5 max-w-[640px]" style="color: var(--ink-soft);">
                                {{ $lead->excerpt }}
                            </p>
                        @endif
                        <span class="inline-flex items-center gap-2 mt-7 text-[14px] font-medium" style="color: var(--accent);">
                            Baca selengkapnya <i class="fas fa-arrow-right text-[12px] transition-transform group-hover:translate-x-1"></i>
                        </span>
                    </div>
                    <div class="lg:col-span-5 order-1 lg:order-2">
                        <div class="aspect-[4/3] rounded-2xl overflow-hidden" style="background: var(--paper-sunken);">
                            @if ($lead->thumbnail_url)
                                <img src="{{ $lead->thumbnail_url }}" alt="{{ $lead->title }}"
                                    width="960" height="720" fetchpriority="high" decoding="async"
                                    class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-[1.03]">
                            @else
                                <div class="w-full h-full flex items-center justify-center" style="color: var(--ink-faint);">
                                    <i class="fas fa-newspaper text-4xl"></i>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </a>

            {{-- Grid editorial dengan garis pemisah atas --}}
            @if ($rest->isNotEmpty())
                <div class="mt-16 sm:mt-20 pt-10 border-t" style="border-color: var(--line);">
                    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-x-8 gap-y-12" data-reveal-stagger>
                        @foreach ($rest as $item)
                            <article class="group flex flex-col">
                                <a href="{{ route('berita.show', $item->slug) }}" class="block aspect-[16/10] rounded-xl overflow-hidden" style="background: var(--paper-sunken);">
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
                                <h2 class="text-[19px] font-bold leading-[1.3] tracking-[-0.015em] mt-3 line-clamp-2">
                                    <a href="{{ route('berita.show', $item->slug) }}" class="group-hover:text-[var(--accent)] transition-colors" style="color: var(--ink);">{{ $item->title }}</a>
                                </h2>
                                @if ($item->excerpt)
                                    <p class="text-[14px] leading-[1.75] mt-3 line-clamp-2" style="color: var(--ink-soft);">{{ $item->excerpt }}</p>
                                @endif
                            </article>
                        @endforeach
                    </div>
                </div>
            @endif

            @if ($news->hasPages())
                <div class="mt-16">{{ $news->links() }}</div>
            @endif
        @endif

    </div>
</section>

@endsection
