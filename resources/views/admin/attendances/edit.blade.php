@extends('layouts.admin')

@section('content')

<x-page-header
    title="Edit Absensi"
    subtitle="Perbarui data absensi member yang telah terdaftar."
/>

<div class="form-card">
    <form action="{{ route('admin.attendances.update', $attendance) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label class="custom-label">Event</label>
            <select name="event_id" class="custom-input" required>
                <option value="">Pilih Event</option>

                @foreach($events as $event)
                    <option
                        value="{{ $event->id }}"
                        {{ old('event_id', $attendance->event_id) == $event->id ? 'selected' : '' }}>
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
                        {{ old('member_id', $attendance->member_id) == $member->id ? 'selected' : '' }}>
                        {{ $member->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label class="custom-label">Status</label>
            <select name="status" class="custom-input">
                <option value="present" {{ old('status', $attendance->status) == 'present' ? 'selected' : '' }}>
                    Present
                </option>
                <option value="absent" {{ old('status', $attendance->status) == 'absent' ? 'selected' : '' }}>
                    Absent
                </option>
            </select>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-solid-success">
                <i class="fas fa-check me-2"></i> Perbarui Data
            </button>

            <a href="{{ route('admin.attendances.index') }}" class="btn-solid-light">
                Batal
            </a>
        </div>
    </form>
</div>

@endsection