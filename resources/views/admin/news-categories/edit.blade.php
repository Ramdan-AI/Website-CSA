@extends('layouts.admin')

@section('content')

<x-page-header
    title="Edit Kategori Berita"
    subtitle="Perbarui informasi kategori berita yang sudah terdaftar."
/>

<div class="form-card">
    <form action="{{ route('admin.news-categories.update', $newsCategory) }}" method="POST">
        @csrf
        @method('PUT')

        <x-form.input
            name="name"
            label="Nama Kategori"
            :value="$newsCategory->name"
            placeholder="Contoh: Teknologi"
            required="true"
        />

        <x-form.input
            name="slug"
            label="Slug"
            :value="$newsCategory->slug"
            placeholder="contoh: teknologi"
            required="true"
        />

        <x-form.textarea
            name="description"
            label="Deskripsi"
            :value="$newsCategory->description"
            placeholder="Tuliskan deskripsi singkat kategori berita..."
        />

        <div class="form-actions">
            <button type="submit" class="btn-solid-success">
                <i class="fas fa-check me-2"></i> Perbarui Data
            </button>

            <a href="{{ route('admin.news-categories.index') }}" class="btn-solid-light">
                Batal
            </a>
        </div>
    </form>
</div>

@endsection