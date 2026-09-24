@extends('layouts.admin')

@php
    $isEdit = isset($album);
    $title = $isEdit ? 'Edit Album' : 'Tambah Album';
    $hasCover = $isEdit && $album->cover_image;
    $coverUrl = $hasCover ? asset('storage/' . $album->cover_image) : '#';
@endphp

@section('content')
<div class="space-y-4 sm:space-y-5">
    <div>
        <a href="{{ route('admin.albums.index') }}" class="inline-flex items-center gap-1.5 text-[12px] text-[var(--text-muted)] hover:text-[var(--text-primary)] transition-colors">
            <x-admin.icon name="arrow-left" :size="14" /> Kembali ke Album
        </a>
        <h1 class="text-[26px] sm:text-[30px] font-bold text-[var(--text-primary)] tracking-[-0.03em] mt-2">{{ $title }}</h1>
        <p class="text-[13px] text-[var(--text-muted)] mt-1">
            {{ $isEdit ? 'Perbarui informasi album.' : 'Tambahkan album baru untuk mengelompokkan foto.' }}
        </p>
    </div>

    <form action="{{ $isEdit ? route('admin.albums.update', $album) : route('admin.albums.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
        @csrf
        @if ($isEdit) @method('PUT') @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
            <div class="lg:col-span-1">
                <div class="csa-panel p-5">
                    <label class="block text-[13px] font-medium text-[var(--text-secondary)] mb-2">Cover Album <span class="text-[var(--text-muted)] font-normal">· Opsional</span></label>
                    <div class="w-full aspect-[4/3] rounded-2xl border border-dashed border-[var(--border-strong)] flex items-center justify-center overflow-hidden bg-[var(--bg-muted)] hover:border-[var(--accent)] transition-colors cursor-pointer"
                        onclick="document.getElementById('cover-input').click()">
                        <img id="cover-preview" src="{{ $hasCover ? $coverUrl : '#' }}" alt="Pratinjau cover" class="{{ $hasCover ? 'w-full h-full object-cover' : 'hidden' }}">
                        <div id="cover-placeholder" class="{{ $hasCover ? 'hidden' : 'flex' }} flex-col items-center text-[var(--text-muted)]">
                            <x-admin.icon name="camera" :size="22" />
                            <span class="text-[12px] font-medium mt-2">Cover album</span>
                            <span class="text-[11px] mt-0.5">JPG, PNG, WEBP</span>
                        </div>
                    </div>
                    <input type="file" name="cover_image" id="cover-input" accept="image/jpeg,image/png,image/webp" class="hidden" onchange="previewCover(event)">
                    @error('cover_image')<p class="text-[12px] text-red-500 mt-2">{{ $message }}</p>@enderror
                    <p class="text-[11px] text-[var(--text-muted)] mt-2">Jika kosong, cover diambil dari foto pertama.</p>
                </div>
            </div>

            <div class="lg:col-span-2 space-y-4">
                <section class="csa-panel p-5 space-y-4">
                    <h3 class="text-[14px] font-semibold text-[var(--text-primary)]">Informasi Album</h3>
                    <x-form.input name="title" id="title" label="Nama Album" :value="old('title', $album->title ?? '')" placeholder="Contoh: Mubes 2026, Study Club Week 5" required autofocus />
                    <x-form.textarea name="description" id="description" label="Deskripsi" :value="old('description', $album->description ?? '')" placeholder="Deskripsikan isi album ini..." rows="4" />
                </section>

                @if ($isEdit && $album->galleries->count() > 0)
                    <section class="csa-panel p-5">
                        <h3 class="text-[14px] font-semibold text-[var(--text-primary)] mb-4">Foto dalam Album</h3>
                        <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 gap-2">
                            @foreach ($album->galleries->take(6) as $gallery)
                                <div class="aspect-square rounded-lg overflow-hidden bg-[var(--bg-muted)]">
                                    <img src="{{ asset('storage/' . $gallery->image) }}" alt="{{ $gallery->caption ?? 'Foto' }}" class="w-full h-full object-cover" loading="lazy">
                                </div>
                            @endforeach
                        </div>
                        @if ($album->galleries->count() > 6)
                            <p class="text-[11px] text-[var(--text-muted)] mt-3">+{{ $album->galleries->count() - 6 }} foto lainnya</p>
                        @endif
                    </section>
                @endif
            </div>
        </div>

        <div class="flex items-center gap-3">
            <button type="submit" class="csa-btn csa-btn-primary">
                <x-admin.icon :name="$isEdit ? 'save' : 'plus'" :size="16" />
                {{ $isEdit ? 'Simpan Perubahan' : 'Tambah Album' }}
            </button>
            <a href="{{ route('admin.albums.index') }}" class="csa-btn csa-btn-ghost">Batal</a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    function previewCover(event) {
        const file = event.target.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = (e) => {
            const preview = document.getElementById('cover-preview');
            preview.src = e.target.result;
            preview.classList.remove('hidden');
            document.getElementById('cover-placeholder').classList.add('hidden');
        };
        reader.readAsDataURL(file);
    }
</script>
@endpush
