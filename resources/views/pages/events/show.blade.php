@extends('layouts.admin')

@php
    $attendanceChip = match (true) {
        $event->attendance_enabled && !$event->attendance_closed_at => ['Presensi Dibuka', 'csa-chip-green'],
        (bool) $event->attendance_closed_at => ['Presensi Ditutup', 'csa-chip-red'],
        default => ['Belum Dibuka', 'csa-chip-amber'],
    };
@endphp

@section('content')
<div class="space-y-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <a href="{{ route('admin.events.index') }}" class="inline-flex items-center gap-1.5 text-[12px] text-[var(--text-muted)] hover:text-[var(--text-primary)] transition-colors">
                <x-admin.icon name="arrow-left" :size="14" /> Kembali ke Acara
            </a>
            <h1 class="text-[22px] sm:text-[26px] font-bold text-[var(--text-primary)] tracking-[-0.03em] mt-2">{{ $event->title }}</h1>
            <p class="text-[13px] text-[var(--text-muted)] mt-1 inline-flex items-center gap-3 flex-wrap">
                <span class="csa-chip {{ $event->type === 'study_club' ? 'csa-chip-violet' : 'csa-chip-blue' }}">{{ \App\Models\Event::TYPES[$event->type] ?? 'Acara' }}</span>
                <span class="inline-flex items-center gap-1.5"><x-admin.icon name="calendar-days" :size="14" /> {{ $event->event_date?->translatedFormat('d M Y') }}</span>
                @if($event->location)
                    <span class="inline-flex items-center gap-1.5"><x-admin.icon name="map-pin" :size="14" /> {{ $event->location }}</span>
                @endif
            </p>
        </div>
        @can('manage-events')
            <a href="{{ route('admin.events.edit', $event) }}" class="csa-btn csa-btn-ghost">
                <x-admin.icon name="pen" :size="16" /> Edit Acara
            </a>
        @endcan
    </div>

    @if($event->banner_url)
        <div class="w-full max-h-[300px] rounded-2xl overflow-hidden bg-[var(--bg-muted)]">
            <img src="{{ $event->banner_url }}" alt="{{ $event->title }}" class="w-full h-full object-cover">
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <section class="lg:col-span-2 csa-panel p-5">
            <h3 class="text-[14px] font-semibold text-[var(--text-primary)] mb-3">Deskripsi Kegiatan</h3>
            <div class="text-[13px] text-[var(--text-secondary)] leading-relaxed">{!! nl2br(e($event->description)) !!}</div>
        </section>

        <section class="csa-panel p-5 space-y-4 h-fit">
            <h3 class="text-[14px] font-semibold text-[var(--text-primary)]">Pendaftaran & Presensi</h3>

            <div>
                <p class="text-[11px] text-[var(--text-muted)] mb-1.5">Status Acara</p>
                <span class="csa-chip {{ $event->status === 'published' ? 'csa-chip-green' : '' }}">{{ ucfirst($event->status) }}</span>
            </div>

            <div>
                <p class="text-[11px] text-[var(--text-muted)] mb-1.5">Status Presensi</p>
                <span class="csa-chip {{ $attendanceChip[1] }}">{{ $attendanceChip[0] }}</span>
            </div>

            @if($event->registration_link)
                <a href="{{ $event->registration_link }}" target="_blank" rel="noopener" class="csa-btn csa-btn-ghost w-full">
                    <x-admin.icon name="external-link" :size="16" /> Link Pendaftaran Eksternal
                </a>
            @endif

            @if($event->attendance_enabled && !$event->attendance_closed_at && auth()->user()?->member)
                @php
                    $alreadyPresent = $event->attendances->contains(fn ($a) => $a->member_id === auth()->user()->member?->id && $a->status === 'present');
                @endphp
                @if($alreadyPresent)
                    <span class="csa-chip csa-chip-green w-full justify-center">Anda sudah tercatat hadir</span>
                @elseif($event->self_attendance_enabled)
                    <form action="{{ route('admin.member.attendance.self', $event) }}" method="POST">
                        @csrf
                        <button type="submit" class="csa-btn csa-btn-primary w-full">
                            <x-admin.icon name="user-check" :size="16" /> Presensi Mandiri
                        </button>
                    </form>
                @else
                    <button type="button" class="csa-btn csa-btn-primary w-full" disabled title="Presensi mandiri belum diaktifkan panitia">
                        <x-admin.icon name="user-check" :size="16" /> Presensi Mandiri
                    </button>
                    <p class="text-[12px] text-[var(--text-muted)] flex items-center gap-1.5">
                        <x-admin.icon name="info" :size="13" /> Presensi mandiri belum diaktifkan panitia — silakan scan QR.
                    </p>
                @endif
            @endif

            @can('manage-attendances')
                <a href="{{ route('admin.attendances.event', $event) }}" class="csa-btn csa-btn-ghost w-full">
                    <x-admin.icon name="clipboard-check" :size="16" /> Kelola Presensi & Export
                </a>
            @endcan
        </section>
    </div>
</div>
@endsection

