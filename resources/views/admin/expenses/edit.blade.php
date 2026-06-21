{{-- resources/views/admin/expenses/edit.blade.php --}}
@extends('layouts.admin')

@section('content')

<x-page-header
    title="Edit Expense"
    subtitle="Perbarui data pengeluaran yang tersimpan."
/>

<div class="form-card">
    <form action="{{ route('admin.expenses.update', $expense) }}" method="POST">
        @csrf
        @method('PUT')

        <x-form.input
            name="title"
            label="Title"
            :value="$expense->title"
            placeholder="Contoh: Pembelian Konsumsi"
            required="true"
        />

        <x-form.input
            name="amount"
            label="Amount"
            type="number"
            :value="$expense->amount"
            placeholder="Contoh: 500000"
            required="true"
        />

        <x-form.input
            name="transaction_date"
            label="Transaction Date"
            type="date"
            value="{{ old('transaction_date', $expense->transaction_date?->format('Y-m-d')) }}"
            required="true"
        />

        <x-form.textarea
            name="description"
            label="Description"
            :value="$expense->description"
            placeholder="Deskripsi pengeluaran..."
        />

        <div class="form-actions">
            <button type="submit" class="btn-solid-success">
                <i class="fas fa-check me-2"></i> Perbarui Data
            </button>

            <a href="{{ route('admin.expenses.index') }}" class="btn-solid-light">
                Batal
            </a>
        </div>
    </form>
</div>

@endsection