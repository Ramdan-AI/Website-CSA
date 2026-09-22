@extends('layouts.admin')

@section('content')
    @php
        $isEdit = isset($expense);
        $title = $isEdit ? 'Edit Pengeluaran' : 'Tambah Pengeluaran';
    @endphp

    <div class="space-y-4 sm:space-y-5 max-w-2xl">
        <div>
            <a href="{{ route('admin.expenses.index') }}" class="inline-flex items-center gap-1.5 text-[12px] text-[var(--text-muted)] hover:text-[var(--text-primary)] transition-colors">
                <x-admin.icon name="arrow-left" :size="14" /> Kembali ke Pengeluaran
            </a>
            <h1 class="text-[26px] sm:text-[30px] font-bold text-[var(--text-primary)] tracking-[-0.03em] mt-2">{{ $title }}</h1>
            <p class="text-[13px] text-[var(--text-muted)] mt-1">
                {{ $isEdit ? 'Perbarui data pengeluaran.' : 'Catat pengeluaran baru organisasi.' }}
            </p>
        </div>

        <form action="{{ $isEdit ? route('admin.expenses.update', $expense) : route('admin.expenses.store') }}" method="POST" class="csa-card p-5 sm:p-6 space-y-4">
            @csrf
            @if($isEdit) @method('PUT') @endif

            <x-form.input name="title" id="title" label="Keterangan" :value="old('title', $expense->title ?? '')" placeholder="Contoh: Beli konsumsi rapat, Cetak banner" required autofocus />

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <x-form.input name="amount" id="amount" type="number" label="Jumlah (Rp)" :value="old('amount', $expense->amount ?? '')" placeholder="100000" min="1" required />
                <x-form.input name="transaction_date" id="transaction_date" type="date" label="Tanggal Transaksi"
                    :value="old('transaction_date', isset($expense) ? $expense->transaction_date->format('Y-m-d') : now()->format('Y-m-d'))" required />
            </div>

            <x-form.textarea name="description" id="description" label="Deskripsi" :value="old('description', $expense->description ?? '')" placeholder="Detail pengeluaran..." rows="3" />

            <div class="flex items-center gap-3 pt-2 border-t border-[var(--border-soft)]">
                <button type="submit" class="csa-btn csa-btn-primary">
                    <x-admin.icon :name="$isEdit ? 'save' : 'plus'" :size="16" />
                    {{ $isEdit ? 'Simpan Perubahan' : 'Tambah Pengeluaran' }}
                </button>
                <a href="{{ route('admin.expenses.index') }}" class="csa-btn csa-btn-ghost">Batal</a>
            </div>
        </form>
    </div>
@endsection
