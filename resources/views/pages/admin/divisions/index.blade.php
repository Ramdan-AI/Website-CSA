{{-- resources/views/pages/admin/divisions/index.blade.php --}}
@extends('layouts.admin')

@section('content')
    <div class="space-y-6">

        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
            <div>
                <p class="text-xs font-semibold text-blue-400 uppercase tracking-widest">Data Master</p>
                <h1 class="text-3xl font-bold text-gray-900 mt-1">Divisi</h1>
                <p class="text-sm text-gray-400 mt-1.5 font-medium">Kelola struktur dan informasi divisi dalam sistem
                    operasional CSA.</p>
            </div>
            <a href="{{ route('admin.divisions.create') }}"
                class="relative inline-flex items-center gap-2 px-5 py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white text-sm font-semibold rounded-2xl shadow-[0_4px_20px_rgba(59,130,246,0.3)] hover:shadow-[0_4px_25px_rgba(59,130,246,0.45)] hover:scale-105 transition-all duration-300 overflow-hidden group">
                <div
                    class="absolute inset-0 bg-white/20 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                </div>
                <span class="relative flex items-center gap-2">
                    <i class="fas fa-plus text-xs"></i>
                    Tambah Divisi
                </span>
            </a>
        </div>

        <x-admin.table :headers="[
            ['label' => 'ID', 'width' => 'w-20'],
            ['label' => 'Logo', 'width' => 'w-24'],
            ['label' => 'Divisi', 'width' => ''],
            ['label' => 'Deskripsi', 'width' => ''],
            ['label' => 'Status', 'width' => 'w-24'],
            ['label' => 'Aksi', 'width' => 'w-32'],
        ]" :hasPages="$divisions->hasPages()" :paginator="$divisions">
            @forelse($divisions as $division)
                <tr class="hover:bg-white/60 transition-all duration-200 group">
                    <td class="py-4 px-6">
                        <span
                            class="inline-flex items-center px-2.5 py-1 rounded-lg bg-gray-100/80 text-xs font-mono font-medium text-gray-500 group-hover:bg-blue-50 group-hover:text-blue-600 transition-colors">
                            #{{ str_pad($division->id, 3, '0', STR_PAD_LEFT) }}
                        </span>
                    </td>
                    <td class="py-4 px-6">
                        @if ($division->image)
                            <div class="w-14 h-14 rounded-2xl overflow-hidden bg-gray-100 cursor-pointer relative group/img shadow-[0_4px_12px_rgba(59,130,246,0.1)]"
                                onclick="openImagePreview('imagePreviewModal', '{{ asset('storage/' . $division->image) }}', '{{ $division->name }}')">
                                <img src="{{ asset('storage/' . $division->image) }}" alt="{{ $division->name }}"
                                    class="w-full h-full object-cover group-hover/img:scale-110 transition-transform duration-300">
                                <div
                                    class="absolute inset-0 bg-black/0 group-hover/img:bg-black/20 transition-colors duration-300 flex items-center justify-center">
                                    <i
                                        class="fas fa-search-plus text-white opacity-0 group-hover/img:opacity-100 transition-opacity duration-300 text-xs"></i>
                                </div>
                            </div>
                        @else
                            <div
                                class="w-14 h-14 rounded-2xl bg-gradient-to-br from-blue-500/10 to-purple-500/10 flex items-center justify-center text-blue-500 font-bold text-sm">
                                {{ strtoupper(substr($division->name, 0, 2)) }}
                            </div>
                        @endif
                    </td>
                    <td class="py-4 px-6">
                        <p class="text-sm font-semibold text-gray-900">{{ $division->name }}</p>
                        <p class="text-[10px] text-gray-400 font-mono mt-0.5">{{ $division->slug }}</p>
                    </td>
                    <td class="py-4 px-6">
                        <p class="text-sm text-gray-500 line-clamp-2 max-w-xs">{{ $division->description ?: '-' }}</p>
                    </td>
                    <td class="py-4 px-6 text-center">
                        @if ($division->is_active)
                            <span
                                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-emerald-50 text-xs font-semibold text-emerald-600">
                                <span
                                    class="w-1.5 h-1.5 rounded-full bg-emerald-500 shadow-[0_0_6px_rgba(16,185,129,0.4)]"></span>
                                Aktif
                            </span>
                        @else
                            <span
                                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-gray-100 text-xs font-semibold text-gray-400">
                                <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span>
                                Nonaktif
                            </span>
                        @endif
                    </td>
                    <td class="py-4 px-6">
                        <div class="flex items-center justify-center gap-2">
                            <a href="{{ route('admin.divisions.edit', $division) }}"
                                class="w-9 h-9 rounded-xl flex items-center justify-center bg-blue-50 text-blue-600 hover:bg-blue-500 hover:text-white hover:shadow-[0_4px_16px_rgba(59,130,246,0.3)] transition-all duration-200">
                                <i class="fas fa-pen text-xs"></i>
                            </a>
                            <button type="button"
                                onclick="openDeleteModal('deleteModal', '{{ route('admin.divisions.destroy', $division) }}')"
                                class="w-9 h-9 rounded-xl flex items-center justify-center bg-red-50 text-red-500 hover:bg-red-500 hover:text-white hover:shadow-[0_4px_16px_rgba(239,68,68,0.3)] transition-all duration-200">
                                <i class="fas fa-trash text-xs"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            @empty
                <x-admin.empty-state icon="fa-sitemap" title="Belum ada divisi"
                    description="Tambahkan divisi pertama untuk memulai" :createRoute="route('admin.divisions.create')" createLabel="Tambah Divisi"
                    :colspan="6" />
            @endforelse
        </x-admin.table>
    </div>

    <x-admin.modal.delete id="deleteModal" title="Hapus Divisi?" message="Divisi yang dihapus tidak dapat dikembalikan." />

    <x-admin.modal.image-preview id="imagePreviewModal" />
@endsection
