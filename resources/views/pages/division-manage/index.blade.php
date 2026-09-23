@extends('layouts.admin')

@section('content')
<div class="space-y-4 sm:space-y-5">
    <div>
        <h1 class="text-[26px] sm:text-[30px] font-bold text-[var(--text-primary)] tracking-[-0.03em]">Kelola Anggota Divisi</h1>
        <p class="text-[13px] text-[var(--text-muted)] mt-1">Anggota divisi {{ $division->name }}.</p>
    </div>

    @if (session('success'))
        <div class="csa-card p-3.5 text-[13px] text-emerald-600 dark:text-emerald-400">{{ session('success') }}</div>
    @endif

    {{-- Tambah anggota --}}
    <div class="csa-card p-5" x-data="{ open: false }">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h2 class="text-[14px] font-semibold text-[var(--text-primary)]">Tambah Anggota</h2>
                <p class="text-[12px] text-[var(--text-muted)] mt-0.5">
                    {{ $candidates->count() }} anggota belum memiliki divisi.
                </p>
            </div>
            <button type="button" @click="open = !open" class="csa-btn csa-btn-ghost !min-h-[40px] !text-[13px]">
                <x-admin.icon name="user-plus" :size="15" /> <span x-text="open ? 'Tutup' : 'Pilih anggota'"></span>
            </button>
        </div>

        @if ($candidates->isNotEmpty())
            <form action="{{ route('admin.division-manage.store') }}" method="POST" x-show="open" x-cloak class="mt-5">
                @csrf

                <div class="max-h-72 overflow-y-auto rounded-xl border border-[var(--border-soft)] divide-y divide-[var(--border-soft)]">
                    @foreach ($candidates as $candidate)
                        <label class="flex items-center gap-3 p-3 hover:bg-[var(--bg-hover)] cursor-pointer min-h-[52px]">
                            <input type="checkbox" name="member_ids[]" value="{{ $candidate->id }}"
                                class="w-4 h-4 rounded border-[var(--border-strong)] accent-[var(--accent)]">
                            <span class="min-w-0">
                                <span class="block text-[13px] font-medium text-[var(--text-primary)] truncate">{{ $candidate->name }}</span>
                                <span class="block text-[12px] text-[var(--text-muted)] font-mono">{{ $candidate->nim ?? 'Tanpa NIM' }}</span>
                            </span>
                        </label>
                    @endforeach
                </div>

                <button type="submit" class="csa-btn csa-btn-primary mt-4">Tambahkan Terpilih</button>
            </form>
        @endif
    </div>

    {{-- Daftar anggota --}}
    <div class="csa-card overflow-hidden">
        <div class="p-4 sm:p-5 border-b border-[var(--border-soft)] flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <h2 class="text-[14px] font-semibold text-[var(--text-primary)]">Anggota Saat Ini</h2>
            <form method="GET" class="flex gap-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau NIM..." class="csa-input sm:w-56" aria-label="Cari anggota">
                <button type="submit" class="csa-btn csa-btn-ghost">Cari</button>
            </form>
        </div>

        <div class="divide-y divide-[var(--border-soft)]">
            @forelse ($members as $member)
                <div class="flex items-center justify-between gap-3 p-4 hover:bg-[var(--bg-hover)]">
                    <div class="flex items-center gap-3 min-w-0">
                        <img src="{{ $member->photo_url ?: asset('storage/profile/profile-placeholder.png') }}"
                            alt="{{ $member->name }}" class="w-10 h-10 rounded-full object-cover bg-[var(--bg-muted)] shrink-0">
                        <div class="min-w-0">
                            <p class="text-[13px] font-medium text-[var(--text-primary)] truncate">{{ $member->name }}</p>
                            <p class="text-[12px] text-[var(--text-muted)]">
                                {{ $member->nim ?: 'Tanpa NIM' }} · {{ $member->position?->name ?? 'Tanpa jabatan' }}
                            </p>
                        </div>
                    </div>

                    <button type="button"
                        onclick="openDeleteModal('deleteModal', '{{ route('admin.division-manage.destroy', $member) }}')"
                        class="csa-icon-btn hover:!text-red-500 shrink-0" title="Keluarkan" aria-label="Keluarkan anggota">
                        <x-admin.icon name="user-minus" :size="16" />
                    </button>
                </div>
            @empty
                <div class="p-12 text-center">
                    <x-admin.icon name="users" :size="30" class="mx-auto text-[var(--text-muted)]" />
                    <p class="text-[13px] font-medium text-[var(--text-secondary)] mt-3">Belum ada anggota di divisi ini</p>
                </div>
            @endforelse
        </div>

        @if ($members->hasPages())
            <div class="p-4 sm:p-5 border-t border-[var(--border-soft)]">{{ $members->links() }}</div>
        @endif
    </div>
</div>
@endsection
