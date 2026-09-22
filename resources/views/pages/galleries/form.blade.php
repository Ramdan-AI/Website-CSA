@extends('layouts.admin')

@php
    $isEdit = isset($gallery);
    $title = $isEdit ? 'Edit Foto' : 'Tambah Foto';
    $hasImage = $isEdit && $gallery->image;
    $imageUrl = $hasImage ? asset('storage/' . $gallery->image) : '#';
@endphp

@section('content')
<div class="space-y-4 sm:space-y-5">
    <div>
        <a href="{{ route('admin.galleries.index') }}" class="inline-flex items-center gap-1.5 text-[12px] text-[var(--text-muted)] hover:text-[var(--text-primary)] transition-colors">
            <x-admin.icon name="arrow-left" :size="14" /> Kembali ke Galeri
        </a>
        <h1 class="text-[26px] sm:text-[30px] font-bold text-[var(--text-primary)] tracking-[-0.03em] mt-2">{{ $title }}</h1>
        <p class="text-[13px] text-[var(--text-muted)] mt-1">
            {{ $isEdit ? 'Perbarui informasi foto.' : 'Tambahkan foto baru ke galeri.' }}
        </p>
    </div>

    <form action="{{ $isEdit ? route('admin.galleries.update', $gallery) : route('admin.galleries.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
        @csrf
        @if($isEdit) @method('PUT') @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
            <div class="lg:col-span-1">
                <div class="csa-card p-5">
                    <label class="block text-[13px] font-medium text-[var(--text-secondary)] mb-2">Foto</label>
                    <div class="w-full aspect-square rounded-2xl border border-dashed border-[var(--border-strong)] flex items-center justify-center overflow-hidden bg-[var(--bg-muted)] hover:border-[var(--accent)] transition-colors cursor-pointer"
                        onclick="document.getElementById('image-input').click()">
                        <img id="image-preview" src="{{ $hasImage ? $imageUrl : '#' }}" alt="Pratinjau foto" class="{{ $hasImage ? 'w-full h-full object-cover' : 'hidden' }}">
                        <div id="image-placeholder" class="{{ $hasImage ? 'hidden' : 'flex' }} flex-col items-center text-[var(--text-muted)]">
                            <x-admin.icon name="image" :size="22" />
                            <span class="text-[12px] font-medium mt-2">Klik untuk upload</span>
                            <span class="text-[11px] mt-0.5">JPG, PNG, WEBP</span>
                        </div>
                    </div>
                    <input type="file" name="image" id="image-input" accept="image/jpeg,image/png,image/webp" class="hidden" onchange="previewImage(event)" {{ $isEdit ? '' : 'required' }}>
                    @error('image')<p class="text-[12px] text-red-500 mt-2">{{ $message }}</p>@enderror
                    <p class="text-[11px] text-[var(--text-muted)] mt-2">Maksimal 5MB. Format JPG, PNG, WEBP.</p>
                </div>
            </div>

            <div class="lg:col-span-2">
                <section class="csa-card p-5 space-y-4">
                    <h3 class="text-[14px] font-semibold text-[var(--text-primary)]">Informasi Foto</h3>
                    <div>
                        <label for="album_id" class="block text-[13px] font-medium text-[var(--text-secondary)] mb-1.5">Album</label>
                        <select name="album_id" id="album_id" required class="csa-input">
                            <option value="">Pilih album</option>
                            @foreach($albums as $album)
                                <option value="{{ $album->id }}" @selected(old('album_id', $gallery->album_id ?? '') == $album->id)>{{ $album->title }}</option>
                            @endforeach
                        </select>
                        @error('album_id')<p class="text-[12px] text-red-500 mt-1.5">{{ $message }}</p>@enderror
                    </div>
                    <x-form.input name="caption" id="caption" label="Keterangan" :value="old('caption', $gallery->caption ?? '')" placeholder="Deskripsi singkat foto ini..." />
                </section>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <button type="submit" class="csa-btn csa-btn-primary">
                <x-admin.icon :name="$isEdit ? 'save' : 'plus'" :size="16" />
                {{ $isEdit ? 'Simpan Perubahan' : 'Tambah Foto' }}
            </button>
            <a href="{{ route('admin.galleries.index') }}" class="csa-btn csa-btn-ghost">Batal</a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    function previewImage(event) {
        const file = event.target.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = (e) => {
            const preview = document.getElementById('image-preview');
            preview.src = e.target.result;
            preview.classList.remove('hidden');
            document.getElementById('image-placeholder').classList.add('hidden');
        };
        reader.readAsDataURL(file);
    }
</script>
@endpush
