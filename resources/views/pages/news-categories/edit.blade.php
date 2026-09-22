@extends('layouts.admin')

@section('content')
<div class="space-y-4 sm:space-y-5 max-w-2xl">
    <div>
        <a href="{{ route('admin.news-categories.index') }}" class="inline-flex items-center gap-1.5 text-[12px] text-[var(--text-muted)] hover:text-[var(--text-primary)] transition-colors">
            <x-admin.icon name="arrow-left" :size="14" /> Kembali ke Kategori
        </a>
        <h1 class="text-[26px] sm:text-[30px] font-bold text-[var(--text-primary)] tracking-[-0.03em] mt-2">Edit Kategori</h1>
        <p class="text-[13px] text-[var(--text-muted)] mt-1">Perbarui informasi kategori berita yang sudah terdaftar.</p>
    </div>

    <form action="{{ route('admin.news-categories.update', $newsCategory) }}" method="POST" class="csa-card p-5 sm:p-6 space-y-4">
        @csrf
        @method('PUT')

        <x-form.input name="name" id="name" label="Nama Kategori" :value="old('name', $newsCategory->name)" placeholder="Contoh: Teknologi" required autofocus />
        <x-form.textarea name="description" id="description" label="Deskripsi" :value="old('description', $newsCategory->description)" placeholder="Tuliskan deskripsi singkat kategori berita..." rows="3" />

        <div class="flex items-center gap-3 pt-2 border-t border-[var(--border-soft)]">
            <button type="submit" class="csa-btn csa-btn-primary">
                <x-admin.icon name="save" :size="16" /> Simpan Perubahan
            </button>
            <a href="{{ route('admin.news-categories.index') }}" class="csa-btn csa-btn-ghost">Batal</a>
        </div>
    </form>
</div>
@endsection
