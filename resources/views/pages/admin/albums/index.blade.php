@extends('layouts.admin')

@section('content')

<x-page-header
    title="Albums Management"
    subtitle="Kelola seluruh album galeri yang tersimpan dalam sistem."
>
    <x-slot:actions>
        <a href="{{ route('admin.albums.create') }}" class="btn-csa-primary">
            <i class="fas fa-plus"></i> Tambah Album
        </a>
    </x-slot:actions>
</x-page-header>

<x-admin.crud-table :pagination="$albums">

    <x-slot:head>
        <tr>
            <th width="100">ID</th>
            <th>Judul</th>
            <th>Cover</th>
            <th width="140" class="text-end">Aksi Sistem</th>
        </tr>
    </x-slot:head>

    @forelse($albums as $album)
        <tr>
            <td>
                <span class="data-id">
                    #{{ str_pad($album->id, 3, '0', STR_PAD_LEFT) }}
                </span>
            </td>

            <td>
                <div class="data-name">
                    {{ $album->title }}
                </div>
            </td>

            <td>
                <div class="data-description" title="{{ $album->cover_image }}">
                    {{ $album->cover_image ?: '-' }}
                </div>
            </td>

            <td>
                <x-admin.action-buttons
                    :editRoute="route('admin.albums.edit', $album)"
                    :deleteRoute="route('admin.albums.destroy', $album)"
                />
            </td>
        </tr>
    @empty
        <x-admin.empty-state
            colspan="4"
            icon="images"
            title="Database Kosong"
            subtitle="Belum ada album yang tersimpan."
        />
    @endforelse

</x-admin.crud-table>

@endsection