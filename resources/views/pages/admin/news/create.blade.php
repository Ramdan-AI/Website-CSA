@extends('layouts.admin')

@section('content')

<x-page-header
    title="Tambah Berita"
    subtitle="Publikasikan berita baru ke dalam sistem konten."
/>

<div class="form-card">
    <form action="{{ route('admin.news.store') }}" method="POST">
        @csrf

        <div class="mb-4">
            <label class="custom-label">Kategori</label>

            <select
                name="category_id"
                class="custom-input @error('category_id') is-invalid @enderror"
                required
            >
                <option value="">Pilih Kategori</option>

                @foreach($categories as $category)
                    <option
                        value="{{ $category->id }}"
                        {{ old('category_id') == $category->id ? 'selected' : '' }}
                    >
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>

            @error('category_id')
                <div class="text-danger mt-2">{{ $message }}</div>
            @enderror
        </div>

        <x-form.input
            name="title"
            label="Judul"
            value="{{ old('title') }}"
            placeholder="Masukkan judul berita"
            required="true"
        />

        <x-form.input
            name="slug"
            label="Slug"
            value="{{ old('slug') }}"
            placeholder="contoh-judul-berita"
            required="true"
        />

        <x-form.textarea
            name="content"
            label="Konten"
            value="{{ old('content') }}"
            required="true"
            placeholder="Tuliskan isi berita..."
            rows="10"
        />

        <div class="mb-4">
            <label class="custom-label">Status</label>

            <select name="status" class="custom-input">
                <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>
                    Draft
                </option>
                <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>
                    Published
                </option>
            </select>
        </div>

        <div class="mb-4">
            <label class="custom-label">Tanggal Publish</label>

            <input
                type="datetime-local"
                name="published_at"
                class="custom-input"
                value="{{ old('published_at') }}"
            >
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-csa-primary">
                <i class="fas fa-save me-2"></i> Simpan Data
            </button>

            <a href="{{ route('admin.news.index') }}" class="btn-solid-light">
                Batal
            </a>
        </div>
    </form>
</div>

@endsection