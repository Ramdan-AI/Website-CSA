@extends('layouts.admin')

@section('content')
<div class="space-y-4 sm:space-y-5">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h1 class="text-[26px] sm:text-[30px] font-bold text-[var(--text-primary)] tracking-[-0.03em]">Jabatan</h1>
            <p class="text-[13px] text-[var(--text-muted)] mt-1">Kelola jabatan dan atur anggota untuk setiap posisi.</p>
        </div>
        <a href="{{ route('admin.positions.create') }}" class="csa-btn csa-btn-primary">
            <x-admin.icon name="plus" :size="16" /> Tambah Jabatan
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
        @forelse($positions as $position)
            @php
                $firstMember = $position->members->first();
                $isUnique = in_array(strtolower($position->name), ['ketua', 'wakil ketua', 'ketua divisi', 'wakil ketua divisi', 'sekretaris', 'bendahara']);
            @endphp
            <article class="csa-panel p-5 flex flex-col">
                <div class="flex items-start justify-between gap-3">
                    <div class="flex items-center gap-3 min-w-0">
                        @if($isUnique && $firstMember)
                            <img src="{{ $firstMember->photo_url ?: asset('storage/profile/profile-placeholder.png') }}" alt="{{ $firstMember->name }}" class="w-10 h-10 rounded-full object-cover shrink-0">
                        @else
                            <div class="w-10 h-10 rounded-xl bg-[var(--bg-muted)] flex items-center justify-center text-[var(--text-secondary)] shrink-0">
                                <x-admin.icon name="briefcase" :size="18" />
                            </div>
                        @endif
                        <div class="min-w-0">
                            <h3 class="text-[14px] font-semibold text-[var(--text-primary)] truncate">{{ $position->name }}</h3>
                            <p class="text-[12px] text-[var(--text-muted)] truncate">
                                {{ $isUnique && $firstMember ? $firstMember->name : $position->members_count . ' anggota' }}
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center gap-0.5 shrink-0">
                        <a href="{{ route('admin.positions.edit', $position) }}" class="csa-icon-btn" title="Edit" aria-label="Edit jabatan">
                            <x-admin.icon name="pen" :size="16" />
                        </a>
                        <button type="button" onclick="openDeleteModal('deleteModal', '{{ route('admin.positions.destroy', $position) }}')" class="csa-icon-btn hover:!text-red-500" title="Hapus" aria-label="Hapus jabatan">
                            <x-admin.icon name="trash-2" :size="16" />
                        </button>
                    </div>
                </div>

                <p class="text-[12px] text-[var(--text-secondary)] leading-relaxed line-clamp-2 mt-3.5 flex-1">{{ $position->description ?: 'Belum ada deskripsi.' }}</p>

                <div class="mt-4 pt-3.5 border-t border-[var(--border-soft)] flex items-center justify-between">
                    @if($isUnique)
                        <span class="inline-flex items-center gap-2 text-[12px] font-medium {{ $firstMember ? 'text-emerald-600 dark:text-emerald-400' : 'text-[var(--text-muted)]' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ $firstMember ? 'bg-emerald-500' : 'bg-[var(--text-muted)]' }}"></span>
                            {{ $firstMember ? 'Terisi' : 'Kosong' }}
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 text-[12px] text-[var(--text-secondary)]">
                            <x-admin.icon name="users" :size="14" /> {{ $position->members_count }} anggota
                        </span>
                    @endif
                    <a href="{{ route('admin.positions.members', $position) }}" class="csa-btn csa-btn-ghost !min-h-[34px] !px-3 !text-[12px]">
                        Atur <x-admin.icon name="chevron-right" :size="14" />
                    </a>
                </div>
            </article>
        @empty
            <div class="col-span-full csa-panel p-12 text-center">
                <x-admin.icon name="briefcase" :size="32" class="mx-auto text-[var(--text-muted)]" />
                <p class="text-[13px] font-medium text-[var(--text-secondary)] mt-3">Belum ada jabatan</p>
                <p class="text-[12px] text-[var(--text-muted)] mt-1">Tambahkan jabatan pertama untuk memulai.</p>
            </div>
        @endforelse
    </div>

    @if($positions->hasPages())
        {{ $positions->links() }}
    @endif
</div>
@endsection
