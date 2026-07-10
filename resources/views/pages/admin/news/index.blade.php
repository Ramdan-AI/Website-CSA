@extends('layouts.admin')

@section('content')

<x-page-header
    title="News Management"
    subtitle="Kelola seluruh berita dan publikasi konten dalam sistem."
>
    <x-slot:actions>
        <a href="{{ route('admin.news.create') }}" class="btn-csa-primary">
            <i class="fas fa-plus"></i> Tambah Berita
        </a>
    </x-slot:actions>
</x-page-header>

<x-admin.crud-table :pagination="$news">

    <x-slot:head>
        <tr>
            <th width="320">Judul</th>
            <th width="200">Kategori</th>
            <th width="180">Author</th>
            <th width="140">Status</th>
            <th width="140" class="text-end">Aksi Sistem</th>
        </tr>
    </x-slot:head>

    @forelse($news as $item)
        <tr>
            <td>
                <div class="data-name">{{ $item->title }}</div>
            </td>

            <td>
                <div class="data-description">
                    {{ $item->category?->name ?? '-' }}
                </div>
            </td>

            <td>
                <div class="data-description">
                    {{ $item->author?->name ?? '-' }}
                </div>
            </td>

            <td>
                @if($item->status === 'published')
                    <span class="badge bg-success">Published</span>
                @else
                    <span class="badge bg-warning text-dark">Draft</span>
                @endif
            </td>

            <td>
                <x-admin.action-buttons
                    :editRoute="route('admin.news.edit', $item)"
                    :deleteRoute="route('admin.news.destroy', $item)"
                />
            </td>
        </tr>
    @empty
        <x-admin.empty-state
            colspan="5"
            icon="newspaper"
            title="Database Kosong"
            subtitle="Belum ada berita yang dipublikasikan."
        />
    @endforelse

</x-admin.crud-table>

@endsection