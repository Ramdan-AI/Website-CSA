@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <div>
        <a href="{{ route('admin.positions.index') }}" class="inline-flex items-center gap-1.5 text-[12px] text-[var(--text-muted)] hover:text-[var(--text-primary)] transition-colors">
            <x-admin.icon name="arrow-left" :size="14" /> Kembali ke Jabatan
        </a>
        <div class="flex items-center gap-3.5 mt-3">
            <span class="w-12 h-12 rounded-xl bg-[var(--bg-muted)] flex items-center justify-center text-[var(--text-secondary)] shrink-0">
                <x-admin.icon name="briefcase" :size="22" />
            </span>
            <div>
                <h1 class="text-[22px] sm:text-[26px] font-bold text-[var(--text-primary)] tracking-[-0.03em]">{{ $position->name }}</h1>
                <p class="text-[13px] text-[var(--text-muted)] mt-0.5">
                    {{ $members->total() }} dari maksimal {{ $position->max_members }} orang
                </p>
            </div>
        </div>
    </div>

    @if (session('success'))
        <div class="csa-panel p-3.5 text-[13px] text-emerald-600 dark:text-emerald-400">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="csa-panel p-3.5 text-[13px] text-red-500">{{ session('error') }}</div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 items-start">
        {{-- Form tambah anggota --}}
        <section class="csa-panel p-5 lg:sticky lg:top-20">
            <h3 class="text-[14px] font-semibold text-[var(--text-primary)]">Tambah Anggota</h3>
            <p class="text-[12px] text-[var(--text-muted)] mt-0.5 mb-4">Pilih anggota untuk mengisi jabatan ini.</p>

            @if ($position->members_count >= $position->max_members)
                <div class="rounded-xl bg-amber-500/10 text-amber-600 dark:text-amber-400 p-3.5 text-[12px] mb-4">
                    Jabatan ini sudah penuh. Lepaskan salah satu anggota untuk menggantinya.
                </div>
            @endif

            <form action="{{ route('admin.positions.assign', $position) }}" method="POST" class="space-y-4">
                @csrf
                <x-form.select name="member_id" label="Pilih Anggota" required placeholder="— Pilih anggota —" :searchable="true">
                    @foreach ($allMembers as $member)
                        @php
                            $isCurrent = $member->position_id == $position->id;
                            $hasOther = $member->position_id && $member->position_id != $position->id;
                        @endphp
                        <option value="{{ $member->id }}">
                            {{ $member->name }}
                            @if ($member->division) — {{ $member->division->name }} @endif
                            @if ($isCurrent)
                                (saat ini)
                            @elseif ($hasOther)
                                ({{ $member->position?->name }})
                            @endif
                        </option>
                    @endforeach
                </x-form.select>
                <button type="submit" class="csa-btn csa-btn-primary w-full">
                    <x-admin.icon name="plus" :size="16" /> Tambahkan ke Jabatan
                </button>
            </form>
        </section>

        {{-- Daftar anggota --}}
        <section class="csa-panel overflow-hidden lg:col-span-2">
            <div class="px-5 py-4 border-b border-[var(--border-soft)] flex items-center justify-between">
                <h3 class="text-[14px] font-semibold text-[var(--text-primary)]">Anggota {{ $position->name }}</h3>
                <span class="text-[12px] text-[var(--text-muted)]">{{ $members->total() }} orang</span>
            </div>

            @if ($members->count() > 0)
                <div class="divide-y divide-[var(--border-soft)]">
                    @foreach ($members as $member)
                        <div class="flex items-center justify-between gap-3 p-4 hover:bg-[var(--bg-hover)] transition-colors">
                            <div class="flex items-center gap-3 min-w-0">
                                <img src="{{ $member->photo_url ?: asset('storage/profile/profile-placeholder.png') }}"
                                    alt="{{ $member->name }}" class="w-10 h-10 rounded-full object-cover bg-[var(--bg-muted)] shrink-0">
                                <div class="min-w-0">
                                    <p class="text-[13px] font-medium text-[var(--text-primary)] truncate">{{ $member->name }}</p>
                                    <p class="text-[12px] text-[var(--text-muted)] truncate">
                                        {{ $member->division?->name ?? 'Tanpa Divisi' }}@if($member->nim) · {{ $member->nim }}@endif
                                    </p>
                                </div>
                            </div>
                            <button type="button" onclick="openDeleteModal('deleteModal', '{{ route('admin.positions.remove-member', [$position, $member->id]) }}')"
                                class="csa-icon-btn hover:!text-red-500 shrink-0" title="Keluarkan" aria-label="Keluarkan anggota">
                                <x-admin.icon name="user-minus" :size="16" />
                            </button>
                        </div>
                    @endforeach
                </div>

                @if ($members->hasPages())
                    <div class="px-4 py-3 border-t border-[var(--border-soft)]">{{ $members->links() }}</div>
                @endif
            @else
                <div class="py-16 text-center">
                    <div class="w-14 h-14 rounded-2xl bg-[var(--bg-muted)] flex items-center justify-center mx-auto text-[var(--text-muted)]">
                        <x-admin.icon name="user-minus" :size="24" />
                    </div>
                    <p class="text-[13px] font-medium text-[var(--text-secondary)] mt-3">Belum ada anggota</p>
                    <p class="text-[12px] text-[var(--text-muted)] mt-1">Tambahkan anggota lewat form di samping.</p>
                </div>
            @endif
        </section>
    </div>
</div>
@endsection
