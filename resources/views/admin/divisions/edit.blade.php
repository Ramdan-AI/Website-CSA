@extends('layouts.admin')

@push('styles')
<style>
/* Page Header */
.page-header {
    margin-bottom: 32px;
}

.page-title {
    font-size: 1.8rem;
    font-weight: 800;
    color: var(--text-main);
    letter-spacing: -0.02em;
    margin-bottom: 6px;
}

.page-subtitle {
    color: var(--text-muted);
    font-size: 0.95rem;
    font-weight: 500;
    margin: 0;
}

/* Form Card */
.form-card {
    background: var(--surface);
    border: 1px solid var(--border-soft);
    border-radius: 16px;
    padding: 36px;
    box-shadow: 0 1px 3px rgba(15, 23, 42, 0.02);
    max-width: 800px;
}

/* Custom Form Elements */
.custom-label {
    font-size: 0.85rem;
    font-weight: 700;
    color: var(--text-main);
    margin-bottom: 10px;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.text-optional {
    font-size: 0.75rem;
    font-weight: 500;
    color: #94A3B8;
    background: #F8FAFC;
    padding: 2px 8px;
    border-radius: 4px;
    border: 1px solid var(--border-soft);
}

.custom-input, 
.custom-textarea {
    width: 100%;
    background-color: #F8FAFC;
    border: 1px solid var(--border-soft);
    color: var(--text-main);
    border-radius: 10px;
    padding: 14px 16px;
    font-size: 0.95rem;
    transition: all 0.2s ease;
    font-family: 'Inter', sans-serif;
}

.custom-input:focus, 
.custom-textarea:focus {
    outline: none;
    background-color: var(--surface);
    border-color: var(--csa-green);
    box-shadow: 0 0 0 4px rgba(69, 184, 75, 0.08); /* Shadow statis, bukan glow */
}

.custom-textarea {
    min-height: 140px;
    resize: vertical;
    line-height: 1.5;
}

/* Error State */
.is-invalid {
    border-color: #EF4444;
    background-color: #FEF2F2;
}
.is-invalid:focus {
    border-color: #EF4444;
    box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.08);
}

/* Form Actions */
.form-actions {
    display: flex;
    gap: 14px;
    margin-top: 36px;
    padding-top: 24px;
    border-top: 1px solid var(--border-soft);
}

.btn-solid-success {
    background-color: var(--csa-green);
    color: #ffffff;
    font-weight: 600;
    font-size: 0.95rem;
    padding: 12px 28px;
    border-radius: 10px;
    border: none;
    transition: all 0.2s ease;
    cursor: pointer;
    box-shadow: 0 2px 4px rgba(69, 184, 75, 0.15);
}

.btn-solid-success:hover {
    background-color: #3aa03f;
    transform: translateY(-1px);
    box-shadow: 0 4px 6px rgba(69, 184, 75, 0.2);
}

.btn-solid-light {
    background-color: var(--surface);
    color: var(--text-muted);
    font-weight: 600;
    font-size: 0.95rem;
    padding: 12px 28px;
    border-radius: 10px;
    border: 1px solid var(--border-hard);
    text-decoration: none;
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
}

.btn-solid-light:hover {
    background-color: #F8FAFC;
    color: var(--text-main);
    border-color: #94A3B8;
}
</style>
@endpush

@section('content')

<div class="page-header">
    <h1 class="page-title">Edit Data Divisi</h1>
    <p class="page-subtitle">Perbarui informasi dan struktur divisi operasional CSA.</p>
</div>

<div class="form-card">
    <form action="{{ route('admin.divisions.update', $division) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label class="custom-label">Nama Divisi</label>
            <input 
                type="text" 
                name="name" 
                class="custom-input @error('name') is-invalid @enderror" 
                value="{{ old('name', $division->name) }}"
                placeholder="Contoh: Pemrograman"
                required>
            @error('name')
                <div class="text-danger mt-2" style="font-size: 0.85rem; font-weight: 600;"><i class="fas fa-exclamation-circle me-1"></i> {{ $message }}</div>
            @enderror
        </div>

        <div class="mb-4">
            <label class="custom-label">
                Deskripsi Divisi
                <span class="text-optional">Opsional</span>
            </label>
            <textarea 
                name="description" 
                class="custom-textarea @error('description') is-invalid @enderror"
                placeholder="Tuliskan tugas dan tanggung jawab singkat dari divisi ini..."
            >{{ old('description', $division->description) }}</textarea>
            @error('description')
                <div class="text-danger mt-2" style="font-size: 0.85rem; font-weight: 600;"><i class="fas fa-exclamation-circle me-1"></i> {{ $message }}</div>
            @enderror
        </div>

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