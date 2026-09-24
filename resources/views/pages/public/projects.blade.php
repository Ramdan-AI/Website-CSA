{{-- resources/views/pages/public/projects.blade.php --}}
@extends('layouts.landing')

@php
    $baseUrl = route('projects');
    $filtered = $q !== '' || $activeTech !== '';
    $featured = $projects->first();
    $rest = $projects->skip(1);
@endphp

@section('content')

{{-- Header --}}
<header class="px-5 sm:px-8 pt-24 sm:pt-28 pb-10" style="background: var(--paper);">
    <div class="max-w-[1200px] mx-auto">
        <div class="max-w-[680px]">
            <p class="text-[13px] font-medium mb-3" style="color: var(--accent);">Showcase</p>
            <h1 class="text-[clamp(2rem,4.8vw,3rem)] font-bold leading-[1.15] tracking-[-0.035em]" style="color: var(--ink);">
                Karya anggota CSA
            </h1>
            <p class="text-[16px] sm:text-[17px] leading-[1.75] mt-5" style="color: var(--ink-soft);">
                Kumpulan proyek yang dikerjakan anggota CSA. Lihat demonya atau telusuri kodenya.
            </p>
        </div>

        <div class="flex flex-col gap-4 mt-9">
            <form method="GET" action="{{ $baseUrl }}" class="relative w-full sm:max-w-sm">
                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-[14px] pointer-events-none" style="color: var(--ink-faint);"></i>
                <input type="search" name="q" value="{{ $q }}" placeholder="Cari proyek"
                    class="w-full h-12 pl-11 pr-4 rounded-lg text-[15px] border transition-colors focus:outline-none"
                    style="background: var(--paper-sunken); color: var(--ink); border-color: var(--line);">
                @if ($activeTech !== '')
                    <input type="hidden" name="tech" value="{{ $activeTech }}">
                @endif
            </form>

            @if (!empty($techList))
                <div class="-mx-5 px-5 sm:mx-0 sm:px-0 overflow-x-auto no-scrollbar">
                    <div class="flex items-center gap-2 w-max sm:w-auto sm:flex-wrap">
                        <a href="{{ $baseUrl }}" class="px-4 py-2 rounded-lg text-[13px] font-medium whitespace-nowrap transition-colors"
                            @if ($activeTech === '') style="background: var(--accent-solid); color: var(--on-accent);"
                            @else style="background: var(--paper-sunken); color: var(--ink-soft);" @endif>
                            Semua
                        </a>
                        @foreach ($techList as $tech)
                            <a href="{{ $baseUrl }}?tech={{ urlencode($tech) }}"
                                class="px-4 py-2 rounded-lg text-[13px] font-medium whitespace-nowrap transition-colors"
                                @if ($activeTech === $tech) style="background: var(--accent-solid); color: var(--on-accent);"
                                @else style="background: var(--paper-sunken); color: var(--ink-soft);" @endif>
                                {{ $tech }}
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</header>

<section class="px-5 sm:px-8 pb-16 sm:pb-24" style="background: var(--paper);">
    <div class="max-w-[1200px] mx-auto">

        @if ($filtered)
            <div class="flex items-center justify-between gap-4 pb-6 mb-8 border-b" style="border-color: var(--line);">
                <p class="text-[14px]" style="color: var(--ink-soft);">
                    <span class="font-semibold" style="color: var(--ink);">{{ $projects->total() }}</span> proyek ditemukan
                </p>
                <a href="{{ $baseUrl }}" class="text-[14px] font-medium hover:underline" style="color: var(--accent);">Reset filter</a>
            </div>
        @endif

        @if ($projects->isEmpty())
            <div class="rounded-xl border py-20 text-center" style="background: var(--paper-sunken); border-color: var(--line);">
                <i class="fas fa-cube text-4xl" style="color: var(--line-strong);"></i>
                <p class="text-[16px] font-semibold mt-4" style="color: var(--ink-soft);">Belum ada proyek</p>
                <p class="text-[14px] mt-1" style="color: var(--ink-faint);">Proyek anggota akan tampil di sini.</p>
                @if ($filtered)
                    <a href="{{ $baseUrl }}" class="inline-flex items-center gap-2 mt-6 text-[14px] font-medium hover:underline" style="color: var(--accent);">Reset filter</a>
                @endif
            </div>
        @else
            {{-- Feature --}}
            <a href="{{ route('projects.show', $featured->slug) }}"
                class="group grid lg:grid-cols-2 rounded-xl border overflow-hidden transition-colors hover:border-[var(--line-strong)]"
                style="background: var(--paper); border-color: var(--line);">
                <div class="aspect-[16/10] lg:aspect-auto lg:min-h-[340px] overflow-hidden" style="background: var(--paper-sunken);">
                    @if ($featured->thumbnail_url)
                        <img src="{{ $featured->thumbnail_url }}" alt="{{ $featured->title }}"
                            width="960" height="600" fetchpriority="high" decoding="async"
                            class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-[1.02]">
                    @else
                        <div class="w-full h-full flex items-center justify-center" style="color: var(--ink-faint);">
                            <i class="fas fa-cube text-4xl"></i>
                        </div>
                    @endif
                </div>
                <div class="p-6 sm:p-10 flex flex-col justify-center">
                    <div class="flex items-center gap-2.5">
                        @if ($featured->featured)
                            <span class="px-2.5 py-1 rounded-md text-[12px] font-medium" style="background: var(--accent-soft); color: var(--accent);">Unggulan</span>
                        @endif
                        <span class="text-[13px]" style="color: var(--ink-faint);">
                            {{ $featured->created_at->translatedFormat('M Y') }}
                        </span>
                    </div>
                    <h2 class="text-[24px] sm:text-[30px] font-bold mt-3 leading-[1.25] tracking-[-0.025em]" style="color: var(--ink);">
                        {{ $featured->title }}
                    </h2>
                    @if ($featured->excerpt)
                        <p class="text-[15px] sm:text-[16px] leading-[1.8] mt-4 line-clamp-3" style="color: var(--ink-soft);">{{ $featured->excerpt }}</p>
                    @endif
                    <p class="text-[13px] mt-5" style="color: var(--ink-faint);">{{ $featured->contributors }}</p>

                    @if (!empty($featured->tech_stack))
                        <div class="flex flex-wrap gap-2 mt-4">
                            @foreach (array_slice($featured->tech_stack, 0, 5) as $tech)
                                <span class="px-2.5 py-1 rounded-md text-[12px]" style="background: var(--paper-sunken); color: var(--ink-soft);">{{ $tech }}</span>
                            @endforeach
                        </div>
                    @endif
                </div>
            </a>

            {{-- Sisanya --}}
            @if ($rest->isNotEmpty())
                <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5 mt-5">
                    @foreach ($rest as $project)
                        <a href="{{ route('projects.show', $project->slug) }}"
                            class="group flex flex-col rounded-xl border overflow-hidden transition-all duration-300 hover:-translate-y-1 hover:border-[var(--line-strong)]"
                            style="background: var(--paper); border-color: var(--line);">
                            <div class="aspect-[16/10] overflow-hidden" style="background: var(--paper-sunken);">
                                @if ($project->thumbnail_url)
                                    <img src="{{ $project->thumbnail_url }}" alt="{{ $project->title }}"
                                        width="640" height="400" loading="lazy" decoding="async"
                                        class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-[1.03]">
                                @else
                                    <div class="w-full h-full flex items-center justify-center" style="color: var(--ink-faint);">
                                        <i class="fas fa-cube text-2xl"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="p-5 flex flex-col flex-1">
                                <h2 class="text-[16px] font-semibold leading-[1.45] line-clamp-2" style="color: var(--ink);">{{ $project->title }}</h2>
                                <p class="text-[13px] mt-1.5" style="color: var(--ink-faint);">{{ $project->contributors }}</p>

                                @if (!empty($project->tech_stack))
                                    <div class="flex flex-wrap gap-1.5 mt-3">
                                        @foreach (array_slice($project->tech_stack, 0, 3) as $tech)
                                            <span class="px-2 py-0.5 rounded-md text-[11px]" style="background: var(--paper-sunken); color: var(--ink-soft);">{{ $tech }}</span>
                                        @endforeach
                                    </div>
                                @endif

                                <div class="flex items-center gap-3 mt-auto pt-4 text-[13px] font-medium">
                                    @if ($project->demo_url)
                                        <span style="color: var(--accent);">Lihat demo</span>
                                    @endif
                                    @if ($project->repo_url)
                                        <span style="color: var(--ink-faint);">Repo</span>
                                    @endif
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif

            @if ($projects->hasPages())
                <div class="mt-14">{{ $projects->links() }}</div>
            @endif
        @endif

    </div>
</section>

@endsection
