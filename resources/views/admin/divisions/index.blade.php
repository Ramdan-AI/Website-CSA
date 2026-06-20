@extends('layouts.admin')

@section('content')

<div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div>
        <h1 class="page-title">Divisions Management</h1>
        <p class="page-subtitle">
            Kelola struktur dan informasi divisi dalam sistem operasional CSA.
        </p>
    </div>

    <a href="{{ route('admin.divisions.create') }}" class="btn-csa-primary">
        <i class="fas fa-plus"></i> Tambah Divisi
    </a>
</div>

<x-admin.crud-table :pagination="$divisions">

    <x-slot:head>
        <tr>
            <th width="100">ID</th>
            <th width="280">Nama Divisi</th>
            <th>Deskripsi Operasional</th>
            <th width="140" class="text-end">Aksi Sistem</th>
        </tr>
    </x-slot:head>

    @forelse($divisions as $division)
        <tr>
            <td>
                <span class="data-id">
                    #{{ str_pad($division->id, 3, '0', STR_PAD_LEFT) }}
                </span>
            </td>

            <td>
                <div class="data-name">
                    {{ $division->name }}
                </div>
            </td>

            <td>
                <div class="data-description" title="{{ $division->description }}">
                    {{ $division->description ?: '-' }}
                </div>
            </td>

            <td>
                <x-admin.action-buttons
                    :editRoute="route('admin.divisions.edit', $division)"
                    :deleteRoute="route('admin.divisions.destroy', $division)"
                />
            </td>
        </tr>
    @empty
        <x-admin.empty-state
            colspan="4"
            icon="folder-open"
            title="Database Kosong"
            subtitle="Belum ada data divisi yang diregistrasikan."
        />
    @endforelse

</x-admin.crud-table>

@endsection