{{-- resources/views/pages/admin/albums/form.blade.php --}}
@extends('layouts.admin')

@section('content')

    @php
        $isEdit = isset($album);
        $title = $isEdit ? 'Edit Album' : 'Tambah Album';
        $hasCover = $isEdit && $album->cover_image;
        $coverUrl = $hasCover ? asset('storage/' . $album->cover_image) : '#';
    @endphp

    <div class="space-y-6">

        <div>
            <p class="text-xs font-semibold text-blue-400 uppercase tracking-widest">Media</p>
            <h1 class="text-3xl font-bold text-gray-900 mt-1">{{ $title }}</h1>
            <p class="text-sm text-gray-400 mt-1.5 font-medium">
                {{ $isEdit ? 'Perbarui informasi album.' : 'Tambahkan album baru untuk mengelompokkan foto.' }}
            </p>
        </div>

        <div
            class="relative bg-white/70 backdrop-blur-xl rounded-[30px] border border-white/50 shadow-[0_2px_16px_-4px_rgba(59,130,246,0.08)] overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-blue-400/5 to-cyan-400/5 pointer-events-none"></div>

            <div class="relative p-6">
                <form action="{{ $isEdit ? route('admin.albums.update', $album) : route('admin.albums.store') }}"
                    method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @if ($isEdit)
                        @method('PUT')
                    @endif

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <div class="lg:col-span-1">
                            <label class="block text-xs font-semibold text-gray-500 mb-2">
                                Cover Album <span class="text-gray-400 font-normal">(opsional)</span>
                            </label>

                            <div class="relative">
                                <div class="w-full aspect-[4/3] rounded-[24px] border-2 border-dashed border-gray-200/80 flex items-center justify-center overflow-hidden bg-gray-50/30 hover:border-blue-300 transition-colors cursor-pointer"
                                    onclick="document.getElementById('cover-input').click()">
                                    <img id="cover-preview" src="{{ $hasCover ? $coverUrl : '#' }}"
                                        class="{{ $hasCover ? 'w-full h-full object-cover' : 'hidden' }}">
                                    <div id="cover-placeholder"
                                        class="{{ $hasCover ? 'hidden' : 'flex flex-col items-center' }}">
                                        <div
                                            class="w-12 h-12 rounded-full bg-blue-50 flex items-center justify-center mb-3 shadow-[0_2px_8px_-2px_rgba(59,130,246,0.1)]">
                                            <i class="fas fa-camera text-lg text-blue-400"></i>
                                        </div>
                                        <span class="text-xs text-gray-400 font-medium">Cover album</span>
                                        <span class="text-[10px] text-gray-300 mt-1">JPG, PNG, WEBP</span>
                                    </div>
                                </div>
                            </div>

                            <input type="file" name="cover_image" id="cover-input"
                                accept="image/jpeg,image/png,image/webp" class="hidden" onchange="previewCover(event)">

                            @error('cover_image')
                                <p class="text-xs text-red-500 mt-2">{{ $message }}</p>
                            @enderror

                            <p class="text-[11px] text-gray-400 mt-2 font-medium">Jika tidak diisi, cover diambil dari foto
                                pertama.</p>
                        </div>

                        <div class="lg:col-span-2 space-y-5">
                            <div class="bg-gray-50/40 rounded-[24px] p-5">
                                <h3 class="text-[13px] font-semibold text-gray-900 mb-4 flex items-center gap-2">
                                    <span class="w-1.5 h-5 rounded-full bg-blue-500"></span>
                                    Informasi Album
                                </h3>

                                <div class="space-y-4">
                                    <div>
                                        <label for="title" class="block text-[11px] font-semibold text-gray-500 mb-1.5">
                                            Nama Album <span class="text-red-400">*</span>
                                        </label>
                                        <input type="text" name="title" id="title"
                                            value="{{ old('title', $album->title ?? '') }}"
                                            class="w-full h-[44px] px-4 rounded-[14px] border border-gray-200/80 text-[13px] focus:outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-400/15 transition-all bg-white/60"
                                            placeholder="Contoh: Mubes 2026, Study Club Week 5" required autofocus>
                                        @error('title')
                                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="description"
                                            class="block text-[11px] font-semibold text-gray-500 mb-1.5">
                                            Deskripsi <span class="text-gray-400 font-normal">(opsional)</span>
                                        </label>
                                        <textarea name="description" id="description" rows="4"
                                            class="w-full px-4 py-3 rounded-[14px] border border-gray-200/80 text-[13px] focus:outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-400/15 transition-all resize-none bg-white/60"
                                            placeholder="Deskripsikan isi album ini...">{{ old('description', $album->description ?? '') }}</textarea>
                                        @error('description')
                                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            @if ($isEdit && $album->galleries->count() > 0)
                                <div class="bg-gray-50/40 rounded-[24px] p-5">
                                    <h3 class="text-[13px] font-semibold text-gray-900 mb-4 flex items-center gap-2">
                                        <span class="w-1.5 h-5 rounded-full bg-blue-500"></span>
                                        Foto dalam Album
                                    </h3>
                                    <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 gap-2">
                                        @foreach ($album->galleries->take(6) as $gallery)
                                            <div class="aspect-square rounded-lg overflow-hidden bg-gray-100">
                                                <img src="{{ asset('storage/' . $gallery->image) }}"
                                                    alt="{{ $gallery->caption ?? 'Foto' }}"
                                                    class="w-full h-full object-cover" loading="lazy">
                                            </div>
                                        @endforeach
                                    </div>
                                    @if ($album->galleries->count() > 6)
                                        <p class="text-[11px] text-gray-400 mt-3">+{{ $album->galleries->count() - 6 }}
                                            foto lainnya</p>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="flex items-center gap-3 pt-2">
                        <button type="submit"
                            class="relative inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-blue-500 to-blue-600 text-white text-[13px] font-semibold rounded-[18px] shadow-[0_4px_16px_-4px_rgba(59,130,246,0.3)] hover:shadow-[0_8px_24px_-4px_rgba(59,130,246,0.45)] hover:scale-105 transition-all duration-300 overflow-hidden group">
                            <div
                                class="absolute inset-0 bg-white/20 rounded-[18px] opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            </div>
                            <span class="relative flex items-center gap-2">
                                <i class="fas fa-{{ $isEdit ? 'save' : 'plus' }} text-[10px]"></i>
                                {{ $isEdit ? 'Simpan Perubahan' : 'Tambah Album' }}
                            </span>
                        </button>
                        <a href="{{ route('admin.albums.index') }}"
                            class="px-5 py-2.5 bg-gray-100 text-gray-700 text-[13px] font-semibold rounded-[18px] hover:bg-gray-200 transition-colors">
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
        function previewCover(event) {
            const file = event.target.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('cover-preview').src = e.target.result;
                document.getElementById('cover-preview').classList.remove('hidden');
                document.getElementById('cover-placeholder').classList.add('hidden');
            };
            reader.readAsDataURL(file);
        }
    </script>
@endpush
