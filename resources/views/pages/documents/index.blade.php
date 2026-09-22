@extends('layouts.admin')

@php
    use Illuminate\Support\Str;

    $fileIcon = function (?string $file): string {
        $extension = strtolower(pathinfo((string) $file, PATHINFO_EXTENSION));

        return in_array($extension, ['jpg', 'jpeg', 'png', 'webp'], true) ? 'image' : 'file-text';
    };
@endphp

@section('content')
<div class="space-y-4 sm:space-y-5">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h1 class="text-[26px] sm:text-[30px] font-bold text-[var(--text-primary)] tracking-[-0.03em]">Dokumen</h1>
            <p class="text-[13px] text-[var(--text-muted)] mt-1">Kelola seluruh dokumen yang tersimpan dalam sistem.</p>
        </div>
        <a href="{{ route('admin.documents.create') }}" class="csa-btn csa-btn-primary">
            <x-admin.icon name="plus" :size="16" /> Tambah Dokumen
        </a>
    </div>

    <x-admin.table :headers="[
        ['label' => 'Judul'],
        ['label' => 'File'],
        ['label' => 'Uploader'],
        ['label' => 'Preview', 'width' => 'w-24'],
        ['label' => 'Aksi', 'width' => 'w-24'],
    ]"
        empty-icon="file-text"
        empty-title="Belum ada dokumen"
        empty-description="Belum ada dokumen yang diunggah."
        :create-route="route('admin.documents.create')"
        create-label="Tambah Dokumen"
        :has-pages="$documents->hasPages()"
        :paginator="$documents">
        @forelse($documents as $document)
            @php
                $fileUrl = asset('storage/' . $document->file);
                $downloadUrl = route('admin.documents.download', $document);
                $fileExt = strtoupper(pathinfo($document->file, PATHINFO_EXTENSION));
            @endphp
            <tr class="csa-table-row">
                <td class="csa-table-td">
                    <div class="flex items-center gap-3">
                        <span class="w-9 h-9 rounded-xl bg-[var(--bg-muted)] text-[var(--text-secondary)] flex items-center justify-center shrink-0">
                            <x-admin.icon :name="$fileIcon($document->file)" :size="17" />
                        </span>
                        <div class="min-w-0">
                            <p class="text-[13px] font-medium text-[var(--text-primary)]">{{ $document->title }}</p>
                            @if ($document->description)
                                <p class="text-[11px] text-[var(--text-muted)] line-clamp-1">{{ Str::limit($document->description, 60) }}</p>
                            @endif
                        </div>
                    </div>
                </td>
                <td class="csa-table-td">
                    <div class="flex items-center gap-2">
                        <span class="px-2 py-0.5 rounded-md bg-[var(--bg-muted)] text-[11px] font-mono text-[var(--text-secondary)]">{{ $fileExt }}</span>
                        <span class="text-[11px] text-[var(--text-muted)] truncate max-w-[160px]">{{ basename($document->file) }}</span>
                    </div>
                </td>
                <td class="csa-table-td">
                    <div class="flex items-center gap-2">
                        <img src="{{ $document->user?->avatar ? asset('storage/' . $document->user->avatar) : asset('storage/profile/profile-placeholder.png') }}" alt="{{ $document->user?->name }}" class="w-7 h-7 rounded-full object-cover shrink-0">
                        <span class="text-[12px] text-[var(--text-secondary)]">{{ $document->user?->name ?? '-' }}</span>
                    </div>
                </td>
                <td class="csa-table-td">
                    <div class="flex items-center gap-1">
                        <button type="button" onclick="openDocumentPreview('{{ $fileUrl }}', '{{ $document->title }}', '{{ $downloadUrl }}')" class="csa-icon-btn" title="Preview" aria-label="Preview dokumen">
                            <x-admin.icon name="eye" :size="16" />
                        </button>
                        <a href="{{ $downloadUrl }}" class="csa-icon-btn" title="Download" aria-label="Unduh dokumen">
                            <x-admin.icon name="download" :size="16" />
                        </a>
                    </div>
                </td>
                <td class="csa-table-td">
                    <div class="flex items-center gap-1">
                        <a href="{{ route('admin.documents.edit', $document) }}" class="csa-icon-btn" title="Edit" aria-label="Edit dokumen">
                            <x-admin.icon name="pen" :size="16" />
                        </a>
                        <button type="button" onclick="openDeleteModal('deleteModal', '{{ route('admin.documents.destroy', $document) }}')" class="csa-icon-btn hover:!text-red-500" title="Hapus" aria-label="Hapus dokumen">
                            <x-admin.icon name="trash-2" :size="16" />
                        </button>
                    </div>
                </td>
            </tr>
        @empty
        @endforelse
    </x-admin.table>
</div>
@endsection
