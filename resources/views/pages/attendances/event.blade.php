@extends('layouts.admin')

@section('content')
<div class="space-y-4 sm:space-y-5">
    <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
        <div>
            <a href="{{ route('admin.attendances.index') }}" class="inline-flex items-center gap-1.5 text-[12px] text-[var(--text-muted)] hover:text-[var(--text-primary)] transition-colors">
                <x-admin.icon name="arrow-left" :size="14" /> Kembali ke Presensi
            </a>
            <h1 class="text-[22px] sm:text-[26px] font-bold text-[var(--text-primary)] tracking-[-0.03em] mt-2">{{ $event->title }}</h1>
            <p class="text-[13px] text-[var(--text-muted)] mt-0.5">{{ $event->event_date?->translatedFormat('d M Y') }} · {{ $event->location ?? 'Lokasi belum ditentukan' }}</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <form method="POST" action="{{ route('admin.attendance.toggle', $event) }}">
                @csrf
                <button class="csa-btn {{ $event->attendance_enabled ? 'csa-btn-danger' : 'csa-btn-ghost' }} !min-h-[40px] !px-4 !text-[12px]">
                    {{ $event->attendance_enabled ? 'Nonaktifkan Mandiri' : 'Aktifkan Mandiri' }}
                </button>
            </form>
            @if($event->attendance_enabled && !$event->attendance_closed_at)
                <form method="POST" action="{{ route('admin.attendance.close', $event) }}">
                    @csrf
                    <button class="csa-btn csa-btn-ghost !min-h-[40px] !px-4 !text-[12px]">Tutup Sesi</button>
                </form>
            @else
                <form method="POST" action="{{ route('admin.attendance.open', $event) }}">
                    @csrf
                    <button class="csa-btn csa-btn-ghost !min-h-[40px] !px-4 !text-[12px]">Buka Sesi</button>
                </form>
            @endif
            <a href="{{ route('admin.attendances.qr', $event) }}" class="csa-btn csa-btn-ghost !min-h-[40px] !px-4 !text-[12px]">
                <x-admin.icon name="qr-code" :size="15" /> QR
            </a>
            <a href="{{ route('admin.attendance.export', $event) }}" class="csa-btn csa-btn-ghost !min-h-[40px] !px-4 !text-[12px]">
                <x-admin.icon name="download" :size="15" /> Export
            </a>
        </div>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
        @foreach ([
            ['Hadir', $event->present_count, 'text-emerald-600 dark:text-emerald-400'],
            ['Izin', $event->permit_count, 'text-amber-600 dark:text-amber-400'],
            ['Sakit', $event->sick_count, 'text-blue-600 dark:text-blue-400'],
            ['Alfa', $event->absent_count, 'text-red-600 dark:text-red-400'],
        ] as [$label, $value, $tone])
            <div class="csa-panel p-4">
                <p class="csa-stat-label">{{ $label }}</p>
                <p class="text-[22px] font-bold mt-1.5 {{ $tone }}">{{ $value }}</p>
            </div>
        @endforeach
    </div>

    <form method="POST" action="{{ route('admin.attendances.bulk', $event) }}" class="csa-panel overflow-hidden">
        @csrf
        <div class="p-4 sm:p-5 border-b border-[var(--border-soft)] flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="text-[14px] font-semibold text-[var(--text-primary)]">Daftar Anggota</h2>
                <p class="text-[12px] text-[var(--text-muted)] mt-0.5">Atur status setiap anggota lalu simpan sekaligus.</p>
            </div>
            <button type="submit" class="csa-btn csa-btn-primary">Simpan Presensi</button>
        </div>
        <div class="divide-y divide-[var(--border-soft)]">
            @foreach($members as $member)
                @php
                    $attendance = $attendances->get($member->id);
                    $status = $attendance?->status ?? 'absent';
                @endphp
                <div class="p-4 flex flex-col lg:flex-row lg:items-center gap-4">
                    <div class="flex items-center gap-3 flex-1 min-w-0">
                        <span class="w-9 h-9 rounded-full bg-[var(--bg-muted)] flex items-center justify-center text-[12px] font-semibold text-[var(--text-secondary)] shrink-0">{{ substr($member->name, 0, 1) }}</span>
                        <div class="min-w-0">
                            <p class="text-[13px] font-medium text-[var(--text-primary)] truncate">{{ $member->name }}</p>
                            <p class="text-[12px] text-[var(--text-muted)]">{{ $member->nim }} · {{ $member->division?->name ?? '-' }} · {{ $member->position?->name ?? 'Anggota' }}</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-4 gap-2 w-full lg:w-auto">
                        @foreach(['present' => 'Hadir', 'permit' => 'Izin', 'sick' => 'Sakit', 'absent' => 'Alfa'] as $value => $label)
                            <label class="cursor-pointer">
                                <input type="radio" name="attendances[{{ $member->id }}][status]" value="{{ $value }}" class="peer sr-only" {{ $status === $value ? 'checked' : '' }}>
                                <span class="block min-w-[62px] px-2 py-2 text-center rounded-lg border border-[var(--border-strong)] text-[12px] font-medium text-[var(--text-secondary)] peer-checked:bg-[var(--accent)] peer-checked:text-white peer-checked:border-[var(--accent)] transition-colors">{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>
                    <input type="hidden" name="attendances[{{ $member->id }}][member_id]" value="{{ $member->id }}">
                </div>
            @endforeach
        </div>
    </form>
</div>
@endsection
