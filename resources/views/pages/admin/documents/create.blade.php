@extends('layouts.admin')

@section('content')

<x-page-header
    title="Tambah Dokumen"
    subtitle="Unggah dan registrasikan dokumen baru ke dalam sistem."
/>

<div class="form-card">
    <form action="{{ route('admin.documents.store') }}" method="POST">
        @csrf

        <x-form.input
            name="title"
            label="Judul Dokumen"
            value="{{ old('title') }}"
            placeholder="Contoh: SOP Divisi Programming"
            required="true"
        />

        <x-form.input
            name="file"
            label="File"
            value="{{ old('file') }}"
            placeholder="Masukkan path atau nama file"
            required="true"
        />

        <x-form.textarea
            name="description"
            label="Deskripsi"
            value="{{ old('description') }}"
            placeholder="Tuliskan deskripsi singkat dokumen..."
        />

        <div class="form-actions">
            <button type="submit" class="btn-csa-primary">
                <i class="fas fa-save me-2"></i> Simpan Data
            </button>

            <a href="{{ route('admin.documents.index') }}" class="btn-solid-light">
                Batal
            </a>
        </div>
    </form>
</div>

@endsection