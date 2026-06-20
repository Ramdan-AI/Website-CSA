@extends('layouts.admin')

@section('content')

<x-page-header
    title="Edit Event"
    subtitle="Perbarui informasi event yang telah terdaftar."
/>

<div class="form-card">
    <form action="{{ route('admin.events.update', $event) }}" method="POST">
        @csrf
        @method('PUT')

        <x-form.input
            name="title"
            label="Judul Event"
            :value="$event->title"
            placeholder="Contoh: Workshop Laravel"
            required="true"
        />

        <x-form.input
            name="slug"
            label="Slug"
            :value="$event->slug"
            placeholder="contoh: workshop-laravel"
            required="true"
        />

        <x-form.textarea
            name="description"
            label="Deskripsi Event"
            :value="$event->description"
            placeholder="Tuliskan deskripsi lengkap event..."
            required="true"
        />

        <x-form.input
            name="location"
            label="Lokasi"
            :value="$event->location"
            placeholder="Contoh: Aula Kampus"
        />

        <x-form.input
            name="event_date"
            label="Tanggal Event"
            type="date"
            value="{{ old('event_date', $event->event_date->format('Y-m-d')) }}"
            required="true"
        />

        <x-form.input
            name="banner"
            label="Banner"
            :value="$event->banner"
            placeholder="URL banner event"
        />

        <x-form.input
            name="registration_link"
            label="Registration Link"
            :value="$event->registration_link"
            placeholder="https://..."
        />

        <div class="mb-4">
            <label class="custom-label">Status</label>

            <select name="status" class="custom-input">
                <option value="draft" {{ old('status', $event->status) == 'draft' ? 'selected' : '' }}>
                    Draft
                </option>
                <option value="published" {{ old('status', $event->status) == 'published' ? 'selected' : '' }}>
                    Published
                </option>
                <option value="closed" {{ old('status', $event->status) == 'closed' ? 'selected' : '' }}>
                    Closed
                </option>
            </select>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-solid-success">
                <i class="fas fa-check me-2"></i> Perbarui Data
            </button>

            <a href="{{ route('admin.events.index') }}" class="btn-solid-light">
                Batal
            </a>
        </div>
    </form>
</div>

@endsection