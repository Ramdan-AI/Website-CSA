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
    $statItems = [
        ['label' => 'Saldo Kas', 'value' => $rupiah($balance)],
        ['label' => 'Masuk bulan ini', 'value' => $compact($incomeThisMonth), 'note' => 'Total masuk ' . $compact($totalIncome)],
        ['label' => 'Keluar bulan ini', 'value' => $compact($expenseThisMonth), 'note' => 'Total keluar ' . $compact($totalExpense)],
    ];
    $chartLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'];
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

    {{-- Keuangan: satu baris angka ringkas, lalu grafik arus kas. --}}
    <section class="csa-panel p-5 sm:p-6">
        <x-admin.stat-strip :lead="true" :items="$statItems" />

        <div class="mt-6 pt-6 border-t border-[var(--border-soft)]">
            <div class="flex flex-wrap items-center gap-x-5 gap-y-1.5 mb-5">
                <h2 class="text-[15px] font-semibold text-[var(--text-primary)]">Arus Kas {{ now()->year }}</h2>
                <span class="flex items-center gap-1.5 text-[11px] text-[var(--text-secondary)]">
                    <span class="w-2.5 h-0.5 rounded bg-emerald-500"></span>Pemasukan
                </span>
                <span class="flex items-center gap-1.5 text-[11px] text-[var(--text-secondary)]">
                    <span class="w-2.5 h-0.5 rounded bg-red-500"></span>Pengeluaran
                </span>
            </div>
            <div class="h-60 sm:h-72"><canvas id="financeChart"></canvas></div>
        </div>
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

@push('scripts')
<script>
    (() => {
        const initChart = () => {
            const canvas = document.getElementById('financeChart');
            if (!canvas || !window.Chart || canvas.dataset.initialized === 'true') return;

            const context = canvas.getContext('2d');
            const incomeFill = context.createLinearGradient(0, 0, 0, 300);
            incomeFill.addColorStop(0, 'rgba(16, 185, 129, 0.20)');
            incomeFill.addColorStop(1, 'rgba(16, 185, 129, 0)');
            const expenseFill = context.createLinearGradient(0, 0, 0, 300);
            expenseFill.addColorStop(0, 'rgba(239, 68, 68, 0.14)');
            expenseFill.addColorStop(1, 'rgba(239, 68, 68, 0)');
            const dark = document.documentElement.classList.contains('dark');
            const muted = dark ? '#8b92a8' : '#64748b';
            const grid = dark ? 'rgba(255,255,255,0.06)' : 'rgba(15,23,42,0.08)';

            new window.Chart(canvas, {
                type: 'line',
                data: {
                    labels: @json($chartLabels),
                    datasets: [
                        { label: 'Pemasukan', data: @json($chartIncomes), borderColor: '#10b981', backgroundColor: incomeFill, borderWidth: 2, tension: .4, pointRadius: 0, pointHoverRadius: 4, pointHoverBackgroundColor: '#10b981', pointHoverBorderColor: '#fff', pointHoverBorderWidth: 2, fill: true },
                        { label: 'Pengeluaran', data: @json($chartExpenses), borderColor: '#ef4444', backgroundColor: expenseFill, borderWidth: 2, tension: .4, pointRadius: 0, pointHoverRadius: 4, pointHoverBackgroundColor: '#ef4444', pointHoverBorderColor: '#fff', pointHoverBorderWidth: 2, fill: true },
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: { backgroundColor: dark ? '#1a1f2e' : '#ffffff', titleColor: dark ? '#e6e9f0' : '#0f172a', bodyColor: muted, borderColor: grid, borderWidth: 1, cornerRadius: 10, padding: 12, titleFont: { weight: '600', family: 'Plus Jakarta Sans' } }
                    },
                    interaction: { mode: 'index', intersect: false },
                    scales: {
                        x: { grid: { display: false }, border: { display: false }, ticks: { color: muted, font: { size: 11, family: 'Plus Jakarta Sans' } } },
                        y: { grid: { color: grid }, border: { display: false }, ticks: { color: muted, font: { size: 11, family: 'Plus Jakarta Sans' }, maxTicksLimit: 5, callback: (value) => value >= 1000000 ? (value / 1000000) + 'M' : value >= 1000 ? (value / 1000) + 'K' : value } }
                    }
                }
            });
            canvas.dataset.initialized = 'true';
        };

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initChart);
        } else {
            initChart();
        }
        window.addEventListener('load', initChart);
        let retries = 0;
        const interval = setInterval(() => {
            initChart();
            retries++;
            if (retries > 10 || document.getElementById('financeChart')?.dataset.initialized === 'true') {
                clearInterval(interval);
            }
        }, 150);
    })();
</script>
@endpush
