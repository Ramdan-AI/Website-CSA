@extends('layouts.admin')

@section('content')
<div class="space-y-4 sm:space-y-5 max-w-3xl">
    <div>
        <a href="{{ route('admin.news.index') }}" class="inline-flex items-center gap-1.5 text-[12px] text-[var(--text-muted)] hover:text-[var(--text-primary)] transition-colors">
            <x-admin.icon name="arrow-left" :size="14" /> Kembali ke Berita
        </a>
        <h1 class="text-[26px] sm:text-[30px] font-bold text-[var(--text-primary)] tracking-[-0.03em] mt-2">Tambah Berita</h1>
        <p class="text-[13px] text-[var(--text-muted)] mt-1">Publikasikan berita baru ke dalam sistem konten.</p>
    </div>

    <form action="{{ route('admin.news.store') }}" method="POST" enctype="multipart/form-data" class="csa-panel p-5 sm:p-6 space-y-4">
        @csrf

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <x-form.select name="category_id" id="category_id" label="Kategori" required
                :value="old('category_id')" placeholder="Pilih Kategori"
                :options="collect($categories)->mapWithKeys(fn ($c) => [$c->id => $c->name])->all()" />
            <x-form.select name="status" id="status" label="Status" required
                :value="old('status', 'draft')"
                :options="['draft' => 'Draft', 'published' => 'Published']" />
        </div>

        <x-form.input name="title" id="title" label="Judul" :value="old('title')" placeholder="Judul berita" required autofocus />
        <x-form.textarea name="content" id="content" label="Konten" :value="old('content')" placeholder="Tuliskan isi berita..." rows="10" required />
        <x-form.input name="published_at" id="published_at" type="datetime-local" label="Tanggal Publish" :value="old('published_at')" />

        <div>
            <label class="block text-[13px] font-medium text-[var(--text-secondary)] mb-1.5">Gambar Berita <span class="text-[var(--text-muted)] font-normal">· Maks 8 gambar</span></label>
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

        <div class="flex items-center gap-3 pt-2 border-t border-[var(--border-soft)]">
            <button type="submit" class="csa-btn csa-btn-primary">
                <x-admin.icon name="plus" :size="16" /> Tambah Berita
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
