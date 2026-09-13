@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
        <div>
            <a href="{{ route('admin.attendances.index') }}" class="text-xs text-gray-400 hover:text-gray-700"><i class="fas fa-arrow-left mr-1"></i> Kembali ke Presensi</a>
            <h1 class="text-3xl font-bold text-gray-900 mt-2">{{ $event->title }}</h1>
            <p class="text-sm text-gray-400 mt-1">{{ $event->event_date?->format('d M Y') }} · {{ $event->location ?? 'Lokasi belum ditentukan' }}</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <form method="POST" action="{{ route('admin.attendance.toggle', $event) }}">@csrf<button class="px-3 py-2 rounded-lg text-xs font-semibold {{ $event->attendance_enabled ? 'bg-red-50 text-red-600' : 'bg-emerald-50 text-emerald-700' }}">{{ $event->attendance_enabled ? 'Nonaktifkan Mandiri' : 'Aktifkan Mandiri' }}</button></form>
            @if($event->attendance_enabled && !$event->attendance_closed_at)
                <form method="POST" action="{{ route('admin.attendance.close', $event) }}">@csrf<button class="px-3 py-2 rounded-lg bg-amber-50 text-amber-700 text-xs font-semibold">Tutup Sesi</button></form>
            @else
                <form method="POST" action="{{ route('admin.attendance.open', $event) }}">@csrf<button class="px-3 py-2 rounded-lg bg-blue-50 text-blue-700 text-xs font-semibold">Buka Sesi</button></form>
            @endif
            <a href="{{ route('admin.attendances.qr', $event) }}" class="px-3 py-2 rounded-lg bg-gray-100 text-gray-700 text-xs font-semibold">Tampilkan QR</a>
            <a href="{{ route('admin.attendance.export', $event) }}" class="px-3 py-2 rounded-lg bg-gray-100 text-gray-700 text-xs font-semibold">Export CSV</a>
        </div>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
        <div class="bg-white border border-gray-200 rounded-xl p-4"><p class="text-xs text-gray-400">Hadir</p><p class="text-2xl font-bold text-emerald-600 mt-1">{{ $event->present_count }}</p></div>
        <div class="bg-white border border-gray-200 rounded-xl p-4"><p class="text-xs text-gray-400">Izin</p><p class="text-2xl font-bold text-amber-600 mt-1">{{ $event->permit_count }}</p></div>
        <div class="bg-white border border-gray-200 rounded-xl p-4"><p class="text-xs text-gray-400">Sakit</p><p class="text-2xl font-bold text-blue-600 mt-1">{{ $event->sick_count }}</p></div>
        <div class="bg-white border border-gray-200 rounded-xl p-4"><p class="text-xs text-gray-400">Alfa</p><p class="text-2xl font-bold text-red-600 mt-1">{{ $event->absent_count }}</p></div>
    </div>

    <form method="POST" action="{{ route('admin.attendances.bulk', $event) }}" class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
        @csrf
        <div class="p-5 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div><h2 class="text-base font-bold text-gray-900">Daftar Anggota</h2><p class="text-xs text-gray-400 mt-1">Atur status setiap anggota lalu simpan sekaligus.</p></div>
            <button type="submit" class="px-4 py-2.5 bg-gray-900 text-white text-xs font-semibold rounded-xl hover:bg-gray-800">Simpan Presensi</button>
        </div>
        <div class="divide-y divide-gray-100">
            @foreach($members as $member)
                @php $attendance = $attendances->get($member->id); $status = $attendance?->status ?? 'absent'; @endphp
                <div class="p-4 flex flex-col lg:flex-row lg:items-center gap-4">
                    <div class="flex items-center gap-3 flex-1 min-w-0">
                        <div class="w-9 h-9 rounded-full bg-gray-100 flex items-center justify-center text-gray-600 text-xs font-bold">{{ substr($member->name, 0, 1) }}</div>
                        <div class="min-w-0"><p class="text-sm font-semibold text-gray-900 truncate">{{ $member->name }}</p><p class="text-xs text-gray-400">{{ $member->nim }} · {{ $member->division?->name ?? '-' }} · {{ $member->position?->name ?? 'Anggota' }}</p></div>
                    </div>
                    <div class="grid grid-cols-4 gap-2 w-full lg:w-auto">
                        @foreach(['present' => 'Hadir', 'permit' => 'Izin', 'sick' => 'Sakit', 'absent' => 'Alfa'] as $value => $label)
                            <label class="cursor-pointer"><input type="radio" name="attendances[{{ $member->id }}][status]" value="{{ $value }}" class="peer sr-only" {{ $status === $value ? 'checked' : '' }}><span class="block min-w-[62px] px-2 py-2 text-center rounded-lg border border-gray-200 text-[11px] font-semibold text-gray-500 peer-checked:bg-gray-900 peer-checked:text-white peer-checked:border-gray-900">{{ $label }}</span></label>
                        @endforeach
                    </div>
                    <input type="hidden" name="attendances[{{ $member->id }}][member_id]" value="{{ $member->id }}">
                </div>
            @endforeach
        </div>
    </form>
</div>
@endsection
