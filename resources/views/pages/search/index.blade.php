@extends('layouts.admin')

@section('content')
<div class="space-y-4 sm:space-y-5">
    <div>
        <h1 class="text-[26px] sm:text-[30px] font-bold text-[var(--text-primary)] tracking-[-0.03em]">Hasil Pencarian</h1>
        <p class="text-[13px] text-[var(--text-muted)] mt-1">Hasil untuk "{{ $query }}" di seluruh data sistem.</p>
    </div>

    @php
        $sections = [
            ['Anggota', 'users', $members, 'admin.members.edit'],
            ['Acara', 'calendar-days', $events, 'admin.events.show'],
            ['Berita', 'newspaper', $news, 'admin.news.show'],
            ['Dokumen', 'file-text', $documents, 'admin.documents.edit'],
        ];
    @endphp

    @php $hasResults = false; @endphp

    @foreach ($sections as [$label, $icon, $items, $route])
        @if ($items->isNotEmpty())
            @php $hasResults = true; @endphp
            <section class="csa-card overflow-hidden">
                <div class="px-4 py-3.5 border-b border-[var(--border-soft)] flex items-center gap-2.5">
                    <span class="w-8 h-8 rounded-lg bg-[var(--bg-muted)] text-[var(--text-secondary)] flex items-center justify-center">
                        <x-admin.icon :name="$icon" :size="16" />
                    </span>
                    <h3 class="text-[13px] font-semibold text-[var(--text-primary)]">{{ $label }}</h3>
                    <span class="csa-chip ml-auto">{{ $items->count() }}</span>
                </div>
                <div class="divide-y divide-[var(--border-soft)]">
                    @foreach ($items as $item)
                        <a href="{{ route($route, $item) }}" class="flex items-center gap-3 p-3.5 hover:bg-[var(--bg-hover)] transition-colors min-h-[52px]">
                            <div class="min-w-0">
                                <p class="text-[13px] font-medium text-[var(--text-primary)] truncate">{{ $item->title ?? $item->name }}</p>
                                @if ($item->nim ?? false)
                                    <p class="text-[12px] text-[var(--text-muted)] font-mono">{{ $item->nim }}</p>
                                @elseif ($item->event_date ?? false)
                                    <p class="text-[12px] text-[var(--text-muted)]">{{ $item->event_date->translatedFormat('d M Y') }}</p>
                                @elseif ($item->created_at ?? false)
                                    <p class="text-[12px] text-[var(--text-muted)]">{{ $item->created_at->translatedFormat('d M Y') }}</p>
                                @endif
                            </div>
                            <x-admin.icon name="chevron-right" :size="16" class="text-[var(--text-muted)] ml-auto shrink-0" />
                        </a>
                    @endforeach
                </div>
            </section>
        @endif
    @endforeach

    @unless ($hasResults)
        <div class="csa-card p-12 text-center">
            <x-admin.icon name="search" :size="32" class="mx-auto text-[var(--text-muted)]" />
            <p class="text-[13px] font-medium text-[var(--text-secondary)] mt-3">Tidak ada data yang cocok</p>
            <p class="text-[12px] text-[var(--text-muted)] mt-1">Coba kata kunci lain.</p>
        </div>
    @endunless
</div>
@endsection
