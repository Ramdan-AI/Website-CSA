@extends('layouts.admin')

@php
    $format = fn ($value) => 'Rp ' . number_format((float) $value, 0, ',', '.');
@endphp

@section('content')
<div class="space-y-4 sm:space-y-5">
    <div>
        <h1 class="text-[26px] sm:text-[30px] font-bold text-[var(--text-primary)] tracking-[-0.03em]">Dashboard Bendahara</h1>
        <p class="text-[13px] text-[var(--text-muted)] mt-1">Ringkasan keuangan organisasi.</p>
    </div>

    <section class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-3 sm:gap-4">
        <x-admin.metric-card label="Saldo Kas" :value="$format($balance)"
            :change="$format($incomeThisMonth - $expenseThisMonth)" change-label="selisih bulan ini"
            :tone="$balance >= 0 ? 'green' : 'red'" :trend="$balance >= 0 ? 'up' : 'down'" />
        <x-admin.metric-card label="Pemasukan" :value="$format($totalIncome)"
            :change="$format($incomeThisMonth)" change-label="bulan ini" tone="blue" />
        <x-admin.metric-card label="Pengeluaran" :value="$format($totalExpense)"
            :change="$format($expenseThisMonth)" change-label="bulan ini" trend="down" tone="red" />
    </section>

    <div class="csa-card p-5">
        <div class="flex items-center justify-between gap-4 mb-5">
            <div>
                <h2 class="text-[15px] font-semibold text-[var(--text-primary)]">Arus Kas {{ now()->year }}</h2>
                <p class="text-[12px] text-[var(--text-muted)] mt-0.5">Pemasukan dan pengeluaran per bulan</p>
            </div>
            <a href="{{ route('admin.finance-report.index') }}" class="text-[13px] font-medium hover:underline shrink-0" style="color: var(--accent);">
                Laporan lengkap
            </a>
        </div>
        <div class="h-64 sm:h-72"><canvas id="treasurerChart"></canvas></div>
    </div>

    <section class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        @foreach ([
            ['Pemasukan Terbaru', $recentIncomes, 'admin.incomes.index'],
            ['Pengeluaran Terbaru', $recentExpenses, 'admin.expenses.index'],
        ] as [$title, $items, $route])
            <div class="csa-card overflow-hidden">
                <div class="px-5 py-3.5 border-b border-[var(--border-soft)] flex items-center justify-between">
                    <h2 class="text-[14px] font-semibold text-[var(--text-primary)]">{{ $title }}</h2>
                    <a href="{{ route($route) }}" class="text-[12px] font-medium hover:underline" style="color: var(--accent);">Lihat semua</a>
                </div>
                <div class="divide-y divide-[var(--border-soft)]">
                    @forelse ($items as $item)
                        <div class="flex items-center justify-between gap-3 p-4">
                            <div class="min-w-0">
                                <p class="text-[13px] font-medium text-[var(--text-primary)] truncate">{{ $item->title }}</p>
                                <p class="text-[12px] text-[var(--text-muted)]">{{ $item->transaction_date->translatedFormat('d M Y') }}</p>
                            </div>
                            <p class="text-[13px] font-semibold whitespace-nowrap {{ $title === 'Pemasukan Terbaru' ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-600 dark:text-red-400' }}">
                                {{ $format($item->amount) }}
                            </p>
                        </div>
                    @empty
                        <p class="p-8 text-center text-[13px] text-[var(--text-muted)]">Belum ada transaksi.</p>
                    @endforelse
                </div>
            </div>
        @endforeach
    </section>
    <x-admin.announcement-feed :announcements="$announcements" />
    <x-admin.activity-feed :logs="$systemActivity" />
</div>
@endsection

@push('scripts')
<script>
    (() => {
        const canvas = document.getElementById('treasurerChart');
        if (!canvas || !window.Chart) return;

        const dark = document.documentElement.classList.contains('dark');
        const muted = dark ? '#8b92a8' : '#64748b';
        const grid = dark ? 'rgba(255,255,255,0.06)' : 'rgba(15,23,42,0.08)';

        new window.Chart(canvas, {
            type: 'line',
            data: {
                labels: ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Ags','Sep','Okt','Nov','Des'],
                datasets: [
                    { label: 'Pemasukan', data: @json($chartIncomes), borderColor: '#10b981', borderWidth: 2, tension: .4, pointRadius: 0, fill: false },
                    { label: 'Pengeluaran', data: @json($chartExpenses), borderColor: '#ef4444', borderWidth: 2, tension: .4, pointRadius: 0, fill: false },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { labels: { color: muted, boxWidth: 10, font: { size: 11 } } },
                    tooltip: {
                        backgroundColor: dark ? '#1a1f2e' : '#ffffff',
                        titleColor: dark ? '#e6e9f0' : '#0f172a',
                        bodyColor: muted,
                        borderColor: grid,
                        borderWidth: 1,
                        cornerRadius: 10,
                        padding: 12,
                    },
                },
                interaction: { mode: 'index', intersect: false },
                scales: {
                    x: { grid: { display: false }, border: { display: false }, ticks: { color: muted, font: { size: 11 } } },
                    y: {
                        grid: { color: grid },
                        border: { display: false },
                        ticks: {
                            color: muted,
                            font: { size: 11 },
                            maxTicksLimit: 5,
                            callback: (v) => v >= 1000000 ? (v / 1000000) + 'M' : v >= 1000 ? (v / 1000) + 'K' : v,
                        },
                    },
                },
            },
        });
    })();
</script>
@endpush
