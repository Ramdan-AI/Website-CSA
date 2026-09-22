@extends('layouts.admin')

@section('content')
<div class="space-y-4 sm:space-y-5">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h1 class="text-[26px] sm:text-[30px] font-bold text-[var(--text-primary)] tracking-[-0.03em]">Album</h1>
            <p class="text-[13px] text-[var(--text-muted)] mt-1">Kelola album foto dan dokumentasi kegiatan CSA.</p>
        </div>
        <a href="{{ route('admin.albums.create') }}" class="csa-btn csa-btn-primary">
            <x-admin.icon name="plus" :size="16" /> Tambah Album
        </a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($albums as $album)
            <article class="csa-card csa-card-hover overflow-hidden flex flex-col">
                @php
                    $cover = $album->cover_image
                        ? asset('storage/' . $album->cover_image)
                        : ($album->galleries->first() ? asset('storage/' . $album->galleries->first()->image) : null);
                @endphp
                <div class="relative aspect-[16/10] overflow-hidden bg-[var(--bg-muted)]">
                    @if($cover)
                        <img src="{{ $cover }}" alt="{{ $album->title }}" class="w-full h-full object-cover" loading="lazy">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-[var(--text-muted)]">
                            <x-admin.icon name="images" :size="28" />
                        </div>
                    @endif
                    <span class="absolute top-3 right-3 csa-chip bg-black/50 text-white">
                        <x-admin.icon name="image" :size="12" /> {{ $album->galleries_count ?? $album->galleries->count() }}
                    </span>
                </div>

                <div class="p-4 flex-1 flex flex-col">
                    <div class="flex items-start justify-between gap-3">
                        <div class="min-w-0">
                            <h3 class="text-[14px] font-semibold text-[var(--text-primary)] truncate">{{ $album->title }}</h3>
                            <p class="text-[12px] text-[var(--text-muted)] mt-0.5 line-clamp-2">{{ $album->description ?: 'Tidak ada deskripsi.' }}</p>
                        </div>
                        <div class="flex items-center gap-0.5 shrink-0">
                            <a href="{{ route('admin.albums.edit', $album) }}" class="csa-icon-btn" title="Edit" aria-label="Edit album">
                                <x-admin.icon name="pen" :size="16" />
                            </a>
                            <button type="button" onclick="openDeleteModal('deleteModal', '{{ route('admin.albums.destroy', $album) }}')" class="csa-icon-btn hover:!text-red-500" title="Hapus" aria-label="Hapus album">
                                <x-admin.icon name="trash-2" :size="16" />
                            </button>
                        </div>
                    </div>

                    <div class="mt-auto pt-3 border-t border-[var(--border-soft)] flex items-center justify-between">
                        <span class="text-[11px] text-[var(--text-muted)]">{{ $album->created_at?->diffForHumans() }}</span>
                        <a href="{{ route('admin.galleries.index', ['album_id' => $album->id]) }}" class="text-[12px] font-medium text-[var(--accent-bright)] hover:underline inline-flex items-center gap-1">
                            Lihat Foto <x-admin.icon name="arrow-right" :size="13" />
                        </a>
                    </div>
                </div>
            </article>
        @empty
            <div class="col-span-full csa-card p-12 text-center">
                <x-admin.icon name="folder-open" :size="32" class="mx-auto text-[var(--text-muted)]" />
                <p class="text-[13px] font-medium text-[var(--text-secondary)] mt-3">Belum ada album</p>
                <p class="text-[12px] text-[var(--text-muted)] mt-1">Tambahkan album pertama untuk mengelompokkan foto.</p>
            </div>
        @endforelse
    </div>

    @if($albums->hasPages())
        {{ $albums->links() }}
    @endif
</div>
@endsection
