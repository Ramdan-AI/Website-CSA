@extends('layouts.admin')

@php
    $isEdit = isset($role) && $role->exists;
    $title = $isEdit ? 'Edit Role' : 'Tambah Role';
@endphp

@section('content')
<div class="space-y-4 sm:space-y-5 max-w-2xl">
    <div>
        <a href="{{ route('admin.roles.index') }}" class="inline-flex items-center gap-1.5 text-[12px] text-[var(--text-muted)] hover:text-[var(--text-primary)] transition-colors">
            <x-admin.icon name="arrow-left" :size="14" /> Kembali ke Role
        </a>
        <h1 class="text-[26px] sm:text-[30px] font-bold text-[var(--text-primary)] tracking-[-0.03em] mt-2">{{ $title }}</h1>
        <p class="text-[13px] text-[var(--text-muted)] mt-1">
            {{ $isEdit ? 'Perbarui informasi role.' : 'Tambahkan role baru ke dalam sistem.' }}
        </p>
    </div>

    <form action="{{ $isEdit ? route('admin.roles.update', $role) : route('admin.roles.store') }}" method="POST" class="csa-card p-5 sm:p-6 space-y-4">
        @csrf
        @if ($isEdit)
            @method('PUT')
        @endif

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <x-form.input name="name" id="name" label="Nama Role" :value="old('name', $role->name ?? '')" placeholder="Contoh: Ketua Divisi" required autofocus />
            <div>
                <label for="slug" class="block text-[13px] font-medium text-[var(--text-secondary)] mb-1.5">Slug</label>
                <input type="text" name="slug" id="slug" value="{{ old('slug', $role->slug ?? '') }}" class="csa-input font-mono" placeholder="ketua-divisi" required>
                @error('slug')<p class="text-[12px] text-red-500 mt-1.5">{{ $message }}</p>@enderror
            </div>
        </div>

        <x-form.textarea name="description" id="description" label="Deskripsi" :value="old('description', $role->description ?? '')" placeholder="Jelaskan peran ini..." rows="3" />

        <div class="flex items-center gap-3 pt-2 border-t border-[var(--border-soft)]">
            <button type="submit" class="csa-btn csa-btn-primary">
                <x-admin.icon :name="$isEdit ? 'save' : 'plus'" :size="16" />
                {{ $isEdit ? 'Simpan Perubahan' : 'Tambah Role' }}
            </button>
            <a href="{{ route('admin.roles.index') }}" class="csa-btn csa-btn-ghost">Batal</a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    (() => {
        const nameInput = document.getElementById('name');
        const slugInput = document.getElementById('slug');
        if (!nameInput || !slugInput) return;

        nameInput.addEventListener('input', function () {
            if (slugInput.dataset.modified === 'true') return;
            slugInput.value = this.value.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-+|-+$/g, '');
        });

        slugInput.addEventListener('input', function () {
            this.dataset.modified = this.value !== '' ? 'true' : 'false';
        });
    })();
</script>
@endpush
