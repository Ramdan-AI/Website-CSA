@extends('layouts.admin')

@section('content')

<div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div>
        <h1 class="page-title">Positions Management</h1>
        <p class="page-subtitle">
            Kelola daftar jabatan operasional dan peran entitas dalam sistem.
        </p>
    </div>

    <a href="{{ route('admin.positions.create') }}" class="btn-csa-primary">
        <i class="fas fa-plus"></i> Tambah Posisi
    </a>
</div>

<x-admin.crud-table :pagination="$positions">

    <x-slot:head>
        <tr>
            <th width="100">ID</th>
            <th width="280">Nama Posisi</th>
            <th>Deskripsi Peran</th>
            <th width="140" class="text-end">Aksi Sistem</th>
        </tr>
    </x-slot:head>

    @forelse($positions as $position)
        <tr>
            <td>
                <span class="data-id">
                    #{{ str_pad($position->id, 3, '0', STR_PAD_LEFT) }}
                </span>
            </td>

            <td>
                <div class="data-name">
                    {{ $position->name }}
                </div>
            </td>

            <td>
                <div class="data-description" title="{{ $position->description }}">
                    {{ $position->description ?: '-' }}
                </div>
            </td>

            <td>
                <x-admin.action-buttons
                    :editRoute="route('admin.positions.edit', $position)"
                    :deleteRoute="route('admin.positions.destroy', $position)"
                />
            </td>
        </tr>
    @empty
        <x-admin.empty-state
            colspan="4"
            icon="briefcase"
            title="Database Kosong"
            subtitle="Belum ada data posisi atau jabatan yang diregistrasikan."
        />
    @endforelse

</x-admin.crud-table>

@endsection