@extends('layouts.admin')

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
            change="+Rp {{ number_format($totalIncome ?? 0, 0, ',', '.') }}"
            change-label="total pemasukan"
            tone="green" />
        <x-admin.metric-card
            label="Pemasukan"
            value="Rp {{ number_format($totalIncome ?? 0, 0, ',', '.') }}"
            change-label="akumulasi pemasukan"
            tone="blue" />
        <x-admin.metric-card
            label="Pengeluaran"
            value="Rp {{ number_format($totalExpense ?? 0, 0, ',', '.') }}"
            change-label="akumulasi pengeluaran"
            trend="down"
            tone="red" />
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
                <p class="text-[11px] text-[var(--text-muted)] mt-0.5">Akses cepat ke data inti</p>
            </div>
            <div class="p-2">
                @can('manage-news')
                <a href="{{ route('admin.news.index') }}" class="flex items-center gap-3 p-3 rounded-xl hover:bg-[var(--bg-hover)] transition-colors min-h-[52px]">
                    <span class="w-9 h-9 rounded-xl bg-teal-500/12 text-teal-600 dark:text-teal-400 flex items-center justify-center shrink-0"><x-admin.icon name="newspaper" :size="17" /></span>
                    <span class="min-w-0 flex-1"><span class="block text-[13px] font-medium text-[var(--text-primary)]">Berita</span><span class="block text-[11px] text-[var(--text-muted)]">{{ number_format($totalNews ?? 0) }} artikel</span></span>
                    <x-admin.icon name="chevron-right" :size="16" class="text-[var(--text-muted)]" />
                </a>
                @endcan
                <a href="{{ route('admin.events.index') }}" class="flex items-center gap-3 p-3 rounded-xl hover:bg-[var(--bg-hover)] transition-colors min-h-[52px]">
                    <span class="w-9 h-9 rounded-xl bg-cyan-500/12 text-cyan-600 dark:text-cyan-400 flex items-center justify-center shrink-0"><x-admin.icon name="calendar-days" :size="17" /></span>
                    <span class="min-w-0 flex-1"><span class="block text-[13px] font-medium text-[var(--text-primary)]">Kegiatan</span><span class="block text-[11px] text-[var(--text-muted)]">{{ number_format($totalEvents ?? 0) }} acara</span></span>
                    <x-admin.icon name="chevron-right" :size="16" class="text-[var(--text-muted)]" />
                </a>
                @can('manage-documents')
                <a href="{{ route('admin.documents.index') }}" class="flex items-center gap-3 p-3 rounded-xl hover:bg-[var(--bg-hover)] transition-colors min-h-[52px]">
                    <span class="w-9 h-9 rounded-xl bg-amber-500/12 text-amber-600 dark:text-amber-400 flex items-center justify-center shrink-0"><x-admin.icon name="folder-open" :size="17" /></span>
                    <span class="min-w-0 flex-1"><span class="block text-[13px] font-medium text-[var(--text-primary)]">Dokumen</span><span class="block text-[11px] text-[var(--text-muted)]">{{ number_format($totalDocuments ?? 0) }} file</span></span>
                    <x-admin.icon name="chevron-right" :size="16" class="text-[var(--text-muted)]" />
                </a>
                @endcan
                @can('manage-members')
                <a href="{{ route('admin.members.index') }}" class="flex items-center gap-3 p-3 rounded-xl hover:bg-[var(--bg-hover)] transition-colors min-h-[52px]">
                    <span class="w-9 h-9 rounded-xl bg-violet-500/12 text-violet-600 dark:text-violet-400 flex items-center justify-center shrink-0"><x-admin.icon name="users" :size="17" /></span>
                    <span class="min-w-0 flex-1"><span class="block text-[13px] font-medium text-[var(--text-primary)]">Keanggotaan</span><span class="block text-[11px] text-[var(--text-muted)]">{{ number_format($totalMembers ?? 0) }} anggota</span></span>
                    <x-admin.icon name="chevron-right" :size="16" class="text-[var(--text-muted)]" />
                </a>
                @endcan
            </div>
        </article>
    </section>

    <section class="grid grid-cols-2 sm:grid-cols-4 gap-3">
        <x-admin.metric-card label="Divisi" :value="number_format($totalDivisions ?? 0)" change-label="divisi aktif" tone="violet" />
        <x-admin.metric-card label="Jabatan" :value="number_format($totalPositions ?? 0)" change-label="jabatan terdaftar" tone="blue" />
        <x-admin.metric-card label="Berita" :value="number_format($totalNews ?? 0)" change-label="artikel tersedia" tone="green" />
        <x-admin.metric-card label="Dokumen" :value="number_format($totalDocuments ?? 0)" change-label="file tersedia" tone="red" trend="down" />
    </section>
</div>
@endsection
