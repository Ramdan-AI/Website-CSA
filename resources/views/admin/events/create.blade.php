@extends('layouts.admin')

@section('content')

<x-page-header
    title="Tambah Event Baru"
    subtitle="Registrasikan event baru ke dalam sistem."
/>

<div class="form-card">
    <form action="{{ route('admin.events.store') }}" method="POST">
        @csrf

        <x-form.input
            name="title"
            label="Judul Event"
            value="{{ old('title') }}"
            placeholder="Contoh: Workshop Laravel"
            required="true"
        />

        <x-form.input
            name="slug"
            label="Slug"
            value="{{ old('slug') }}"
            placeholder="contoh: workshop-laravel"
            required="true"
        />

        <x-form.textarea
            name="description"
            label="Deskripsi Event"
            placeholder="Tuliskan deskripsi lengkap event..."
            required="true"
        />

        <x-form.input
            name="location"
            label="Lokasi"
            value="{{ old('location') }}"
            placeholder="Contoh: Aula Kampus"
        />

        <x-form.input
            name="event_date"
            label="Tanggal Event"
            type="date"
            value="{{ old('event_date') }}"
            required="true"
        />

        <x-form.input
            name="banner"
            label="Banner"
            value="{{ old('banner') }}"
            placeholder="URL banner event"
        />

        <x-form.input
            name="registration_link"
            label="Registration Link"
            value="{{ old('registration_link') }}"
            placeholder="https://..."
        />

        <div class="mb-4">
            <label class="custom-label">Status</label>

            <select
                name="status"
                class="custom-input"
            >
                <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>
                    Draft
                </option>
                <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>
                    Published
                </option>
                <option value="closed" {{ old('status') == 'closed' ? 'selected' : '' }}>
                    Closed
                </option>
            </select>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-csa-primary">
                <i class="fas fa-save me-2"></i> Simpan Data
            </button>

            <a href="{{ route('admin.events.index') }}" class="btn-solid-light">
                Batal
            </a>
        </div>
    </form>
</div>

@endsection