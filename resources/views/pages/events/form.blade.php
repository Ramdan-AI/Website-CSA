@extends('layouts.admin')

@php
    $isEdit = isset($event) && $event->exists;
    $title = $isEdit ? 'Edit Kegiatan' : 'Tambah Kegiatan';
@endphp

@section('content')
<div class="space-y-4 sm:space-y-5 max-w-3xl">
    <div>
        <a href="{{ route('admin.events.index') }}" class="inline-flex items-center gap-1.5 text-[12px] text-[var(--text-muted)] hover:text-[var(--text-primary)] transition-colors">
            <x-admin.icon name="arrow-left" :size="14" /> Kembali ke Kegiatan
        </a>
        <h1 class="text-[26px] sm:text-[30px] font-bold text-[var(--text-primary)] tracking-[-0.03em] mt-2">{{ $title }}</h1>
        <p class="text-[13px] text-[var(--text-muted)] mt-1">Acara terjadwal atau study club rutin CSA.</p>
    </div>

    <form action="{{ $isEdit ? route('admin.events.update', $event) : route('admin.events.store') }}" method="POST" enctype="multipart/form-data" class="csa-panel p-5 sm:p-6 space-y-4">
        @csrf
        @if($isEdit) @method('PUT') @endif

        <x-form.input name="title" id="title" label="Judul Kegiatan" :value="old('title', $event->title ?? '')" placeholder="Contoh: Workshop UI/UX Design" required autofocus />
        <x-form.textarea name="description" id="description" label="Deskripsi Kegiatan" :value="old('description', $event->description ?? '')" placeholder="Jelaskan detail kegiatan secara lengkap..." rows="5" required />

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <x-form.select name="type" id="type" label="Jenis Kegiatan" required
                :value="old('type', $event->type ?? 'acara')"
                :options="\App\Models\Event::TYPES"
                hint="Study club untuk kegiatan rutin, acara untuk event terjadwal." />
            <x-form.input name="location" id="location" label="Lokasi" :value="old('location', $event->location ?? '')" placeholder="Contoh: Lab Komputer" />
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <x-form.input name="event_date" id="event_date" type="date" label="Tanggal Pelaksanaan"
                :value="old('event_date', isset($event) && $event->event_date ? $event->event_date->format('Y-m-d') : '')" required />
            <x-form.select name="status" id="status" label="Status" required
                :value="old('status', $event->status ?? 'draft')"
                :options="['draft' => 'Draft', 'published' => 'Published', 'closed' => 'Closed']" />
        </div>

        <x-form.input name="registration_link" id="registration_link" type="url" label="Link Registrasi Eksternal" :value="old('registration_link', $event->registration_link ?? '')" placeholder="https://forms.gle/..." />

        <x-form.file-crop
            name="banner"
            label="Banner Kegiatan"
            :current="isset($event) && $event->banner ? asset('storage/' . $event->banner) : null"
            :aspect="21/9"
            shape="square"
            preview-size="w-32 h-16"
            hint="JPG, PNG, WEBP. Maksimal 2MB." />

        <div class="flex items-center gap-3 pt-2 border-t border-[var(--border-soft)]">
            <button type="submit" class="csa-btn csa-btn-primary">
                <x-admin.icon :name="$isEdit ? 'save' : 'plus'" :size="16" /> {{ $isEdit ? 'Simpan Perubahan' : 'Tambah Kegiatan' }}
            </button>
            <a href="{{ route('admin.events.index') }}" class="csa-btn csa-btn-ghost">Batal</a>
        </div>
    </form>
</div>
@endsection
