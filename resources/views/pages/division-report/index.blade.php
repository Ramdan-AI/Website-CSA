@extends('layouts.admin')

@section('content')
<div class="space-y-4 sm:space-y-5">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h1 class="text-[26px] sm:text-[30px] font-bold text-[var(--text-primary)] tracking-[-0.03em]">Rekap Kehadiran Divisi</h1>
            <p class="text-[13px] text-[var(--text-muted)] mt-1">Kehadiran anggota divisi {{ $division->name }}.</p>
        </div>

        <div class="flex items-center gap-2">
            <form method="GET" class="flex items-center gap-2">
                <x-form.select name="event_id" :value="$eventId" onchange="this.form.submit()" class="!min-h-[40px] w-56" aria-label="Filter kegiatan"
                    placeholder="Semua kegiatan"
                    :options="collect($events)->mapWithKeys(fn ($e) => [$e->id => Str::limit($e->title, 34)])->all()" />
            </form>
            <a href="{{ route('admin.division-report.export', ['event_id' => $eventId]) }}" class="csa-btn csa-btn-ghost !min-h-[40px] !text-[13px]">
                <x-admin.icon name="download" :size="15" /> Ekspor CSV
            </a>
        </div>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
        <div class="csa-panel p-4">
            <p class="csa-stat-label">Anggota Divisi</p>
            <p class="csa-stat-number mt-2">{{ number_format($rows->count()) }}</p>
        </div>
        <div class="csa-panel p-4">
            <p class="csa-stat-label">Rata-rata Hadir</p>
            <p class="csa-stat-number mt-2">{{ $divisionAverage }}%</p>
        </div>
        <div class="csa-panel p-4">
            <p class="csa-stat-label">Total Tercatat</p>
            <p class="csa-stat-number mt-2">{{ number_format($rows->sum('total')) }}</p>
        </div>
        <div class="csa-panel p-4">
            <p class="csa-stat-label">Total Hadir</p>
            <p class="csa-stat-number mt-2">{{ number_format($rows->sum('present')) }}</p>
        </div>
    </div>

    <x-admin.table :headers="[
        ['label' => 'Anggota'],
        ['label' => 'Total'],
        ['label' => 'Hadir'],
        ['label' => 'Izin'],
        ['label' => 'Sakit'],
        ['label' => 'Alfa'],
        ['label' => 'Persentase'],
    ]"
        empty-icon="clipboard-check"
        empty-title="Belum ada data kehadiran"
        empty-description="Rekap muncul setelah anggota tercatat pada kegiatan.">
        @forelse ($rows as $row)
            <tr class="csa-table-row">
                <td class="csa-table-td">
                    <p class="text-[13px] font-medium text-[var(--text-primary)]">{{ $row['member']->name }}</p>
                    <p class="text-[12px] text-[var(--text-muted)] font-mono">{{ $row['member']->nim ?? '-' }}</p>
                </td>
                <td class="csa-table-td">{{ $row['total'] }}</td>
                <td class="csa-table-td text-emerald-600 dark:text-emerald-400 font-medium">{{ $row['present'] }}</td>
                <td class="csa-table-td text-amber-600 dark:text-amber-400 font-medium">{{ $row['permit'] }}</td>
                <td class="csa-table-td text-blue-600 dark:text-blue-400 font-medium">{{ $row['sick'] }}</td>
                <td class="csa-table-td text-red-600 dark:text-red-400 font-medium">{{ $row['absent'] }}</td>
                <td class="csa-table-td">
                    <span class="csa-chip {{ $row['rate'] >= 75 ? 'csa-chip-green' : ($row['rate'] >= 50 ? 'csa-chip-amber' : 'csa-chip-red') }}">
                        {{ $row['rate'] }}%
                    </span>
                </td>
            </tr>
        @empty
        @endforelse
    </x-admin.table>
</div>
@endsection
