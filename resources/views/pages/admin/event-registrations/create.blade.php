@extends('layouts.admin')

@section('content')

<x-page-header
    title="Tambah Registrasi Event"
    subtitle="Tambahkan data registrasi member ke dalam event."
/>

<div class="form-card">
    <form action="{{ route('admin.event-registrations.store') }}" method="POST">
        @csrf

        <div class="mb-4">
            <label class="custom-label">Event</label>
            <select name="event_id" class="custom-input" required>
                <option value="">Pilih Event</option>

                @foreach($events as $event)
                    <option
                        value="{{ $event->id }}"
                        {{ old('event_id') == $event->id ? 'selected' : '' }}>
                        {{ $event->title }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label class="custom-label">Member</label>
            <select name="member_id" class="custom-input" required>
                <option value="">Pilih Member</option>

                @foreach($members as $member)
                    <option
                        value="{{ $member->id }}"
                        {{ old('member_id') == $member->id ? 'selected' : '' }}>
                        {{ $member->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-csa-primary">
                <i class="fas fa-save me-2"></i> Simpan Data
            </button>

            <a href="{{ route('admin.event-registrations.index') }}" class="btn-solid-light">
                Batal
            </a>
        </div>
    </form>
</div>

@endsection