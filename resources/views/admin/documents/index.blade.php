@extends('layouts.admin')

@section('content')

<x-page-header
    title="Documents Management"
    subtitle="Kelola seluruh dokumen yang tersimpan dalam sistem."
>
    <x-slot:actions>
        <a href="{{ route('admin.documents.create') }}" class="btn-csa-primary">
            <i class="fas fa-plus"></i> Tambah Dokumen
        </a>
    </x-slot:actions>
</x-page-header>

<x-admin.crud-table :pagination="$documents">

    <x-slot:head>
        <tr>
            <th width="320">Judul</th>
            <th>File</th>
            <th width="200">Uploader</th>
            <th width="140" class="text-end">Aksi Sistem</th>
        </tr>
    </x-slot:head>

    @forelse($documents as $document)
        <tr>
            <td>
                <div class="data-name">
                    {{ $document->title }}
                </div>
            </td>

            <td>
                <div class="data-description" title="{{ $document->file }}">
                    {{ $document->file }}
                </div>
            </td>

            <td>
                <div class="data-description">
                    {{ $document->user?->name ?? '-' }}
                </div>
            </td>

            <td>
                <x-admin.action-buttons
                    :editRoute="route('admin.documents.edit', $document)"
                    :deleteRoute="route('admin.documents.destroy', $document)"
                />
            </td>
        </tr>
    @empty
        <x-admin.empty-state
            colspan="4"
            icon="file-alt"
            title="Database Kosong"
            subtitle="Belum ada dokumen yang diunggah."
        />
    @endforelse

</x-admin.crud-table>

@endsection