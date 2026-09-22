@extends('layouts.admin')

@section('content')
<div class="space-y-4 sm:space-y-5 max-w-3xl">
    <div>
        <a href="{{ route('admin.news.index') }}" class="inline-flex items-center gap-1.5 text-[12px] text-[var(--text-muted)] hover:text-[var(--text-primary)] transition-colors">
            <x-admin.icon name="arrow-left" :size="14" /> Kembali ke Berita
        </a>
        <h1 class="text-[26px] sm:text-[30px] font-bold text-[var(--text-primary)] tracking-[-0.03em] mt-2">Edit Berita</h1>
        <p class="text-[13px] text-[var(--text-muted)] mt-1">Perbarui konten berita yang telah terpublikasi.</p>
    </div>

    <form action="{{ route('admin.news.update', $news) }}" method="POST" enctype="multipart/form-data" class="csa-card p-5 sm:p-6 space-y-4">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label for="category_id" class="block text-[13px] font-medium text-[var(--text-secondary)] mb-1.5">Kategori</label>
                <select name="category_id" id="category_id" required class="csa-input">
                    <option value="">Pilih Kategori</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" @selected(old('category_id', $news->category_id) == $category->id)>{{ $category->name }}</option>
                    @endforeach
                </select>
                @error('category_id')<p class="text-[12px] text-red-500 mt-1.5">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="status" class="block text-[13px] font-medium text-[var(--text-secondary)] mb-1.5">Status</label>
                <select name="status" id="status" required class="csa-input">
                    <option value="draft" @selected(old('status', $news->status) == 'draft')>Draft</option>
                    <option value="published" @selected(old('status', $news->status) == 'published')>Published</option>
                </select>
                @error('status')<p class="text-[12px] text-red-500 mt-1.5">{{ $message }}</p>@enderror
            </div>
        </div>

        <x-form.input name="title" id="title" label="Judul" :value="old('title', $news->title)" placeholder="Judul berita" required autofocus />
        <x-form.textarea name="content" id="content" label="Konten" :value="old('content', $news->content)" placeholder="Tuliskan isi berita..." rows="10" required />
        <x-form.input name="published_at" id="published_at" type="datetime-local" label="Tanggal Publish" :value="old('published_at', $news->published_at ? $news->published_at->format('Y-m-d\TH:i') : '')" />

        <div>
            <label class="block text-[13px] font-medium text-[var(--text-secondary)] mb-1.5">Upload Gambar Baru <span class="text-[var(--text-muted)] font-normal">· Opsional</span></label>
            <div class="border border-dashed border-[var(--border-strong)] rounded-xl p-6 flex flex-col items-center justify-center hover:border-[var(--accent)] transition-colors cursor-pointer text-[var(--text-muted)]"
                onclick="document.getElementById('news-images-input').click()">
                <x-admin.icon name="upload" :size="22" />
                <p class="text-[13px] font-medium mt-2">Klik untuk upload gambar</p>
                <p class="text-[11px] mt-0.5">JPG, PNG, WEBP. Maks 2MB per file, total 8 file.</p>
            </div>
            <input type="file" name="images[]" id="news-images-input" accept="image/jpeg,image/png,image/webp" multiple class="hidden" onchange="previewNewsImages(event)">
            @error('images')<p class="text-[12px] text-red-500 mt-1.5">{{ $message }}</p>@enderror
            <div id="news-images-preview" class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-6 gap-3 hidden mt-3"></div>
        </div>

        @if($news->images)
            <div>
                <label class="block text-[13px] font-medium text-[var(--text-secondary)] mb-2">Gambar Saat Ini <span class="text-[var(--text-muted)] font-normal">· Klik untuk hapus</span></label>
                <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-6 gap-3">
                    @foreach($news->images as $path)
                        <div class="relative aspect-square rounded-lg overflow-hidden bg-[var(--bg-muted)] group">
                            <img src="{{ asset('storage/' . $path) }}" alt="Gambar berita" class="w-full h-full object-cover">
                            <input type="checkbox" name="removed_images[]" value="{{ $path }}" class="hidden" checked>
                            <button type="button" onclick="this.previousElementSibling.remove(); this.parentElement.remove();" class="absolute top-1 right-1 csa-icon-btn !w-7 !h-7 !bg-red-500 !text-white opacity-0 group-hover:opacity-100 transition-opacity" aria-label="Hapus gambar">
                                <x-admin.icon name="x" :size="14" />
                            </button>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="flex items-center gap-3 pt-2 border-t border-[var(--border-soft)]">
            <button type="submit" class="csa-btn csa-btn-primary">
                <x-admin.icon name="save" :size="16" /> Simpan Perubahan
            </button>
            <a href="{{ route('admin.news.index') }}" class="csa-btn csa-btn-ghost">Batal</a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    function previewNewsImages(event) {
        const files = event.target.files;
        if (!files.length) return;

        const container = document.getElementById('news-images-preview');
        container.classList.remove('hidden');
        container.innerHTML = '';

        Array.from(files).forEach((file) => {
            if (!file.type.match('image.*')) return;
            const reader = new FileReader();
            reader.onload = (e) => {
                const div = document.createElement('div');
                div.className = 'relative aspect-square rounded-lg overflow-hidden bg-[var(--bg-muted)]';
                div.innerHTML = `<img src="${e.target.result}" alt="Pratinjau" class="w-full h-full object-cover">`;
                container.appendChild(div);
            };
            reader.readAsDataURL(file);
        });
    }
</script>
@endpush
