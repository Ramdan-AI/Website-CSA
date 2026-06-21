@extends('layouts.admin')

@section('content')

<x-page-header
    title="Expense Management"
    subtitle="Kelola seluruh pengeluaran keuangan dalam sistem."
>
    <x-slot:actions>
        <a href="{{ route('admin.expenses.create') }}" class="btn-csa-primary">
            <i class="fas fa-plus"></i> Tambah Expense
        </a>
    </x-slot:actions>
</x-page-header>

<x-admin.crud-table :pagination="$expenses">

    <x-slot:head>
        <tr>
            <th width="260">Title</th>
            <th width="180">Amount</th>
            <th width="180">Date</th>
            <th>Created By</th>
            <th width="140" class="text-end">Aksi Sistem</th>
        </tr>
    </x-slot:head>

    @forelse($expenses as $expense)
        <tr>
            <td>
                <div class="data-name">
                    {{ $expense->title }}
                </div>
            </td>

            <td>
                <div class="data-description">
                    Rp {{ number_format($expense->amount, 0, ',', '.') }}
                </div>
            </td>

            <td>
                <div class="data-description">
                    {{ $expense->transaction_date }}
                </div>
            </td>

            <td>
                <div class="data-description">
                    {{ $expense->user?->name ?? '-' }}
                </div>
            </td>

            <td>
                <x-admin.action-buttons
                    :editRoute="route('admin.expenses.edit', $expense)"
                    :deleteRoute="route('admin.expenses.destroy', $expense)"
                />
            </td>
        </tr>
    @empty
        <x-admin.empty-state
            colspan="5"
            icon="receipt"
            title="Data Expense Kosong"
            subtitle="Belum ada pengeluaran yang tercatat."
        />
    @endforelse

</x-admin.crud-table>

@endsection