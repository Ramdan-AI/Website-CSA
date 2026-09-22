@extends('layouts.admin')

@section('content')
<div class="space-y-4 sm:space-y-5">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h1 class="text-[26px] sm:text-[30px] font-bold text-[var(--text-primary)] tracking-[-0.03em]">Kategori Berita</h1>
            <p class="text-[13px] text-[var(--text-muted)] mt-1">Kelola kategori untuk mengelompokkan berita.</p>
        </div>
        <a href="{{ route('admin.news-categories.create') }}" class="csa-btn csa-btn-primary">
            <x-admin.icon name="plus" :size="16" /> Tambah Kategori
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
        @forelse($categories as $category)
            <article class="csa-card csa-card-hover overflow-hidden flex flex-col">
                <a href="{{ route('admin.news-categories.show', $category) }}" class="block p-4 flex-1">
                    <div class="flex items-start justify-between gap-3">
                        <span class="w-10 h-10 rounded-xl bg-[var(--bg-muted)] text-[var(--text-secondary)] flex items-center justify-center shrink-0">
                            <x-admin.icon name="tags" :size="18" />
                        </span>
                        <span class="csa-chip">{{ $category->news_count }} berita</span>
                    </div>

                    <h2 class="mt-3.5 text-[14px] font-semibold text-[var(--text-primary)]">{{ $category->name }}</h2>
                    <p class="mt-1 text-[12px] text-[var(--text-muted)] line-clamp-2">{{ $category->description ?: 'Belum ada deskripsi kategori.' }}</p>

                    <div class="mt-3 flex items-center gap-3 text-[12px] font-medium">
                        <span class="text-emerald-600 dark:text-emerald-400">{{ $category->published_news_count }} terbit</span>
                        <span class="text-amber-600 dark:text-amber-400">{{ $category->draft_news_count }} draf</span>
                    </div>

                    @if($category->news->isNotEmpty())
                        <div class="mt-3 pt-3 border-t border-[var(--border-soft)] space-y-1.5">
                            @foreach($category->news as $latest)
                                <p class="text-[12px] text-[var(--text-muted)] truncate">{{ $latest->title }}</p>
                            @endforeach
                        </div>
                    @endif
                </a>

                <div class="px-4 py-3 border-t border-[var(--border-soft)] flex items-center justify-end gap-1">
                    <a href="{{ route('admin.news-categories.edit', $category) }}" class="csa-icon-btn" title="Edit" aria-label="Edit kategori">
                        <x-admin.icon name="pen" :size="16" />
                    </a>
                    <button type="button" onclick="openDeleteModal('deleteModal', '{{ route('admin.news-categories.destroy', $category) }}')" class="csa-icon-btn hover:!text-red-500" title="Hapus" aria-label="Hapus kategori">
                        <x-admin.icon name="trash-2" :size="16" />
                    </button>
                </div>
            </article>
        @empty
            <div class="col-span-full csa-card p-12 text-center">
                <x-admin.icon name="tags" :size="32" class="mx-auto text-[var(--text-muted)]" />
                <p class="text-[13px] font-medium text-[var(--text-secondary)] mt-3">Belum ada kategori berita</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
