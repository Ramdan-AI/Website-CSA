@extends('layouts.admin')

@section('content')

<x-page-header 
    title="Tambah Divisi Baru" 
    subtitle="Registrasikan data divisi baru ke dalam sistem operasional." 
/>

<div class="form-card">
    <form action="{{ route('admin.divisions.store') }}" method="POST">
        @csrf

        <x-form.input 
            name="name" 
            label="Nama Divisi" 
            value="{{ old('name') }}"
            placeholder="Contoh: Pemrograman" 
            required="true" 
        />

        <x-form.textarea 
            name="description" 
            label="Deskripsi Divisi" 
            placeholder="Tuliskan tugas dan tanggung jawab singkat dari divisi ini..." 
        />

        <div class="form-actions">
            <button type="submit" class="btn-csa-primary">
                <i class="fas fa-save me-2"></i> Simpan Data
            </button>
            <a href="{{ route('admin.divisions.index') }}" class="btn-solid-light">
                Batal
            </a>
        </div>
    </form>
</div>

@endsection