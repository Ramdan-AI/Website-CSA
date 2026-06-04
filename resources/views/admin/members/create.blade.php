@extends('layouts.admin')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">

    <h2>Tambah Member</h2>

    <a
        href="{{ route('admin.members.index') }}"
        class="btn btn-secondary">

        Kembali

    </a>

</div>

@if ($errors->any())

    <div class="alert alert-danger">

        <ul class="mb-0">

            @foreach ($errors->all() as $error)

                <li>{{ $error }}</li>

            @endforeach

        </ul>

    </div>

@endif

<form
    action="{{ route('admin.members.store') }}"
    method="POST">

    @csrf

    <div class="card">

        <div class="card-body">

            {{-- USER --}}

            <div class="mb-3">

                <label class="form-label">

                    User Login (Opsional)

                </label>

                <select
                    name="user_id"
                    class="form-control">

                    <option value="">

                        Tidak Ada User

                    </option>

                    @foreach($users as $user)

                        <option
                            value="{{ $user->id }}"
                            {{ old('user_id') == $user->id ? 'selected' : '' }}>

                            {{ $user->name }} ({{ $user->email }})

                        </option>

                    @endforeach

                </select>

            </div>

            {{-- DIVISION --}}

            <div class="mb-3">

                <label class="form-label">

                    Divisi

                </label>

                <select
                    name="division_id"
                    class="form-control"
                    required>

                    <option value="">

                        Pilih Divisi

                    </option>

                    @foreach($divisions as $division)

                        <option
                            value="{{ $division->id }}"
                            {{ old('division_id') == $division->id ? 'selected' : '' }}>

                            {{ $division->name }}

                        </option>

                    @endforeach

                </select>

            </div>

            {{-- POSITION --}}

            <div class="mb-3">

                <label class="form-label">

                    Posisi

                </label>

                <select
                    name="position_id"
                    class="form-control"
                    required>

                    <option value="">

                        Pilih Posisi

                    </option>

                    @foreach($positions as $position)

                        <option
                            value="{{ $position->id }}"
                            {{ old('position_id') == $position->id ? 'selected' : '' }}>

                            {{ $position->name }}

                        </option>

                    @endforeach

                </select>

            </div>

            {{-- NAME --}}

            <div class="mb-3">

                <label class="form-label">

                    Nama

                </label>

                <input
                    type="text"
                    name="name"
                    class="form-control"
                    value="{{ old('name') }}"
                    required>

            </div>

            {{-- NIM --}}

            <div class="mb-3">

                <label class="form-label">

                    NIM

                </label>

                <input
                    type="text"
                    name="nim"
                    class="form-control"
                    value="{{ old('nim') }}">

            </div>

            {{-- PHONE --}}

            <div class="mb-3">

                <label class="form-label">

                    No. HP

                </label>

                <input
                    type="text"
                    name="phone"
                    class="form-control"
                    value="{{ old('phone') }}">

            </div>

            {{-- BIO --}}

            <div class="mb-3">

                <label class="form-label">

                    Bio

                </label>

                <textarea
                    name="bio"
                    rows="4"
                    class="form-control">{{ old('bio') }}</textarea>

            </div>

            {{-- LINKEDIN --}}

            <div class="mb-3">

                <label class="form-label">

                    LinkedIn

                </label>

                <input
                    type="text"
                    name="linkedin"
                    class="form-control"
                    value="{{ old('linkedin') }}">

            </div>

            {{-- INSTAGRAM --}}

            <div class="mb-3">

                <label class="form-label">

                    Instagram

                </label>

                <input
                    type="text"
                    name="instagram"
                    class="form-control"
                    value="{{ old('instagram') }}">

            </div>

            {{-- GITHUB --}}

            <div class="mb-3">

                <label class="form-label">

                    Github

                </label>

                <input
                    type="text"
                    name="github"
                    class="form-control"
                    value="{{ old('github') }}">

            </div>

            {{-- STATUS --}}

            <div class="mb-3">

                <label class="form-label">

                    Status

                </label>

                <select
                    name="is_active"
                    class="form-control">

                    <option value="1" selected>

                        Aktif

                    </option>

                    <option value="0">

                        Nonaktif

                    </option>

                </select>

            </div>

        </div>

        <div class="card-footer">

            <button
                type="submit"
                class="btn btn-primary">

                Simpan

            </button>

        </div>

    </div>

</form>

@endsection