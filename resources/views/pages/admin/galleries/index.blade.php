@extends('layouts.admin')

@section('content')

<x-page-header
    title="Gallery Management"
    subtitle="Kelola seluruh galeri foto yang terhubung dengan album."
>
    <x-slot:actions>
        <a href="{{ route('admin.galleries.create') }}" class="btn-csa-primary">
            <i class="fas fa-plus"></i> Tambah Gallery
        </a>
    </x-slot:actions>
</x-page-header>

<x-admin.crud-table :pagination="$galleries">

    <x-slot:head>
        <tr>
            <th width="80">ID</th>
            <th width="220">Album</th>
            <th>Image</th>
            <th>Caption</th>
            <th width="140" class="text-end">Aksi Sistem</th>
        </tr>
    </x-slot:head>

    @forelse($galleries as $gallery)
        <tr>
            <td>
                <div class="data-description">
                    #{{ $gallery->id }}
                </div>
            </td>

            <td>
                <div class="data-name">
                    {{ $gallery->album->title }}
                </div>
            </td>

            <td>
                <div class="data-description" title="{{ $gallery->image }}">
                    {{ $gallery->image }}
                </div>
            </td>

            <td>
                <div class="data-description">
                    {{ $gallery->caption ?: '-' }}
                </div>
            </td>

            <td>
                <x-admin.action-buttons
                    :editRoute="route('admin.galleries.edit', $gallery)"
                    :deleteRoute="route('admin.galleries.destroy', $gallery)"
                />
            </td>
        </tr>
    @empty
        <x-admin.empty-state
            colspan="5"
            icon="images"
            title="Gallery Kosong"
            subtitle="Belum ada gambar yang ditambahkan."
        />
    @endforelse

</x-admin.crud-table>

@endsection