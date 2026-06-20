@extends('layouts.admin')

@section('content')

<x-page-header
    title="Income Management"
    subtitle="Kelola seluruh pemasukan keuangan dalam sistem."
>
    <x-slot:actions>
        <a href="{{ route('admin.incomes.create') }}" class="btn-csa-primary">
            <i class="fas fa-plus"></i> Tambah Income
        </a>
    </x-slot:actions>
</x-page-header>

<x-admin.crud-table :pagination="$incomes">

    <x-slot:head>
        <tr>
            <th width="260">Title</th>
            <th width="180">Amount</th>
            <th width="180">Date</th>
            <th>Created By</th>
            <th width="140" class="text-end">Aksi Sistem</th>
        </tr>
    </x-slot:head>

    @forelse($incomes as $income)
        <tr>
            <td>
                <div class="data-name">
                    {{ $income->title }}
                </div>
            </td>

            <td>
                <div class="data-description">
                    Rp {{ number_format($income->amount, 0, ',', '.') }}
                </div>
            </td>

            <td>
                <div class="data-description">
                    {{ $income->transaction_date }}
                </div>
            </td>

            <td>
                <div class="data-description">
                    {{ $income->user?->name ?? '-' }}
                </div>
            </td>

            <td>
                <x-admin.action-buttons
                    :editRoute="route('admin.incomes.edit', $income)"
                    :deleteRoute="route('admin.incomes.destroy', $income)"
                />
            </td>
        </tr>
    @empty
        <x-admin.empty-state
            colspan="5"
            icon="wallet"
            title="Data Income Kosong"
            subtitle="Belum ada pemasukan yang tercatat."
        />
    @endforelse

</x-admin.crud-table>

@endsection