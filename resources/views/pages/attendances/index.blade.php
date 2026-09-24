@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-[26px] sm:text-[30px] font-bold text-[var(--text-primary)] tracking-[-0.03em]">Presensi Kegiatan</h1>
        <p class="text-[13px] text-[var(--text-muted)] mt-1">Rekap kehadiran seluruh kegiatan UKM CSA.</p>
    </div>

    <form method="GET" data-ajax-filter class="csa-panel p-4 space-y-3">
        <div class="relative">
            <x-admin.icon name="search" :size="18" class="absolute left-4 top-1/2 -translate-y-1/2 text-[var(--text-muted)] pointer-events-none" />
            <input type="search" name="search" value="{{ request('search') }}" placeholder="Cari nama kegiatan..." class="csa-input !pl-[48px] !rounded-full csa-search-input" aria-label="Cari kegiatan">
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
            <x-form.date name="date_from" :value="request('date_from')" placeholder="Dari tanggal" aria-label="Dari tanggal" />
            <x-form.date name="date_to" :value="request('date_to')" placeholder="Sampai tanggal" aria-label="Sampai tanggal" />
            <x-form.select name="sort" :value="request('sort', 'date_desc')" onchange="this.form.submit()" aria-label="Urutkan"
                :options="['date_desc' => 'Tanggal terbaru', 'date_asc' => 'Tanggal terlama', 'title_asc' => 'Nama A-Z', 'title_desc' => 'Nama Z-A']" />
            <div class="flex gap-2">
                <button type="submit" class="csa-btn csa-btn-primary flex-1">Cari</button>
                <a href="{{ route('admin.attendances.index') }}" class="csa-btn csa-btn-ghost">Reset</a>
            </div>
        </div>
    </form>

    <x-admin.table
        :headers="[
            ['label' => 'Kegiatan'],
            ['label' => 'Tanggal'],
            ['label' => 'Total'],
            ['label' => 'Hadir'],
            ['label' => 'Izin'],
            ['label' => 'Sakit'],
            ['label' => 'Alfa'],
            ['label' => 'Status'],
            ['label' => 'Aksi', 'width' => 'w-32'],
        ]"
        empty-icon="calendar-x"
        empty-title="Tidak ada kegiatan ditemukan"
        empty-description="Coba ubah kriteria pencarian."
        :has-pages="$events->hasPages()"
        :paginator="$events"
    >
        @forelse($events as $event)
            <tr class="csa-table-row">
                <td class="csa-table-td">
                    <p class="text-[13px] font-medium text-[var(--text-primary)]">{{ $event->title }}</p>
                    <p class="text-[12px] text-[var(--text-muted)] truncate max-w-[220px]">{{ $event->location ?? 'Lokasi belum ditentukan' }}</p>
                </td>
                <td class="csa-table-td whitespace-nowrap">{{ $event->event_date?->translatedFormat('d M Y') ?: '-' }}</td>
                <td class="csa-table-td font-medium text-[var(--text-primary)]">{{ $event->attendances_count }}</td>
                <td class="csa-table-td text-emerald-600 dark:text-emerald-400 font-medium">{{ $event->present_count }}</td>
                <td class="csa-table-td text-amber-600 dark:text-amber-400 font-medium">{{ $event->permit_count }}</td>
                <td class="csa-table-td text-blue-600 dark:text-blue-400 font-medium">{{ $event->sick_count }}</td>
                <td class="csa-table-td text-red-600 dark:text-red-400 font-medium">{{ $event->absent_count }}</td>
                <td class="csa-table-td">
                    <span class="csa-chip {{ $event->attendance_enabled ? 'csa-chip-green' : '' }}">{{ $event->attendance_enabled ? 'Aktif' : 'Nonaktif' }}</span>
                </td>
                <td class="csa-table-td">
                    <div class="flex items-center gap-1.5">
                        <a href="{{ route('admin.attendances.event', $event) }}" class="csa-btn csa-btn-ghost !min-h-[34px] !px-3 !text-[12px]">Kelola</a>
                        <a href="{{ route('admin.attendance.export', $event) }}" class="csa-icon-btn" title="Export CSV" aria-label="Export CSV">
                            <x-admin.icon name="download" :size="16" />
                        </a>
                    </div>
                </td>
            </tr>
        @empty
        @endforelse
    </x-admin.table>
</div>
@endsection
