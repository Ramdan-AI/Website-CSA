{{-- resources/views/admin/incomes/edit.blade.php --}}
@extends('layouts.admin')

@section('content')

<x-page-header
    title="Edit Income"
    subtitle="Perbarui data pemasukan yang tersimpan."
/>

<div class="form-card">
    <form action="{{ route('admin.incomes.update', $income) }}" method="POST">
        @csrf
        @method('PUT')

        <x-form.input
            name="title"
            label="Title"
            :value="$income->title"
            placeholder="Contoh: Sponsorship Event"
            required="true"
        />

        <x-form.input
            name="amount"
            label="Amount"
            type="number"
            :value="$income->amount"
            placeholder="Contoh: 500000"
            required="true"
        />

        <x-form.input
            name="transaction_date"
            label="Transaction Date"
            type="date"
            value="{{ old('transaction_date', $income->transaction_date?->format('Y-m-d')) }}"
            required="true"
        />

        <x-form.textarea
            name="description"
            label="Description"
            :value="$income->description"
            placeholder="Deskripsi pemasukan..."
        />

        <div class="form-actions">
            <button type="submit" class="btn-solid-success">
                <i class="fas fa-check me-2"></i> Perbarui Data
            </button>

            <a href="{{ route('admin.incomes.index') }}" class="btn-solid-light">
                Batal
            </a>
        </div>
    </form>
</div>

@endsection