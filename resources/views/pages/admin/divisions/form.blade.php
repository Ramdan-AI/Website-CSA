@extends('layouts.admin')

@section('content')
    @php
    $title = $isEdit ? 'Edit Divisi' : 'Tambah Divisi';
    $hasImage = $isEdit && !empty($division->image);
    $imageUrl = ($isEdit && $hasImage) ? asset('storage/' . $division->image) : '#';
@endphp

    <div class="space-y-6">

        <div>
            <p class="text-xs font-semibold text-blue-400 uppercase tracking-widest">Data Master</p>
            <h1 class="text-3xl font-bold text-gray-900 mt-1">{{ $title }}</h1>
            <p class="text-sm text-gray-400 mt-1.5 font-medium">
                {{ $isEdit ? 'Perbarui informasi dan logo divisi yang sudah ada.' : 'Tambahkan divisi baru ke dalam sistem operasional CSA.' }}
            </p>
        </div>

        <div
            class="relative bg-white/80 backdrop-blur-xl rounded-[32px] border border-white/60 shadow-[0_8px_32px_rgba(59,130,246,0.08)] overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-blue-400/5 to-purple-400/5 pointer-events-none"></div>

            <div class="relative p-8">
                <form action="{{ $isEdit ? route('admin.divisions.update', $division) : route('admin.divisions.store') }}"
                    method="POST" enctype="multipart/form-data" class="space-y-8">
                    @csrf
                    @if ($isEdit)
                        @method('PUT')
                    @endif

                    <input type="hidden" name="remove_image" id="remove-image" value="0">

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        <div class="lg:col-span-1">
                            <label class="block text-sm font-semibold text-gray-900 mb-3">Logo Divisi</label>

                            <div class="relative group">
                                <div class="w-full aspect-square rounded-2xl border-2 border-dashed border-gray-200 flex items-center justify-center overflow-hidden bg-gray-50/50 hover:border-blue-300 transition-colors cursor-pointer"
                                    onclick="document.getElementById('image-input').click()">
                                    <img id="image-preview" src="{{ $hasImage ? $imageUrl : '#' }}"
                                        class="{{ $hasImage ? 'w-full h-full object-cover' : 'hidden' }}">
                                    <div id="image-placeholder"
                                        class="{{ $hasImage ? 'hidden' : 'flex flex-col items-center' }}">
                                        <div
                                            class="w-14 h-14 rounded-2xl bg-gradient-to-br from-blue-500/10 to-purple-500/10 flex items-center justify-center mb-3">
                                            <i class="fas fa-image text-xl text-blue-400"></i>
                                        </div>
                                        <span class="text-xs text-gray-400 font-medium">Klik untuk upload</span>
                                        <span class="text-[10px] text-gray-300 mt-1">PNG, JPG, WEBP</span>
                                    </div>
                                </div>
                            </div>

                            <input type="file" name="image" id="image-input"
                                accept="image/jpeg,image/png,image/jpg,image/webp" class="hidden"
                                onchange="previewImage(event)">

                            @error('image')
                                <p class="text-xs text-red-500 mt-2">{{ $message }}</p>
                            @enderror

                            @if ($hasImage)
                                <div id="image-indicator" class="flex items-center gap-2 mt-3">
                                    <i class="fas fa-check-circle text-emerald-500 text-xs"></i>
                                    <span class="text-xs text-gray-500 font-medium">Logo tersimpan</span>
                                    <button type="button" onclick="removeImage()"
                                        class="text-xs text-red-500 hover:text-red-600 font-medium ml-auto">
                                        Hapus
                                    </button>
                                </div>
                            @endif

                            <p class="text-[11px] text-gray-400 mt-2 font-medium">Ukuran maksimal 2MB. Rasio 1:1 disarankan.
                            </p>
                        </div>

                        <div class="lg:col-span-2 space-y-6">
                            <div>
                                <label for="name" class="block text-sm font-semibold text-gray-900 mb-2">Nama
                                    Divisi</label>
                                <input type="text" name="name" id="name"
                                    value="{{ old('name', $division->name ?? '') }}"
                                    class="w-full h-12 px-4 rounded-xl border border-gray-200 text-sm focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 transition-all bg-white/50"
                                    placeholder="Masukkan nama divisi" required>
                                @error('name')
                                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="description"
                                    class="block text-sm font-semibold text-gray-900 mb-2">Deskripsi</label>
                                <textarea name="description" id="description" rows="5"
                                    class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 transition-all resize-none bg-white/50"
                                    placeholder="Deskripsi singkat tentang divisi ini...">{{ old('description', $division->description ?? '') }}</textarea>
                                @error('description')
                                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            @if ($isEdit)
                                <div class="flex items-center gap-4 p-4 rounded-2xl bg-gray-50/50">
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="hidden" name="is_active" value="0">
                                        <input type="checkbox" name="is_active" value="1" class="sr-only peer"
                                            {{ old('is_active', $division->is_active) ? 'checked' : '' }}>
                                        <div
                                            class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600">
                                        </div>
                                    </label>
                                    <div>
                                        <p class="text-sm font-semibold text-gray-900">
                                            {{ old('is_active', $division->is_active) ? 'Divisi Aktif' : 'Divisi Nonaktif' }}
                                        </p>
                                        <p class="text-xs text-gray-400">Nonaktifkan untuk menyembunyikan divisi dari sistem
                                        </p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="flex items-center gap-3 pt-2">
                        <button type="submit"
                            class="relative inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white text-sm font-semibold rounded-2xl shadow-[0_4px_20px_rgba(59,130,246,0.3)] hover:shadow-[0_4px_25px_rgba(59,130,246,0.45)] hover:scale-105 transition-all duration-300 overflow-hidden group">
                            <div
                                class="absolute inset-0 bg-white/20 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            </div>
                            <span class="relative flex items-center gap-2">
                                <i class="fas fa-{{ $isEdit ? 'save' : 'plus' }} text-xs"></i>
                                {{ $isEdit ? 'Simpan Perubahan' : 'Tambah Divisi' }}
                            </span>
                        </button>
                        <a href="{{ route('admin.divisions.index') }}"
                            class="px-6 py-3 bg-gray-100 text-gray-700 text-sm font-semibold rounded-2xl hover:bg-gray-200 transition-colors">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function previewImage(event) {
            const file = event.target.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('image-preview').src = e.target.result;
                document.getElementById('image-preview').classList.remove('hidden');
                document.getElementById('image-placeholder').classList.add('hidden');

                const indicator = document.getElementById('image-indicator');
                if (indicator) indicator.classList.add('hidden');

                document.getElementById('remove-image').value = '0';
            };
            reader.readAsDataURL(file);
        }

        function removeImage() {
            document.getElementById('image-preview').src = '#';
            document.getElementById('image-preview').classList.add('hidden');
            document.getElementById('image-placeholder').classList.remove('hidden');
            document.getElementById('image-indicator').classList.add('hidden');
            document.getElementById('image-input').value = '';
            document.getElementById('remove-image').value = '1';
        }
    </script>
@endpush
