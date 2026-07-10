@extends('layouts.admin')

@section('content')

<x-page-header
    title="Event Registrations Management"
    subtitle="Kelola seluruh data registrasi member terhadap event."
>
    <x-slot:actions>
        <a href="{{ route('admin.event-registrations.create') }}" class="btn-csa-primary">
            <i class="fas fa-plus"></i> Tambah Registrasi
        </a>
    </x-slot:actions>
</x-page-header>

<x-admin.crud-table :pagination="$registrations">

    <x-slot:head>
        <tr>
            <th>Event</th>
            <th>Member</th>
            <th width="200">Registered At</th>
            <th width="140" class="text-end">Aksi Sistem</th>
        </tr>
    </x-slot:head>

    @forelse($registrations as $registration)
        <tr>
            <td>
                <div class="data-name">
                    {{ $registration->event->title }}
                </div>
            </td>

            <td>
                <div class="data-description">
                    {{ $registration->member->name }}
                </div>
            </td>

            <td>
                <div class="data-description">
                    {{ $registration->registered_at }}
                </div>
            </td>

            <td>
                <x-admin.action-buttons
                    :editRoute="route('admin.event-registrations.edit', $registration)"
                    :deleteRoute="route('admin.event-registrations.destroy', $registration)"
                />
            </td>
        </tr>
    @empty
        <x-admin.empty-state
            colspan="4"
            icon="calendar-check"
            title="Database Kosong"
            subtitle="Belum ada registrasi event yang tercatat."
        />
    @endforelse

</x-admin.crud-table>

@endsection