@extends('layouts.admin')

@section('content')
<div class="space-y-4 sm:space-y-5">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h1 class="text-[26px] sm:text-[30px] font-bold text-[var(--text-primary)] tracking-[-0.03em]">Divisi</h1>
            <p class="text-[13px] text-[var(--text-muted)] mt-1">Kelola divisi kegiatan organisasi (maksimal 5 divisi).</p>
        </div>
        @if($canCreate)
            <a href="{{ route('admin.divisions.create') }}" class="csa-btn csa-btn-primary">
                <x-admin.icon name="plus" :size="16" /> Tambah Divisi
            </a>
        @else
            <button disabled class="csa-btn csa-btn-ghost cursor-not-allowed">
                <x-admin.icon name="lock" :size="16" /> Maksimal 5 Divisi
            </button>
        @endif
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($divisions as $division)
            <article class="csa-card p-5 flex flex-col justify-between">
                <div>
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex items-center gap-3 min-w-0">
                            @if ($division->image)
                                <img src="{{ asset('storage/' . $division->image) }}" alt="{{ $division->name }}" class="w-10 h-10 rounded-xl object-cover shrink-0">
                            @else
                                <div class="w-10 h-10 rounded-xl bg-[var(--bg-muted)] flex items-center justify-center text-[var(--text-secondary)] font-semibold text-[13px] shrink-0">{{ substr($division->name, 0, 2) }}</div>
                            @endif
                            <div class="min-w-0">
                                <h3 class="text-[14px] font-semibold text-[var(--text-primary)] truncate">{{ $division->name }}</h3>
                                <p class="text-[12px] text-[var(--text-muted)] truncate">Ketua: {{ $division->leader_name ?? 'Belum ada' }}</p>
                            </div>
                        </div>
                        <span class="csa-chip {{ $division->is_active ? 'csa-chip-green' : '' }} shrink-0">{{ $division->is_active ? 'Aktif' : 'Nonaktif' }}</span>
                    </div>

                    @if($division->description)
                        <p class="text-[12px] text-[var(--text-secondary)] line-clamp-2 mt-3.5 leading-relaxed">{{ $division->description }}</p>
                    @endif

                    @if($division->skills)
                        <div class="flex flex-wrap gap-1.5 mt-3.5">
                            @foreach($division->skills as $skill)
                                <span class="px-2 py-0.5 rounded-md bg-[var(--bg-muted)] text-[var(--text-secondary)] text-[11px]">{{ $skill }}</span>
                            @endforeach
                        </div>
                    @endif
                </div>

                <div class="mt-4 pt-4 border-t border-[var(--border-soft)] flex items-center justify-between">
                    <a href="{{ route('admin.divisions.members', $division) }}" class="text-[12px] font-medium text-[var(--accent-bright)] hover:underline inline-flex items-center gap-1.5">
                        <x-admin.icon name="users" :size="14" /> {{ $division->members_count }} Anggota
                    </a>
                    <div class="flex items-center gap-1">
                        <a href="{{ route('admin.divisions.edit', $division) }}" class="csa-icon-btn" title="Edit" aria-label="Edit divisi">
                            <x-admin.icon name="pen" :size="16" />
                        </a>
                        <button type="button" onclick="openDeleteModal('deleteModal', '{{ route('admin.divisions.destroy', $division) }}')" class="csa-icon-btn hover:!text-red-500" title="Hapus" aria-label="Hapus divisi">
                            <x-admin.icon name="trash-2" :size="16" />
                        </button>
                    </div>
                </div>
            </article>
        @empty
            <div class="col-span-full csa-card p-12 text-center">
                <x-admin.icon name="building-2" :size="32" class="mx-auto text-[var(--text-muted)]" />
                <p class="text-[13px] font-medium text-[var(--text-secondary)] mt-3">Belum ada divisi tercatat</p>
                <p class="text-[12px] text-[var(--text-muted)] mt-1">Tambahkan divisi untuk mulai mengelola anggota.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
