@extends('layouts.admin')

@section('content')
<div class="space-y-4 sm:space-y-5">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h1 class="text-[26px] sm:text-[30px] font-bold text-[var(--text-primary)] tracking-[-0.03em]">Galeri</h1>
            <p class="text-[13px] text-[var(--text-muted)] mt-1">Kelola koleksi foto dan dokumentasi kegiatan CSA.</p>
        </div>
        <a href="{{ route('admin.galleries.create') }}" class="csa-btn csa-btn-primary">
            <x-admin.icon name="plus" :size="16" /> Tambah Foto
        </a>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-3.5">
        @forelse($galleries as $gallery)
            <article class="csa-panel csa-panel-hover overflow-hidden group">
                <div class="relative aspect-square overflow-hidden cursor-pointer" onclick="openImagePreview('imagePreviewModal', '{{ asset('storage/' . $gallery->image) }}', '{{ $gallery->caption ?? 'Galeri CSA' }}')">
                    <img src="{{ asset('storage/' . $gallery->image) }}" alt="{{ $gallery->caption ?? 'Foto kegiatan' }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" loading="lazy">
                </div>

                <div class="p-3">
                    <p class="text-[12px] font-medium text-[var(--text-primary)] truncate">{{ $gallery->caption ?: 'Tanpa keterangan' }}</p>
                    <p class="text-[11px] text-[var(--text-muted)] truncate mt-0.5">{{ $gallery->album?->title ?? 'Tanpa album' }}</p>

                    <div class="mt-2.5 pt-2.5 border-t border-[var(--border-soft)] flex items-center justify-between">
                        <form action="{{ route('admin.galleries.toggle-hero', $gallery) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="inline-flex items-center gap-1.5 text-[11px] font-medium {{ $gallery->is_hero ? 'text-amber-500' : 'text-[var(--text-muted)] hover:text-[var(--text-secondary)]' }} transition-colors" title="{{ $gallery->is_hero ? 'Hapus dari unggulan' : 'Jadikan unggulan' }}">
                                <x-admin.icon name="star" :size="13" /> {{ $gallery->is_hero ? 'Unggulan' : 'Set unggulan' }}
                            </button>
                        </form>
                        <div class="flex items-center gap-0.5">
                            <a href="{{ route('admin.galleries.edit', $gallery) }}" class="csa-icon-btn !w-8 !h-8" title="Edit" aria-label="Edit foto">
                                <x-admin.icon name="pen" :size="14" />
                            </a>
                            <button type="button" onclick="openDeleteModal('deleteModal', '{{ route('admin.galleries.destroy', $gallery) }}')" class="csa-icon-btn !w-8 !h-8 hover:!text-red-500" title="Hapus" aria-label="Hapus foto">
                                <x-admin.icon name="trash-2" :size="14" />
                            </button>
                        </div>
                    </div>
                </div>
            </article>
        @empty
            <div class="col-span-full csa-panel p-12 text-center">
                <x-admin.icon name="images" :size="32" class="mx-auto text-[var(--text-muted)]" />
                <p class="text-[13px] font-medium text-[var(--text-secondary)] mt-3">Belum ada foto</p>
                <p class="text-[12px] text-[var(--text-muted)] mt-1">Tambahkan foto pertama ke galeri.</p>
            </div>
        @endforelse
    </div>

    @if($galleries->hasPages())
        {{ $galleries->links() }}
    @endif
</div>
@endsection
