@extends('layouts.admin')

@php
    $isEdit = isset($gallery);
    $title = $isEdit ? 'Edit Foto' : 'Tambah Foto';
    $hasImage = $isEdit && $gallery->image;
    $imageUrl = $hasImage ? asset('storage/' . $gallery->image) : '#';
@endphp

@section('content')
<div class="space-y-4 sm:space-y-5">
    <div>
        <a href="{{ route('admin.galleries.index') }}" class="inline-flex items-center gap-1.5 text-[12px] text-[var(--text-muted)] hover:text-[var(--text-primary)] transition-colors">
            <x-admin.icon name="arrow-left" :size="14" /> Kembali ke Galeri
        </a>
        <h1 class="text-[26px] sm:text-[30px] font-bold text-[var(--text-primary)] tracking-[-0.03em] mt-2">{{ $title }}</h1>
        <p class="text-[13px] text-[var(--text-muted)] mt-1">
            {{ $isEdit ? 'Perbarui informasi foto.' : 'Tambahkan foto baru ke galeri.' }}
        </p>
    </div>

    <form action="{{ $isEdit ? route('admin.galleries.update', $gallery) : route('admin.galleries.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
        @csrf
        @if($isEdit) @method('PUT') @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
            <div class="lg:col-span-1">
                <div class="csa-panel p-5">
                    <x-form.file-crop
                        name="image"
                        label="Foto"
                        :current="$hasImage ? $imageUrl : null"
                        :aspect="1"
                        preview-size="w-24 h-24"
                        :required="! $isEdit"
                        hint="Maksimal 5MB. JPG, PNG, WEBP." />
                </div>
            </div>

            <div class="lg:col-span-2">
                <section class="csa-panel p-5 space-y-4">
                    <h3 class="text-[14px] font-semibold text-[var(--text-primary)]">Informasi Foto</h3>
                    <x-form.select name="album_id" id="album_id" label="Album" required
                        :value="old('album_id', $gallery->album_id ?? '')" placeholder="Pilih album"
                        :options="collect($albums)->mapWithKeys(fn ($a) => [$a->id => $a->title])->all()" />
                    <x-form.input name="caption" id="caption" label="Keterangan" :value="old('caption', $gallery->caption ?? '')" placeholder="Deskripsi singkat foto ini..." />
                </section>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <button type="submit" class="csa-btn csa-btn-primary">
                <x-admin.icon :name="$isEdit ? 'save' : 'plus'" :size="16" />
                {{ $isEdit ? 'Simpan Perubahan' : 'Tambah Foto' }}
            </button>
            <a href="{{ route('admin.galleries.index') }}" class="csa-btn csa-btn-ghost">Batal</a>
        </div>
    </form>
</div>
@endsection
