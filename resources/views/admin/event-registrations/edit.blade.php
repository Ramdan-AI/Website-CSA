@extends('layouts.admin')

@section('content')

<x-page-header
    title="Edit Registrasi Event"
    subtitle="Perbarui data registrasi event yang telah terdaftar."
/>

<div class="form-card">
    <form action="{{ route('admin.event-registrations.update', $eventRegistration) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label class="custom-label">Event</label>
            <select name="event_id" class="custom-input" required>
                <option value="">Pilih Event</option>

                @foreach($events as $event)
                    <option
                        value="{{ $event->id }}"
                        {{ old('event_id', $eventRegistration->event_id) == $event->id ? 'selected' : '' }}>
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
                        {{ old('member_id', $eventRegistration->member_id) == $member->id ? 'selected' : '' }}>
                        {{ $member->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-solid-success">
                <i class="fas fa-check me-2"></i> Perbarui Data
            </button>

            <a href="{{ route('admin.event-registrations.index') }}" class="btn-solid-light">
                Batal
            </a>
        </div>
    </form>
</div>

@endsection