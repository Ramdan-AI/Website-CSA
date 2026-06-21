@extends('layouts.admin')

@section('content')

<x-page-header 
    title="Tambah Posisi Baru" 
    subtitle="Registrasikan data posisi atau jabatan baru ke dalam sistem operasional." 
/>

<div class="form-card">
    <form action="{{ route('admin.positions.store') }}" method="POST">
        @csrf

        <x-form.input 
            name="name"
            label="Nama Posisi"
            value="{{ old('name') }}"
            placeholder="Contoh: Ketua Umum"
            required="true"
        />

        <x-form.textarea
            name="description"
            label="Deskripsi Posisi"
            value="{{ old('description') }}"
            placeholder="Tuliskan wewenang dan tanggung jawab singkat dari posisi ini..."
        />

        <div class="form-actions">
            <button type="submit" class="btn-csa-primary">
                <i class="fas fa-save me-2"></i> Simpan Data
            </button>

            <a href="{{ route('admin.positions.index') }}" class="btn-solid-light">
                Batal
            </a>
        </div>
    </form>
</div>

@endsection