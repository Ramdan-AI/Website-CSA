@extends('layouts.admin')

@section('content')

<x-page-header
    title="Tambah Expense Baru"
    subtitle="Tambahkan data pengeluaran ke dalam sistem."
/>

<div class="form-card">
    <form action="{{ route('admin.expenses.store') }}" method="POST">
        @csrf

        <x-form.input
            name="title"
            label="Title"
            value="{{ old('title') }}"
            placeholder="Contoh: Pembelian Konsumsi"
            required="true"
        />

        <x-form.input
            name="amount"
            label="Amount"
            type="number"
            value="{{ old('amount') }}"
            placeholder="Contoh: 500000"
            required="true"
        />

        <x-form.input
            name="transaction_date"
            label="Transaction Date"
            type="date"
            value="{{ old('transaction_date') }}"
            required="true"
        />

        <x-form.textarea
            name="description"
            label="Description"
            placeholder="Deskripsi pengeluaran..."
        />

        <div class="form-actions">
            <button type="submit" class="btn-csa-primary">
                <i class="fas fa-save me-2"></i> Simpan Data
            </button>

            <a href="{{ route('admin.expenses.index') }}" class="btn-solid-light">
                Batal
            </a>
        </div>
    </form>
</div>

@endsection