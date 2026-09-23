@extends('layouts.admin')

@section('content')
<div class="space-y-4 sm:space-y-5">

    @if (!$division)
        <div class="csa-card p-12 text-center">
            <x-admin.icon name="building-2" :size="32" class="mx-auto text-[var(--text-muted)]" />
            <p class="text-[15px] font-medium text-[var(--text-secondary)] mt-3">Anda belum terhubung ke divisi</p>
            <p class="text-[13px] text-[var(--text-muted)] mt-1">Hubungi pengurus untuk menetapkan divisi Anda.</p>
        </div>
    @else
        <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h1 class="text-[26px] sm:text-[30px] font-bold text-[var(--text-primary)] tracking-[-0.03em]">Divisi {{ $division->name }}</h1>
                <p class="text-[13px] text-[var(--text-muted)] mt-1">Ringkasan anggota dan kehadiran divisi Anda.</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.division-manage.index') }}" class="csa-btn csa-btn-ghost !min-h-[42px] !text-[13px]">
                    <x-admin.icon name="users" :size="15" /> Kelola Anggota
                </a>
                <a href="{{ route('admin.division-report.index') }}" class="csa-btn csa-btn-primary !min-h-[42px] !text-[13px]">
                    <x-admin.icon name="clipboard-check" :size="15" /> Rekap Kehadiran
                </a>
            </div>
        </div>

        <section class="grid grid-cols-2 lg:grid-cols-4 gap-3">
            <x-admin.metric-card label="Anggota Divisi" :value="number_format($members->count())" change-label="aktif" tone="blue" />
            <x-admin.metric-card label="Rata-rata Hadir" :value="$divisionAverage . '%'" change-label="seluruh anggota" tone="green" />
            <x-admin.metric-card label="Total Tercatat" :value="number_format($rows->sum('total'))" change-label="kehadiran" tone="violet" />
            <x-admin.metric-card label="Total Hadir" :value="number_format($rows->sum('present'))" change-label="kehadiran" tone="green" />
        </section>

        <section class="grid grid-cols-1 lg:grid-cols-2 gap-4">
            {{-- Anggota divisi --}}
            <div class="csa-card overflow-hidden">
                <div class="px-5 py-3.5 border-b border-[var(--border-soft)] flex items-center justify-between">
                    <h2 class="text-[14px] font-semibold text-[var(--text-primary)]">Anggota Divisi</h2>
                    <span class="text-[12px] text-[var(--text-muted)]">{{ $members->count() }} orang</span>
                </div>
                <div class="divide-y divide-[var(--border-soft)] max-h-96 overflow-y-auto">
                    @forelse ($rows as $row)
                        <div class="flex items-center justify-between gap-3 p-4">
                            <div class="flex items-center gap-3 min-w-0">
                                <img src="{{ $row['member']->photo_url ?: asset('storage/profile/profile-placeholder.png') }}"
                                    alt="{{ $row['member']->name }}" class="w-9 h-9 rounded-full object-cover bg-[var(--bg-muted)] shrink-0">
                                <div class="min-w-0">
                                    <p class="text-[13px] font-medium text-[var(--text-primary)] truncate">{{ $row['member']->name }}</p>
                                    <p class="text-[12px] text-[var(--text-muted)] font-mono">{{ $row['member']->nim ?? '-' }}</p>
                                </div>
                            </div>
                            <span class="csa-chip shrink-0 {{ $row['rate'] >= 75 ? 'csa-chip-green' : ($row['rate'] >= 50 ? 'csa-chip-amber' : 'csa-chip-red') }}">
                                {{ $row['rate'] }}%
                            </span>
                        </div>
                    @empty
                        <p class="p-8 text-center text-[13px] text-[var(--text-muted)]">Belum ada anggota di divisi ini.</p>
                    @endforelse
                </div>
            </div>

            {{-- Kegiatan mendatang --}}
            <div class="csa-card overflow-hidden">
                <div class="px-5 py-3.5 border-b border-[var(--border-soft)] flex items-center justify-between">
                    <h2 class="text-[14px] font-semibold text-[var(--text-primary)]">Kegiatan Mendatang</h2>
                    <a href="{{ route('admin.events.index') }}" class="text-[12px] font-medium hover:underline" style="color: var(--accent);">Lihat semua</a>
                </div>
                <div class="divide-y divide-[var(--border-soft)]">
                    @forelse ($upcomingEvents as $event)
                        <div class="p-4">
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="text-[12px] font-medium text-[var(--text-secondary)]">{{ $event->event_date->translatedFormat('d M Y') }}</span>
                                @if ($event->attendance_enabled && !$event->attendance_closed_at)
                                    <span class="csa-chip csa-chip-green">Presensi dibuka</span>
                                @endif
                            </div>
                            <p class="text-[13px] font-medium text-[var(--text-primary)] mt-1.5">{{ $event->title }}</p>
                            @if ($event->location)
                                <p class="text-[12px] text-[var(--text-muted)] mt-0.5">{{ $event->location }}</p>
                            @endif
                        </div>
                    @empty
                        <p class="p-8 text-center text-[13px] text-[var(--text-muted)]">Belum ada kegiatan mendatang.</p>
                    @endforelse
                </div>
            </div>
        </section>
    @endif

    <x-admin.announcement-feed :announcements="$announcements" />
    <x-admin.activity-feed :logs="$systemActivity" />
</div>
@endsection
