@extends('layouts.admin')

@php
    $format = fn ($value) => 'Rp ' . number_format((float) $value, 0, ',', '.');
@endphp

@section('content')
<div class="space-y-4 sm:space-y-5">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h1 class="text-[26px] sm:text-[30px] font-bold text-[var(--text-primary)] tracking-[-0.03em]">Laporan Keuangan</h1>
            <p class="text-[13px] text-[var(--text-muted)] mt-1">Rekap pemasukan, pengeluaran, dan saldo per bulan.</p>
        </div>

        <div class="flex items-center gap-2">
            <form method="GET" class="flex items-center gap-2">
                <select name="year" onchange="this.form.submit()" class="csa-input !min-h-[40px] w-32" aria-label="Pilih tahun">
                    @foreach ($years as $option)
                        <option value="{{ $option }}" @selected((int) $option === $year)>{{ $option }}</option>
                    @endforeach
                </select>
            </form>
            <a href="{{ route('admin.finance-report.export', ['year' => $year]) }}" class="csa-btn csa-btn-ghost !min-h-[40px] !text-[13px]">
                <x-admin.icon name="download" :size="15" /> Ekspor CSV
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
        <x-admin.metric-card label="Total Pemasukan" :value="$format($totalIncome)" change-label="tahun {{ $year }}" tone="green" />
        <x-admin.metric-card label="Total Pengeluaran" :value="$format($totalExpense)" change-label="tahun {{ $year }}" trend="down" tone="red" />
        <x-admin.metric-card label="Saldo Akhir" :value="$format($balance)"
            :change-label="'awal ' . $format($openingBalance)" :tone="$balance >= 0 ? 'blue' : 'red'" />
    </div>

    <div class="csa-card p-5">
        <h2 class="text-[14px] font-semibold text-[var(--text-primary)] mb-4">Tren {{ $year }}</h2>
        <div class="h-64"><canvas id="financeReportChart"></canvas></div>
    </div>

    <x-admin.table :headers="[
        ['label' => 'Bulan'],
        ['label' => 'Pemasukan'],
        ['label' => 'Pengeluaran'],
        ['label' => 'Selisih'],
        ['label' => 'Saldo'],
    ]">
        @foreach ($rows as $row)
            <tr class="csa-table-row">
                <td class="csa-table-td font-medium text-[var(--text-primary)]">{{ $row['label'] }}</td>
                <td class="csa-table-td text-emerald-600 dark:text-emerald-400 whitespace-nowrap">{{ $format($row['income']) }}</td>
                <td class="csa-table-td text-red-600 dark:text-red-400 whitespace-nowrap">{{ $format($row['expense']) }}</td>
                <td class="csa-table-td whitespace-nowrap {{ $row['net'] >= 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-600 dark:text-red-400' }}">
                    {{ $format($row['net']) }}
                </td>
                <td class="csa-table-td font-medium text-[var(--text-primary)] whitespace-nowrap">{{ $format($row['balance']) }}</td>
            </tr>
        @endforeach
    </x-admin.table>
</div>
@endsection

@push('scripts')
<script>
    (() => {
        const canvas = document.getElementById('financeReportChart');
        if (!canvas || !window.Chart) return;

        const dark = document.documentElement.classList.contains('dark');
        const muted = dark ? '#8b92a8' : '#64748b';
        const grid = dark ? 'rgba(255,255,255,0.06)' : 'rgba(15,23,42,0.08)';

        new window.Chart(canvas, {
            type: 'bar',
            data: {
                labels: @json(array_column($rows, 'label')),
                datasets: [
                    {
                        label: 'Pemasukan',
                        data: @json(array_column($rows, 'income')),
                        backgroundColor: '#10b981',
                        borderRadius: 4,
                    },
                    {
                        label: 'Pengeluaran',
                        data: @json(array_column($rows, 'expense')),
                        backgroundColor: '#ef4444',
                        borderRadius: 4,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: true, labels: { color: muted, boxWidth: 10, font: { size: 11 } } },
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
                scales: {
                    x: { grid: { display: false }, border: { display: false }, ticks: { color: muted, font: { size: 11 } } },
                    y: {
                        grid: { color: grid },
                        border: { display: false },
                        ticks: {
                            color: muted,
                            font: { size: 11 },
                            maxTicksLimit: 5,
                            callback: (value) => value >= 1000000 ? (value / 1000000) + 'M' : value >= 1000 ? (value / 1000) + 'K' : value,
                        },
                    },
                },
            },
        });
    })();
</script>
@endpush
