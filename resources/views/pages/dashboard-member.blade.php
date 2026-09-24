@extends('layouts.admin')

@php
    $statItems = [
        ['label' => 'NIM', 'value' => $member?->nim ?? '-'],
        ['label' => 'Divisi', 'value' => $member?->division?->name ?? 'Belum ada'],
        ['label' => 'Kehadiran', 'value' => $attendanceRate . '%', 'note' => ($attendanceStats['present'] ?? 0) . ' dari ' . ($attendanceStats['total'] ?? 0) . ' tercatat'],
        ['label' => 'Status', 'value' => $member && $member->is_active ? 'Aktif' : 'Nonaktif'],
    ];
@endphp

@section('content')
<div class="space-y-6">

    <div>
        <h1 class="text-[26px] sm:text-[30px] font-bold text-[var(--text-primary)] tracking-[-0.03em]">Halo, {{ explode(' ', $user->name)[0] }}!</h1>
        <p class="text-[13px] text-[var(--text-muted)] mt-1">Selamat datang kembali di panel anggota UKM CSA.</p>
    </div>

    <x-admin.announcement-feed :announcements="$announcements" />

    @php $waLink = \App\Models\Setting::first()?->whatsapp_group_link; @endphp
    @if ($waLink)
        <div class="csa-panel p-4 sm:p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h3 class="text-[13px] font-medium text-[var(--text-primary)]">Grup WhatsApp Divisi {{ $member?->division?->name ?? 'CSA' }}</h3>
                <p class="text-[12px] text-[var(--text-muted)] mt-0.5">Gabung grup untuk koordinasi kegiatan rutin.</p>
            </div>
            <a href="{{ $waLink }}" target="_blank" rel="noopener noreferrer" class="csa-btn csa-btn-primary shrink-0 !min-h-[40px] !text-[12px]">
                Buka Grup WA
            </a>
        </div>
    @endif

    <section class="csa-panel p-5 sm:p-6">
        <x-admin.stat-strip :items="$statItems" />
    </section>

    <section class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <div class="csa-panel p-5">
            <h3 class="text-[14px] font-semibold text-[var(--text-primary)] mb-3">Aksi Cepat</h3>
            <div class="space-y-1">
                <a href="{{ route('admin.profile.edit') }}" class="flex items-center gap-3 p-3 rounded-xl hover:bg-[var(--bg-hover)] transition-colors">
                    <span class="w-9 h-9 rounded-lg bg-[var(--bg-muted)] text-[var(--text-secondary)] flex items-center justify-center shrink-0"><x-admin.icon name="user-round" :size="16" /></span>
                    <span>
                        <span class="block text-[13px] font-medium text-[var(--text-primary)]">Edit Profil</span>
                        <span class="block text-[12px] text-[var(--text-muted)]">Perbarui data diri & foto</span>
                    </span>
                </a>
                <a href="{{ route('admin.member.calendar') }}" class="flex items-center gap-3 p-3 rounded-xl hover:bg-[var(--bg-hover)] transition-colors">
                    <span class="w-9 h-9 rounded-lg bg-[var(--bg-muted)] text-[var(--text-secondary)] flex items-center justify-center shrink-0"><x-admin.icon name="calendar-days" :size="16" /></span>
                    <span>
                        <span class="block text-[13px] font-medium text-[var(--text-primary)]">Presensi Saya</span>
                        <span class="block text-[12px] text-[var(--text-muted)]">Kegiatan & kehadiran</span>
                    </span>
                </a>
                <a href="{{ route('admin.member.attendance') }}" class="flex items-center gap-3 p-3 rounded-xl hover:bg-[var(--bg-hover)] transition-colors">
                    <span class="w-9 h-9 rounded-lg bg-[var(--bg-muted)] text-[var(--text-secondary)] flex items-center justify-center shrink-0"><x-admin.icon name="clipboard-check" :size="16" /></span>
                    <span>
                        <span class="block text-[13px] font-medium text-[var(--text-primary)]">Riwayat Kehadiran</span>
                        <span class="block text-[12px] text-[var(--text-muted)]">Lacak kehadiran Anda</span>
                    </span>
                </a>
                <a href="{{ route('admin.showcase.index') }}" class="flex items-center gap-3 p-3 rounded-xl hover:bg-[var(--bg-hover)] transition-colors">
                    <span class="w-9 h-9 rounded-lg bg-[var(--bg-muted)] text-[var(--text-secondary)] flex items-center justify-center shrink-0"><x-admin.icon name="rocket" :size="16" /></span>
                    <span>
                        <span class="block text-[13px] font-medium text-[var(--text-primary)]">Proyek Saya</span>
                        <span class="block text-[12px] text-[var(--text-muted)]">{{ ($myProjects ?? collect())->count() }} proyek di showcase</span>
                    </span>
                </a>
            </div>
        </div>

        <div class="lg:col-span-2 csa-panel p-5">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-[14px] font-semibold text-[var(--text-primary)]">Kegiatan Mendatang</h3>
                <a href="{{ route('admin.events.index') }}" class="text-[12px] font-medium text-[var(--accent-bright)] hover:underline">Lihat Semua</a>
            </div>
            <div class="space-y-2">
                @forelse($upcomingEvents as $event)
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 p-3 rounded-xl bg-[var(--bg-muted)]">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="text-[12px] font-medium text-[var(--text-secondary)]">{{ $event->event_date->translatedFormat('d M Y') }}</span>
                                @if($event->attendance_enabled && !$event->attendance_closed_at)
                                    <span class="csa-chip csa-chip-green">Presensi Dibuka</span>
                                @endif
                            </div>
                            <p class="text-[13px] font-medium text-[var(--text-primary)] mt-0.5 truncate">{{ $event->title }}</p>
                            <p class="text-[12px] text-[var(--text-muted)] mt-0.5 inline-flex items-center gap-1.5"><x-admin.icon name="map-pin" :size="13" /> {{ $event->location ?? 'Online' }}</p>
                        </div>
                        @if($event->attendance_enabled && !$event->attendance_closed_at)
                            @if($event->self_attendance_enabled)
                                <form action="{{ route('admin.member.attendance.self', $event) }}" method="POST" class="shrink-0">
                                    @csrf
                                    <button type="submit" class="csa-btn csa-btn-primary !min-h-[38px] !text-[12px] !px-4">
                                        <x-admin.icon name="user-check" :size="14" /> Presensi Mandiri
                                    </button>
                                </form>
                            @else
                                <span class="csa-chip csa-chip-amber shrink-0">Scan QR untuk presensi</span>
                            @endif
                        @elseif($event->attendance_closed_at)
                            <span class="csa-chip shrink-0">Selesai</span>
                        @else
                            <span class="csa-chip csa-chip-amber shrink-0">Segera</span>
                        @endif
                    </div>
                @empty
                    <p class="text-center text-[13px] text-[var(--text-muted)] py-8">Belum ada kegiatan mendatang.</p>
                @endforelse
            </div>
        </div>
    </section>

    <div class="csa-panel overflow-hidden">
        <div class="px-5 py-3.5 border-b border-[var(--border-soft)]">
            <h3 class="text-[14px] font-semibold text-[var(--text-primary)]">Riwayat Kehadiran</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full min-w-[600px]">
                <thead>
                    <tr>
                        <th class="csa-table-th">Kegiatan</th>
                        <th class="csa-table-th">Tanggal</th>
                        <th class="csa-table-th">Status</th>
                        <th class="csa-table-th">Waktu Presensi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentAttendances as $attendance)
                        @php
                            $statusLabel = ['present' => 'Hadir', 'permit' => 'Izin', 'sick' => 'Sakit', 'absent' => 'Alfa'][$attendance->status] ?? ucfirst($attendance->status);
                            $statusClass = ['present' => 'csa-chip-green', 'permit' => 'csa-chip-amber', 'sick' => 'csa-chip-blue', 'absent' => 'csa-chip-red'][$attendance->status] ?? '';
                        @endphp
                        <tr class="csa-table-row">
                            <td class="csa-table-td font-medium text-[var(--text-primary)]">{{ $attendance->event->title }}</td>
                            <td class="csa-table-td">{{ $attendance->event->event_date->translatedFormat('d M Y') }}</td>
                            <td class="csa-table-td">
                                <span class="csa-chip {{ $statusClass }}">{{ $statusLabel }}</span>
                            </td>
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
    </div>
    <x-admin.activity-feed :logs="$systemActivity" />
</div>
@endsection
