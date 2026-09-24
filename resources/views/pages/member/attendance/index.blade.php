@extends('layouts.admin')

@section('content')
<div class="space-y-4 sm:space-y-5">

    <div>
        <h1 class="text-[26px] sm:text-[30px] font-bold text-[var(--text-primary)] tracking-[-0.03em]">Riwayat Kehadiran</h1>
        <p class="text-[13px] text-[var(--text-muted)] mt-1">Lacak kehadiran Anda di seluruh kegiatan UKM CSA.</p>
    </div>

    <section class="grid grid-cols-2 lg:grid-cols-4 gap-3">
        @foreach ([
            ['Hadir', $stats['present'], 'user-check'],
            ['Izin', $stats['permit'], 'file-signature'],
            ['Sakit', $stats['sick'], 'thermometer'],
            ['Alfa', $stats['absent'], 'calendar-x'],
        ] as [$label, $value, $icon])
            <div class="csa-panel p-4">
                <div class="flex items-center justify-between">
                    <p class="csa-stat-label">{{ $label }}</p>
                    <x-admin.icon :name="$icon" :size="16" class="text-[var(--text-muted)]" />
                </div>
                <p class="csa-stat-number mt-2">{{ number_format($value) }}</p>
            </div>
        @endforeach
    </section>

    <form method="GET" class="csa-panel p-3 sm:p-4 grid grid-cols-1 sm:grid-cols-[1fr_auto_auto] gap-3">
        <div class="relative">
            <x-admin.icon name="search" :size="16" class="absolute left-3.5 top-1/2 -translate-y-1/2 text-[var(--text-muted)] pointer-events-none" />
            <input type="search" name="search" value="{{ request('search') }}" placeholder="Cari nama kegiatan..."
                class="csa-input pl-10" aria-label="Cari kegiatan">
        </div>
        <x-form.select name="status" :value="request('status')" onchange="this.form.submit()" class="sm:w-40" aria-label="Filter status"
            placeholder="Semua Status"
            :options="['present' => 'Hadir', 'permit' => 'Izin', 'sick' => 'Sakit', 'absent' => 'Alfa']" />
        <x-form.select name="sort" :value="request('sort', 'date_desc')" onchange="this.form.submit()" class="sm:w-48" aria-label="Urutkan"
            :options="['date_desc' => 'Tanggal terbaru', 'date_asc' => 'Tanggal terlama', 'title_asc' => 'Nama A-Z', 'title_desc' => 'Nama Z-A']" />
    </form>

    <div class="csa-panel overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[640px]">
                <thead>
                    <tr>
                        <th class="csa-table-th">Kegiatan</th>
                        <th class="csa-table-th">Tanggal</th>
                        <th class="csa-table-th">Status</th>
                        <th class="csa-table-th">Check-in</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($attendances as $attendance)
                        @php
                            $statusMap = [
                                'present' => ['Hadir', 'csa-chip-green'],
                                'permit' => ['Izin', 'csa-chip-amber'],
                                'sick' => ['Sakit', 'csa-chip-blue'],
                                'absent' => ['Alfa', 'csa-chip-red'],
                            ];
                        @endphp
                        <tr class="csa-table-row">
                            <td class="csa-table-td">
                                <p class="font-medium text-[var(--text-primary)]">{{ $attendance->event->title }}</p>
                                <p class="text-[11px] text-[var(--text-muted)] mt-0.5">{{ $attendance->event->location ?? 'Lokasi tidak ditentukan' }}</p>
                            </td>
                            <td class="csa-table-td whitespace-nowrap">{{ $attendance->event->event_date?->translatedFormat('d M Y') }}</td>
                            <td class="csa-table-td">
                                <span class="csa-chip {{ $statusMap[$attendance->status][1] ?? 'csa-chip-blue' }}">{{ $statusMap[$attendance->status][0] ?? 'Tercatat' }}</span>
                            </td>
                            <td class="csa-table-td whitespace-nowrap">{{ $attendance->check_in_at?->format('H:i') ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-14 text-center">
                                <x-admin.icon name="calendar-x" :size="32" class="mx-auto text-[var(--text-muted)]" />
                                <p class="text-[13px] font-medium text-[var(--text-secondary)] mt-3">Belum ada riwayat kehadiran</p>
                                <p class="text-[12px] text-[var(--text-muted)] mt-1">Riwayat muncul setelah Anda mengikuti kegiatan.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($attendances->hasPages())
            <div class="px-4 py-3 border-t border-[var(--border-soft)]">{{ $attendances->links() }}</div>
        @endif
    </div>
</div>
@endsection
