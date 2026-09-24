@extends('layouts.admin')

@section('content')
<div class="space-y-6 max-w-4xl">
    <div>
        <a href="{{ route('admin.news.index') }}" class="inline-flex items-center gap-1.5 text-[12px] text-[var(--text-muted)] hover:text-[var(--text-primary)] transition-colors">
            <x-admin.icon name="arrow-left" :size="14" /> Kembali ke Berita
        </a>
        <h1 class="text-[26px] sm:text-[30px] font-bold text-[var(--text-primary)] tracking-[-0.03em] mt-2">Edit Berita</h1>
        <p class="text-[13px] text-[var(--text-muted)] mt-1">Perbarui konten berita.</p>
    </div>

    <form action="{{ route('admin.news.update', $news) }}" method="POST" enctype="multipart/form-data"
        class="grid grid-cols-1 lg:grid-cols-[1.6fr_1fr] gap-5 items-start">
        @csrf
        @method('PUT')

        <div class="csa-panel p-5 sm:p-6 space-y-4">
            <x-form.input name="title" id="title" label="Judul" :value="old('title', $news->title)" placeholder="Judul berita" required autofocus />
            <x-form.textarea name="content" id="content" label="Konten" :value="old('content', $news->content)" placeholder="Tuliskan isi berita..." rows="12" required />
        </div>

        <div class="space-y-4 lg:sticky lg:top-20">
            <section class="csa-panel p-5 space-y-4">
                <h3 class="text-[14px] font-semibold text-[var(--text-primary)]">Publikasi</h3>
                <x-form.select name="category_id" id="category_id" label="Kategori" required
                    :value="old('category_id', $news->category_id)" placeholder="Pilih kategori"
                    :options="collect($categories)->mapWithKeys(fn ($c) => [$c->id => $c->name])->all()" />
                <x-form.select name="status" id="status" label="Status" required
                    :value="old('status', $news->status)"
                    :options="['draft' => 'Draf', 'published' => 'Terbit']" />
                <x-form.date name="published_at" id="published_at" :withTime="true" label="Tanggal Terbit"
                    :value="old('published_at', $news->published_at ? $news->published_at->format('Y-m-d\TH:i') : '')" />
            </section>

            <section class="csa-panel p-5">
                <x-form.file-crop name="thumbnail" label="Gambar Utama"
                    :current="$news->thumbnail_url"
                    :aspect="16/9" shape="square" preview-size="w-28 h-16"
                    hint="Ganti untuk memperbarui thumbnail." />
            </section>

            <div class="flex items-center gap-3">
                <button type="submit" class="csa-btn csa-btn-primary flex-1">
                    <x-admin.icon name="save" :size="16" /> Simpan
                </button>
                <a href="{{ route('admin.news.index') }}" class="csa-btn csa-btn-ghost">Batal</a>
            </div>
        </div>
    </form>
</div>
@endsection
