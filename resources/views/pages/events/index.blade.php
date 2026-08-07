@extends('layouts.admin')

@section('content')
<div class="space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
        <div>
            <p class="text-xs font-semibold text-cyan-400 uppercase tracking-widest">Kegiatan</p>
            <h1 class="text-3xl font-bold text-gray-900 mt-1">Acara</h1>
            <p class="text-sm text-gray-400 mt-1.5 font-medium">Kelola seluruh event dan agenda kegiatan dalam sistem.</p>
        </div>
        <a href="{{ route('admin.events.create') }}"
           class="relative inline-flex items-center gap-2 px-5 py-3 bg-gradient-to-r from-cyan-500 to-cyan-600 text-white text-sm font-semibold rounded-2xl shadow-[0_4px_20px_rgba(6,182,212,0.3)] hover:shadow-[0_4px_25px_rgba(6,182,212,0.45)] hover:scale-105 transition-all duration-300 overflow-hidden group">
            <div class="absolute inset-0 bg-white/20 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
            <span class="relative flex items-center gap-2">
                <i class="fas fa-plus text-xs"></i>
                Tambah Acara
            </span>
        </a>
    </div>

    <x-admin.table :headers="[
        ['label' => 'Banner', 'width' => 'w-20'],
        ['label' => 'Judul Event', 'width' => ''],
        ['label' => 'Tanggal & Lokasi', 'width' => ''],
        ['label' => 'Status', 'width' => ''],
        ['label' => 'Aksi', 'width' => 'w-24'],
    ]" :hasPages="$events->hasPages()" :paginator="$events">
        @forelse($events as $event)
            <tr class="hover:bg-cyan-50/30 transition-colors duration-200">
                <td class="py-4 px-6 text-center">
                    @if($event->banner_url)
                        <div class="w-12 h-12 rounded-xl overflow-hidden bg-gray-100 border border-gray-100/50 mx-auto cursor-pointer"
                             onclick="openImagePreview('imagePreviewModal', '{{ $event->banner_url }}', '{{ $event->title }}')">
                            <img src="{{ $event->banner_url }}" alt="{{ $event->title }}" class="w-full h-full object-cover hover:scale-110 transition-transform duration-300">
                        </div>
                    @else
                        <div class="w-12 h-12 rounded-xl bg-gray-50 border border-dashed border-gray-200 mx-auto flex items-center justify-center text-gray-300">
                            <i class="fas fa-image text-sm"></i>
                        </div>
                    @endif
                </td>
                <td class="py-4 px-6">
                    <p class="text-sm font-bold text-gray-900">{{ $event->title }}</p>
                    <p class="text-[10px] font-mono text-gray-400 mt-0.5">/{{ $event->slug }}</p>
                </td>
                <td class="py-4 px-6">
                    <p class="text-xs font-semibold text-gray-700">{{ $event->event_date?->format('d M Y') }}</p>
                    @if($event->location)
                        <p class="text-[11px] text-gray-400 mt-0.5 truncate max-w-[200px]">
                            <i class="fas fa-map-marker-alt text-[9px] mr-1 text-cyan-400"></i>
                            {{ $event->location }}
                        </p>
                    @endif
                </td>
                <td class="py-4 px-6">
                    @php
                        $statusStyles = [
                            'published' => 'bg-emerald-50 text-emerald-600 border-emerald-100/50',
                            'draft' => 'bg-amber-50 text-amber-600 border-amber-100/50',
                            'closed' => 'bg-gray-100 text-gray-500 border-gray-200/50',
                        ];
                        $style = $statusStyles[$event->status] ?? 'bg-gray-50 text-gray-400 border-gray-100/50';
                    @endphp
                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[11px] font-semibold border {{ $style }}">
                        {{ ucfirst($event->status) }}
                    </span>
                </td>
                <td class="py-4 px-6">
                    <div class="flex items-center gap-1">
                        <a href="{{ route('admin.events.edit', $event) }}"
                           class="w-8 h-8 rounded-lg flex items-center justify-center bg-cyan-50 text-cyan-600 hover:bg-cyan-500 hover:text-white transition-all duration-200 hover:shadow-[0_4px_12px_rgba(6,182,212,0.3)]"
                           title="Edit">
                            <i class="fas fa-pen text-[10px]"></i>
                        </a>
                        <button onclick="openDeleteModal('deleteModal', '{{ route('admin.events.destroy', $event) }}')"
                                class="w-8 h-8 rounded-lg flex items-center justify-center bg-red-50 text-red-500 hover:bg-red-500 hover:text-white transition-all duration-200 hover:shadow-[0_4px_12px_rgba(239,68,68,0.3)]"
                                title="Hapus">
                            <i class="fas fa-trash text-[10px]"></i>
                        </button>
                    </div>
                </td>
            </tr>
        @empty
            <x-admin.empty-state icon="fa-calendar-alt" title="Belum ada acara"
                description="Sistem belum memiliki data event apapun." :createRoute="route('admin.events.create')" createLabel="Tambah Acara"
                :colspan="5" />
        @endforelse
    </x-admin.table>
</div>
@endsection
