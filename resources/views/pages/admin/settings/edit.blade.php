@extends('layouts.admin')

@section('content')

<x-page-header
    title="System Settings"
    subtitle="Kelola konfigurasi utama website dan informasi organisasi."
/>

<div class="form-card">
    <form action="{{ route('admin.settings.update') }}" method="POST">
        @csrf
        @method('PUT')

        <x-form.input
            name="site_name"
            label="Nama Website"
            value="{{ old('site_name', $setting->site_name) }}"
            placeholder="Contoh: CSA Organization"
            required="true"
        />

        <x-form.input
            name="email"
            label="Email"
            type="email"
            value="{{ old('email', $setting->email) }}"
            placeholder="admin@example.com"
            required="true"
        />

        <x-form.input
            name="phone"
            label="Nomor Telepon"
            value="{{ old('phone', $setting->phone) }}"
            placeholder="08xxxxxxxxxx"
        />

        <x-form.textarea
            name="address"
            label="Alamat"
            rows="3"
            placeholder="Masukkan alamat lengkap..."
        >{{ old('address', $setting->address) }}</x-form.textarea>

        <x-form.input
            name="instagram"
            label="Instagram"
            value="{{ old('instagram', $setting->instagram) }}"
            placeholder="https://instagram.com/..."
        />

        <x-form.input
            name="youtube"
            label="YouTube"
            value="{{ old('youtube', $setting->youtube) }}"
            placeholder="https://youtube.com/..."
        />

        <x-form.input
            name="linkedin"
            label="LinkedIn"
            value="{{ old('linkedin', $setting->linkedin) }}"
            placeholder="https://linkedin.com/company/..."
        />

        <x-form.textarea
            name="about"
            label="Tentang Organisasi"
            rows="6"
            placeholder="Tuliskan deskripsi organisasi..."
        >{{ old('about', $setting->about) }}</x-form.textarea>

        <div class="form-actions">
            <button type="submit" class="btn-csa-primary">
                <i class="fas fa-save me-2"></i> Simpan Pengaturan
            </button>
        </div>
    </form>
</div>

@endsection