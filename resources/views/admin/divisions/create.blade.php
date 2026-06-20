@extends('layouts.admin')

@section('content')

<div class="page-header">
    <h1 class="page-title">Tambah Divisi Baru</h1>
    <p class="page-subtitle">Registrasikan data divisi baru ke dalam sistem operasional.</p>
</div>

<div class="form-card">
    <form action="{{ route('admin.divisions.store') }}" method="POST">
        @csrf

        <div class="mb-4">
            <label class="custom-label">Nama Divisi</label>
            <input 
                type="text" 
                name="name" 
                class="custom-input @error('name') is-invalid @enderror" 
                value="{{ old('name') }}"
                placeholder="Contoh: Programming"
                required>
            @error('name')
                <div class="text-danger mt-2" style="font-size: 0.85rem; font-weight: 600;">
                    <i class="fas fa-exclamation-circle me-1"></i> {{ $message }}
                </div>
            @enderror
        </div>

        <div class="mb-4">
            <label class="custom-label d-flex justify-content-between align-items-center">
                Deskripsi Divisi
                <span style="font-size: 0.75rem; font-weight: 500; color: #94A3B8; background: #F8FAFC; padding: 2px 8px; border-radius: 4px; border: 1px solid var(--border-soft);">
                    Opsional
                </span>
            </label>
            <textarea 
                name="description" 
                class="custom-textarea @error('description') is-invalid @enderror"
                placeholder="Tuliskan tugas dan tanggung jawab singkat dari divisi ini..."
            >{{ old('description') }}</textarea>
            @error('description')
                <div class="text-danger mt-2" style="font-size: 0.85rem; font-weight: 600;">
                    <i class="fas fa-exclamation-circle me-1"></i> {{ $message }}
                </div>
            @enderror
        </div>

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