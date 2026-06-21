@extends('layouts.admin')

@section('content')

<x-page-header
    title="Edit Album"
    subtitle="Perbarui informasi album yang sudah tersimpan."
/>

<div class="form-card">
    <form action="{{ route('admin.albums.update', $album) }}" method="POST">
        @csrf
        @method('PUT')

        <x-form.input
            name="title"
            label="Judul Album"
            :value="$album->title"
            required="true"
        />

        <x-form.textarea
            name="description"
            label="Deskripsi"
            :value="$album->description"
        />

        <x-form.input
            name="cover_image"
            label="Cover Image"
            :value="$album->cover_image"
        />

        <div class="form-actions">
            <button type="submit" class="btn-solid-success">
                <i class="fas fa-check me-2"></i> Perbarui Data
            </button>

            <a href="{{ route('admin.albums.index') }}" class="btn-solid-light">
                Batal
            </a>
        </div>
    </form>
</div>

@endsection