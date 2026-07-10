@extends('layouts.admin')

@section('content')

<x-page-header
    title="Tambah Member"
    subtitle="Registrasikan anggota baru ke dalam sistem organisasi."
/>

<div class="form-card">
    <form action="{{ route('admin.members.store') }}" method="POST">
        @csrf

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
                        {{ old('user_id') == $user->id ? 'selected' : '' }}
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
                        {{ old('division_id') == $division->id ? 'selected' : '' }}
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
                        {{ old('position_id') == $position->id ? 'selected' : '' }}
                    >
                        {{ $position->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <x-form.input
            name="name"
            label="Nama"
            value="{{ old('name') }}"
            placeholder="Masukkan nama member"
            required="true"
        />

        <x-form.input
            name="nim"
            label="NIM"
            value="{{ old('nim') }}"
            placeholder="Masukkan NIM"
            required="true"
        />

        <x-form.input
            name="phone"
            label="No. HP"
            value="{{ old('phone') }}"
            placeholder="08xxxxxxxxxx"
        />

        <x-form.textarea
            name="bio"
            label="Bio"
            value="{{ old('bio') }}"
            placeholder="Tuliskan bio singkat..."
            {{-- required="true" --}}
        />

        <x-form.input
            name="linkedin"
            label="LinkedIn"
            value="{{ old('linkedin') }}"
            placeholder="https://linkedin.com/in/username"
            {{-- required="true" --}}
        />

        <x-form.input
            name="instagram"
            label="Instagram"
            value="{{ old('instagram') }}"
            placeholder="@username"
            {{-- required="true" --}}
        />

        <x-form.input
            name="github"
            label="Github"
            value="{{ old('github') }}"
            placeholder="https://github.com/username"
            {{-- required="true" --}}
        />

        <div class="mb-4">
            <label class="custom-label">Status</label>

            <select name="is_active" class="custom-input">
                <option value="1" {{ old('is_active', 1) == 1 ? 'selected' : '' }}>
                    Aktif
                </option>

                <option value="0" {{ old('is_active') == 0 ? 'selected' : '' }}>
                    Nonaktif
                </option>
            </select>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-csa-primary">
                <i class="fas fa-save me-2"></i> Simpan Data
            </button>

            <a href="{{ route('admin.members.index') }}" class="btn-solid-light">
                Batal
            </a>
        </div>
    </form>
</div>

@endsection