@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <div>
        <a href="{{ route('admin.members.index') }}" class="inline-flex items-center gap-1.5 text-[12px] text-[var(--text-muted)] hover:text-[var(--text-primary)] transition-colors">
            <x-admin.icon name="arrow-left" :size="14" /> Kembali ke Anggota
        </a>
    </div>

    {{-- Kartu profil --}}
    <section class="csa-panel p-6">
        <div class="flex flex-col sm:flex-row sm:items-center gap-5">
            <img src="{{ $member->photo_url ?: asset('storage/profile/profile-placeholder.png') }}"
                alt="{{ $member->name }}" class="w-20 h-20 rounded-2xl object-cover bg-[var(--bg-muted)] shrink-0">

            <div class="min-w-0 flex-1">
                <div class="flex flex-wrap items-center gap-2">
                    <h1 class="text-[22px] sm:text-[26px] font-bold text-[var(--text-primary)] tracking-[-0.03em]">{{ $member->name }}</h1>
                    <span class="csa-chip {{ $member->is_active ? 'csa-chip-green' : 'csa-chip-red' }}">{{ $member->is_active ? 'Aktif' : 'Nonaktif' }}</span>
                </div>
                <p class="text-[13px] text-[var(--text-muted)] mt-1 font-mono">{{ $member->nim }}</p>
                <div class="flex flex-wrap items-center gap-x-4 gap-y-1.5 mt-3 text-[13px] text-[var(--text-secondary)]">
                    <span class="inline-flex items-center gap-1.5"><x-admin.icon name="building-2" :size="14" /> {{ $member->division?->name ?? 'Belum ada divisi' }}</span>
                    <span class="inline-flex items-center gap-1.5"><x-admin.icon name="briefcase" :size="14" /> {{ $member->position?->name ?? 'Anggota' }}</span>
                    @if ($member->semester)
                        <span class="inline-flex items-center gap-1.5"><x-admin.icon name="graduation-cap" :size="14" /> Semester {{ $member->semester }}</span>
                    @endif
                </div>
            </div>

            @can('manage-members')
                <a href="{{ route('admin.members.edit', $member) }}" class="csa-btn csa-btn-ghost shrink-0 self-start sm:self-center">
                    <x-admin.icon name="pen" :size="16" /> Edit
                </a>
            @endcan
        </div>

        @if ($member->bio)
            <p class="text-[13px] text-[var(--text-secondary)] leading-[1.7] mt-5 pt-5 border-t border-[var(--border-soft)]">{{ $member->bio }}</p>
        @endif
    </section>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 items-start">
        <div class="lg:col-span-2 space-y-5">
            {{-- Kehadiran --}}
            <section class="csa-panel overflow-hidden">
                <div class="px-5 py-3.5 border-b border-[var(--border-soft)]">
                    <h2 class="text-[14px] font-semibold text-[var(--text-primary)]">Rekap Kehadiran</h2>
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-4 divide-x divide-[var(--border-soft)]">
                    @foreach ([
                        ['Hadir', $attendanceStats['present'], 'text-emerald-600 dark:text-emerald-400'],
                        ['Izin', $attendanceStats['permit'], 'text-amber-600 dark:text-amber-400'],
                        ['Sakit', $attendanceStats['sick'], 'text-blue-600 dark:text-blue-400'],
                        ['Alfa', $attendanceStats['absent'], 'text-red-600 dark:text-red-400'],
                    ] as [$label, $value, $tone])
                        <div class="p-4 text-center">
                            <p class="text-[22px] font-bold {{ $tone }}">{{ $value }}</p>
                            <p class="text-[11px] font-medium uppercase tracking-wide text-[var(--text-muted)] mt-1">{{ $label }}</p>
                        </div>
                    @endforeach
                </div>
            </section>

            {{-- Riwayat kehadiran --}}
            <section class="csa-panel overflow-hidden">
                <div class="px-5 py-3.5 border-b border-[var(--border-soft)]">
                    <h2 class="text-[14px] font-semibold text-[var(--text-primary)]">Riwayat Terakhir</h2>
                </div>
                @if ($attendances->isNotEmpty())
                    <div class="divide-y divide-[var(--border-soft)]">
                        @foreach ($attendances as $attendance)
                            @php
                                $map = ['present' => ['Hadir', 'csa-chip-green'], 'permit' => ['Izin', 'csa-chip-amber'], 'sick' => ['Sakit', 'csa-chip-blue'], 'absent' => ['Alfa', 'csa-chip-red']];
                            @endphp
                            <div class="flex items-center justify-between gap-3 p-4">
                                <div class="min-w-0">
                                    <p class="text-[13px] font-medium text-[var(--text-primary)] truncate">{{ $attendance->event?->title ?? 'Kegiatan' }}</p>
                                    <p class="text-[12px] text-[var(--text-muted)]">{{ $attendance->event?->event_date?->translatedFormat('d M Y') }}</p>
                                </div>
                                <span class="csa-chip {{ $map[$attendance->status][1] ?? '' }} shrink-0">{{ $map[$attendance->status][0] ?? $attendance->status }}</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="p-8 text-center text-[13px] text-[var(--text-muted)]">Belum ada riwayat kehadiran.</p>
                @endif
            </section>
        </div>

        <aside class="space-y-5">
            {{-- Tingkatan (hanya divisi bertingkat) --}}
            @if ($member->level)
                <section class="csa-panel p-5">
                    <h3 class="text-[14px] font-semibold text-[var(--text-primary)] mb-3">Tingkatan</h3>
                    @php $lvl = \App\Models\Member::LEVELS[$member->level] ?? null; @endphp
                    <div class="rounded-xl bg-[var(--accent-subtle)] p-4">
                        <p class="text-[15px] font-bold text-[var(--accent-bright)]">{{ $lvl['label'] ?? 'Tingkat ' . $member->level }}</p>
                        <p class="text-[12px] font-semibold text-[var(--accent-bright)] mt-0.5">{{ $lvl['name'] ?? '' }}</p>
                        <p class="text-[12px] text-[var(--text-secondary)] mt-1">{{ $lvl['description'] ?? '' }}</p>
                    </div>
                </section>
            @endif

            {{-- Kontak --}}
            <section class="csa-panel p-5 space-y-3">
                <h3 class="text-[14px] font-semibold text-[var(--text-primary)]">Kontak</h3>
                @foreach ([
                    ['mail', 'email', $member->email],
                    ['phone', 'phone', $member->phone],
                    ['link', 'linkedin', $member->linkedin],
                    ['at-sign', 'instagram', $member->instagram],
                    ['code', 'github', $member->github],
                ] as [$icon, $field, $value])
                    @php
                        $isUrl = in_array($field, ['linkedin', 'instagram', 'github'], true);
                        $href = $isUrl && $value ? (str_starts_with($value, 'http') ? $value : 'https://' . $value) : null;
                    @endphp
                    @if ($value)
                        <div class="flex items-center gap-2.5 min-w-0">
                            <x-admin.icon :name="$icon" :size="15" class="text-[var(--text-muted)] shrink-0" />
                            @if ($href)
                                <a href="{{ $href }}" target="_blank" rel="noopener" class="text-[13px] text-[var(--accent-bright)] hover:underline truncate">{{ $value }}</a>
                            @else
                                <span class="text-[13px] text-[var(--text-secondary)] truncate">{{ $value }}</span>
                            @endif
                        </div>
                    @endif
                @endforeach
                @unless ($member->email || $member->phone || $member->linkedin || $member->instagram || $member->github)
                    <p class="text-[13px] text-[var(--text-muted)]">Belum ada kontak.</p>
                @endunless
            </section>

            {{-- Proyek --}}
            @if ($projects->isNotEmpty())
                <section class="csa-panel p-5">
                    <h3 class="text-[14px] font-semibold text-[var(--text-primary)] mb-3">Proyek Showcase</h3>
                    <div class="space-y-2">
                        @foreach ($projects as $project)
                            <a href="{{ route('admin.showcase.preview', $project) }}" target="_blank" rel="noopener"
                                class="flex items-center gap-2.5 min-w-0 hover:text-[var(--accent-bright)] transition-colors">
                                <x-admin.icon name="rocket" :size="15" class="text-[var(--text-muted)] shrink-0" />
                                <span class="text-[13px] text-[var(--text-primary)] truncate">{{ $project->title }}</span>
                            </a>
                        @endforeach
                    </div>
                </section>
            @endif
        </aside>
    </div>
</div>
@endsection
