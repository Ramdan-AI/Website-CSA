@extends('layouts.admin')

@section('content')
<div class="space-y-4 sm:space-y-5">
    <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
        <div>
            <a href="{{ route('admin.divisions.index') }}" class="inline-flex items-center gap-1.5 text-[12px] text-[var(--text-muted)] hover:text-[var(--text-primary)] transition-colors">
                <x-admin.icon name="arrow-left" :size="14" /> Kembali ke Divisi
            </a>
            <div class="flex items-center gap-3 mt-2">
                @if ($division->image)
                    <img src="{{ asset('storage/' . $division->image) }}" alt="{{ $division->name }}" class="w-11 h-11 rounded-xl object-cover">
                @else
                    <div class="w-11 h-11 rounded-xl bg-[var(--bg-muted)] flex items-center justify-center text-[var(--text-secondary)] font-semibold">{{ strtoupper(substr($division->name, 0, 2)) }}</div>
                @endif
                <div>
                    <h1 class="text-[22px] sm:text-[26px] font-bold text-[var(--text-primary)] tracking-[-0.03em]">{{ $division->name }}</h1>
                    <p class="text-[13px] text-[var(--text-muted)] mt-0.5">{{ $members->total() }} anggota</p>
                </div>
            </div>
        </div>
        <button type="button" onclick="document.getElementById('addMembersPanel').classList.toggle('hidden')" class="csa-btn csa-btn-primary">
            <x-admin.icon name="user-plus" :size="16" /> Tambah Anggota
        </button>
    </div>

    <div id="addMembersPanel" class="hidden csa-panel overflow-hidden">
        <div class="p-4 sm:p-5 border-b border-[var(--border-soft)] flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="text-[14px] font-semibold text-[var(--text-primary)]">Tambah Anggota</h2>
                <p class="text-[12px] text-[var(--text-muted)] mt-0.5">Pilih anggota yang belum memiliki divisi.</p>
            </div>
            <form method="GET" action="{{ route('admin.divisions.members', $division) }}" class="flex gap-2">
                <input type="text" name="candidate_search" value="{{ request('candidate_search') }}" placeholder="Cari kandidat..." class="csa-input sm:w-56" aria-label="Cari kandidat">
                <button type="submit" class="csa-btn csa-btn-ghost">Cari</button>
            </form>
        </div>
        <form method="POST" action="{{ route('admin.divisions.add-members', $division) }}">
            @csrf
            <div class="max-h-96 overflow-y-auto divide-y divide-[var(--border-soft)]">
                @forelse($availableMembers as $candidate)
                    <label class="flex items-center gap-3 p-4 hover:bg-[var(--bg-hover)] cursor-pointer min-h-[56px]">
                        <input type="checkbox" name="member_ids[]" value="{{ $candidate->id }}" class="w-4 h-4 rounded border-[var(--border-strong)] accent-[var(--accent)]">
                        <span class="w-9 h-9 rounded-full bg-[var(--bg-muted)] flex items-center justify-center text-[12px] font-semibold text-[var(--text-secondary)] shrink-0">{{ substr($candidate->name, 0, 1) }}</span>
                        <span class="min-w-0">
                            <span class="block text-[13px] font-medium text-[var(--text-primary)] truncate">{{ $candidate->name }}</span>
                            <span class="block text-[12px] text-[var(--text-muted)]">{{ $candidate->nim ?: '-' }} · {{ $candidate->position?->name ?: 'Tanpa Jabatan' }}</span>
                        </span>
                    </label>
                @empty
                    <div class="p-8 text-center text-[13px] text-[var(--text-muted)]">Tidak ada kandidat anggota tersedia.</div>
                @endforelse
            </div>
            <div class="p-4 sm:p-5 border-t border-[var(--border-soft)] flex justify-end gap-2">
                <button type="button" onclick="document.getElementById('addMembersPanel').classList.add('hidden')" class="csa-btn csa-btn-ghost">Batal</button>
                <button type="submit" class="csa-btn csa-btn-primary">Tambahkan Terpilih</button>
            </div>
        </form>
    </div>

    <div class="csa-panel overflow-hidden">
        <div class="p-4 sm:p-5 border-b border-[var(--border-soft)] flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <h2 class="text-[14px] font-semibold text-[var(--text-primary)]">Anggota Saat Ini</h2>
            <form method="GET" action="{{ route('admin.divisions.members', $division) }}" class="flex gap-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama / NIM..." class="csa-input sm:w-56" aria-label="Cari anggota">
                <button type="submit" class="csa-btn csa-btn-ghost">Cari</button>
                @if(request()->hasAny(['search', 'candidate_search']))
                    <a href="{{ route('admin.divisions.members', $division) }}" class="csa-btn csa-btn-ghost">Reset</a>
                @endif
            </form>
        </div>

        <div class="divide-y divide-[var(--border-soft)]">
            @forelse ($members as $member)
                <div class="p-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 hover:bg-[var(--bg-hover)]">
                    <div class="flex items-center gap-3 min-w-0">
                        <img src="{{ $member->photo_url ?? asset('storage/profile/profile-placeholder.png') }}" alt="{{ $member->name }}" class="w-10 h-10 rounded-full object-cover bg-[var(--bg-muted)] shrink-0">
                        <div class="min-w-0">
                            <p class="text-[13px] font-medium text-[var(--text-primary)] truncate">{{ $member->name }}</p>
                            <p class="text-[12px] text-[var(--text-muted)]">{{ $member->nim ?: '-' }} · {{ $member->position?->name ?? 'Tanpa Jabatan' }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-1">
                        <a href="{{ route('admin.members.edit', $member) }}" class="csa-icon-btn" title="Edit" aria-label="Edit anggota">
                            <x-admin.icon name="pen" :size="16" />
                        </a>
                        <button type="button" onclick="openDeleteModal('deleteModal', '{{ route('admin.divisions.remove-member', [$division, $member]) }}')" class="csa-icon-btn hover:!text-red-500" title="Keluarkan" aria-label="Keluarkan anggota">
                            <x-admin.icon name="user-minus" :size="16" />
                        </button>
                    </div>
                </div>
            @empty
                <div class="p-12 text-center">
                    <x-admin.icon name="users" :size="30" class="mx-auto text-[var(--text-muted)]" />
                    <p class="text-[13px] font-medium text-[var(--text-secondary)] mt-3">Belum ada anggota pada divisi ini</p>
                </div>
            @endforelse
        </div>

        @if ($members->hasPages())
            <div class="p-4 sm:p-5 border-t border-[var(--border-soft)]">
                {{ $members->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
