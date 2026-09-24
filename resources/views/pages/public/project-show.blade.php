{{-- resources/views/pages/public/project-show.blade.php --}}
@extends('layouts.landing')

@php
    $gallery = collect($project->images ?? [])->filter()->values();
    $team = $project->members;
@endphp

@section('content')

@if ($isPreview ?? false)
    <div class="px-5 sm:px-8 pt-20">
        <div class="max-w-[1200px] mx-auto flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 rounded-xl border px-5 py-3.5"
            style="background: var(--accent-soft); border-color: var(--accent);">
            <p class="text-[14px]" style="color: var(--accent);">
                <i class="fas fa-eye text-[13px] mr-1.5"></i>
                Mode pratinjau. Hanya Anda yang bisa melihat halaman ini.
                @if ($project->status !== 'published')
                    Status saat ini: <strong>draf</strong>, belum tampil di publik.
                @endif
            </p>
            <a href="{{ route('admin.showcase.edit', $project) }}"
                class="shrink-0 text-[14px] font-medium hover:underline" style="color: var(--accent);">
                Kembali ke editor
            </a>
        </div>
    </div>
@endif

<article style="background: var(--paper);">

    <header class="px-5 sm:px-8 pt-8 pb-8">
        <div class="max-w-[860px] mx-auto">
            <nav class="flex items-center gap-2 text-[14px] mb-7">
                <a href="{{ route('projects') }}" class="transition-colors hover:underline" style="color: var(--ink-faint);">Showcase</a>
                <span style="color: var(--line-strong);">/</span>
                <span class="truncate" style="color: var(--ink-faint);">{{ $project->title }}</span>
            </nav>

            @if ($project->featured)
                <span class="inline-block px-2.5 py-1 rounded-md text-[12px] font-medium mb-4" style="background: var(--accent-soft); color: var(--accent);">Unggulan</span>
            @endif

            <h1 class="text-[clamp(1.9rem,4.4vw,2.9rem)] font-bold leading-[1.18] tracking-[-0.035em]" style="color: var(--ink);">
                {{ $project->title }}
            </h1>

            @if ($project->summary)
                <p class="text-[17px] sm:text-[18px] leading-[1.7] mt-5" style="color: var(--ink-soft);">{{ $project->summary }}</p>
            @endif

            <div class="flex flex-wrap items-center gap-3 mt-7">
                @if ($project->demo_url)
                    <a href="{{ $project->demo_url }}" target="_blank" rel="noopener"
                        class="inline-flex items-center gap-2 px-5 min-h-[44px] rounded-lg text-[14px] font-medium text-[var(--on-accent)] transition-opacity hover:opacity-90"
                        style="background: var(--accent-solid);">
                        <i class="fas fa-arrow-up-right-from-square text-[13px]"></i> Lihat demo
                    </a>
                @endif
                @if ($project->repo_url)
                    <a href="{{ $project->repo_url }}" target="_blank" rel="noopener"
                        class="inline-flex items-center gap-2 px-5 min-h-[44px] rounded-lg text-[14px] font-medium border transition-colors hover:border-[var(--line-strong)]"
                        style="color: var(--ink-soft); border-color: var(--line);">
                        <i class="fab fa-github text-[15px]"></i> Lihat kode
                    </a>
                @endif
            </div>
        </div>
    </header>

    @if ($project->thumbnail_url)
        <div class="px-5 sm:px-8 pb-10 sm:pb-12">
            <div class="max-w-[1100px] mx-auto">
                <figure class="rounded-2xl overflow-hidden aspect-[16/9]" style="background: var(--paper-sunken);">
                    <img src="{{ $project->thumbnail_url }}" alt="{{ $project->title }}"
                        width="1600" height="900" decoding="async" class="w-full h-full object-cover">
                </figure>
            </div>
        </div>
    @endif

    <div class="px-5 sm:px-8 pb-14 sm:pb-20">
        <div class="max-w-[1200px] mx-auto grid lg:grid-cols-[1fr_300px] gap-12">

            {{-- Deskripsi --}}
            <div>
                @if ($project->description)
                    <div class="article-body text-[16px] sm:text-[17px] leading-[1.85]" style="color: var(--ink-soft);">
                        {!! nl2br(e($project->description)) !!}
                    </div>
                @endif

                @if ($gallery->isNotEmpty())
                    <div class="mt-12 pt-10 border-t" style="border-color: var(--line);">
                        <h2 class="text-[18px] font-bold mb-5" style="color: var(--ink);">Galeri</h2>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                            @foreach ($gallery as $image)
                                <a href="{{ asset('storage/' . $image) }}" target="_blank" rel="noopener"
                                    class="group block aspect-square rounded-xl overflow-hidden" style="background: var(--paper-sunken);">
                                    <img src="{{ asset('storage/' . $image) }}" alt="Tangkapan layar proyek"
                                        width="480" height="480" loading="lazy" decoding="async"
                                        class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-[1.05]">
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            {{-- Sidebar --}}
            <aside class="space-y-8">
                <div>
                    <h2 class="text-[13px] font-medium mb-3" style="color: var(--ink-faint);">Tim</h2>
                    <ul class="space-y-3">
                        @if ($team->isNotEmpty())
                            @foreach ($team as $member)
                                <li class="flex items-center gap-3">
                                    <span class="w-9 h-9 rounded-full overflow-hidden shrink-0" style="background: var(--paper-sunken);">
                                        <img src="{{ $member->photo_url ?: asset('storage/profile/profile-placeholder.png') }}"
                                            alt="{{ $member->name }}" width="36" height="36" class="w-full h-full object-cover" loading="lazy">
                                    </span>
                                    <span class="min-w-0">
                                        <span class="block text-[14px] font-medium truncate" style="color: var(--ink);">{{ $member->name }}</span>
                                        @if ($member->division?->name)
                                            <span class="block text-[12px]" style="color: var(--ink-faint);">{{ $member->division->name }}</span>
                                        @endif
                                    </span>
                                </li>
                            @endforeach
                        @else
                            <li class="text-[14px]" style="color: var(--ink-soft);">{{ $project->submitter?->name ?? 'CSA' }}</li>
                        @endif
                    </ul>
                </div>

                @if (!empty($project->tech_stack))
                    <div>
                        <h2 class="text-[13px] font-medium mb-3" style="color: var(--ink-faint);">Teknologi</h2>
                        <div class="flex flex-wrap gap-2">
                            @foreach ($project->tech_stack as $tech)
                                <span class="px-2.5 py-1 rounded-md text-[12px]" style="background: var(--paper-sunken); color: var(--ink-soft);">{{ $tech }}</span>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div>
                    <h2 class="text-[13px] font-medium mb-3" style="color: var(--ink-faint);">Detail</h2>
                    <dl class="space-y-2.5 text-[14px]">
                        <div class="flex justify-between gap-3">
                            <dt style="color: var(--ink-faint);">Diunggah</dt>
                            <dd style="color: var(--ink-soft);">{{ $project->created_at->translatedFormat('d M Y') }}</dd>
                        </div>
                        <div class="flex justify-between gap-3">
                            <dt style="color: var(--ink-faint);">Pengunggah</dt>
                            <dd class="truncate" style="color: var(--ink-soft);">{{ $project->submitter?->name ?? 'CSA' }}</dd>
                        </div>
                    </dl>
                </div>
            </aside>

        </div>
    </div>
</article>

{{-- Proyek lain --}}
@if ($related->isNotEmpty())
    <section class="border-t" style="background: var(--paper-sunken); border-color: var(--line);">
        <div class="max-w-[1200px] mx-auto px-5 sm:px-8 py-14 sm:py-20">
            <div class="flex items-end justify-between gap-4 mb-8">
                <div>
                    <p class="text-[13px] font-medium mb-2" style="color: var(--accent);">Jelajahi</p>
                    <h2 class="text-[22px] sm:text-[26px] font-bold tracking-[-0.02em]" style="color: var(--ink);">Proyek lain</h2>
                </div>
                <a href="{{ route('projects') }}" class="text-[15px] font-medium hover:underline shrink-0" style="color: var(--accent);">Semua proyek</a>
            </div>

            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach ($related as $item)
                    <a href="{{ route('projects.show', $item->slug) }}"
                        class="group flex flex-col rounded-xl border overflow-hidden transition-all duration-300 hover:-translate-y-1 hover:border-[var(--line-strong)]"
                        style="background: var(--paper); border-color: var(--line);">
                        <div class="aspect-[16/10] overflow-hidden" style="background: var(--paper-sunken);">
                            @if ($item->thumbnail_url)
                                <img src="{{ $item->thumbnail_url }}" alt="{{ $item->title }}"
                                    width="640" height="400" loading="lazy" decoding="async"
                                    class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-[1.03]">
                            @else
                                <div class="w-full h-full flex items-center justify-center" style="color: var(--ink-faint);">
                                    <i class="fas fa-cube text-2xl"></i>
                                </div>
                            @endif
                        </div>
                        <div class="p-5">
                            <h3 class="text-[16px] font-semibold leading-[1.45] line-clamp-2" style="color: var(--ink);">{{ $item->title }}</h3>
                            <p class="text-[13px] mt-2" style="color: var(--ink-faint);">{{ $item->contributors }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
@endif

@endsection
