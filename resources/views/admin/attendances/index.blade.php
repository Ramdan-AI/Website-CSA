@extends('layouts.admin')

@section('content')

<x-page-header
    title="Attendances Management"
    subtitle="Kelola seluruh data absensi member pada event."
>
    <x-slot:actions>
        <a href="{{ route('admin.attendances.create') }}" class="btn-csa-primary">
            <i class="fas fa-plus"></i> Tambah Absensi
        </a>
    </x-slot:actions>
</x-page-header>

<x-admin.crud-table :pagination="$attendances">

    <x-slot:head>
        <tr>
            <th>Event</th>
            <th>Member</th>
            <th width="140">Status</th>
            <th width="200">Check In</th>
            <th width="140" class="text-end">Aksi Sistem</th>
        </tr>
    </x-slot:head>

    @forelse($attendances as $attendance)
        <tr>
            <td>
                <div class="data-name">
                    {{ $attendance->event->title }}
                </div>
            </td>

            <td>
                <div class="data-description">
                    {{ $attendance->member->name }}
                </div>
            </td>

            <td>
                <div class="data-description">
                    {{ ucfirst($attendance->status) }}
                </div>
            </td>

            <td>
                <div class="data-description">
                    {{ $attendance->check_in_at ?? '-' }}
                </div>
            </td>

            <td>
                <x-admin.action-buttons
                    :editRoute="route('admin.attendances.edit', $attendance)"
                    :deleteRoute="route('admin.attendances.destroy', $attendance)"
                />
            </td>
        </tr>
    @empty
        <x-admin.empty-state
            colspan="5"
            icon="clipboard-check"
            title="Database Kosong"
            subtitle="Belum ada data absensi yang tercatat."
        />
    @endforelse

</x-admin.crud-table>

@endsection