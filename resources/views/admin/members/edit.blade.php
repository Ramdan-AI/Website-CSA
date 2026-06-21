@extends('layouts.admin')

@section('content')

<x-page-header
    title="Edit Member"
    subtitle="Perbarui informasi member yang telah terdaftar."
/>

<div class="form-card">
    <form action="{{ route('admin.members.update', $member) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label class="custom-label">
                User Login
                <span class="text-optional">Opsional</span>
            </label>

            <select name="user_id" class="custom-input">
                <option value="">Tidak Ada User</option>

                @foreach($users as $user)
                    <option
                        value="{{ $user->id }}"
                        {{ old('user_id', $member->user_id) == $user->id ? 'selected' : '' }}
                    >
                        {{ $user->name }} ({{ $user->email }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label class="custom-label">Divisi</label>

            <select
                name="division_id"
                class="custom-input @error('division_id') is-invalid @enderror"
                required
            >
                <option value="">Pilih Divisi</option>

                @foreach($divisions as $division)
                    <option
                        value="{{ $division->id }}"
                        {{ old('division_id', $member->division_id) == $division->id ? 'selected' : '' }}
                    >
                        {{ $division->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label class="custom-label">Posisi</label>

            <select
                name="position_id"
                class="custom-input @error('position_id') is-invalid @enderror"
                required
            >
                <option value="">Pilih Posisi</option>

                @foreach($positions as $position)
                    <option
                        value="{{ $position->id }}"
                        {{ old('position_id', $member->position_id) == $position->id ? 'selected' : '' }}
                    >
                        {{ $position->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <x-form.input
            name="name"
            label="Nama"
            :value="$member->name"
            placeholder="Masukkan nama member"
            required="true"
        />

        <x-form.input
            name="nim"
            label="NIM"
            :value="$member->nim"
            placeholder="Masukkan NIM"
            required="true"
        />

        <x-form.input
            name="phone"
            label="No. HP"
            :value="$member->phone"
            placeholder="08xxxxxxxxxx"
        />

        <x-form.textarea
            name="bio"
            label="Bio"
            :value="$member->bio"
            placeholder="Tuliskan bio singkat..."
        />

        <x-form.input
            name="linkedin"
            label="LinkedIn"
            :value="$member->linkedin"
            placeholder="https://linkedin.com/in/username"
        />

        <x-form.input
            name="instagram"
            label="Instagram"
            :value="$member->instagram"
            placeholder="@username"
        />

        <x-form.input
            name="github"
            label="Github"
            :value="$member->github"
            placeholder="https://github.com/username"
        />

        <div class="mb-4">
            <label class="custom-label">Status</label>

            <select name="is_active" class="custom-input">
                <option value="1" {{ old('is_active', $member->is_active) == 1 ? 'selected' : '' }}>
                    Aktif
                </option>

                <option value="0" {{ old('is_active', $member->is_active) == 0 ? 'selected' : '' }}>
                    Nonaktif
                </option>
            </select>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-solid-success">
                <i class="fas fa-check me-2"></i> Perbarui Data
            </button>

            <a href="{{ route('admin.members.index') }}" class="btn-solid-light">
                Batal
            </a>
        </div>
    </form>
</div>

@endsection