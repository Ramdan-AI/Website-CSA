@extends('layouts.admin')

@php
    $attendanceRate = ($attendanceTotal ?? 0) > 0
        ? round((($attendancePresent ?? 0) / $attendanceTotal) * 100, 1)
        : 0;
@endphp

@section('content')
<div class="space-y-4 sm:space-y-5">
    <div>
        <h1 class="text-[26px] sm:text-[30px] font-bold text-[var(--text-primary)] tracking-[-0.03em]">Dashboard</h1>
        <p class="text-[13px] text-[var(--text-muted)] mt-1">Ringkasan keuangan dan aktivitas organisasi.</p>
    </div>

    <section class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-3 sm:gap-4">
        <x-admin.metric-card
            label="Saldo Kas"
            value="Rp {{ number_format($balance ?? 0, 0, ',', '.') }}"
            change="Rp {{ number_format($totalIncome ?? 0, 0, ',', '.') }}"
            change-label="total pemasukan"
            :tone="($balance ?? 0) >= 0 ? 'green' : 'red'"
            :trend="($balance ?? 0) >= 0 ? 'up' : 'down'" />
        <x-admin.metric-card
            label="Kehadiran Bulan Ini"
            :value="$attendanceRate . '%'"
            :change="number_format($attendancePresent ?? 0) . ' dari ' . number_format($attendanceTotal ?? 0)"
            change-label="tercatat"
            tone="blue" />
        <x-admin.metric-card
            label="Pengeluaran"
            value="Rp {{ number_format($totalExpense ?? 0, 0, ',', '.') }}"
            change-label="akumulasi pengeluaran"
            trend="down"
            tone="red" />
    </section>

    <section class="grid grid-cols-2 lg:grid-cols-4 gap-3">
        <x-admin.metric-card label="Anggota Aktif" :value="number_format($totalMembers ?? 0)" change-label="anggota" tone="blue" />
        <x-admin.metric-card label="Divisi" :value="number_format($totalDivisions ?? 0)" change-label="divisi" tone="violet" />
        <x-admin.metric-card label="Kegiatan Mendatang" :value="number_format($activeEvents ?? 0)" change-label="acara" tone="green" />
        <x-admin.metric-card label="Proyek Showcase" :value="number_format($totalProjects ?? 0)" change-label="karya anggota" tone="red" />
    </section>

    <section class="grid grid-cols-1 xl:grid-cols-5 gap-4">
        <x-admin.finance-chart
            class="xl:col-span-3"
            id="financeChart"
            title="Arus Kas Tahunan"
            description="Pemasukan dan pengeluaran per bulan"
            :labels="['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des']"
            :incomes="$chartIncomes ?? array_fill(0, 12, 0)"
            :expenses="$chartExpenses ?? array_fill(0, 12, 0)" />

        <article class="xl:col-span-2 csa-card overflow-hidden">
            <div class="px-5 py-4 border-b border-[var(--border-soft)]">
                <h2 class="text-[15px] font-semibold text-[var(--text-primary)]">Operasional</h2>
                <p class="text-[12px] text-[var(--text-muted)] mt-0.5">Akses cepat ke data inti</p>
            </div>
            <div class="p-2">
                @php
                    // Kelas ditulis utuh agar terbaca Tailwind saat build.
                    $quickActions = [
                        ['manage-news', 'admin.news.index', 'newspaper', 'Berita', number_format($totalNews ?? 0) . ' artikel', 'bg-teal-500/12 text-teal-600 dark:text-teal-400'],
                        [null, 'admin.events.index', 'calendar-days', 'Kegiatan', number_format($totalEvents ?? 0) . ' acara', 'bg-cyan-500/12 text-cyan-600 dark:text-cyan-400'],
                        ['manage-documents', 'admin.documents.index', 'folder-open', 'Dokumen', number_format($totalDocuments ?? 0) . ' file', 'bg-amber-500/12 text-amber-600 dark:text-amber-400'],
                        ['manage-members', 'admin.members.index', 'users', 'Keanggotaan', number_format($totalMembers ?? 0) . ' anggota', 'bg-violet-500/12 text-violet-600 dark:text-violet-400'],
                        [null, 'admin.showcase.index', 'rocket', 'Showcase', number_format($totalProjects ?? 0) . ' proyek', 'bg-blue-500/12 text-blue-600 dark:text-blue-400'],
                    ];
                @endphp

                @foreach ($quickActions as [$permission, $route, $icon, $label, $meta, $toneClasses])
                    @if (!$permission || auth()->user()->can($permission))
                        <a href="{{ route($route) }}" class="flex items-center gap-3 p-3 rounded-xl hover:bg-[var(--bg-hover)] transition-colors min-h-[52px]">
                            <span class="w-9 h-9 rounded-xl {{ $toneClasses }} flex items-center justify-center shrink-0">
                                <x-admin.icon :name="$icon" :size="17" />
                            </span>
                            <span class="min-w-0 flex-1">
                                <span class="block text-[13px] font-medium text-[var(--text-primary)]">{{ $label }}</span>
                                <span class="block text-[12px] text-[var(--text-muted)]">{{ $meta }}</span>
                            </span>
                            <x-admin.icon name="chevron-right" :size="16" class="text-[var(--text-muted)]" />
                        </a>
                    @endif
                @endforeach
            </div>
        </article>
    </section>
    <x-admin.announcement-feed :announcements="$announcements" />
    <x-admin.activity-feed :logs="$systemActivity" />
</div>
@endsection
