@extends('layouts.admin')

@section('content')
<div class="space-y-4 sm:space-y-5">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h1 class="text-[26px] sm:text-[30px] font-bold text-[var(--text-primary)] tracking-[-0.03em]">Notulen Rapat</h1>
            <p class="text-[13px] text-[var(--text-muted)] mt-1">Catatan rapat, agenda, dan keputusan organisasi.</p>
        </div>
        <a href="{{ route('admin.minutes.create') }}" class="csa-btn csa-btn-primary">
            <x-admin.icon name="plus" :size="16" /> Tambah Notulen
        </a>
    </div>

    @if (session('success'))
        <div class="csa-card p-3.5 text-[13px] text-emerald-600 dark:text-emerald-400">{{ session('success') }}</div>
    @endif

    <form method="GET" class="csa-card p-3 sm:p-4 grid grid-cols-1 sm:grid-cols-[1fr_auto] gap-3">
        <div class="relative">
            <x-admin.icon name="search" :size="16" class="absolute left-3.5 top-1/2 -translate-y-1/2 text-[var(--text-muted)] pointer-events-none" />
            <input type="search" name="search" value="{{ request('search') }}" placeholder="Cari judul atau agenda rapat..." class="csa-input pl-10" aria-label="Cari notulen">
        </div>
        <button type="submit" class="csa-btn csa-btn-ghost">Cari</button>
    </form>

    @if ($minutes->isEmpty())
        <div class="csa-card p-12 text-center">
            <x-admin.icon name="file-text" :size="32" class="mx-auto text-[var(--text-muted)]" />
            <p class="text-[13px] font-medium text-[var(--text-secondary)] mt-3">Belum ada notulen</p>
            <p class="text-[12px] text-[var(--text-muted)] mt-1">Catat hasil rapat pertama untuk diarsipkan.</p>
        </div>
    @else
        <div class="space-y-3">
            @foreach ($minutes as $minute)
                <article class="csa-card p-5">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                        <div class="min-w-0">
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="text-[12px] font-medium text-[var(--text-secondary)]">{{ $minute->meeting_date->translatedFormat('d M Y') }}</span>
                                @if ($minute->location)
                                    <span class="csa-chip">{{ $minute->location }}</span>
                                @endif
                                <span class="csa-chip csa-chip-blue">{{ $minute->attendees_count }} peserta</span>
                            </div>
                            <h2 class="text-[15px] font-semibold text-[var(--text-primary)] mt-2">{{ $minute->title }}</h2>
                            @if ($minute->agenda)
                                <p class="text-[13px] text-[var(--text-muted)] mt-1.5 line-clamp-2">{{ $minute->agenda }}</p>
                            @endif
                        </div>

                        <div class="flex items-center gap-1 shrink-0">
                            @if ($minute->attachment_url)
                                <a href="{{ $minute->attachment_url }}" target="_blank" rel="noopener" class="csa-icon-btn" title="Lampiran" aria-label="Lampiran">
                                    <x-admin.icon name="download" :size="16" />
                                </a>
                            @endif
                            <a href="{{ route('admin.minutes.edit', $minute) }}" class="csa-icon-btn" title="Edit" aria-label="Edit notulen">
                                <x-admin.icon name="pen" :size="16" />
                            </a>
                            <button type="button" onclick="openDeleteModal('deleteModal', '{{ route('admin.minutes.destroy', $minute) }}')" class="csa-icon-btn hover:!text-red-500" title="Hapus" aria-label="Hapus notulen">
                                <x-admin.icon name="trash-2" :size="16" />
                            </button>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>

        @if ($minutes->hasPages())
            <div>{{ $minutes->links() }}</div>
        @endif
    @endif
</div>
@endsection
