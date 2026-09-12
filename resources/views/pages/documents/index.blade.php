@extends('layouts.admin')

@section('content')
    <div class="space-y-6">

        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
            <div>
                <p class="text-xs font-semibold text-teal-400 uppercase tracking-widest">Publikasi</p>
                <h1 class="text-3xl font-bold text-gray-900 mt-1">Dokumen</h1>
                <p class="text-sm text-gray-400 mt-1.5 font-medium">Kelola seluruh dokumen yang tersimpan dalam sistem.</p>
            </div>
            <a href="{{ route('admin.documents.create') }}"
                class="relative inline-flex items-center gap-2 px-5 py-3 bg-gradient-to-r from-teal-500 to-teal-600 text-white text-sm font-semibold rounded-2xl shadow-[0_4px_20px_rgba(20,184,166,0.3)] hover:shadow-[0_4px_25px_rgba(20,184,166,0.45)] hover:scale-105 transition-all duration-300 overflow-hidden group">
                <div
                    class="absolute inset-0 bg-white/20 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                </div>
                <span class="relative flex items-center gap-2">
                    <i class="fas fa-plus text-xs"></i>
                    Tambah Dokumen
                </span>
            </a>
        </div>

        <x-admin.table :headers="[
            ['label' => 'Judul', 'width' => ''],
            ['label' => 'File', 'width' => ''],
            ['label' => 'Uploader', 'width' => ''],
            ['label' => 'Preview', 'width' => 'w-24'],
            ['label' => 'Aksi', 'width' => 'w-24'],
        ]" :hasPages="$documents->hasPages()" :paginator="$documents">
            @forelse($documents as $document)
                @php
                    $fileUrl = asset('storage/' . $document->file);
                    $downloadUrl = route('admin.documents.download', $document);
                    $fileExt = strtoupper(pathinfo($document->file, PATHINFO_EXTENSION));
                @endphp
                <tr class="hover:bg-teal-50/30 transition-colors duration-200">
                    <td class="py-4 px-6">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-teal-50 flex items-center justify-center shrink-0">
                                <i class="fas fa-file-{{ $fileExt === 'PDF' ? 'pdf' : 'alt' }} text-teal-500 text-sm"></i>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-gray-900">{{ $document->title }}</p>
                                @if ($document->description)
                                    <p class="text-[11px] text-gray-400 line-clamp-1">
                                        {{ Str::limit($document->description, 50) }}</p>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="py-4 px-6">
                        <div class="flex items-center gap-2">
                            <span
                                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-gray-50 text-[11px] font-mono text-gray-500 border border-gray-100/50">
                                <i class="fas fa-file text-teal-400 text-[10px]"></i>
                                {{ $fileExt }}
                            </span>
                            <span class="text-[10px] text-gray-400">{{ basename($document->file) }}</span>
                        </div>
                    </td>
                    <td class="py-4 px-6">
                        <div class="flex items-center gap-2">
                            <div
                                class="w-7 h-7 rounded-full overflow-hidden flex items-center justify-center ring-2 ring-white shrink-0 shadow-sm">
                                <img src="{{ $document->user?->avatar ? asset('storage/' . $document->user->avatar) : asset('storage/profile/profile-placeholder.png') }}" alt="{{ $document->user?->name }}" class="w-full h-full object-cover">
                            </div>
                            <span class="text-xs text-gray-600">{{ $document->user?->name ?? '-' }}</span>
                        </div>
                    </td>
                    <td class="py-4 px-6">
                        <div class="flex items-center gap-1">
                            {{-- Preview Button --}}
                            <button
                                onclick="openDocumentPreview('{{ $fileUrl }}', '{{ $document->title }}', '{{ $downloadUrl }}')"
                                class="w-8 h-8 rounded-lg flex items-center justify-center bg-blue-50 text-blue-500 hover:bg-blue-500 hover:text-white transition-all duration-200 hover:shadow-[0_4px_12px_rgba(59,130,246,0.3)]"
                                title="Preview">
                                <i class="fas fa-eye text-[10px]"></i>
                            </button>
                            {{-- Download Button --}}
                            <a href="{{ $downloadUrl }}"
                                class="w-8 h-8 rounded-lg flex items-center justify-center bg-teal-50 text-teal-600 hover:bg-teal-500 hover:text-white transition-all duration-200 hover:shadow-[0_4px_12px_rgba(20,184,166,0.3)]"
                                title="Download">
                                <i class="fas fa-download text-[10px]"></i>
                            </a>
                        </div>
                    </td>
                    <td class="py-4 px-6">
                        <div class="flex items-center gap-1">
                            <a href="{{ route('admin.documents.edit', $document) }}"
                                class="w-8 h-8 rounded-lg flex items-center justify-center bg-teal-50 text-teal-600 hover:bg-teal-500 hover:text-white transition-all duration-200 hover:shadow-[0_4px_12px_rgba(20,184,166,0.3)]"
                                title="Edit">
                                <i class="fas fa-pen text-[10px]"></i>
                            </a>
                            <button
                                onclick="openDeleteModal('deleteModal', '{{ route('admin.documents.destroy', $document) }}')"
                                class="w-8 h-8 rounded-lg flex items-center justify-center bg-red-50 text-red-500 hover:bg-red-500 hover:text-white transition-all duration-200 hover:shadow-[0_4px_12px_rgba(239,68,68,0.3)]"
                                title="Hapus">
                                <i class="fas fa-trash text-[10px]"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            @empty
                <x-admin.empty-state icon="fa-file-alt" title="Belum ada dokumen"
                    description="Belum ada dokumen yang diunggah." :createRoute="route('admin.documents.create')" createLabel="Tambah Dokumen"
                    :colspan="5" />
            @endforelse
        </x-admin.table>
    </div>
@endsection
