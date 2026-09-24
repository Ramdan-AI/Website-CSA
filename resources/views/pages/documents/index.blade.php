@extends('layouts.admin')

@php
    use Illuminate\Support\Str;

    $fileIcon = function (?string $file): string {
        $extension = strtolower(pathinfo((string) $file, PATHINFO_EXTENSION));

        return in_array($extension, ['jpg', 'jpeg', 'png', 'webp'], true) ? 'image' : 'file-text';
    };
@endphp

@section('content')
<div class="space-y-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h1 class="text-[26px] sm:text-[30px] font-bold text-[var(--text-primary)] tracking-[-0.03em]">Dokumen</h1>
            <p class="text-[13px] text-[var(--text-muted)] mt-1">Arsip dokumen yang bisa diunduh anggota.</p>
        </div>
        @can('manage-documents')
            <a href="{{ route('admin.documents.create') }}" class="csa-btn csa-btn-primary self-start sm:self-auto">
                <x-admin.icon name="plus" :size="16" /> Unggah Dokumen
            </a>
        @endcan
    </div>

    @if (session('success'))
        <div class="csa-panel p-3.5 text-[13px] text-emerald-600 dark:text-emerald-400">{{ session('success') }}</div>
    @endif

    <x-admin.search-bar name="search" :value="request('search')" placeholder="Cari judul atau deskripsi dokumen..." class="max-w-md" />

    @if ($documents->isEmpty())
        <div class="csa-panel p-16 text-center">
            <div class="w-14 h-14 rounded-2xl bg-[var(--bg-muted)] flex items-center justify-center mx-auto text-[var(--text-muted)]">
                <x-admin.icon name="file-text" :size="26" />
            </div>
            <p class="text-[15px] font-semibold text-[var(--text-secondary)] mt-4">Belum ada dokumen</p>
            <p class="text-[13px] text-[var(--text-muted)] mt-1">Unggah dokumen pertama untuk dibagikan.</p>
        </div>
    @else
        <div class="space-y-2.5">
            @foreach ($documents as $document)
                @php
                    $fileUrl = asset('storage/' . $document->file);
                    $downloadUrl = route('admin.documents.download', $document);
                    $fileExt = strtoupper(pathinfo($document->file, PATHINFO_EXTENSION));
                @endphp
                <article class="csa-panel csa-panel-hover p-4 flex items-center gap-4">
                    <span class="w-11 h-11 rounded-xl bg-[var(--bg-muted)] text-[var(--text-secondary)] flex items-center justify-center shrink-0">
                        <x-admin.icon :name="$fileIcon($document->file)" :size="20" />
                    </span>

                    <div class="min-w-0 flex-1">
                        <div class="flex items-center gap-2">
                            <h2 class="text-[14px] font-semibold text-[var(--text-primary)] truncate">{{ $document->title }}</h2>
                            <span class="csa-chip shrink-0">{{ $fileExt }}</span>
                        </div>
                        <p class="text-[12px] text-[var(--text-muted)] mt-0.5 truncate">
                            {{ $document->user?->name ?? 'CSA' }} · {{ $document->created_at->diffForHumans() }}
                            @if ($document->description) · {{ Str::limit($document->description, 70) }} @endif
                        </p>
                    </div>

                    <div class="flex items-center gap-1 shrink-0">
                        <button type="button" onclick="openDocumentPreview('{{ $fileUrl }}', '{{ $document->title }}', '{{ $downloadUrl }}')" class="csa-icon-btn" title="Preview" aria-label="Preview dokumen">
                            <x-admin.icon name="eye" :size="16" />
                        </button>
                        <a href="{{ $downloadUrl }}" class="csa-icon-btn" title="Unduh" aria-label="Unduh dokumen">
                            <x-admin.icon name="download" :size="16" />
                        </a>
                        @can('manage-documents')
                            <a href="{{ route('admin.documents.edit', $document) }}" class="csa-icon-btn" title="Edit" aria-label="Edit dokumen">
                                <x-admin.icon name="pen" :size="16" />
                            </a>
                            <button type="button" onclick="openDeleteModal('deleteModal', '{{ route('admin.documents.destroy', $document) }}')" class="csa-icon-btn hover:!text-red-500" title="Hapus" aria-label="Hapus dokumen">
                                <x-admin.icon name="trash-2" :size="16" />
                            </button>
                        @endcan
                    </div>
                </article>
            @endforeach
        </div>

        @if ($documents->hasPages())
            <div>{{ $documents->links() }}</div>
        @endif
    @endif
</div>
@endsection
