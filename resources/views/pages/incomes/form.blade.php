@extends('layouts.admin')

@section('content')
    @php
        $isEdit = isset($income);
        $title = $isEdit ? 'Edit Pemasukan' : 'Tambah Pemasukan';
    @endphp

    <div class="space-y-4 sm:space-y-5 max-w-2xl">
        <div>
            <a href="{{ route('admin.incomes.index') }}" class="inline-flex items-center gap-1.5 text-[12px] text-[var(--text-muted)] hover:text-[var(--text-primary)] transition-colors">
                <x-admin.icon name="arrow-left" :size="14" /> Kembali ke Pemasukan
            </a>
            <h1 class="text-[26px] sm:text-[30px] font-bold text-[var(--text-primary)] tracking-[-0.03em] mt-2">{{ $title }}</h1>
            <p class="text-[13px] text-[var(--text-muted)] mt-1">
                {{ $isEdit ? 'Perbarui data pemasukan.' : 'Catat pemasukan baru organisasi.' }}
            </p>
        </div>

        <form action="{{ $isEdit ? route('admin.incomes.update', $income) : route('admin.incomes.store') }}" method="POST" class="csa-card p-5 sm:p-6 space-y-4">
            @csrf
            @if ($isEdit)
                @method('PUT')
            @endif

            <x-form.input name="title" id="title" label="Keterangan" :value="old('title', $income->title ?? '')" placeholder="Contoh: Iuran anggota, Donasi, Sponsor" required autofocus />

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <x-form.input name="amount" id="amount" type="number" label="Jumlah (Rp)" :value="old('amount', $income->amount ?? '')" placeholder="100000" min="1" required />
                <x-form.input name="transaction_date" id="transaction_date" type="date" label="Tanggal Transaksi"
                    :value="old('transaction_date', isset($income) ? $income->transaction_date->format('Y-m-d') : now()->format('Y-m-d'))" required />
            </div>

            <x-form.textarea name="description" id="description" label="Deskripsi" :value="old('description', $income->description ?? '')" placeholder="Detail pemasukan..." rows="3" />

            <div class="flex items-center gap-3 pt-2 border-t border-[var(--border-soft)]">
                <button type="submit" class="csa-btn csa-btn-primary">
                    <x-admin.icon :name="$isEdit ? 'save' : 'plus'" :size="16" />
                    {{ $isEdit ? 'Simpan Perubahan' : 'Tambah Pemasukan' }}
                </button>
                <a href="{{ route('admin.incomes.index') }}" class="csa-btn csa-btn-ghost">Batal</a>
            </div>
        </form>
    </div>
@endsection
