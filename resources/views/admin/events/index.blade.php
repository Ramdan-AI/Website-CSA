@extends('layouts.admin')

@section('content')

<x-page-header
    title="Events Management"
    subtitle="Kelola seluruh event dan agenda kegiatan dalam sistem."
>
    <x-slot:actions>
        <a href="{{ route('admin.events.create') }}" class="btn-csa-primary">
            <i class="fas fa-plus"></i> Tambah Event
        </a>
    </x-slot:actions>
</x-page-header>

<x-admin.crud-table :pagination="$events">

    <x-slot:head>
        <tr>
            <th width="280">Judul Event</th>
            <th width="180">Tanggal</th>
            <th>Lokasi</th>
            <th width="140">Status</th>
            <th width="140" class="text-end">Aksi Sistem</th>
        </tr>
    </x-slot:head>

    @forelse($events as $event)
        <tr>
            <td>
                <div class="data-name">
                    {{ $event->title }}
                </div>
            </td>

            <td>
                <div class="data-description">
                    {{ $event->event_date }}
                </div>
            </td>

            <td>
                <div class="data-description" title="{{ $event->location }}">
                    {{ $event->location }}
                </div>
            </td>

            <td>
                <div class="data-description">
                    {{ ucfirst($event->status) }}
                </div>
            </td>

            <td>
                <x-admin.action-buttons
                    :editRoute="route('admin.events.edit', $event)"
                    :deleteRoute="route('admin.events.destroy', $event)"
                />
            </td>
        </tr>
    @empty
        <x-admin.empty-state
            colspan="5"
            icon="calendar-days"
            title="Database Kosong"
            subtitle="Belum ada data event yang diregistrasikan."
        />
    @endforelse

</x-admin.crud-table>

@endsection