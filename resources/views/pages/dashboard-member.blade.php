@extends('layouts.admin')

@php
    $waLink = \App\Models\Setting::first()?->whatsapp_group_link;
    $statItems = [
        ['label' => 'NIM', 'value' => $member?->nim ?? '-'],
        ['label' => 'Divisi', 'value' => $member?->division?->name ?? 'Belum ada'],
        ['label' => 'Kehadiran', 'value' => $attendanceRate . '%', 'note' => ($attendanceStats['present'] ?? 0) . ' dari ' . ($attendanceStats['total'] ?? 0) . ' tercatat'],
    ];
@endphp

@section('content')
<div class="space-y-6">

    {{-- Header personal --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-[26px] sm:text-[30px] font-bold text-[var(--text-primary)] tracking-[-0.03em]">Halo, {{ explode(' ', $user->name)[0] }}</h1>
            <p class="text-[13px] text-[var(--text-muted)] mt-1">Selamat datang kembali di panel anggota CSA.</p>
        </div>
        @if ($waLink)
            <a href="{{ $waLink }}" target="_blank" rel="noopener noreferrer" class="csa-btn csa-btn-primary self-start sm:self-auto">
                <x-admin.icon name="users" :size="16" /> Grup WhatsApp
            </a>
        @endif
    </div>

    {{-- Pengumuman --}}
    <x-admin.announcement-feed :announcements="$announcements" />

    {{-- Ringkasan personal --}}
    <section class="csa-panel p-5 sm:p-6">
        <x-admin.stat-strip :items="$statItems" />
    </section>

    {{-- Kegiatan mendatang --}}
    <section class="csa-panel overflow-hidden">
        <header class="flex items-center justify-between gap-4 px-5 py-4 border-b border-[var(--border-soft)]">
            <h2 class="text-[15px] font-semibold text-[var(--text-primary)]">Kegiatan Mendatang</h2>
            <a href="{{ route('admin.member.calendar') }}" class="inline-flex items-center gap-1 text-[12px] font-medium text-[var(--text-secondary)] hover:text-[var(--accent-bright)] transition-colors">
                Semua kegiatan <x-admin.icon name="arrow-right" :size="13" />
            </a>
        </header>

        @if ($upcomingEvents->isEmpty())
            <p class="py-12 text-center text-[13px] text-[var(--text-muted)]">Belum ada kegiatan mendatang.</p>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4 p-5">
                @foreach ($upcomingEvents as $event)
                    <article class="rounded-xl border border-[var(--border-soft)] overflow-hidden flex flex-col">
                        <div class="aspect-[16/9] bg-[var(--bg-muted)] overflow-hidden">
                            @if ($event->banner_url)
                                <img src="{{ $event->banner_url }}" alt="{{ $event->title }}" loading="lazy" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-[var(--text-muted)]">
                                    <x-admin.icon name="calendar-days" :size="24" />
                                </div>
                            @endif
                        </div>
                        <div class="p-4 flex flex-col flex-1">
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="csa-chip csa-chip-blue">{{ $event->event_date->translatedFormat('d M Y') }}</span>
                                @if ($event->attendance_enabled && ! $event->attendance_closed_at)
                                    <span class="csa-chip csa-chip-green">Presensi dibuka</span>
                                @elseif ($event->attendance_closed_at)
                                    <span class="csa-chip">Selesai</span>
                                @endif
                            </div>
                            <h3 class="text-[14px] font-semibold text-[var(--text-primary)] mt-2.5 leading-snug line-clamp-2">{{ $event->title }}</h3>
                            @if ($event->location)
                                <p class="text-[12px] text-[var(--text-muted)] mt-1 inline-flex items-center gap-1.5">
                                    <x-admin.icon name="map-pin" :size="13" /> {{ $event->location }}
                                </p>
                            @endif

                            <div class="mt-auto pt-4">
                                <a href="{{ route('admin.member.calendar') }}" class="csa-btn csa-btn-ghost w-full !min-h-[38px] !text-[12px]">
                                    <x-admin.icon name="arrow-right" :size="14" /> Lihat Kegiatan
                                </a>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        @endif
    </section>

    {{-- Riwayat kehadiran --}}
    <section class="csa-panel overflow-hidden">
        <header class="flex items-center justify-between gap-4 px-5 py-4 border-b border-[var(--border-soft)]">
            <h2 class="text-[15px] font-semibold text-[var(--text-primary)]">Riwayat Kehadiran</h2>
            <a href="{{ route('admin.member.attendance') }}" class="inline-flex items-center gap-1 text-[12px] font-medium text-[var(--text-secondary)] hover:text-[var(--accent-bright)] transition-colors">
                Lihat semua <x-admin.icon name="arrow-right" :size="13" />
            </a>
        </header>
        <div class="overflow-x-auto">
            <table class="w-full min-w-[560px]">
                <thead>
                    <tr>
                        <th class="csa-table-th">Kegiatan</th>
                        <th class="csa-table-th">Tanggal</th>
                        <th class="csa-table-th">Status</th>
                        <th class="csa-table-th">Waktu Presensi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($recentAttendances as $attendance)
                        @php
                            $statusLabel = ['present' => 'Hadir', 'permit' => 'Izin', 'sick' => 'Sakit', 'absent' => 'Alfa'][$attendance->status] ?? ucfirst($attendance->status);
                            $statusClass = ['present' => 'csa-chip-green', 'permit' => 'csa-chip-amber', 'sick' => 'csa-chip-blue', 'absent' => 'csa-chip-red'][$attendance->status] ?? '';
                        @endphp
                        <tr class="csa-table-row">
                            <td class="csa-table-td font-medium text-[var(--text-primary)]">{{ $attendance->event->title }}</td>
                            <td class="csa-table-td whitespace-nowrap">{{ $attendance->event->event_date->translatedFormat('d M Y') }}</td>
                            <td class="csa-table-td"><span class="csa-chip {{ $statusClass }}">{{ $statusLabel }}</span></td>
                            <td class="csa-table-td">{{ $attendance->check_in_at?->format('H:i') ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-10 text-center text-[var(--text-muted)]">Belum ada riwayat kehadiran.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <x-admin.activity-feed :logs="$systemActivity" />
</div>
@endsection
