@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <div>
        <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest">Keanggotaan</p>
        <h1 class="text-3xl font-bold text-gray-900 mt-1">Presensi Kegiatan</h1>
        <p class="text-sm text-gray-400 mt-1.5 font-medium">Pilih kegiatan untuk membuka presensi mandiri, QR, atau pencatatan manual.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
        @forelse($events as $event)
            <article class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5 flex flex-col">
                <div class="flex items-start justify-between gap-4">
                    <div class="min-w-0">
                        <p class="text-xs font-semibold text-blue-600">{{ $event->event_date?->format('d M Y') }}</p>
                        <h2 class="mt-1 text-base font-bold text-gray-900 line-clamp-2">{{ $event->title }}</h2>
                        <p class="mt-1 text-xs text-gray-400 truncate">{{ $event->location ?? 'Lokasi belum ditentukan' }}</p>
                    </div>
                    <span class="shrink-0 px-2.5 py-1 rounded-full text-xs font-semibold {{ $event->attendance_enabled ? 'bg-emerald-50 text-emerald-700' : 'bg-gray-100 text-gray-500' }}">
                        {{ $event->attendance_enabled ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </div>

                <dl class="grid grid-cols-4 gap-2 py-5 mt-5 border-y border-gray-100 text-center">
                    <div><dt class="text-[10px] text-gray-400 uppercase">Hadir</dt><dd class="mt-1 text-sm font-bold text-emerald-600">{{ $event->present_count }}</dd></div>
                    <div><dt class="text-[10px] text-gray-400 uppercase">Izin</dt><dd class="mt-1 text-sm font-bold text-amber-600">{{ $event->permit_count }}</dd></div>
                    <div><dt class="text-[10px] text-gray-400 uppercase">Sakit</dt><dd class="mt-1 text-sm font-bold text-blue-600">{{ $event->sick_count }}</dd></div>
                    <div><dt class="text-[10px] text-gray-400 uppercase">Alfa</dt><dd class="mt-1 text-sm font-bold text-red-600">{{ $event->absent_count }}</dd></div>
                </dl>

                <div class="mt-5 flex items-center gap-2">
                    <a href="{{ route('admin.attendances.event', $event) }}" class="flex-1 py-2.5 text-center bg-gray-900 text-white text-xs font-semibold rounded-xl hover:bg-gray-800 transition-colors">
                        Kelola Presensi
                    </a>
                    <a href="{{ route('admin.attendance.export', $event) }}" class="w-10 h-10 rounded-xl border border-gray-200 text-gray-600 flex items-center justify-center hover:bg-gray-50" title="Export CSV">
                        <i class="fas fa-file-export text-xs"></i>
                    </a>
                </div>
            </article>
        @empty
            <div class="col-span-full bg-white rounded-2xl border border-gray-200 p-12 text-center text-gray-400">
                <i class="fas fa-calendar-times text-4xl mb-3"></i>
                <p class="text-sm font-semibold">Belum ada kegiatan untuk dikelola.</p>
            </div>
        @endforelse
    </div>

    {{ $events->links() }}
</div>
@endsection
