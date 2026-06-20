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

/* Form Elements */
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
    border-color: var(--csa-blue);
    box-shadow: 0 0 0 4px rgba(54, 100, 175, 0.08);
}

.custom-textarea {
    min-height: 140px;
    resize: vertical;
    line-height: 1.5;
}

/* Error */
.is-invalid {
    border-color: #EF4444;
    background-color: #FEF2F2;
}

.is-invalid:focus {
    border-color: #EF4444;
    box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.08);
}

/* Actions */
.form-actions {
    display: flex;
    gap: 14px;
    margin-top: 36px;
    padding-top: 24px;
    border-top: 1px solid var(--border-soft);
}

.btn-solid-primary {
    background-color: var(--csa-blue);
    color: #ffffff;
    font-weight: 600;
    font-size: 0.95rem;
    padding: 12px 28px;
    border-radius: 10px;
    border: none;
    transition: all 0.2s ease;
    cursor: pointer;
    box-shadow: 0 2px 4px rgba(54, 100, 175, 0.15);
}

.btn-solid-primary:hover {
    background-color: #2a508f;
    transform: translateY(-1px);
    box-shadow: 0 4px 6px rgba(54, 100, 175, 0.2);
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
    <h1 class="page-title">Tambah Posisi Baru</h1>
    <p class="page-subtitle">Registrasikan data posisi atau jabatan baru ke dalam sistem operasional.</p>
</div>

<div class="form-card">
    <form action="{{ route('admin.positions.store') }}" method="POST">
        @csrf

        <div class="mb-4">
            <label class="custom-label">Nama Posisi</label>
            <input 
                type="text"
                name="name"
                class="custom-input @error('name') is-invalid @enderror"
                value="{{ old('name') }}"
                placeholder="Contoh: Ketua Umum"
                required>
            @error('name')
                <div class="text-danger mt-2" style="font-size: 0.85rem; font-weight: 600;">
                    <i class="fas fa-exclamation-circle me-1"></i> {{ $message }}
                </div>
            @enderror
        </div>

        <div class="mb-4">
            <label class="custom-label">
                Deskripsi Posisi
                <span class="text-optional">Opsional</span>
            </label>

            <textarea 
                name="description"
                class="custom-textarea @error('description') is-invalid @enderror"
                placeholder="Tuliskan wewenang dan tanggung jawab singkat dari posisi ini..."
            >{{ old('description') }}</textarea>

            @error('description')
                <div class="text-danger mt-2" style="font-size: 0.85rem; font-weight: 600;">
                    <i class="fas fa-exclamation-circle me-1"></i> {{ $message }}
                </div>
            @enderror
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-solid-primary">
                <i class="fas fa-save me-2"></i> Simpan Data
            </button>

            <a href="{{ route('admin.positions.index') }}" class="btn-solid-light">
                Batal
            </a>
        </div>
    </form>
</div>

@endsection