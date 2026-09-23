{{-- resources/views/pages/public/berita.blade.php --}}
@extends('layouts.landing')

@php
    $baseUrl = route('berita');
    $filtered = $q !== '' || $categorySlug !== '';
    $featured = $news->first();
    $rest = $news->skip(1);
@endphp

@section('content')

{{-- Header --}}
<header class="px-5 sm:px-8 pt-24 sm:pt-28 pb-10" style="background: var(--paper);">
    <div class="max-w-[1200px] mx-auto">
        <div class="max-w-[680px]">
            <p class="text-[13px] font-medium mb-3" style="color: var(--accent);">Publikasi</p>
            <h1 class="text-[clamp(2rem,4.8vw,3rem)] font-bold leading-[1.15] tracking-[-0.035em]" style="color: var(--ink);">
                Berita dan kegiatan CSA
            </h1>
            <p class="text-[16px] sm:text-[17px] leading-[1.75] mt-5" style="color: var(--ink-soft);">
                Kabar terbaru seputar kegiatan, program kerja, dan pencapaian anggota CSA.
            </p>
        </div>

        {{-- Pencarian + kategori --}}
        <div class="flex flex-col gap-4 mt-9">
            <form method="GET" action="{{ $baseUrl }}" class="relative w-full sm:max-w-sm">
                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-[14px] pointer-events-none" style="color: var(--ink-faint);"></i>
                <input type="search" name="q" value="{{ $q }}" placeholder="Cari berita"
                    class="w-full h-12 pl-11 pr-4 rounded-lg text-[15px] border transition-colors focus:outline-none"
                    style="background: var(--paper-sunken); color: var(--ink); border-color: var(--line);">
                @if ($categorySlug !== '')
                    <input type="hidden" name="kategori" value="{{ $categorySlug }}">
                @endif
            </form>

            @if ($categories->isNotEmpty())
                <div class="-mx-5 px-5 sm:mx-0 sm:px-0 overflow-x-auto no-scrollbar">
                    <div class="flex items-center gap-2 w-max sm:w-auto sm:flex-wrap">
                        <a href="{{ $baseUrl }}"
                            class="px-4 py-2 rounded-lg text-[13px] font-medium whitespace-nowrap transition-colors"
                            @if ($categorySlug === '')
                                style="background: var(--accent-solid); color: var(--on-accent);"
                            @else
                                style="background: var(--paper-sunken); color: var(--ink-soft);"
                            @endif>
                            Semua
                        </a>
                        @foreach ($categories as $category)
                            <a href="{{ $baseUrl }}?kategori={{ $category->slug }}"
                                class="px-4 py-2 rounded-lg text-[13px] font-medium whitespace-nowrap transition-colors"
                                @if ($categorySlug === $category->slug)
                                    style="background: var(--accent-solid); color: var(--on-accent);"
                                @else
                                    style="background: var(--paper-sunken); color: var(--ink-soft);"
                                @endif>
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
<section class="px-5 sm:px-8 pb-16 sm:pb-24" style="background: var(--paper);">
    <div class="max-w-[1200px] mx-auto">

        @if ($filtered)
            <div class="flex items-center justify-between gap-4 pb-6 mb-8 border-b" style="border-color: var(--line);">
                <p class="text-[14px]" style="color: var(--ink-soft);">
                    <span class="font-semibold" style="color: var(--ink);">{{ $news->total() }}</span> berita ditemukan
                </p>
                <a href="{{ $baseUrl }}" class="text-[14px] font-medium hover:underline" style="color: var(--accent);">Reset filter</a>
            </div>
        @endif

        @if ($news->isEmpty())
            <div class="rounded-xl border py-20 text-center" style="background: var(--paper-sunken); border-color: var(--line);">
                <i class="fas fa-newspaper text-4xl" style="color: var(--line-strong);"></i>
                <p class="text-[16px] font-semibold mt-4" style="color: var(--ink-soft);">Belum ada berita ditemukan</p>
                <p class="text-[14px] mt-1" style="color: var(--ink-faint);">Coba ubah kata kunci atau filter kategori.</p>
                @if ($filtered)
                    <a href="{{ $baseUrl }}" class="inline-flex items-center gap-2 mt-6 text-[14px] font-medium hover:underline" style="color: var(--accent);">
                        Reset filter
                    </a>
                @endif
            </div>
        @else
            {{-- Feature --}}
            <a href="{{ route('berita.show', $featured->slug) }}"
                class="group grid lg:grid-cols-2 rounded-xl border overflow-hidden transition-colors hover:border-[var(--line-strong)]"
                style="background: var(--paper); border-color: var(--line);">
                <div class="aspect-[16/10] lg:aspect-auto lg:min-h-[340px] overflow-hidden" style="background: var(--paper-sunken);">
                    @if ($featured->thumbnail_url)
                        <img src="{{ $featured->thumbnail_url }}" alt="{{ $featured->title }}"
                            width="960" height="600" fetchpriority="high" decoding="async"
                            class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-[1.02]">
                    @else
                        <div class="w-full h-full flex items-center justify-center" style="color: var(--ink-faint);">
                            <i class="fas fa-newspaper text-4xl"></i>
                        </div>
                    @endif
                </div>
                <div class="p-6 sm:p-10 flex flex-col justify-center">
                    <div class="flex items-center gap-3">
                        @if ($featured->category)
                            <span class="text-[13px] font-medium" style="color: var(--accent);">{{ $featured->category->name }}</span>
                        @endif
                        <span class="text-[13px]" style="color: var(--ink-faint);">
                            {{ $featured->published_at?->translatedFormat('d M Y') ?? $featured->created_at->translatedFormat('d M Y') }}
                        </span>
                    </div>
                    <h2 class="text-[24px] sm:text-[30px] font-bold mt-3 leading-[1.25] tracking-[-0.025em]" style="color: var(--ink);">
                        {{ $featured->title }}
                    </h2>
                    @if ($featured->excerpt)
                        <p class="text-[15px] sm:text-[16px] leading-[1.8] mt-4 line-clamp-3" style="color: var(--ink-soft);">
                            {{ $featured->excerpt }}
                        </p>
                    @endif
                    <p class="text-[13px] mt-6" style="color: var(--ink-faint);">{{ $featured->author?->name ?? 'CSA' }}</p>
                </div>
            </a>

            {{-- Sisanya --}}
            @if ($rest->isNotEmpty())
                <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5 mt-5">
                    @foreach ($rest as $item)
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
                                <h2 class="text-[16px] font-semibold mt-2 leading-[1.45] line-clamp-2" style="color: var(--ink);">{{ $item->title }}</h2>
                                @if ($item->excerpt)
                                    <p class="text-[14px] leading-[1.7] mt-2 line-clamp-2" style="color: var(--ink-soft);">{{ $item->excerpt }}</p>
                                @endif
                                <p class="text-[13px] mt-auto pt-4" style="color: var(--ink-faint);">
                                    {{ $item->published_at?->translatedFormat('d M Y') ?? $item->created_at->translatedFormat('d M Y') }}
                                </p>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif

            @if ($news->hasPages())
                <div class="mt-14">{{ $news->links() }}</div>
            @endif
        @endif

    </div>
</section>

@endsection
