{{-- resources/views/pages/public/berita.blade.php --}}
@extends('layouts.landing')

@php
    $baseUrl = route('berita');
    $filtered = $q !== '' || $categorySlug !== '';
@endphp

@section('content')

{{-- HERO --}}
<section class="bg-[#fcfcfc] border-b border-gray-100">
    <div class="max-w-[1200px] mx-auto px-5 sm:px-8 pt-28 sm:pt-36 pb-10 sm:pb-14">
        <p class="text-[13px] font-semibold text-[#1a73e8] mb-4" data-reveal="down">Publikasi</p>
        <h1 class="text-[clamp(2rem,5.2vw,3.4rem)] font-bold text-gray-900 leading-[1.12] tracking-[-0.035em] max-w-[720px]" data-reveal="up">
            Berita dan kegiatan CSA
        </h1>
        <p class="text-[16px] sm:text-[17px] text-gray-500 leading-[1.7] mt-5 max-w-[560px]" data-reveal="up" data-reveal-delay="0.1">
            Kabar terbaru seputar kegiatan, program kerja, dan pencapaian anggota CSA.
        </p>
    </div>
</section>

{{-- FILTER + LIST --}}
<section class="py-10 sm:py-14 bg-white">
    <div class="max-w-[1200px] mx-auto px-5 sm:px-8">

        {{-- Filter --}}
        <div class="flex flex-col gap-5 mb-8">
            <form method="GET" action="{{ $baseUrl }}" class="relative w-full sm:max-w-sm">
                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-300 text-sm pointer-events-none"></i>
                <input type="search" name="q" value="{{ $q }}" placeholder="Cari berita..."
                    class="w-full h-12 pl-11 pr-4 rounded-xl bg-[#f1f3f4] text-[15px] text-gray-900 placeholder:text-gray-400 border border-transparent focus:border-[#1a73e8]/40 focus:bg-white focus:outline-none transition-colors">
                @if ($categorySlug !== '')
                    <input type="hidden" name="kategori" value="{{ $categorySlug }}">
                @endif
            </form>

            @if ($categories->isNotEmpty())
                <div class="-mx-5 px-5 sm:mx-0 sm:px-0 overflow-x-auto no-scrollbar">
                    <div class="flex items-center gap-2 w-max sm:w-auto sm:flex-wrap">
                        <a href="{{ $baseUrl }}"
                            class="px-4 py-2 rounded-full text-[13px] font-medium whitespace-nowrap transition-colors {{ $categorySlug === '' ? 'bg-[#1a73e8] text-white' : 'bg-[#f1f3f4] text-gray-600 hover:text-gray-900' }}">
                            Semua
                        </a>
                        @foreach ($categories as $category)
                            <a href="{{ $baseUrl }}?kategori={{ $category->slug }}"
                                class="px-4 py-2 rounded-full text-[13px] font-medium whitespace-nowrap transition-colors {{ $categorySlug === $category->slug ? 'bg-[#1a73e8] text-white' : 'bg-[#f1f3f4] text-gray-600 hover:text-gray-900' }}">
                                {{ $category->name }}
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        @if ($filtered)
            <div class="flex items-center justify-between gap-4 mb-8">
                <p class="text-[14px] text-gray-500">
                    <span class="font-semibold text-gray-900">{{ $news->total() }}</span> berita ditemukan
                </p>
                <a href="{{ $baseUrl }}" class="text-[14px] font-medium text-[#1a73e8] hover:underline">Reset filter</a>
            </div>
        @endif

        @if ($news->isEmpty())
            <div class="rounded-xl bg-[#f8f9fa] border border-gray-100 py-20 text-center">
                <i class="fas fa-newspaper text-4xl text-gray-300"></i>
                <p class="text-[16px] font-semibold text-gray-600 mt-4">Belum ada berita ditemukan</p>
                <p class="text-[14px] text-gray-400 mt-1">Coba ubah kata kunci atau filter kategori.</p>
                @if ($filtered)
                    <a href="{{ $baseUrl }}" class="inline-flex items-center gap-2 mt-6 text-[14px] font-medium text-[#1a73e8] hover:underline">
                        <i class="fas fa-rotate-left text-[11px]"></i> Reset filter
                    </a>
                @endif
            </div>
        @else
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach ($news as $item)
                    <a href="{{ route('berita.show', $item->slug) }}"
                        class="group bg-white rounded-xl border border-gray-200/80 overflow-hidden hover:border-[#1a73e8]/30 transition-all duration-300 flex flex-col">
                        <div class="aspect-[16/10] bg-[#e8f0fe] overflow-hidden">
                            @if ($item->thumbnail_url)
                                <img src="{{ $item->thumbnail_url }}" alt="{{ $item->title }}"
                                    width="640" height="400" loading="lazy" decoding="async"
                                    class="w-full h-full object-cover group-hover:scale-[1.03] transition-transform duration-500">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-[#1a73e8]/30">
                                    <i class="fas fa-newspaper text-3xl"></i>
                                </div>
                            @endif
                        </div>
                        <div class="p-5 flex flex-col flex-1">
                            @if ($item->category)
                                <span class="text-[12px] font-medium text-[#1a73e8]">{{ $item->category->name }}</span>
                            @endif
                            <h2 class="text-[16px] font-bold text-gray-900 mt-2 leading-snug line-clamp-2">{{ $item->title }}</h2>
                            @if ($item->excerpt)
                                <p class="text-[14px] text-gray-500 leading-[1.65] mt-2 line-clamp-2">{{ $item->excerpt }}</p>
                            @endif
                            <div class="flex items-center justify-between text-[12px] text-gray-400 mt-auto pt-4">
                                <span class="truncate">{{ $item->author?->name ?? 'CSA' }}</span>
                                <span class="shrink-0">{{ $item->published_at?->translatedFormat('d M Y') ?? $item->created_at->translatedFormat('d M Y') }}</span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

            @if ($news->hasPages())
                <div class="mt-12">{{ $news->links() }}</div>
            @endif
        @endif

    </div>
</section>

@endsection
