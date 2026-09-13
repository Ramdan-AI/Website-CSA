@extends('layouts.admin')

@section('content')
<div class="max-w-xl mx-auto">
    <div>
        <a href="{{ route('admin.attendances.event', $event) }}" class="text-xs text-gray-400 hover:text-gray-700"><i class="fas fa-arrow-left mr-1"></i> Kembali</a>
        <h1 class="text-2xl font-bold text-gray-900 mt-3">QR Presensi</h1>
        <p class="text-sm text-gray-400 mt-1">{{ $event->title }} · {{ $event->event_date?->format('d M Y') }}</p>
    </div>

    <div class="mt-6 bg-white border border-gray-200 rounded-2xl shadow-sm p-8 text-center">
        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-4">Arahkan kamera anggota ke kode berikut</p>

        @php
            $scanUrl = route('admin.attendance.scan', ['event' => $event->id, 'code' => $event->attendance_code]);
            $qrApiUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=280x280&data=' . urlencode($scanUrl);
        @endphp

        <img src="{{ $qrApiUrl }}" alt="QR Presensi" class="w-56 h-56 mx-auto">

        <div class="mt-6 p-4 bg-gray-50 rounded-xl">
            <p class="text-[10px] text-gray-400 uppercase font-semibold">Kode Manual</p>
            <p class="text-3xl font-bold font-mono tracking-[0.3em] text-gray-900 mt-1">{{ $event->attendance_code }}</p>
        </div>

        <a href="{{ $scanUrl }}" target="_blank" class="mt-4 inline-flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-700 text-xs font-semibold rounded-xl hover:bg-gray-200">
            <i class="fas fa-external-link-alt"></i> Link Presensi Langsung
        </a>
    </div>
</div>
@endsection