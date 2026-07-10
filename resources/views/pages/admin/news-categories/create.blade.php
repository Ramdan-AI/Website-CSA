@extends('layouts.admin')

@section('content')

<x-page-header
    title="Tambah Kategori Berita"
    subtitle="Registrasikan kategori berita baru ke dalam sistem."
/>

<div class="form-card">
    <form action="{{ route('admin.news-categories.store') }}" method="POST">
        @csrf

        <x-form.input
            name="name"
            label="Nama Kategori"
            value="{{ old('name') }}"
            placeholder="Contoh: Teknologi"
            required="true"
        />

        <x-form.input
            name="slug"
            label="Slug"
            value="{{ old('slug') }}"
            placeholder="contoh: teknologi"
            required="true"
        />

        <x-form.textarea
            name="description"
            label="Deskripsi"
            value="{{ old('description') }}"
            placeholder="Tuliskan deskripsi singkat kategori berita..."
        />

        <div class="form-actions">
            <button type="submit" class="btn-csa-primary">
                <i class="fas fa-save me-2"></i> Simpan Data
            </button>

            <a href="{{ route('admin.news-categories.index') }}" class="btn-solid-light">
                Batal
            </a>
        </div>
    </form>
</div>

@endsection