@extends('layouts.admin')

@section('content')
<div class="space-y-4 sm:space-y-5">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h1 class="text-[26px] sm:text-[30px] font-bold text-[var(--text-primary)] tracking-[-0.03em]">Pemasukan</h1>
            <p class="text-[13px] text-[var(--text-muted)] mt-1">Kelola data pemasukan dan keuangan organisasi CSA.</p>
        </div>
        <a href="{{ route('admin.incomes.create') }}" class="csa-btn csa-btn-primary">
            <x-admin.icon name="plus" :size="16" /> Tambah Pemasukan
        </a>
    </div>

    <x-admin.table :headers="[
        ['label' => 'Keterangan'],
        ['label' => 'Jumlah'],
        ['label' => 'Tanggal'],
        ['label' => 'Dicatat Oleh'],
        ['label' => 'Aksi', 'width' => 'w-24'],
    ]"
        empty-icon="trending-up"
        empty-title="Belum ada pemasukan"
        empty-description="Tambahkan data pemasukan pertama."
        :create-route="route('admin.incomes.create')"
        create-label="Tambah Pemasukan"
        :has-pages="$incomes->hasPages()"
        :paginator="$incomes">
        @forelse($incomes as $income)
            <tr class="csa-table-row">
                <td class="csa-table-td">
                    <p class="text-[13px] font-medium text-[var(--text-primary)]">{{ $income->title }}</p>
                    @if ($income->description)
                        <p class="text-[11px] text-[var(--text-muted)] line-clamp-1 mt-0.5">{{ $income->description }}</p>
                    @endif
                </td>
                <td class="csa-table-td font-medium text-emerald-600 dark:text-emerald-400 whitespace-nowrap">Rp {{ number_format($income->amount, 0, ',', '.') }}</td>
                <td class="csa-table-td whitespace-nowrap">{{ $income->transaction_date->translatedFormat('d M Y') }}</td>
                <td class="csa-table-td">
                    <div class="flex items-center gap-2">
                        <img src="{{ $income->user->avatar ? asset('storage/' . $income->user->avatar) : asset('storage/profile/profile-placeholder.png') }}" alt="{{ $income->user->name }}" class="w-7 h-7 rounded-full object-cover shrink-0">
                        <span class="text-[12px] text-[var(--text-secondary)]">{{ $income->user->name }}</span>
                    </div>
                </td>
                <td class="csa-table-td">
                    <div class="flex items-center gap-1">
                        <a href="{{ route('admin.incomes.edit', $income) }}" class="csa-icon-btn" title="Edit" aria-label="Edit pemasukan">
                            <x-admin.icon name="pen" :size="16" />
                        </a>
                        <button type="button" onclick="openDeleteModal('deleteModal', '{{ route('admin.incomes.destroy', $income) }}')" class="csa-icon-btn hover:!text-red-500" title="Hapus" aria-label="Hapus pemasukan">
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
