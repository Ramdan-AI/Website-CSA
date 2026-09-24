@extends('layouts.admin')

@php
    $isEdit = isset($document) && $document->exists;
    $title = $isEdit ? 'Edit Dokumen' : 'Tambah Dokumen';
    $subtitle = $isEdit
        ? 'Perbarui informasi dokumen yang telah tersimpan.'
        : 'Unggah dan registrasikan dokumen baru ke dalam sistem.';
    $hasFile = $isEdit && !empty($document->file);
@endphp

@section('content')
<div class="space-y-4 sm:space-y-5 max-w-2xl">
    <div>
        <a href="{{ route('admin.documents.index') }}" class="inline-flex items-center gap-1.5 text-[12px] text-[var(--text-muted)] hover:text-[var(--text-primary)] transition-colors">
            <x-admin.icon name="arrow-left" :size="14" /> Kembali ke Dokumen
        </a>
        <h1 class="text-[26px] sm:text-[30px] font-bold text-[var(--text-primary)] tracking-[-0.03em] mt-2">{{ $title }}</h1>
        <p class="text-[13px] text-[var(--text-muted)] mt-1">{{ $subtitle }}</p>
    </div>

    <form action="{{ $isEdit ? route('admin.documents.update', $document) : route('admin.documents.store') }}" method="POST" enctype="multipart/form-data" class="csa-panel p-5 sm:p-6 space-y-5">
        @csrf
        @if ($isEdit)
            @method('PUT')
        @endif

        <x-form.input name="title" id="title" label="Judul Dokumen" :value="old('title', $document->title ?? '')" placeholder="Contoh: SOP Divisi Programming" required autofocus />

        <div>
            <label class="block text-[13px] font-medium text-[var(--text-secondary)] mb-1.5">
                File Dokumen
                @if (!$isEdit)
                    <span class="text-red-500">*</span>
                @else
                    <span class="text-[var(--text-muted)] font-normal">· Biarkan kosong jika tidak diganti</span>
                @endif
            </label>

            @if ($hasFile)
                <div class="mb-3 flex items-center gap-3 p-3 rounded-xl bg-[var(--bg-muted)]">
                    <span class="w-10 h-10 rounded-xl bg-[var(--bg-card)] text-[var(--text-secondary)] flex items-center justify-center shrink-0">
                        <x-admin.icon name="file-text" :size="18" />
                    </span>
                    <div class="flex-1 min-w-0">
                        <p class="text-[13px] font-medium text-[var(--text-primary)] truncate">{{ basename($document->file) }}</p>
                        <p class="text-[11px] text-[var(--text-muted)]">{{ strtoupper(pathinfo($document->file, PATHINFO_EXTENSION)) }} · Tersimpan</p>
                    </div>
                    <a href="{{ asset('storage/' . $document->file) }}" target="_blank" rel="noopener" class="csa-icon-btn" title="Lihat file" aria-label="Lihat file">
                        <x-admin.icon name="external-link" :size="16" />
                    </a>
                </div>
            @endif

            <div class="w-full h-[120px] rounded-xl border border-dashed border-[var(--border-strong)] flex items-center justify-center bg-[var(--bg-muted)] hover:border-[var(--accent)] transition-colors cursor-pointer"
                onclick="document.getElementById('file-input').click()">
                <div id="file-placeholder" class="flex flex-col items-center text-[var(--text-muted)] {{ $hasFile ? 'hidden' : '' }}">
                    <x-admin.icon name="upload" :size="22" />
                    <span class="text-[12px] font-medium mt-2">Klik untuk upload file</span>
                    <span class="text-[11px] mt-0.5">PDF, DOC, DOCX, XLS, XLSX. Maks 10MB.</span>
                </div>
                <div id="file-selected" class="hidden flex-col items-center">
                    <x-admin.icon name="circle-check" :size="22" class="text-emerald-500" />
                    <span class="text-[12px] font-medium text-[var(--text-primary)] mt-2" id="file-name-display"></span>
                    <span class="text-[11px] text-[var(--text-muted)] mt-0.5" id="file-size-display"></span>
                </div>
            </div>
            <input type="file" name="file" id="file-input" accept=".pdf,.doc,.docx,.xls,.xlsx" class="hidden" onchange="previewFile(event)" {{ $isEdit ? '' : 'required' }}>
            @error('file')<p class="text-[12px] text-red-500 mt-1.5">{{ $message }}</p>@enderror
        </div>

        <x-form.textarea name="description" id="description" label="Deskripsi" :value="old('description', $document->description ?? '')" placeholder="Tuliskan deskripsi singkat dokumen..." rows="3" />

        <div class="flex items-center gap-3 pt-2 border-t border-[var(--border-soft)]">
            <button type="submit" class="csa-btn csa-btn-primary">
                <x-admin.icon :name="$isEdit ? 'save' : 'plus'" :size="16" />
                {{ $isEdit ? 'Simpan Perubahan' : 'Tambah Dokumen' }}
            </button>
            <a href="{{ route('admin.documents.index') }}" class="csa-btn csa-btn-ghost">Batal</a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    function previewFile(event) {
        const file = event.target.files[0];
        if (!file) return;
        document.getElementById('file-placeholder').classList.add('hidden');
        document.getElementById('file-selected').classList.remove('hidden');
        document.getElementById('file-selected').classList.add('flex');
        document.getElementById('file-name-display').textContent = file.name;
        document.getElementById('file-size-display').textContent = (file.size / 1024).toFixed(1) + ' KB';
    }
</script>
@endpush
