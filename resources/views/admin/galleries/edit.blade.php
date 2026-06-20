@extends('layouts.admin')

@section('content')

<x-page-header
    title="Edit Gallery"
    subtitle="Perbarui informasi gallery yang tersimpan."
/>

<div class="form-card">
    <form action="{{ route('admin.galleries.update', $gallery) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label class="custom-label">Album</label>

            <select name="album_id" class="custom-input" required>
                @foreach($albums as $album)
                    <option
                        value="{{ $album->id }}"
                        {{ old('album_id', $gallery->album_id) == $album->id ? 'selected' : '' }}
                    >
                        {{ $album->title }}
                    </option>
                @endforeach
            </select>
        </div>

        <x-form.input
            name="image"
            label="Image"
            :value="$gallery->image"
            placeholder="URL gambar"
            required="true"
        />

        <x-form.input
            name="caption"
            label="Caption"
            :value="$gallery->caption"
            placeholder="Caption gambar (opsional)"
        />

        <div class="form-actions">
            <button type="submit" class="btn-solid-success">
                <i class="fas fa-check me-2"></i> Perbarui Data
            </button>

            <a href="{{ route('admin.galleries.index') }}" class="btn-solid-light">
                Batal
            </a>
        </div>
    </form>
</div>

@endsection