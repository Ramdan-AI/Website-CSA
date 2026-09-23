@extends('layouts.admin')

@section('content')
<div class="space-y-4 sm:space-y-5">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h1 class="text-[26px] sm:text-[30px] font-bold text-[var(--text-primary)] tracking-[-0.03em]">Pengumuman</h1>
            <p class="text-[13px] text-[var(--text-muted)] mt-1">Informasi yang tampil di dashboard seluruh anggota.</p>
        </div>
        <a href="{{ route('admin.announcements.create') }}" class="csa-btn csa-btn-primary">
            <x-admin.icon name="plus" :size="16" /> Buat Pengumuman
        </a>
    </div>

    @if (session('success'))
        <div class="csa-card p-3.5 text-[13px] text-emerald-600 dark:text-emerald-400">{{ session('success') }}</div>
    @endif

    <form method="GET" class="csa-card p-3 sm:p-4 grid grid-cols-1 sm:grid-cols-[1fr_auto_auto] gap-3">
        <div class="relative">
            <x-admin.icon name="search" :size="16" class="absolute left-3.5 top-1/2 -translate-y-1/2 text-[var(--text-muted)] pointer-events-none" />
            <input type="search" name="search" value="{{ request('search') }}" placeholder="Cari judul atau isi pengumuman..." class="csa-input pl-10" aria-label="Cari pengumuman">
        </div>
        <select name="category" onchange="this.form.submit()" class="csa-input sm:w-44" aria-label="Kategori">
            <option value="">Semua kategori</option>
            <option value="info" @selected($category === 'info')>Informasi</option>
            <option value="penting" @selected($category === 'penting')>Penting</option>
            <option value="urgent" @selected($category === 'urgent')>Penting Sekali</option>
        </select>
        <button type="submit" class="csa-btn csa-btn-ghost">Cari</button>
    </form>

    @if ($announcements->isEmpty())
        <div class="csa-card p-12 text-center">
            <x-admin.icon name="megaphone" :size="32" class="mx-auto text-[var(--text-muted)]" />
            <p class="text-[13px] font-medium text-[var(--text-secondary)] mt-3">Belum ada pengumuman</p>
            <p class="text-[12px] text-[var(--text-muted)] mt-1">Buat pengumuman pertama untuk ditampilkan di dashboard.</p>
        </div>
    @else
        <div class="space-y-3">
            @foreach ($announcements as $announcement)
                <article class="csa-card p-5">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                        <div class="min-w-0">
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="csa-chip {{ $announcement->category_classes }}">{{ $announcement->category_label }}</span>
                                @if ($announcement->pinned)
                                    <span class="csa-chip csa-chip-violet">Disematkan</span>
                                @endif
                                @if ($announcement->status === 'draft')
                                    <span class="csa-chip csa-chip-amber">Draf</span>
                                @endif
                            </div>

                            <h2 class="text-[15px] font-semibold text-[var(--text-primary)] mt-2.5">{{ $announcement->title }}</h2>
                            <p class="text-[13px] text-[var(--text-secondary)] mt-1.5 line-clamp-2">{{ $announcement->body }}</p>

                            <div class="flex flex-wrap items-center gap-x-4 gap-y-1 mt-3 text-[12px] text-[var(--text-muted)]">
                                <span>{{ $announcement->creator?->name ?? '-' }}</span>
                                <span>{{ $announcement->created_at->translatedFormat('d M Y H:i') }}</span>
                                @if ($announcement->period_label)
                                    <span>{{ $announcement->period_label }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="flex items-center gap-1 shrink-0">
                            <a href="{{ route('admin.announcements.edit', $announcement) }}" class="csa-icon-btn" title="Edit" aria-label="Edit pengumuman">
                                <x-admin.icon name="pen" :size="16" />
                            </a>
                            <button type="button" onclick="openDeleteModal('deleteModal', '{{ route('admin.announcements.destroy', $announcement) }}')" class="csa-icon-btn hover:!text-red-500" title="Hapus" aria-label="Hapus pengumuman">
                                <x-admin.icon name="trash-2" :size="16" />
                            </button>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>

        @if ($announcements->hasPages())
            <div>{{ $announcements->links() }}</div>
        @endif
    @endif
</div>
@endsection
