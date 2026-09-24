@extends('layouts.admin')

@section('content')
@php
    $isEdit = isset($position);
    $title = $isEdit ? 'Edit Jabatan' : 'Tambah Jabatan';
@endphp
<div class="space-y-6 max-w-3xl">
    <div>
        <a href="{{ route('admin.positions.index') }}" class="inline-flex items-center gap-1.5 text-[12px] text-[var(--text-muted)] hover:text-[var(--text-primary)] transition-colors">
            <x-admin.icon name="arrow-left" :size="14" /> Kembali ke Jabatan
        </a>
        <h1 class="text-[26px] sm:text-[30px] font-bold text-[var(--text-primary)] tracking-[-0.03em] mt-2">{{ $title }}</h1>
        <p class="text-[13px] text-[var(--text-muted)] mt-1">{{ $isEdit ? 'Perbarui informasi jabatan.' : 'Tambahkan jabatan baru ke dalam struktur.' }}</p>
    </div>

    @if ($errors->any())
        <div class="csa-panel p-4 border-l-2 border-red-500 space-y-1.5">
            <p class="text-[13px] font-medium text-red-500">Periksa kembali data berikut:</p>
            <ul class="space-y-1">
                @foreach ($errors->all() as $message)
                    <li class="text-[12px] text-red-500">· {{ $message }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ $isEdit ? route('admin.positions.update', $position) : route('admin.positions.store') }}"
        method="POST" class="csa-panel p-5 sm:p-6 space-y-5">
        @csrf
        @if ($isEdit)
            @method('PUT')
        @endif

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <x-form.input name="name" id="name" label="Nama Jabatan" :value="old('name', $position->name ?? '')"
                placeholder="Contoh: Sekretaris" required autofocus />
            <x-form.input name="max_members" id="max_members" type="number" label="Maksimal Anggota"
                :value="old('max_members', $position->max_members ?? 1)" min="1" required />
        </div>

        <x-form.textarea name="description" id="description" label="Deskripsi"
            :value="old('description', $position->description ?? '')"
            placeholder="Jelaskan tugas dan tanggung jawab jabatan ini..." rows="4" />

        <div class="flex items-center gap-3 pt-2 border-t border-[var(--border-soft)]">
            <button type="submit" class="csa-btn csa-btn-primary">
                <x-admin.icon :name="$isEdit ? 'save' : 'plus'" :size="16" />
                {{ $isEdit ? 'Simpan Perubahan' : 'Tambah Jabatan' }}
            </button>
            <a href="{{ route('admin.positions.index') }}" class="csa-btn csa-btn-ghost">Batal</a>

            @if ($isEdit)
                <button type="button" onclick="openDeleteModal('deleteModal', '{{ route('admin.positions.destroy', $position) }}')" class="csa-btn csa-btn-danger ml-auto">
                    <x-admin.icon name="trash-2" :size="15" /> Hapus
                </button>
            @endif
        </div>
    </form>
</div>
@endsection
