@extends('layouts.admin')

@php
    $rupiah = fn ($value) => 'Rp ' . number_format((float) $value, 0, ',', '.');
    $compact = function ($value) {
        $value = (float) $value;
        if ($value >= 1_000_000_000) return 'Rp ' . rtrim(rtrim(number_format($value / 1_000_000_000, 1, ',', '.'), '0'), '.') . ' M';
        if ($value >= 1_000_000) return 'Rp ' . rtrim(rtrim(number_format($value / 1_000_000, 1, ',', '.'), '0'), '.') . ' jt';
        if ($value >= 1_000) return 'Rp ' . rtrim(rtrim(number_format($value / 1_000, 1, ',', '.'), '0'), '.') . ' rb';
        return 'Rp ' . number_format($value, 0, ',', '.');
    };
@endphp

@section('content')
<div class="space-y-6">

    {{-- Header: konteks waktu, bukan sekumpulan angka. --}}
    <div class="flex flex-col gap-1 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h1 class="text-[26px] sm:text-[30px] font-bold text-[var(--text-primary)] tracking-[-0.03em]">Dashboard</h1>
            <p class="text-[13px] text-[var(--text-muted)] mt-1">{{ now()->translatedFormat('l, d F Y') }}</p>
        </div>
        <a href="{{ route('admin.finance-report.index') }}"
            class="inline-flex items-center gap-1.5 text-[13px] font-medium text-[var(--text-secondary)] hover:text-[var(--accent-bright)] transition-colors self-start sm:self-auto">
            Laporan keuangan
            <x-admin.icon name="arrow-right" :size="14" />
        </a>
    </div>

    {{-- Pengumuman: hal pertama yang harus dibaca pengurus. --}}
    <x-admin.announcement-feed :announcements="$announcements" />

    {{-- Keuangan: satu fokus angka, sisanya konteks. --}}
    <section class="grid grid-cols-1 xl:grid-cols-5 gap-4">
        <div class="xl:col-span-2 csa-panel p-6 flex flex-col">
            <p class="csa-stat-label">Saldo Kas</p>
            <p class="text-[34px] sm:text-[40px] font-bold tracking-[-0.03em] text-[var(--text-primary)] leading-none mt-3 tabular-nums">
                {{ $rupiah($balance) }}
            </p>

            <div class="mt-6 grid grid-cols-2 gap-4">
                <div class="border-l-2 border-emerald-500/60 pl-3">
                    <p class="text-[11px] font-medium text-[var(--text-muted)] uppercase tracking-wide">Masuk bulan ini</p>
                    <p class="text-[15px] font-semibold text-[var(--text-primary)] mt-1 tabular-nums">{{ $compact($incomeThisMonth) }}</p>
                </div>
                <div class="border-l-2 border-red-500/60 pl-3">
                    <p class="text-[11px] font-medium text-[var(--text-muted)] uppercase tracking-wide">Keluar bulan ini</p>
                    <p class="text-[15px] font-semibold text-[var(--text-primary)] mt-1 tabular-nums">{{ $compact($expenseThisMonth) }}</p>
                </div>
            </div>

            <div class="mt-auto pt-6 flex items-center justify-between text-[12px] text-[var(--text-muted)]">
                <span>Total masuk <span class="text-[var(--text-secondary)] font-medium">{{ $compact($totalIncome) }}</span></span>
                <span>Total keluar <span class="text-[var(--text-secondary)] font-medium">{{ $compact($totalExpense) }}</span></span>
            </div>
        </div>

        <x-admin.finance-chart
            class="xl:col-span-3"
            id="financeChart"
            title="Arus Kas {{ now()->year }}"
            description="Pemasukan dan pengeluaran per bulan"
            :labels="['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des']"
            :incomes="$chartIncomes ?? array_fill(0, 12, 0)"
            :expenses="$chartExpenses ?? array_fill(0, 12, 0)" />
    </section>

    {{-- Berita terbaru: visual, dengan jalan pintas ke halaman berita. --}}
    <x-admin.panel
        title="Berita Terbaru"
        subtitle="Artikel yang baru terbit di situs"
        :moreRoute="route('admin.news.index')">
        @if ($recentNews->isEmpty())
            <p class="text-[13px] text-[var(--text-muted)] py-8 text-center">Belum ada berita terbit.</p>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                @foreach ($recentNews as $item)
                    <a href="{{ route('admin.news.show', $item) }}" class="group block">
                        <div class="aspect-[16/10] rounded-xl overflow-hidden bg-[var(--bg-muted)]">
                            @if ($item->thumbnail_url)
                                <img src="{{ $item->thumbnail_url }}" alt="{{ $item->title }}"
                                    class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-[1.04]" loading="lazy">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-[var(--text-muted)]">
                                    <x-admin.icon name="newspaper" :size="24" />
                                </div>
                            @endif
                        </div>
                        <p class="text-[11px] font-medium text-[var(--accent-bright)] mt-3">{{ $item->category?->name ?? 'Tanpa kategori' }}</p>
                        <h3 class="text-[14px] font-semibold text-[var(--text-primary)] leading-snug mt-1 line-clamp-2 group-hover:text-[var(--accent-bright)] transition-colors">{{ $item->title }}</h3>
                        <p class="text-[12px] text-[var(--text-muted)] mt-1.5">
                            {{ $item->author?->name ?? 'CSA' }} · {{ $item->published_at?->translatedFormat('d M Y') ?? $item->created_at->translatedFormat('d M Y') }}
                        </p>
                    </a>
                @endforeach
            </div>
        @endif
    </x-admin.panel>

    {{-- Tiga kolom konten: agenda, dokumen, showcase. --}}
    <section class="grid grid-cols-1 lg:grid-cols-3 gap-4">

        {{-- Agenda terdekat --}}
        <x-admin.panel
            title="Agenda Terdekat"
            :moreRoute="route('admin.events.index')">
            @if ($upcomingEvents->isEmpty())
                <p class="text-[13px] text-[var(--text-muted)] py-6 text-center">Tidak ada kegiatan mendatang.</p>
            @else
                <ul class="space-y-1">
                    @foreach ($upcomingEvents as $event)
                        <li>
                            <a href="{{ route('admin.events.show', $event) }}" class="csa-row">
                                <div class="w-11 shrink-0 text-center rounded-lg bg-[var(--bg-muted)] py-1.5">
                                    <p class="text-[15px] font-bold text-[var(--text-primary)] leading-none">{{ $event->event_date?->format('d') }}</p>
                                    <p class="text-[10px] font-medium text-[var(--text-muted)] uppercase mt-0.5">{{ $event->event_date?->translatedFormat('M') }}</p>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-[13px] font-medium text-[var(--text-primary)] truncate">{{ $event->title }}</p>
                                    <p class="text-[12px] text-[var(--text-muted)] truncate">{{ $event->location ?: 'Lokasi belum ditentukan' }}</p>
                                </div>
                            </a>
                        </li>
                    @endforeach
                </ul>
            @endif
        </x-admin.panel>

        {{-- Dokumen terbaru --}}
        <x-admin.panel
            title="Dokumen Terbaru"
            :moreRoute="route('admin.documents.index')">
            @if ($recentDocuments->isEmpty())
                <p class="text-[13px] text-[var(--text-muted)] py-6 text-center">Belum ada dokumen diunggah.</p>
            @else
                <ul class="space-y-1">
                    @foreach ($recentDocuments as $doc)
                        <li>
                            <a href="{{ route('admin.documents.download', $doc) }}" class="csa-row">
                                <span class="w-9 h-9 rounded-lg bg-[var(--bg-muted)] flex items-center justify-center shrink-0 text-[var(--text-secondary)]">
                                    <x-admin.icon name="file-text" :size="16" />
                                </span>
                                <div class="min-w-0 flex-1">
                                    <p class="text-[13px] font-medium text-[var(--text-primary)] truncate">{{ $doc->title }}</p>
                                    <p class="text-[12px] text-[var(--text-muted)] truncate">{{ $doc->user?->name ?? 'CSA' }} · {{ $doc->created_at->diffForHumans() }}</p>
                                </div>
                                <x-admin.icon name="download" :size="15" class="text-[var(--text-muted)] shrink-0" />
                            </a>
                        </li>
                    @endforeach
                </ul>
            @endif
        </x-admin.panel>

        {{-- Showcase: siapa mengerjakan apa. --}}
        <x-admin.panel
            title="Showcase Proyek"
            :moreRoute="route('admin.showcase.index')">
            @if ($recentProjects->isEmpty())
                <p class="text-[13px] text-[var(--text-muted)] py-6 text-center">Belum ada proyek dipublikasikan.</p>
            @else
                <ul class="space-y-1">
                    @foreach ($recentProjects as $project)
                        <li>
                            <a href="{{ route('admin.showcase.preview', $project) }}" target="_blank" rel="noopener" class="csa-row">
                                <span class="w-9 h-9 rounded-lg overflow-hidden bg-[var(--bg-muted)] shrink-0">
                                    @if ($project->thumbnail_url)
                                        <img src="{{ $project->thumbnail_url }}" alt="" class="w-full h-full object-cover" loading="lazy">
                                    @else
                                        <span class="w-full h-full flex items-center justify-center text-[var(--text-muted)]">
                                            <x-admin.icon name="rocket" :size="16" />
                                        </span>
                                    @endif
                                </span>
                                <div class="min-w-0 flex-1">
                                    <p class="text-[13px] font-medium text-[var(--text-primary)] truncate">{{ $project->title }}</p>
                                    <p class="text-[12px] text-[var(--text-muted)] truncate">{{ $project->contributors }}</p>
                                </div>
                            </a>
                        </li>
                    @endforeach
                </ul>
            @endif
        </x-admin.panel>
    </section>

    {{-- Aktivitas sistem: hanya untuk pemegang izin log (sudah difilter). --}}
    <x-admin.activity-feed :logs="$systemActivity" />
</div>
@endsection
