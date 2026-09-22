@extends('layouts.admin')

@section('content')
<div class="max-w-xl mx-auto space-y-4 sm:space-y-5">
    <div>
        <a href="{{ route('admin.attendances.event', $event) }}" class="inline-flex items-center gap-1.5 text-[12px] text-[var(--text-muted)] hover:text-[var(--text-primary)] transition-colors">
            <x-admin.icon name="arrow-left" :size="14" /> Kembali
        </a>
        <h1 class="text-[22px] sm:text-[26px] font-bold text-[var(--text-primary)] tracking-[-0.03em] mt-2">QR Presensi</h1>
        <p class="text-[13px] text-[var(--text-muted)] mt-0.5">{{ $event->title }} · {{ $event->event_date?->translatedFormat('d M Y') }}</p>
    </div>

    <div class="csa-card p-6 sm:p-8 text-center">
        <p class="text-[12px] font-medium text-[var(--text-muted)] mb-4">Arahkan kamera anggota ke kode berikut.</p>

        @php
            $scanUrl = route('admin.attendance.scan', ['event' => $event->id, 'code' => $event->attendance_code]);
            $qrApiUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=280x280&data=' . urlencode($scanUrl);
        @endphp

        <div class="inline-block p-4 rounded-2xl bg-white">
            <img src="{{ $qrApiUrl }}" alt="QR Presensi" class="w-48 h-48 sm:w-56 sm:h-56">
        </div>

        <div class="mt-6 p-4 rounded-xl bg-[var(--bg-muted)]">
            <p class="text-[11px] font-medium text-[var(--text-muted)]">Kode Manual</p>
            <p class="text-[26px] sm:text-[30px] font-bold font-mono tracking-[0.3em] text-[var(--text-primary)] mt-1 break-all">{{ $event->attendance_code }}</p>
        </div>

        <a href="{{ $scanUrl }}" target="_blank" rel="noopener" class="csa-btn csa-btn-ghost mt-4">
            <x-admin.icon name="external-link" :size="16" /> Buka Link Presensi
        </a>
    </div>
</div>
@endsection
