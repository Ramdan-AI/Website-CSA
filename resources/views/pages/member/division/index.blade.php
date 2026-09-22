@extends('layouts.admin')

@section('content')
<div class="space-y-4 sm:space-y-5">

    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h1 class="text-[26px] sm:text-[30px] font-bold text-[var(--text-primary)] tracking-[-0.03em]">Divisi {{ $division->name }}</h1>
            <p class="text-[13px] text-[var(--text-muted)] mt-1">Daftar anggota yang tergabung dalam divisi Anda.</p>
        </div>
        <form method="GET" class="flex gap-2">
            <div class="relative">
                <x-admin.icon name="search" :size="16" class="absolute left-3.5 top-1/2 -translate-y-1/2 text-[var(--text-muted)] pointer-events-none" />
                <input type="search" name="search" value="{{ request('search') }}" placeholder="Cari nama / NIM..." class="csa-input pl-10 sm:w-64" aria-label="Cari anggota">
            </div>
            <button type="submit" class="csa-btn csa-btn-ghost">Cari</button>
        </form>
    </div>

    <div class="csa-card overflow-hidden">
        <div class="px-4 py-3 border-b border-[var(--border-soft)]">
            <p class="text-[13px] font-medium text-[var(--text-secondary)]">{{ $members->total() }} anggota</p>
        </div>

        <div class="divide-y divide-[var(--border-soft)]">
            @forelse($members as $member)
                <div class="flex items-center gap-3.5 p-4">
                    <div class="relative shrink-0">
                        <div class="w-10 h-10 rounded-full overflow-hidden bg-[var(--bg-muted)] flex items-center justify-center">
                            @if($member->photo)
                                <img src="{{ asset('storage/' . $member->photo) }}" alt="{{ $member->name }}" class="w-full h-full object-cover">
                            @else
                                <span class="text-[15px] font-semibold text-[var(--text-muted)]">{{ substr($member->name, 0, 1) }}</span>
                            @endif
                        </div>
                        @if($member->is_active)
                            <span class="absolute -bottom-0.5 -right-0.5 w-3 h-3 rounded-full bg-emerald-500 border-2 border-[var(--bg-card)]"></span>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-[13px] font-medium text-[var(--text-primary)] truncate">{{ $member->name }}</p>
                        <p class="text-[12px] text-[var(--text-muted)] truncate">{{ $member->nim ?? 'Belum ada NIM' }}</p>
                    </div>
                    <span class="text-[12px] text-[var(--text-secondary)] shrink-0">{{ $member->position?->name ?? 'Anggota' }}</span>
                </div>
            @empty
                <div class="p-12 text-center">
                    <x-admin.icon name="users" :size="30" class="mx-auto text-[var(--text-muted)]" />
                    <p class="text-[13px] font-medium text-[var(--text-secondary)] mt-3">Belum ada anggota di divisi ini</p>
                </div>
            @endforelse
        </div>

        @if($members->hasPages())
            <div class="px-4 py-3 border-t border-[var(--border-soft)]">{{ $members->links() }}</div>
        @endif
    </div>
</div>
@endsection
