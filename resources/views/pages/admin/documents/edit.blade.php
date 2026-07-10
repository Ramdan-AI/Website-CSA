@extends('layouts.admin')

@section('content')

<x-page-header
    title="Edit Dokumen"
    subtitle="Perbarui informasi dokumen yang telah tersimpan."
/>

<div class="form-card">
    <form action="{{ route('admin.documents.update', $document) }}" method="POST">
        @csrf
        @method('PUT')

        <x-form.input
            name="title"
            label="Judul Dokumen"
            :value="$document->title"
            placeholder="Contoh: SOP Divisi Programming"
            required="true"
        />

        <x-form.input
            name="file"
            label="File"
            :value="$document->file"
            placeholder="Masukkan path atau nama file"
            required="true"
        />

        <x-form.textarea
            name="description"
            label="Deskripsi"
            :value="$document->description"
            placeholder="Tuliskan deskripsi singkat dokumen..."
        />

        <div class="form-actions">
            <button type="submit" class="btn-solid-success">
                <i class="fas fa-check me-2"></i> Perbarui Data
            </button>

            <a href="{{ route('admin.documents.index') }}" class="btn-solid-light">
                Batal
            </a>
        </div>
    </form>
</div>

@endsection