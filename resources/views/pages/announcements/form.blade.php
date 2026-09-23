@extends('layouts.admin')

@php
    $isEdit = $announcement !== null;
    $title = $isEdit ? 'Edit Pengumuman' : 'Buat Pengumuman';
@endphp

@section('content')
<div class="space-y-4 sm:space-y-5 max-w-2xl">
    <div>
        <a href="{{ route('admin.announcements.index') }}" class="inline-flex items-center gap-1.5 text-[12px] text-[var(--text-muted)] hover:text-[var(--text-primary)] transition-colors">
            <x-admin.icon name="arrow-left" :size="14" /> Kembali ke Pengumuman
        </a>
        <h1 class="text-[26px] sm:text-[30px] font-bold text-[var(--text-primary)] tracking-[-0.03em] mt-2">{{ $title }}</h1>
    </div>

    @if ($errors->any())
        <div class="csa-card p-4 border-l-2 border-red-500 space-y-1.5">
            <p class="text-[13px] font-medium text-red-500">Periksa kembali data berikut:</p>
            <ul class="space-y-1">
                @foreach ($errors->all() as $message)
                    <li class="text-[12px] text-red-500">· {{ $message }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ $isEdit ? route('admin.announcements.update', $announcement) : route('admin.announcements.store') }}"
        method="POST" class="csa-card p-5 sm:p-6 space-y-5">
        @csrf
        @if ($isEdit) @method('PUT') @endif

        <x-form.input name="title" id="title" label="Judul" :value="old('title', $announcement->title ?? '')"
            placeholder="Contoh: Study club diliburkan" required autofocus />

        <x-form.textarea name="body" id="body" label="Isi Pengumuman" :value="old('body', $announcement->body ?? '')"
            placeholder="Tuliskan detail pengumuman." rows="6" required />

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label for="category" class="block text-[13px] font-medium text-[var(--text-secondary)] mb-1.5">Kategori</label>
                <select name="category" id="category" class="csa-input">
                    <option value="info" @selected(old('category', $announcement->category ?? 'info') === 'info')>Informasi</option>
                    <option value="penting" @selected(old('category', $announcement->category ?? '') === 'penting')>Penting</option>
                    <option value="urgent" @selected(old('category', $announcement->category ?? '') === 'urgent')>Penting Sekali</option>
                </select>
                <p class="text-[11px] text-[var(--text-muted)] mt-1.5">Kategori memengaruhi warna label di dashboard.</p>
            </div>

            <div>
                <label for="status" class="block text-[13px] font-medium text-[var(--text-secondary)] mb-1.5">Status</label>
                <select name="status" id="status" class="csa-input">
                    <option value="published" @selected(old('status', $announcement->status ?? 'published') === 'published')>Terbitkan</option>
                    <option value="draft" @selected(old('status', $announcement->status ?? '') === 'draft')>Simpan sebagai draf</option>
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <x-form.input name="starts_at" id="starts_at" type="date" label="Mulai Tampil"
                :value="old('starts_at', $isEdit && $announcement->starts_at ? $announcement->starts_at->format('Y-m-d') : '')"
                hint="Kosongkan bila langsung tampil." />
            <x-form.input name="ends_at" id="ends_at" type="date" label="Akhir Tampil"
                :value="old('ends_at', $isEdit && $announcement->ends_at ? $announcement->ends_at->format('Y-m-d') : '')"
                hint="Kosongkan bila tampil terus." />
        </div>

        <label class="flex items-center gap-3 cursor-pointer">
            <input type="hidden" name="pinned" value="0">
            <input type="checkbox" name="pinned" value="1" class="peer sr-only"
                @checked(old('pinned', $announcement->pinned ?? false))>
            <span class="relative w-10 h-6 rounded-full bg-[var(--border-strong)] after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:w-5 after:h-5 after:rounded-full after:bg-white after:transition-all peer-checked:bg-[var(--accent)] peer-checked:after:translate-x-4"></span>
            <span>
                <span class="block text-[13px] font-medium text-[var(--text-primary)]">Sematkan di paling atas</span>
                <span class="block text-[12px] text-[var(--text-muted)] mt-0.5">Cocok untuk informasi yang wajib dibaca.</span>
            </span>
        </label>

        <div class="flex items-center gap-3 pt-2 border-t border-[var(--border-soft)]">
            <button type="submit" class="csa-btn csa-btn-primary">
                <x-admin.icon :name="$isEdit ? 'save' : 'plus'" :size="16" />
                {{ $isEdit ? 'Simpan Perubahan' : 'Terbitkan' }}
            </button>
            <a href="{{ route('admin.announcements.index') }}" class="csa-btn csa-btn-ghost">Batal</a>
        </div>
    </form>
</div>
@endsection