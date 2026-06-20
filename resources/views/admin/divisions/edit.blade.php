@extends('layouts.admin')

@section('content')

<x-page-header 
    title="Edit Data Divisi" 
    subtitle="Perbarui informasi dan struktur divisi operasional CSA." 
/>

<div class="form-card">
    <form action="{{ route('admin.divisions.update', $division) }}" method="POST">
        @csrf
        @method('PUT')

        <x-form.input 
            name="name" 
            label="Nama Divisi" 
            :value="$division->name" 
            placeholder="Contoh: Pemrograman" 
            required="true" 
        />

        <x-form.textarea 
            name="description" 
            label="Deskripsi Divisi" 
            :value="$division->description" 
            placeholder="Tuliskan tugas dan tanggung jawab singkat dari divisi ini..." 
        />

        <div class="form-actions">
            <button type="submit" class="btn-solid-success">
                <i class="fas fa-check me-2"></i> Perbarui Data
            </button>
            <a href="{{ route('admin.divisions.index') }}" class="btn-solid-light">
                Batal
            </a>
        </div>
    </form>
</div>

@endsection