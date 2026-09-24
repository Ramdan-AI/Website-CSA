@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h1 class="text-[26px] sm:text-[30px] font-bold text-[var(--text-primary)] tracking-[-0.03em]">Jabatan</h1>
            <p class="text-[13px] text-[var(--text-muted)] mt-1">Kelola jabatan dan susunan pengurus organisasi.</p>
        </div>
        @can('manage-positions')
            <a href="{{ route('admin.positions.create') }}" class="csa-btn csa-btn-primary self-start sm:self-auto">
                <x-admin.icon name="plus" :size="16" /> Tambah Jabatan
            </a>
        @endcan
    </div>

    @if (session('success'))
        <div class="csa-panel p-3.5 text-[13px] text-emerald-600 dark:text-emerald-400">{{ session('success') }}</div>
    @endif

    <x-admin.search-bar name="search" :value="$search ?? request('search')" placeholder="Cari nama atau deskripsi jabatan..." class="max-w-md" />

    @if ($positions->isEmpty())
        <div class="csa-panel p-16 text-center">
            <div class="w-14 h-14 rounded-2xl bg-[var(--bg-muted)] flex items-center justify-center mx-auto text-[var(--text-muted)]">
                <x-admin.icon name="briefcase" :size="26" />
            </div>
            <p class="text-[15px] font-semibold text-[var(--text-secondary)] mt-4">Belum ada jabatan</p>
            <p class="text-[13px] text-[var(--text-muted)] mt-1">Tambahkan jabatan pertama untuk menyusun kepengurusan.</p>
        </div>
    @else
        <div class="csa-panel overflow-hidden">
            <div class="divide-y divide-[var(--border-soft)]">
                @foreach ($positions as $position)
                    @php
                        $leads = $position->members->take(4);
                        $filled = $position->members_count;
                    @endphp
                    <article class="flex items-center gap-4 p-4 hover:bg-[var(--bg-hover)] transition-colors">
                        <span class="w-11 h-11 rounded-xl bg-[var(--bg-muted)] flex items-center justify-center text-[var(--text-secondary)] shrink-0">
                            <x-admin.icon name="briefcase" :size="20" />
                        </span>

                        <div class="min-w-0 flex-1">
                            <div class="flex items-center gap-2">
                                <h2 class="text-[14px] font-semibold text-[var(--text-primary)] truncate">{{ $position->name }}</h2>
                                <span class="csa-chip {{ $position->max_members > 1 ? 'csa-chip-blue' : '' }} shrink-0">
                                    {{ $position->max_members > 1 ? 'Kolektif' : 'Tunggal' }}
                                </span>
                            </div>
                            <p class="text-[12px] text-[var(--text-muted)] truncate mt-0.5">
                                {{ $position->description ?: 'Belum ada deskripsi.' }}
                            </p>
                        </div>

                        {{-- Anggota yang menjabat --}}
                        <div class="hidden sm:flex items-center gap-2.5 shrink-0">
                            <div class="flex -space-x-2">
                                @forelse ($leads as $lead)
                                    <img src="{{ $lead->photo_url ?: asset('storage/profile/profile-placeholder.png') }}"
                                        alt="{{ $lead->name }}" title="{{ $lead->name }}"
                                        class="w-7 h-7 rounded-full object-cover ring-2 ring-[var(--bg-card)] bg-[var(--bg-muted)]">
                                @empty
                                    <span class="text-[12px] text-[var(--text-muted)]">Kosong</span>
                                @endforelse
                            </div>
                            @if ($leads->isNotEmpty())
                                <span class="text-[12px] text-[var(--text-muted)]">
                                    {{ $filled > 4 ? '+' . ($filled - 4) : '' }}
                                </span>
                            @endif
                        </div>

                        <span class="hidden md:inline-block text-[12px] text-[var(--text-muted)] shrink-0 w-16 text-right tabular-nums">
                            {{ $filled }}/{{ $position->max_members }}
                        </span>

                        <div class="flex items-center gap-1 shrink-0">
                            <a href="{{ route('admin.positions.members', $position) }}" class="csa-icon-btn" title="Atur anggota" aria-label="Atur anggota">
                                <x-admin.icon name="users" :size="16" />
                            </a>
                            @can('manage-positions')
                                <a href="{{ route('admin.positions.edit', $position) }}" class="csa-icon-btn" title="Edit" aria-label="Edit jabatan">
                                    <x-admin.icon name="pen" :size="16" />
                                </a>
                                <button type="button" onclick="openDeleteModal('deleteModal', '{{ route('admin.positions.destroy', $position) }}')" class="csa-icon-btn hover:!text-red-500" title="Hapus" aria-label="Hapus jabatan">
                                    <x-admin.icon name="trash-2" :size="16" />
                                </button>
                            @endcan
                        </div>
                    </article>
                @endforeach
            </div>
        </div>

        @if ($positions->hasPages())
            <div>{{ $positions->links() }}</div>
        @endif
    @endif
</div>
@endsection
