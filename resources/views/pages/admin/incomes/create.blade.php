@extends('layouts.admin')

@section('content')

<x-page-header
    title="Tambah Income Baru"
    subtitle="Tambahkan data pemasukan ke dalam sistem."
/>

<div class="form-card">
    <form action="{{ route('admin.incomes.store') }}" method="POST">
        @csrf

        <x-form.input
            name="title"
            label="Title"
            value="{{ old('title') }}"
            placeholder="Contoh: Sponsorship Event"
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
            placeholder="Deskripsi pemasukan..."
        />

        <div class="form-actions">
            <button type="submit" class="btn-csa-primary">
                <i class="fas fa-save me-2"></i> Simpan Data
            </button>

            <a href="{{ route('admin.incomes.index') }}" class="btn-solid-light">
                Batal
            </a>
        </div>
    </form>
</div>

@endsection