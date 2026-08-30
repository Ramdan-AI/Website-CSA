@extends('layouts.admin')

@section('content')
<div class="space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
        <div>
            <p class="text-xs font-semibold text-teal-400 uppercase tracking-widest">Publikasi</p>
            <h1 class="text-3xl font-bold text-gray-900 mt-1">Berita</h1>
            <p class="text-sm text-gray-400 mt-1.5 font-medium">Kelola seluruh berita dan publikasi konten dalam sistem.</p>
        </div>
        <a href="{{ route('admin.news.create') }}"
           class="relative inline-flex items-center gap-2 px-5 py-3 bg-gradient-to-r from-teal-500 to-teal-600 text-white text-sm font-semibold rounded-2xl shadow-[0_4px_20px_rgba(20,184,166,0.3)] hover:shadow-[0_4px_25px_rgba(20,184,166,0.45)] hover:scale-105 transition-all duration-300 overflow-hidden group">
            <div class="absolute inset-0 bg-white/20 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
            <span class="relative flex items-center gap-2">
                <i class="fas fa-plus text-xs"></i>
                Tambah Berita
            </span>
        </a>
    </div>

    <x-admin.table :headers="[
        ['label' => 'Judul', 'width' => ''],
        ['label' => 'Kategori', 'width' => ''],
        ['label' => 'Author', 'width' => ''],
        ['label' => 'Status', 'width' => ''],
        ['label' => 'Aksi', 'width' => 'w-24'],
    ]" :hasPages="$news->hasPages()" :paginator="$news">
        @forelse($news as $item)
            <tr class="hover:bg-teal-50/30 transition-colors duration-200">
                <td class="py-4 px-6">
                    <p class="text-sm font-bold text-gray-900 truncate max-w-[280px]">{{ $item->title }}</p>
                </td>
                <td class="py-4 px-6">
                    @if($item->category)
                        <span class="inline-flex px-2.5 py-1 rounded-lg bg-teal-50 text-[11px] font-semibold text-teal-700 border border-teal-100/50">
                            {{ $item->category->name }}
                        </span>
                    @else
                        <span class="text-xs text-gray-300">-</span>
                    @endif
                </td>
                <td class="py-4 px-6">
                    <div class="flex items-center gap-2">
                        <div class="w-7 h-7 rounded-full overflow-hidden flex items-center justify-center ring-2 ring-white shrink-0 shadow-sm">
                            <img src="{{ $item->author?->avatar ? asset('storage/' . $item->author->avatar) : asset('storage/profile/profile-placeholder.png') }}" alt="{{ $item->author?->name }}" class="w-full h-full object-cover">
                        </div>
                        <span class="text-xs text-gray-600">{{ $item->author?->name ?? '-' }}</span>
                    </div>
                </td>
                <td class="py-4 px-6">
                    @if($item->status === 'published')
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-emerald-50 text-[11px] font-semibold text-emerald-600 border border-emerald-100/50">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 shadow-[0_0_6px_rgba(16,185,129,0.4)]"></span>
                            Published
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-amber-50 text-[11px] font-semibold text-amber-600 border border-amber-100/50">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>
                            Draft
                        </span>
                    @endif
                </td>
                <td class="py-4 px-6">
                    <div class="flex items-center gap-1">
                        <a href="{{ route('admin.news.edit', $item) }}"
                           class="w-8 h-8 rounded-lg flex items-center justify-center bg-teal-50 text-teal-600 hover:bg-teal-500 hover:text-white transition-all duration-200 hover:shadow-[0_4px_12px_rgba(20,184,166,0.3)]"
                           title="Edit">
                            <i class="fas fa-pen text-[10px]"></i>
                        </a>
                        <button onclick="openDeleteModal('deleteModal', '{{ route('admin.news.destroy', $item) }}')"
                                class="w-8 h-8 rounded-lg flex items-center justify-center bg-red-50 text-red-500 hover:bg-red-500 hover:text-white transition-all duration-200 hover:shadow-[0_4px_12px_rgba(239,68,68,0.3)]"
                                title="Hapus">
                            <i class="fas fa-trash text-[10px]"></i>
                        </button>
                    </div>
                </td>
            </tr>
        @empty
            <x-admin.empty-state icon="fa-newspaper" title="Belum ada berita"
                description="Belum ada berita yang dipublikasikan." :createRoute="route('admin.news.create')" createLabel="Tambah Berita"
                :colspan="5" />
        @endforelse
    </x-admin.table>
</div>
@endsection
