@extends('layouts.admin')

@section('content')

<x-page-header
    title="Members Management"
    subtitle="Kelola seluruh anggota, divisi, dan struktur jabatan dalam sistem."
>
    <x-slot:actions>
        <a href="{{ route('admin.members.create') }}" class="btn-csa-primary">
            <i class="fas fa-plus"></i> Tambah Member
        </a>
    </x-slot:actions>
</x-page-header>

<x-admin.crud-table :pagination="$members">

    <x-slot:head>
        <tr>
            <th width="220">Nama</th>
            <th width="180">Divisi</th>
            <th width="180">Posisi</th>
            <th>User</th>
            <th width="120">Status</th>
            <th width="140" class="text-end">Aksi Sistem</th>
        </tr>
    </x-slot:head>

    @forelse($members as $member)
        <tr>
            <td>
                <div class="data-name">
                    {{ $member->name }}
                </div>
            </td>

            <td>
                <div class="data-description">
                    {{ $member->division?->name ?? '-' }}
                </div>
            </td>

            <td>
                <div class="data-description">
                    {{ $member->position?->name ?? '-' }}
                </div>
            </td>

            <td>
                <div class="data-description">
                    {{ $member->user?->email ?? '-' }}
                </div>
            </td>

            <td>
                @if($member->is_active)
                    <span class="badge bg-success">Aktif</span>
                @else
                    <span class="badge bg-secondary">Nonaktif</span>
                @endif
            </td>

            <td>
                <x-admin.action-buttons
                    :editRoute="route('admin.members.edit', $member)"
                    :deleteRoute="route('admin.members.destroy', $member)"
                />
            </td>
        </tr>
    @empty
        <x-admin.empty-state
            colspan="6"
            icon="users"
            title="Database Kosong"
            subtitle="Belum ada data member yang terdaftar."
        />
    @endforelse

</x-admin.crud-table>

@endsection