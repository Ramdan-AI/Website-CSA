@extends('layouts.admin')

@section('content')
<div class="space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
        <div>
            <p class="text-xs font-semibold text-teal-400 uppercase tracking-widest">Publikasi</p>
            <h1 class="text-3xl font-bold text-gray-900 mt-1">Kategori</h1>
            <p class="text-sm text-gray-400 mt-1.5 font-medium">Kelola kategori berita dalam sistem publikasi konten.</p>
        </div>
        <a href="{{ route('admin.news-categories.create') }}"
           class="relative inline-flex items-center gap-2 px-5 py-3 bg-gradient-to-r from-teal-500 to-teal-600 text-white text-sm font-semibold rounded-2xl shadow-[0_4px_20px_rgba(20,184,166,0.3)] hover:shadow-[0_4px_25px_rgba(20,184,166,0.45)] hover:scale-105 transition-all duration-300 overflow-hidden group">
            <div class="absolute inset-0 bg-white/20 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
            <span class="relative flex items-center gap-2">
                <i class="fas fa-plus text-xs"></i>
                Tambah Kategori
            </span>
        </a>
    </div>

    <x-admin.table :headers="[
        ['label' => 'ID', 'width' => 'w-20'],
        ['label' => 'Nama', 'width' => ''],
        ['label' => 'Slug', 'width' => ''],
        ['label' => 'Deskripsi', 'width' => ''],
        ['label' => 'Aksi', 'width' => 'w-24'],
    ]" :hasPages="$categories->hasPages()" :paginator="$categories">
        @forelse($categories as $category)
            <tr class="hover:bg-teal-50/30 transition-colors duration-200">
                <td class="py-4 px-6">
                    <span class="text-xs font-mono text-gray-400 font-semibold">
                        #{{ str_pad($category->id, 3, '0', STR_PAD_LEFT) }}
                    </span>
                </td>
                <td class="py-4 px-6">
                    <p class="text-sm font-bold text-gray-900">{{ $category->name }}</p>
                </td>
                <td class="py-4 px-6">
                    <span class="text-xs font-mono text-gray-500 bg-gray-50 px-2 py-1 rounded-lg">{{ $category->slug }}</span>
                </td>
                <td class="py-4 px-6">
                    <p class="text-xs text-gray-400 line-clamp-2">{{ $category->description ?: '-' }}</p>
                </td>
                <td class="py-4 px-6">
                    <div class="flex items-center gap-1">
                        <a href="{{ route('admin.news-categories.edit', $category) }}"
                           class="w-8 h-8 rounded-lg flex items-center justify-center bg-teal-50 text-teal-600 hover:bg-teal-500 hover:text-white transition-all duration-200 hover:shadow-[0_4px_12px_rgba(20,184,166,0.3)]"
                           title="Edit">
                            <i class="fas fa-pen text-[10px]"></i>
                        </a>
                        <button onclick="openDeleteModal('deleteModal', '{{ route('admin.news-categories.destroy', $category) }}')"
                                class="w-8 h-8 rounded-lg flex items-center justify-center bg-red-50 text-red-500 hover:bg-red-500 hover:text-white transition-all duration-200 hover:shadow-[0_4px_12px_rgba(239,68,68,0.3)]"
                                title="Hapus">
                            <i class="fas fa-trash text-[10px]"></i>
                        </button>
                    </div>
                </td>
            </tr>
        @empty
            <x-admin.empty-state icon="fa-tags" title="Belum ada kategori"
                description="Tambahkan kategori berita pertama" :createRoute="route('admin.news-categories.create')" createLabel="Tambah Kategori"
                :colspan="5" />
        @endforelse
    </x-admin.table>
</div>
@endsection
