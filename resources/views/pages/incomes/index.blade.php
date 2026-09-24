@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h1 class="text-[26px] sm:text-[30px] font-bold text-[var(--text-primary)] tracking-[-0.03em]">Pemasukan</h1>
            <p class="text-[13px] text-[var(--text-muted)] mt-1">Catat semua pemasukan kas organisasi.</p>
        </div>
        @can('manage-finance')
            <a href="{{ route('admin.incomes.create') }}" class="csa-btn csa-btn-primary self-start sm:self-auto">
                <x-admin.icon name="plus" :size="16" /> Tambah Pemasukan
            </a>
        @endcan
    </div>

    @if (session('success'))
        <div class="csa-panel p-3.5 text-[13px] text-emerald-600 dark:text-emerald-400">{{ session('success') }}</div>
    @endif

    {{-- Ringkasan --}}
    <section class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div class="csa-panel p-5">
            <p class="csa-stat-label">Total Pemasukan</p>
            <p class="text-[26px] font-bold text-emerald-600 dark:text-emerald-400 mt-2 tabular-nums">Rp {{ number_format($total, 0, ',', '.') }}</p>
        </div>
        <div class="csa-panel p-5">
            <p class="csa-stat-label">Bulan Ini ({{ now()->translatedFormat('F Y') }})</p>
            <p class="text-[26px] font-bold text-[var(--text-primary)] mt-2 tabular-nums">Rp {{ number_format($monthTotal, 0, ',', '.') }}</p>
        </div>
    </section>

    <x-admin.search-bar name="search" :value="request('search')" placeholder="Cari keterangan atau deskripsi..." class="max-w-md" />

    @if ($incomes->isEmpty())
        <div class="csa-panel p-16 text-center">
            <div class="w-14 h-14 rounded-2xl bg-emerald-500/10 flex items-center justify-center mx-auto text-emerald-600 dark:text-emerald-400">
                <x-admin.icon name="trending-up" :size="26" />
            </div>
            <p class="text-[15px] font-semibold text-[var(--text-secondary)] mt-4">Belum ada pemasukan</p>
            <p class="text-[13px] text-[var(--text-muted)] mt-1">Catat pemasukan pertama organisasi.</p>
        </div>
    @else
        <div class="csa-panel overflow-hidden">
            <div class="divide-y divide-[var(--border-soft)]">
                @foreach ($incomes as $income)
                    <article class="p-4 flex items-center gap-4 hover:bg-[var(--bg-hover)] transition-colors">
                        <span class="w-10 h-10 rounded-xl bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0">
                            <x-admin.icon name="trending-up" :size="18" />
                        </span>

                        <div class="min-w-0 flex-1">
                            <p class="text-[14px] font-medium text-[var(--text-primary)] truncate">{{ $income->title }}</p>
                            <p class="text-[12px] text-[var(--text-muted)] truncate">
                                {{ $income->transaction_date->translatedFormat('d M Y') }}
                                · {{ $income->user?->name ?? 'CSA' }}
                                @if ($income->description) · {{ \Illuminate\Support\Str::limit($income->description, 60) }} @endif
                            </p>
                        </div>

                        <p class="text-[14px] font-semibold text-emerald-600 dark:text-emerald-400 whitespace-nowrap shrink-0 tabular-nums">
                            +Rp {{ number_format($income->amount, 0, ',', '.') }}
                        </p>

                        @can('manage-finance')
                            <div class="flex items-center gap-1 shrink-0">
                                <a href="{{ route('admin.incomes.edit', $income) }}" class="csa-icon-btn" title="Edit" aria-label="Edit pemasukan">
                                    <x-admin.icon name="pen" :size="16" />
                                </a>
                                <button type="button" onclick="openDeleteModal('deleteModal', '{{ route('admin.incomes.destroy', $income) }}')" class="csa-icon-btn hover:!text-red-500" title="Hapus" aria-label="Hapus pemasukan">
                                    <x-admin.icon name="trash-2" :size="16" />
                                </button>
                            </div>
                        @endcan
                    </article>
                @endforeach
            </div>
        </div>

        @if ($incomes->hasPages())
            <div>{{ $incomes->links() }}</div>
        @endif
    @endif
</div>
@endsection
