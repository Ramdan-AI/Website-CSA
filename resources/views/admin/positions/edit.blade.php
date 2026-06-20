@extends('layouts.admin')

@push('styles')
<style>
.page-header { margin-bottom: 24px; }
.page-title { font-size: 1.5rem; font-weight: 800; color: var(--text-main); margin-bottom: 4px; }
.page-subtitle { color: var(--text-muted); font-size: 0.9rem; margin: 0; }

.form-card {
    background: var(--surface);
    border: 1px solid var(--border-soft);
    border-radius: 12px;
    padding: 32px;
    box-shadow: 0 1px 3px rgba(15, 23, 42, 0.03);
    max-width: 800px;
}

.custom-label { font-size: 0.85rem; font-weight: 700; color: var(--text-main); margin-bottom: 8px; display: block; }
.custom-input, .custom-textarea {
    width: 100%;
    background-color: #F8FAFC;
    border: 1px solid var(--border-soft);
    color: var(--text-main);
    border-radius: 8px;
    padding: 12px 16px;
    font-size: 0.95rem;
    transition: all 0.2s ease;
}
.custom-input:focus, .custom-textarea:focus {
    outline: none;
    background-color: var(--surface);
    border-color: var(--csa-green);
    box-shadow: 0 0 0 3px rgba(69, 184, 75, 0.1);
}
.custom-textarea { min-height: 120px; resize: vertical; }

.form-actions { display: flex; gap: 12px; margin-top: 32px; padding-top: 24px; border-top: 1px solid var(--border-soft); }
.btn-solid-success { background-color: var(--csa-green); color: #ffffff; font-weight: 600; padding: 10px 24px; border-radius: 8px; border: none; transition: 0.2s; cursor: pointer; }
.btn-solid-success:hover { background-color: #3aa03f; color: #ffffff; }
.btn-solid-light { background-color: var(--surface); color: var(--text-muted); font-weight: 600; padding: 10px 24px; border-radius: 8px; border: 1px solid var(--border-hard); text-decoration: none; transition: 0.2s; display: inline-block; }
.btn-solid-light:hover { background-color: #F1F5F9; color: var(--text-main); }
</style>
@endpush

@section('content')

<div class="page-header">
    <h1 class="page-title">Edit Data Posisi</h1>
    <p class="page-subtitle">Perbarui informasi jabatan yang sudah terdaftar.</p>
</div>

<div class="form-card">
    <form action="{{ route('admin.positions.update', $position) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label class="custom-label">Nama Posisi</label>
            <input 
                type="text" 
                name="name" 
                class="custom-input @error('name') is-invalid @enderror" 
                value="{{ old('name', $position->name) }}"
                required>
            @error('name')
                <div class="text-danger mt-1" style="font-size: 0.8rem; font-weight: 500;">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-4">
            <label class="custom-label">Deskripsi Posisi</label>
            <textarea 
                name="description" 
                class="custom-textarea @error('description') is-invalid @enderror"
                required>{{ old('description', $position->description) }}</textarea>
            @error('description')
                <div class="text-danger mt-1" style="font-size: 0.8rem; font-weight: 500;">{{ $message }}</div>
            @enderror
        </div>

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