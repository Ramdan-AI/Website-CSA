@extends('layouts.admin')

@section('content')

<x-page-header 
    title="Edit Data Posisi" 
    subtitle="Perbarui informasi jabatan yang sudah terdaftar." 
/>

<div class="form-card">
    <form action="{{ route('admin.positions.update', $position) }}" method="POST">
        @csrf
        @method('PUT')

        <x-form.input 
            name="name"
            label="Nama Posisi"
            :value="$position->name"
            placeholder="Contoh: Ketua Umum"
            required="true"
        />

        <x-form.textarea
            name="description"
            label="Deskripsi Posisi"
            :value="$position->description"
            placeholder="Tuliskan wewenang dan tanggung jawab posisi ini..."
        />

        <div class="form-actions">
            <button type="submit" class="btn-solid-success">
                <i class="fas fa-check me-2"></i> Perbarui Data
            </button>

            <a href="{{ route('admin.positions.index') }}" class="btn-solid-light">
                Batal
            </a>
        </div>
    </form>
</div>

@endsection