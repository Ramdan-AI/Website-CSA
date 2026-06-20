@extends('layouts.admin')

@section('content')
    <x-page-header title="News Categories Management" subtitle="Kelola kategori berita dalam sistem publikasi konten.">
        <x-slot:actions>
            <a href="{{ route('admin.news-categories.create') }}" class="btn-csa-primary mb-4">
                <i class="fas fa-plus"></i> Tambah Kategori
            </a>
        </x-slot:actions>
    </x-page-header>

    <x-admin.crud-table :pagination="$categories">

        <x-slot:head>
            <tr>
                <th width="100">ID</th>
                <th width="250">Nama</th>
                <th width="220">Slug</th>
                <th>Deskripsi</th>
                <th width="140" class="text-end">Aksi Sistem</th>
            </tr>
        </x-slot:head>

        @forelse($categories as $category)
            <tr>
                <td>
                    <span class="data-id">
                        #{{ str_pad($category->id, 3, '0', STR_PAD_LEFT) }}
                    </span>
                </td>

                <td>
                    <div class="data-name">{{ $category->name }}</div>
                </td>

                <td>
                    <div class="data-description">{{ $category->slug }}</div>
                </td>

                <td>
                    <div class="data-description" title="{{ $category->description }}">
                        {{ $category->description ?: '-' }}
                    </div>
                </td>

                <td>
                    <x-admin.action-buttons :editRoute="route('admin.news-categories.edit', $category)" :deleteRoute="route('admin.news-categories.destroy', $category)" />
                </td>
            </tr>
        @empty
            <x-admin.empty-state colspan="5" icon="newspaper" title="Database Kosong"
                subtitle="Belum ada kategori berita yang diregistrasikan." />
        @endforelse

    </x-admin.crud-table>
@endsection
