@extends('layouts.admin')

@section('content')
<div class="space-y-4 sm:space-y-5">
    <div>
        <a href="{{ route('admin.positions.index') }}" class="inline-flex items-center gap-1.5 text-[12px] text-[var(--text-muted)] hover:text-[var(--text-primary)] transition-colors">
            <x-admin.icon name="arrow-left" :size="14" /> Kembali ke Jabatan
        </a>
        <div class="flex items-center gap-3 mt-2">
            <div class="w-11 h-11 rounded-xl bg-[var(--bg-muted)] flex items-center justify-center text-[var(--text-secondary)] font-semibold">
                {{ strtoupper(substr($position->name, 0, 2)) }}
            </div>
            <div>
                <h1 class="text-[22px] sm:text-[26px] font-bold text-[var(--text-primary)] tracking-[-0.03em]">{{ $position->name }}</h1>
                <p class="text-[13px] text-[var(--text-muted)] mt-0.5">{{ $position->members->count() }} anggota</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <div class="lg:col-span-1">
            <section class="csa-panel p-5 lg:sticky lg:top-20">
                <h3 class="text-[14px] font-semibold text-[var(--text-primary)]">Tambah Anggota</h3>
                <p class="text-[12px] text-[var(--text-muted)] mt-0.5 mb-4">Pilih anggota untuk jabatan {{ $position->name }}.</p>

                <form action="{{ route('admin.positions.assign', $position) }}" method="POST" class="space-y-4">
                    @csrf
                    <x-form.select name="member_id" id="member_id" label="Pilih Anggota" required placeholder="-- Pilih anggota --">
                        @foreach($allMembers as $member)
                            @php
                                $isCurrentPosition = $member->position_id == $position->id;
                                $hasOtherPosition = $member->position_id && $member->position_id != $position->id;
                            @endphp
                            <option value="{{ $member->id }}" @selected($isCurrentPosition)>
                                {{ $member->name }}@if($member->division) — {{ $member->division->name }}@endif
                                @if($isCurrentPosition) (saat ini) @elseif($hasOtherPosition) ({{ $member->position?->name }}) @else (tersedia) @endif
                            </option>
                        @endforeach
                    </x-form.select>
                    <button type="submit" class="csa-btn csa-btn-primary w-full">
                        <x-admin.icon name="plus" :size="16" /> Tambahkan
                    </button>
                </form>
            </section>
        </div>

        <div class="lg:col-span-2">
            <section class="csa-panel overflow-hidden">
                <div class="px-5 py-4 border-b border-[var(--border-soft)] flex items-center justify-between">
                    <h3 class="text-[14px] font-semibold text-[var(--text-primary)]">Anggota {{ $position->name }}</h3>
                    <span class="text-[12px] text-[var(--text-muted)]">{{ $members->total() }} orang</span>
                </div>

                @if($members->count() > 0)
                    <div class="divide-y divide-[var(--border-soft)]">
                        @foreach($members as $member)
                            <div class="flex items-center justify-between gap-3 p-4 hover:bg-[var(--bg-hover)]">
                                <div class="flex items-center gap-3 min-w-0">
                                    <img src="{{ $member->photo_url ?? asset('storage/profile/profile-placeholder.png') }}" alt="{{ $member->name }}" class="w-10 h-10 rounded-full object-cover bg-[var(--bg-muted)] shrink-0">
                                    <div class="min-w-0">
                                        <p class="text-[13px] font-medium text-[var(--text-primary)] truncate">{{ $member->name }}</p>
                                        <p class="text-[12px] text-[var(--text-muted)] truncate">{{ $member->division?->name ?? 'Tanpa Divisi' }}</p>
                                    </div>
                                </div>
                                <button type="button" onclick="openDeleteModal('deleteModal', '{{ route('admin.positions.remove-member', [$position, $member->id]) }}')" class="csa-icon-btn hover:!text-red-500 shrink-0" title="Keluarkan" aria-label="Keluarkan anggota">
                                    <x-admin.icon name="user-minus" :size="16" />
                                </button>
                            </div>
                        @endforeach
                    </div>

                    @if($members->hasPages())
                        <div class="px-4 py-3 border-t border-[var(--border-soft)]">{{ $members->links() }}</div>
                    @endif
                @else
                    <div class="py-16 text-center">
                        <x-admin.icon name="user-minus" :size="30" class="mx-auto text-[var(--text-muted)]" />
                        <p class="text-[13px] font-medium text-[var(--text-secondary)] mt-3">Belum ada anggota</p>
                        <p class="text-[12px] text-[var(--text-muted)] mt-1">Tambahkan anggota menggunakan form di samping.</p>
                    </div>
                @endif
            </section>
        </div>
    </div>
</div>
@endsection
