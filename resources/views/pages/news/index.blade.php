@extends('layouts.admin')

@php
    use Illuminate\Support\Str;

    $statusFilter = request('status', '');
    $categoryFilter = request('category_id', '');
    $total = $news->total();
@endphp

@section('content')
<div class="space-y-6" x-data="{ categoryModal: false }">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h1 class="text-[26px] sm:text-[30px] font-bold text-[var(--text-primary)] tracking-[-0.03em]">Berita</h1>
            <p class="text-[13px] text-[var(--text-muted)] mt-1">Kelola berita dan artikel yang tampil di situs.</p>
        </div>
        <div class="flex items-center gap-2 self-start sm:self-auto">
            @can('manage-news')
                <button type="button" @click="categoryModal = true" class="csa-btn csa-btn-ghost">
                    <x-admin.icon name="tags" :size="16" /> Kategori
                </button>
                <a href="{{ route('admin.news.create') }}" class="csa-btn csa-btn-primary">
                    <x-admin.icon name="plus" :size="16" /> Tulis Berita
                </a>
            @endcan
        </div>
    </div>

    @if (session('success'))
        <div class="csa-panel p-3.5 text-[13px] text-emerald-600 dark:text-emerald-400">{{ session('success') }}</div>
    @endif

    {{-- Toolbar: search Google + tab status + kategori --}}
    <div class="flex flex-col gap-4 lg:flex-row lg:items-center">
        <x-admin.search-bar name="search" :value="request('search')" placeholder="Cari judul atau isi berita..."
            class="lg:max-w-md flex-1" />

        <div class="flex flex-wrap items-center gap-2 lg:ml-auto">
            <div class="inline-flex items-center gap-1 p-1 rounded-full bg-[var(--bg-muted)]">
                @foreach (['' => 'Semua', 'published' => 'Terbit', 'draft' => 'Draf'] as $value => $label)
                    <a href="{{ route('admin.news.index', array_filter(['status' => $value, 'category_id' => $categoryFilter, 'search' => request('search')])) }}" data-ajax-link
                        class="px-3.5 py-1.5 rounded-full text-[12px] font-medium transition-colors {{ $statusFilter === $value ? 'bg-[var(--bg-card)] text-[var(--text-primary)] shadow-sm' : 'text-[var(--text-muted)] hover:text-[var(--text-primary)]' }}">
                        {{ $label }}
                    </a>
                @endforeach
            </div>

            <form method="GET" class="w-48">
                @if (request('search'))
                    <input type="hidden" name="search" value="{{ request('search') }}">
                @endif
                @if ($statusFilter !== '')
                    <input type="hidden" name="status" value="{{ $statusFilter }}">
                @endif
                <x-form.select name="category_id" :value="$categoryFilter" onchange="this.form.submit()"
                    placeholder="Semua kategori"
                    :options="collect($categories)->mapWithKeys(fn ($c) => [$c->id => $c->name])->all()" />
            </form>
        </div>
    </div>

    @if ($news->isEmpty())
        <div class="csa-panel p-16 text-center">
            <div class="w-14 h-14 rounded-2xl bg-[var(--bg-muted)] flex items-center justify-center mx-auto text-[var(--text-muted)]">
                <x-admin.icon name="newspaper" :size="26" />
            </div>
            <p class="text-[15px] font-semibold text-[var(--text-secondary)] mt-4">Belum ada berita</p>
            <p class="text-[13px] text-[var(--text-muted)] mt-1">Tulis berita pertama untuk ditampilkan di situs.</p>
            @can('manage-news')
                <a href="{{ route('admin.news.create') }}" class="csa-btn csa-btn-primary mt-5 inline-flex">
                    <x-admin.icon name="plus" :size="16" /> Tulis Berita
                </a>
            @endcan
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4">
            @foreach ($news as $item)
                <article class="csa-panel csa-panel-hover overflow-hidden flex flex-col group">
                    <a href="{{ route('admin.news.show', $item) }}" class="block">
                        <div class="aspect-[16/10] bg-[var(--bg-muted)] overflow-hidden">
                            @if ($item->thumbnail_url)
                                <img src="{{ $item->thumbnail_url }}" alt="{{ $item->title }}" loading="lazy"
                                    class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-[1.03]">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-[var(--text-muted)]">
                                    <x-admin.icon name="newspaper" :size="28" />
                                </div>
                            @endif
                        </div>
                    </a>

                    <div class="p-4 flex flex-col flex-1">
                        <div class="flex items-center justify-between gap-2">
                            <span class="text-[11px] font-medium text-[var(--accent-bright)]">{{ $item->category?->name ?? 'Tanpa kategori' }}</span>
                            <span class="csa-chip {{ $item->status === 'published' ? 'csa-chip-green' : 'csa-chip-amber' }}">
                                {{ $item->status === 'published' ? 'Terbit' : 'Draf' }}
                            </span>
                        </div>

                        <h2 class="text-[14px] font-semibold text-[var(--text-primary)] mt-2 leading-snug line-clamp-2">
                            <a href="{{ route('admin.news.show', $item) }}" class="hover:text-[var(--accent-bright)] transition-colors">{{ $item->title }}</a>
                        </h2>
                        <p class="text-[12px] text-[var(--text-muted)] mt-1.5 line-clamp-2 leading-relaxed">{{ Str::limit(strip_tags($item->content), 110) }}</p>

                        <div class="mt-auto pt-3.5 mt-3.5 border-t border-[var(--border-soft)] flex items-center justify-between text-[11px] text-[var(--text-muted)]">
                            <span>{{ $item->author?->name ?? 'CSA' }}</span>
                            <span>{{ $item->published_at?->translatedFormat('d M Y') ?? $item->created_at->translatedFormat('d M Y') }}</span>
                        </div>
                    </div>

                    @can('manage-news')
                        <div class="px-4 py-2.5 border-t border-[var(--border-soft)] flex items-center justify-end gap-1">
                            <a href="{{ route('admin.news.edit', $item) }}" class="csa-icon-btn" title="Edit" aria-label="Edit berita">
                                <x-admin.icon name="pen" :size="16" />
                            </a>
                            <button type="button" onclick="openDeleteModal('deleteModal', '{{ route('admin.news.destroy', $item) }}')" class="csa-icon-btn hover:!text-red-500" title="Hapus" aria-label="Hapus berita">
                                <x-admin.icon name="trash-2" :size="16" />
                            </button>
                        </div>
                    @endcan
                </article>
            @endforeach
        </div>

        @if ($news->hasPages())
            <div>{{ $news->links() }}</div>
        @endif
    @endif

    @can('manage-news')
        <x-admin.modal.news-category :categories="$categories" />
    @endcan
</div>
@endsection
