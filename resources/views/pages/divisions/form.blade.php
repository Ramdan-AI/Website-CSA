@extends('layouts.admin')

@section('content')
    @php
        $title = $isEdit ? 'Edit Divisi' : 'Tambah Divisi';
        $hasImage = $isEdit && !empty($division->image);
        $imageUrl = ($isEdit && $hasImage) ? asset('storage/' . $division->image) : '#';
    @endphp

    <div class="space-y-4 sm:space-y-5">
        <div>
            <a href="{{ route('admin.divisions.index') }}" class="inline-flex items-center gap-1.5 text-[12px] text-[var(--text-muted)] hover:text-[var(--text-primary)] transition-colors">
                <x-admin.icon name="arrow-left" :size="14" /> Kembali ke Divisi
            </a>
            <h1 class="text-[26px] sm:text-[30px] font-bold text-[var(--text-primary)] tracking-[-0.03em] mt-2">{{ $title }}</h1>
            <p class="text-[13px] text-[var(--text-muted)] mt-1">
                {{ $isEdit ? 'Perbarui informasi dan logo divisi.' : 'Tambahkan divisi baru ke dalam sistem operasional CSA.' }}
            </p>
        </div>

        <form action="{{ $isEdit ? route('admin.divisions.update', $division) : route('admin.divisions.store') }}"
            method="POST" enctype="multipart/form-data" class="csa-panel p-5 sm:p-6 space-y-6">
            @csrf
            @if ($isEdit)
                @method('PUT')
            @endif

            <input type="hidden" name="remove_image" id="remove-image" value="0">

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-1">
                    <label class="block text-[13px] font-medium text-[var(--text-secondary)] mb-2">Logo Divisi</label>

                    <div class="w-full aspect-square rounded-2xl border border-dashed border-[var(--border-strong)] flex items-center justify-center overflow-hidden bg-[var(--bg-muted)] cursor-pointer hover:border-[var(--accent)] transition-colors"
                        onclick="document.getElementById('image-input').click()">
                        <img id="image-preview" src="{{ $hasImage ? $imageUrl : '#' }}" alt="Pratinjau logo"
                            class="{{ $hasImage ? 'w-full h-full object-cover' : 'hidden' }}">
                        <div id="image-placeholder" class="{{ $hasImage ? 'hidden' : 'flex' }} flex-col items-center text-[var(--text-muted)]">
                            <x-admin.icon name="image" :size="24" />
                            <span class="text-[12px] font-medium mt-2">Klik untuk upload</span>
                            <span class="text-[11px] mt-0.5">PNG, JPG, WEBP</span>
                        </div>
                    </div>

                    <input type="file" name="image" id="image-input" accept="image/jpeg,image/png,image/jpg,image/webp" class="hidden" onchange="previewImage(event)">

                    @error('image')
                        <p class="text-[12px] text-red-500 mt-2">{{ $message }}</p>
                    @enderror

                    @if ($hasImage)
                        <div id="image-indicator" class="flex items-center gap-2 mt-3">
                            <x-admin.icon name="circle-check" :size="14" class="text-emerald-500" />
                            <span class="text-[12px] text-[var(--text-secondary)]">Logo tersimpan</span>
                            <button type="button" onclick="removeImage()" class="text-[12px] text-red-500 hover:underline ml-auto">Hapus</button>
                        </div>
                    @endif

                    <p class="text-[11px] text-[var(--text-muted)] mt-2">Ukuran maksimal 2MB. Rasio 1:1 disarankan.</p>
                </div>

                <div class="lg:col-span-2 space-y-4">
                    <x-form.input name="name" id="name" label="Nama Divisi" :value="old('name', $division->name ?? '')" placeholder="Masukkan nama divisi" required />

                    <x-form.textarea name="description" id="description" label="Deskripsi" :value="old('description', $division->description ?? '')" placeholder="Deskripsi singkat tentang divisi ini..." rows="4" />

                    <x-form.input name="skills" id="skills" label="Keterampilan yang Diasah"
                        :value="old('skills', $isEdit && !empty($division->skills) ? implode(', ', $division->skills) : '')"
                        placeholder="Canva, Blender, Photoshop, HTML/CSS, Laravel"
                        hint="Pisahkan setiap keterampilan dengan koma." />

                    @if ($isEdit)
                        <label class="flex items-center gap-3 p-3.5 rounded-xl bg-[var(--bg-muted)] cursor-pointer">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" name="is_active" value="1" class="peer sr-only" {{ old('is_active', $division->is_active) ? 'checked' : '' }}>
                            <span class="relative w-10 h-6 rounded-full bg-[var(--border-strong)] after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:w-5 after:h-5 after:rounded-full after:bg-white after:transition-all peer-checked:bg-[var(--accent)] peer-checked:after:translate-x-4"></span>
                            <span>
                                <span class="block text-[13px] font-medium text-[var(--text-primary)]">{{ old('is_active', $division->is_active) ? 'Divisi Aktif' : 'Divisi Nonaktif' }}</span>
                                <span class="block text-[12px] text-[var(--text-muted)] mt-0.5">Nonaktifkan untuk menyembunyikan divisi dari sistem.</span>
                            </span>
                        </label>
                    @endif
                </div>
            </div>

            <div class="flex items-center gap-3 pt-2 border-t border-[var(--border-soft)]">
                <button type="submit" class="csa-btn csa-btn-primary">
                    <x-admin.icon :name="$isEdit ? 'save' : 'plus'" :size="16" />
                    {{ $isEdit ? 'Simpan Perubahan' : 'Tambah Divisi' }}
                </button>
                <a href="{{ route('admin.divisions.index') }}" class="csa-btn csa-btn-ghost">Batal</a>
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
                document.getElementById('image-indicator')?.classList.add('hidden');
                document.getElementById('remove-image').value = '0';
            };
            reader.readAsDataURL(file);
        }

        function removeImage() {
            const preview = document.getElementById('image-preview');
            preview.src = '#';
            preview.classList.add('hidden');
            document.getElementById('image-placeholder').classList.remove('hidden');
            document.getElementById('image-indicator')?.classList.add('hidden');
            document.getElementById('image-input').value = '';
            document.getElementById('remove-image').value = '1';
        }
    </script>
@endpush
