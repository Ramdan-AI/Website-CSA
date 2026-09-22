@extends('layouts.admin')

@section('content')
<div class="space-y-4 sm:space-y-5">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h1 class="text-[26px] sm:text-[30px] font-bold text-[var(--text-primary)] tracking-[-0.03em]">Pengeluaran</h1>
            <p class="text-[13px] text-[var(--text-muted)] mt-1">Kelola data pengeluaran dan keuangan organisasi CSA.</p>
        </div>
        <a href="{{ route('admin.expenses.create') }}" class="csa-btn csa-btn-primary">
            <x-admin.icon name="plus" :size="16" /> Tambah Pengeluaran
        </a>
    </div>

    <x-admin.table :headers="[
        ['label' => 'Keterangan'],
        ['label' => 'Jumlah'],
        ['label' => 'Tanggal'],
        ['label' => 'Dicatat Oleh'],
        ['label' => 'Aksi', 'width' => 'w-24'],
    ]"
        empty-icon="trending-down"
        empty-title="Belum ada pengeluaran"
        empty-description="Tambahkan data pengeluaran pertama."
        :create-route="route('admin.expenses.create')"
        create-label="Tambah Pengeluaran"
        :has-pages="$expenses->hasPages()"
        :paginator="$expenses">
        @forelse($expenses as $expense)
            <tr class="csa-table-row">
                <td class="csa-table-td">
                    <p class="text-[13px] font-medium text-[var(--text-primary)]">{{ $expense->title }}</p>
                    @if($expense->description)
                        <p class="text-[11px] text-[var(--text-muted)] line-clamp-1 mt-0.5">{{ $expense->description }}</p>
                    @endif
                </td>
                <td class="csa-table-td font-medium text-red-600 dark:text-red-400 whitespace-nowrap">Rp {{ number_format($expense->amount, 0, ',', '.') }}</td>
                <td class="csa-table-td whitespace-nowrap">{{ $expense->transaction_date->translatedFormat('d M Y') }}</td>
                <td class="csa-table-td">
                    <div class="flex items-center gap-2">
                        <img src="{{ $expense->user->avatar ? asset('storage/' . $expense->user->avatar) : asset('storage/profile/profile-placeholder.png') }}" alt="{{ $expense->user->name }}" class="w-7 h-7 rounded-full object-cover shrink-0">
                        <span class="text-[12px] text-[var(--text-secondary)]">{{ $expense->user->name }}</span>
                    </div>
                </td>
                <td class="csa-table-td">
                    <div class="flex items-center gap-1">
                        <a href="{{ route('admin.expenses.edit', $expense) }}" class="csa-icon-btn" title="Edit" aria-label="Edit pengeluaran">
                            <x-admin.icon name="pen" :size="16" />
                        </a>
                        <button type="button" onclick="openDeleteModal('deleteModal', '{{ route('admin.expenses.destroy', $expense) }}')" class="csa-icon-btn hover:!text-red-500" title="Hapus" aria-label="Hapus pengeluaran">
                            <x-admin.icon name="trash-2" :size="16" />
                        </button>
                    </div>
                </td>
            </tr>
        @empty
        @endforelse
    </x-admin.table>
</div>
@endsection
