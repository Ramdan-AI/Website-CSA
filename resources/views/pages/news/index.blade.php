@extends('layouts.admin')

@php
    use Illuminate\Support\Str;
@endphp

@section('content')
<div class="space-y-4 sm:space-y-5">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h1 class="text-[26px] sm:text-[30px] font-bold text-[var(--text-primary)] tracking-[-0.03em]">Berita</h1>
            <p class="text-[13px] text-[var(--text-muted)] mt-1">Kelola berita dan artikel yang tampil di situs.</p>
        </div>
        <a href="{{ route('admin.news.create') }}" class="csa-btn csa-btn-primary">
            <x-admin.icon name="plus" :size="16" /> Tambah Berita
        </a>
    </div>

    <form method="GET" class="csa-panel p-3 sm:p-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-[1.6fr_1fr_1fr_1fr_auto] gap-3">
        <div class="relative">
            <x-admin.icon name="search" :size="16" class="absolute left-3.5 top-1/2 -translate-y-1/2 text-[var(--text-muted)] pointer-events-none" />
            <input type="search" name="search" value="{{ request('search') }}" placeholder="Cari judul atau isi berita..." class="csa-input pl-10" aria-label="Cari berita">
        </div>
        <x-form.select name="category_id" :value="request('category_id')" onchange="this.form.submit()" aria-label="Kategori"
            placeholder="Semua Kategori"
            :options="collect($categories)->mapWithKeys(fn ($c) => [$c->id => $c->name])->all()" />
        <x-form.select name="status" :value="request('status')" onchange="this.form.submit()" aria-label="Status"
            placeholder="Semua Status"
            :options="['published' => 'Terbit', 'draft' => 'Draf']" />
        <x-form.select name="sort" :value="request('sort', 'newest')" onchange="this.form.submit()" aria-label="Urutkan"
            :options="['newest' => 'Terbaru', 'oldest' => 'Terlama', 'title_asc' => 'Judul A-Z', 'title_desc' => 'Judul Z-A']" />
        <a href="{{ route('admin.news.index') }}" class="csa-btn csa-btn-ghost">Reset</a>
    </form>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($news as $item)
            <article class="csa-panel csa-panel-hover overflow-hidden flex flex-col">
                <a href="{{ route('admin.news.show', $item) }}" class="block">
                    @if($item->thumbnail_url)
                        <div class="h-44 w-full bg-[var(--bg-muted)] overflow-hidden">
                            <img src="{{ $item->thumbnail_url }}" alt="{{ $item->title }}" class="w-full h-full object-cover" loading="lazy">
                        </div>
                    @else
                        <div class="h-24 w-full bg-[var(--bg-muted)] flex items-center justify-center text-[var(--text-muted)]">
                            <x-admin.icon name="newspaper" :size="26" />
                        </div>
                    @endif
                    <div class="p-4">
                        <div class="flex items-center justify-between gap-2 mb-1.5">
                            <span class="text-[11px] font-medium text-[var(--accent-bright)]">{{ $item->category?->name ?? 'Tanpa kategori' }}</span>
                            <span class="csa-chip {{ $item->status === 'published' ? 'csa-chip-green' : 'csa-chip-amber' }}">{{ ucfirst($item->status) }}</span>
                        </div>
                        <h2 class="text-[14px] font-semibold text-[var(--text-primary)] line-clamp-2">{{ $item->title }}</h2>
                        <p class="text-[12px] text-[var(--text-muted)] line-clamp-2 mt-2 leading-relaxed">{{ Str::limit(strip_tags($item->content), 120) }}</p>
                        <div class="mt-3.5 pt-3 border-t border-[var(--border-soft)] flex items-center justify-between text-[11px] text-[var(--text-muted)]">
                            <span>{{ $item->author?->name ?? '-' }}</span>
                            <span>{{ $item->published_at?->translatedFormat('d M Y') ?? $item->created_at->translatedFormat('d M Y') }}</span>
                        </div>
                    </div>
                </a>
                <div class="px-4 py-2.5 border-t border-[var(--border-soft)] flex items-center justify-end gap-1 mt-auto">
                    <a href="{{ route('admin.news.edit', $item) }}" class="csa-icon-btn" title="Edit" aria-label="Edit berita">
                        <x-admin.icon name="pen" :size="16" />
                    </a>
                    <button type="button" onclick="openDeleteModal('deleteModal', '{{ route('admin.news.destroy', $item) }}')" class="csa-icon-btn hover:!text-red-500" title="Hapus" aria-label="Hapus berita">
                        <x-admin.icon name="trash-2" :size="16" />
                    </button>
                </div>
            </article>
        @empty
            <div class="col-span-full csa-panel p-12 text-center">
                <x-admin.icon name="newspaper" :size="32" class="mx-auto text-[var(--text-muted)]" />
                <p class="text-[13px] font-medium text-[var(--text-secondary)] mt-3">Tidak ada berita ditemukan</p>
                <p class="text-[12px] text-[var(--text-muted)] mt-1">Coba ubah kriteria pencarian.</p>
            </div>
        @endforelse
    </div>

    @if ($news->hasPages())
        {{ $news->links() }}
    @endif
</div>
@endsection
