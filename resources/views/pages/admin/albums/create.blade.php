@extends('layouts.admin')

@section('content')

<x-page-header
    title="Tambah Album Baru"
    subtitle="Tambahkan album baru ke dalam galeri sistem."
/>

<div class="form-card">
    <form action="{{ route('admin.albums.store') }}" method="POST">
        @csrf

        <x-form.input
            name="title"
            label="Judul Album"
            :value="old('title')"
            required="true"
        />

        <x-form.textarea
            name="description"
            label="Deskripsi"
            :value="old('description')"
        />

        <x-form.input
            name="cover_image"
            label="Cover Image"
            :value="old('cover_image')"
        />

        <div class="form-actions">
            <button type="submit" class="btn-csa-primary">
                <i class="fas fa-save me-2"></i> Simpan Data
            </button>

            <a href="{{ route('admin.albums.index') }}" class="btn-solid-light">
                Batal
            </a>
        </div>
    </form>
</div>

@endsection