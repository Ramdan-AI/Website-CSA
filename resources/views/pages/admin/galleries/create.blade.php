@extends('layouts.admin')

@section('content')

<x-page-header
    title="Tambah Gallery Baru"
    subtitle="Tambahkan gambar baru ke dalam album galeri."
/>

<div class="form-card">
    <form action="{{ route('admin.galleries.store') }}" method="POST">
        @csrf

        <div class="mb-4">
            <label class="custom-label">Album</label>

            <select name="album_id" class="custom-input" required>
                <option value="">Pilih Album</option>

                @foreach($albums as $album)
                    <option
                        value="{{ $album->id }}"
                        {{ old('album_id') == $album->id ? 'selected' : '' }}
                    >
                        {{ $album->title }}
                    </option>
                @endforeach
            </select>
        </div>

        <x-form.input
            name="image"
            label="Image"
            value="{{ old('image') }}"
            placeholder="URL gambar"
            required="true"
        />

        <x-form.input
            name="caption"
            label="Caption"
            value="{{ old('caption') }}"
            placeholder="Caption gambar (opsional)"
        />

        <div class="form-actions">
            <button type="submit" class="btn-csa-primary">
                <i class="fas fa-save me-2"></i> Simpan Data
            </button>

            <a href="{{ route('admin.galleries.index') }}" class="btn-solid-light">
                Batal
            </a>
        </div>
    </form>
</div>

@endsection