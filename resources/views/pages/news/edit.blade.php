@extends('layouts.admin')

@section('content')
<div class="space-y-6">

    <div>
        <p class="text-xs font-semibold text-teal-400 uppercase tracking-widest">Publikasi</p>
        <h1 class="text-3xl font-bold text-gray-900 mt-1">Edit Berita</h1>
        <p class="text-sm text-gray-400 mt-1.5 font-medium">Perbarui konten berita yang telah terpublikasi.</p>
    </div>

    <div class="relative bg-white/70 backdrop-blur-xl rounded-[30px] border border-white/50 shadow-[0_2px_16px_-4px_rgba(20,184,166,0.08)] overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-teal-400/5 to-cyan-400/5 pointer-events-none"></div>

        <div class="relative p-6">
            <form action="{{ route('admin.news.update', $news) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="bg-gray-50/40 rounded-[24px] p-5">
                    <h3 class="text-[13px] font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <span class="w-1.5 h-5 rounded-full bg-teal-500"></span>
                        Informasi Berita
                    </h3>

                    <div class="space-y-4">
                        <div>
                            <label for="category_id" class="block text-[11px] font-semibold text-gray-500 mb-1.5">
                                Kategori <span class="text-red-400">*</span>
                            </label>
                            <select name="category_id" id="category_id" required
                                    class="w-full h-[44px] px-4 rounded-[14px] border border-gray-200/80 text-[13px] focus:outline-none focus:border-teal-400 focus:ring-2 focus:ring-teal-400/15 transition-all bg-white/60">
                                <option value="">Pilih Kategori</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $news->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="title" class="block text-[11px] font-semibold text-gray-500 mb-1.5">
                                Judul <span class="text-red-400">*</span>
                            </label>
                            <input type="text" name="title" id="title"
                                   value="{{ old('title', $news->title) }}"
                                   class="w-full h-[44px] px-4 rounded-[14px] border border-gray-200/80 text-[13px] focus:outline-none focus:border-teal-400 focus:ring-2 focus:ring-teal-400/15 transition-all bg-white/60"
                                   placeholder="Judul berita" required autofocus>
                            @error('title')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="content" class="block text-[11px] font-semibold text-gray-500 mb-1.5">
                                Konten <span class="text-red-400">*</span>
                            </label>
                            <textarea name="content" id="content" rows="10"
                                      class="w-full px-4 py-3 rounded-[14px] border border-gray-200/80 text-[13px] focus:outline-none focus:border-teal-400 focus:ring-2 focus:ring-teal-400/15 transition-all resize-y bg-white/60"
                                      placeholder="Tuliskan isi berita..." required>{{ old('content', $news->content) }}</textarea>
                            @error('content')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="status" class="block text-[11px] font-semibold text-gray-500 mb-1.5">
                                    Status <span class="text-red-400">*</span>
                                </label>
                                <select name="status" id="status" required
                                        class="w-full h-[44px] px-4 rounded-[14px] border border-gray-200/80 text-[13px] focus:outline-none focus:border-teal-400 focus:ring-2 focus:ring-teal-400/15 transition-all bg-white/60">
                                    <option value="draft" {{ old('status', $news->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="published" {{ old('status', $news->status) == 'published' ? 'selected' : '' }}>Published</option>
                                </select>
                                @error('status')
                                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="published_at" class="block text-[11px] font-semibold text-gray-500 mb-1.5">
                                    Tanggal Publish <span class="text-gray-400 font-normal">(opsional)</span>
                                </label>
                                <input type="datetime-local" name="published_at" id="published_at"
                                       value="{{ old('published_at', $news->published_at ? $news->published_at->format('Y-m-d\TH:i') : '') }}"
                                       class="w-full h-[44px] px-4 rounded-[14px] border border-gray-200/80 text-[13px] focus:outline-none focus:border-teal-400 focus:ring-2 focus:ring-teal-400/15 transition-all bg-white/60">
                                @error('published_at')
                                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50/40 rounded-[24px] p-5">
                    <h3 class="text-[13px] font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <span class="w-1.5 h-5 rounded-full bg-teal-500"></span>
                        Gambar Berita (Maks 8 gambar)
                    </h3>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-[11px] font-semibold text-gray-500 mb-1.5">
                                Upload Gambar Baru <span class="text-gray-400 font-normal">(opsional)</span>
                            </label>
                            <div class="relative">
                                <div class="border-2 border-dashed border-gray-200/80 rounded-[14px] p-6 flex flex-col items-center justify-center hover:border-teal-300 transition-colors cursor-pointer"
                                     onclick="document.getElementById('news-images-input').click()">
                                    <i class="fas fa-cloud-upload-alt text-2xl text-gray-400 mb-2"></i>
                                    <p class="text-sm text-gray-500">Klik atau drag & drop gambar</p>
                                    <p class="text-xs text-gray-400 mt-1">JPG, PNG, WEBP. Maks 2MB per file, total 8 file.</p>
                                </div>
                                <input type="file" name="images[]" id="news-images-input" accept="image/jpeg,image/png,image/webp" multiple class="hidden" onchange="previewNewsImages(event)">
                            </div>
                            @error('images')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        @if($news->images)
                            <div>
                                <label class="block text-[11px] font-semibold text-gray-500 mb-2">
                                    Gambar Saat Ini (klik untuk hapus)
                                </label>
                                <div id="existing-news-images" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3">
                                    @foreach($news->images as $path)
                                        <div class="relative aspect-square bg-white border border-gray-200 rounded-lg overflow-hidden">
                                            <img src="{{ asset('storage/' . $path) }}" alt="News Image" class="w-full h-full object-cover">
                                            <input type="checkbox" name="removed_images[]" value="{{ $path }}" class="hidden" checked>
                                            <button type="button" onclick="this.previousElementSibling.remove(); this.parentElement.remove();" class="absolute top-1 right-1 w-6 h-6 bg-red-500 text-white rounded-full text-xs hover:bg-red-600">×</button>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <div id="news-images-preview" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3 hidden">
                    </div>
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button type="submit"
                            class="relative inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-teal-500 to-teal-600 text-white text-[13px] font-semibold rounded-[18px] shadow-[0_4px_16px_-4px_rgba(20,184,166,0.3)] hover:shadow-[0_8px_24px_-4px_rgba(20,184,166,0.45)] hover:scale-105 transition-all duration-300 overflow-hidden group">
                        <div class="absolute inset-0 bg-white/20 rounded-[18px] opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        <span class="relative flex items-center gap-2">
                            <i class="fas fa-save text-[10px]"></i>
                            Simpan Perubahan
                        </span>
                    </button>
                    <a href="{{ route('admin.news.index') }}"
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
function previewNewsImages(event) {
    const files = event.target.files;
    if (!files.length) return;

    const container = document.getElementById('news-images-preview');
    container.classList.remove('hidden');

    Array.from(files).forEach((file, index) => {
        if (!file.type.match('image.*')) return;

        const reader = new FileReader();
        reader.onload = function(e) {
            const div = document.createElement('div');
            div.className = 'relative aspect-square bg-white border border-gray-200 rounded-lg overflow-hidden';
            div.innerHTML = `
                <img src="${e.target.result}" alt="Preview ${index}" class="w-full h-full object-cover">
                <button type="button" onclick="this.parentElement.remove(); checkEmptyPreview();" class="absolute top-1 right-1 w-6 h-6 bg-red-500 text-white rounded-full text-xs hover:bg-red-600">×</button>
            `;
            container.appendChild(div);
        };
        reader.readAsDataURL(file);
    });
}

function checkEmptyPreview() {
    const container = document.getElementById('news-images-preview');
    if (!container.children.length) {
        container.classList.add('hidden');
    }
}
</script>
@endpush
