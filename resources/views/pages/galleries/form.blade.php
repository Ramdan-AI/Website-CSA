{{-- resources/views/pages/admin/galleries/form.blade.php --}}
@extends('layouts.admin')

@section('content')

@php
    $isEdit = isset($gallery);
    $title = $isEdit ? 'Edit Foto' : 'Tambah Foto';
    $hasImage = $isEdit && $gallery->image;
    $imageUrl = $hasImage ? asset('storage/' . $gallery->image) : '#';
@endphp

<div class="space-y-6">
    
    <div>
        <p class="text-xs font-semibold text-blue-400 uppercase tracking-widest">Media</p>
        <h1 class="text-3xl font-bold text-gray-900 mt-1">{{ $title }}</h1>
        <p class="text-sm text-gray-400 mt-1.5 font-medium">
            {{ $isEdit ? 'Perbarui informasi foto.' : 'Tambahkan foto baru ke galeri.' }}
        </p>
    </div>

    <div class="relative bg-white/70 backdrop-blur-xl rounded-[30px] border border-white/50 shadow-[0_2px_16px_-4px_rgba(59,130,246,0.08)] overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-blue-400/5 to-cyan-400/5 pointer-events-none"></div>
        
        <div class="relative p-6">
            <form action="{{ $isEdit ? route('admin.galleries.update', $gallery) : route('admin.galleries.store') }}" 
                  method="POST" 
                  enctype="multipart/form-data" 
                  class="space-y-6">
                @csrf
                @if($isEdit) @method('PUT') @endif

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div class="lg:col-span-1">
                        <label class="block text-xs font-semibold text-gray-500 mb-2">
                            Foto <span class="text-red-400">*</span>
                        </label>
                        
                        <div class="relative">
                            <div class="w-full aspect-square rounded-[24px] border-2 border-dashed border-gray-200/80 flex items-center justify-center overflow-hidden bg-gray-50/30 hover:border-blue-300 transition-colors cursor-pointer"
                                 onclick="document.getElementById('image-input').click()">
                                <img id="image-preview" 
                                     src="{{ $hasImage ? $imageUrl : '#' }}" 
                                     class="{{ $hasImage ? 'w-full h-full object-cover' : 'hidden' }}">
                                <div id="image-placeholder" class="{{ $hasImage ? 'hidden' : 'flex flex-col items-center' }}">
                                    <div class="w-12 h-12 rounded-full bg-blue-50 flex items-center justify-center mb-3 shadow-[0_2px_8px_-2px_rgba(59,130,246,0.1)]">
                                        <i class="fas fa-image text-lg text-blue-400"></i>
                                    </div>
                                    <span class="text-xs text-gray-400 font-medium">Klik untuk upload</span>
                                    <span class="text-[10px] text-gray-300 mt-1">JPG, PNG, WEBP</span>
                                </div>
                            </div>
                        </div>
                        
                        <input type="file" name="image" id="image-input" accept="image/jpeg,image/png,image/webp" 
                               class="hidden" onchange="previewImage(event)" {{ $isEdit ? '' : 'required' }}>
                        
                        @error('image')
                            <p class="text-xs text-red-500 mt-2">{{ $message }}</p>
                        @enderror

                        <p class="text-[11px] text-gray-400 mt-2 font-medium">Maksimal 5MB. Format JPG, PNG, WEBP.</p>
                    </div>

                    <div class="lg:col-span-2 space-y-5">
                        <div class="bg-gray-50/40 rounded-[24px] p-5">
                            <h3 class="text-[13px] font-semibold text-gray-900 mb-4 flex items-center gap-2">
                                <span class="w-1.5 h-5 rounded-full bg-blue-500"></span>
                                Informasi Foto
                            </h3>
                            
                            <div class="space-y-4">
                                <div>
                                    <label for="album_id" class="block text-[11px] font-semibold text-gray-500 mb-1.5">
                                        Album <span class="text-red-400">*</span>
                                    </label>
                                    <select name="album_id" id="album_id" required
                                            class="w-full h-[44px] px-4 rounded-[14px] border border-gray-200/80 text-[13px] focus:outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-400/15 transition-all bg-white/60">
                                        <option value="">Pilih album</option>
                                        @foreach($albums as $album)
                                            <option value="{{ $album->id }}" {{ old('album_id', $gallery->album_id ?? '') == $album->id ? 'selected' : '' }}>
                                                {{ $album->title }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('album_id')
                                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="caption" class="block text-[11px] font-semibold text-gray-500 mb-1.5">
                                        Keterangan <span class="text-gray-400 font-normal">(opsional)</span>
                                    </label>
                                    <input type="text" name="caption" id="caption" 
                                           value="{{ old('caption', $gallery->caption ?? '') }}"
                                           class="w-full h-[44px] px-4 rounded-[14px] border border-gray-200/80 text-[13px] focus:outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-400/15 transition-all bg-white/60"
                                           placeholder="Deskripsi singkat foto ini...">
                                    @error('caption')
                                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button type="submit" class="relative inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-blue-500 to-blue-600 text-white text-[13px] font-semibold rounded-[18px] shadow-[0_4px_16px_-4px_rgba(59,130,246,0.3)] hover:shadow-[0_8px_24px_-4px_rgba(59,130,246,0.45)] hover:scale-105 transition-all duration-300 overflow-hidden group">
                        <div class="absolute inset-0 bg-white/20 rounded-[18px] opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        <span class="relative flex items-center gap-2">
                            <i class="fas fa-{{ $isEdit ? 'save' : 'plus' }} text-[10px]"></i>
                            {{ $isEdit ? 'Simpan Perubahan' : 'Tambah Foto' }}
                        </span>
                    </button>
                    <a href="{{ route('admin.galleries.index') }}" 
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
function previewImage(event) {
    const file = event.target.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = function(e) {
        document.getElementById('image-preview').src = e.target.result;
        document.getElementById('image-preview').classList.remove('hidden');
        document.getElementById('image-placeholder').classList.add('hidden');
    };
    reader.readAsDataURL(file);
}
</script>
@endpush