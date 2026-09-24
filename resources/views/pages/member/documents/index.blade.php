@extends('layouts.admin')

@php
    use Illuminate\Support\Str;

    $fileIcon = function (?string $file): string {
        $extension = strtolower(pathinfo((string) $file, PATHINFO_EXTENSION));

        return match ($extension) {
            'pdf' => 'file-text',
            'doc', 'docx' => 'file-text',
            'xls', 'xlsx' => 'file-text',
            'ppt', 'pptx' => 'file-text',
            'jpg', 'jpeg', 'png', 'webp' => 'image',
            default => 'folder-open',
        };
    };
@endphp

@section('content')
<div class="space-y-4 sm:space-y-5">

    <div>
        <h1 class="text-[26px] sm:text-[30px] font-bold text-[var(--text-primary)] tracking-[-0.03em]">Dokumen</h1>
        <p class="text-[13px] text-[var(--text-muted)] mt-1">Akses dan unduh dokumen penting UKM CSA.</p>
    </div>

    <form method="GET" class="csa-panel p-3 sm:p-4 grid grid-cols-1 sm:grid-cols-[1fr_auto] gap-3">
        <div class="relative">
            <x-admin.icon name="search" :size="16" class="absolute left-3.5 top-1/2 -translate-y-1/2 text-[var(--text-muted)] pointer-events-none" />
            <input type="search" name="search" value="{{ request('search') }}" placeholder="Cari nama dokumen..." class="csa-input pl-10" aria-label="Cari dokumen">
        </div>
        <x-form.select name="sort" :value="request('sort', 'newest')" onchange="this.form.submit()" class="sm:w-48" aria-label="Urutkan"
            :options="['newest' => 'Terbaru', 'oldest' => 'Terlama', 'title_asc' => 'Nama A-Z', 'title_desc' => 'Nama Z-A']" />
    </form>

    <div class="space-y-2">
        @forelse($documents as $document)
            <a href="{{ route('admin.documents.download', $document) }}" class="csa-panel csa-panel-hover p-4 flex items-center gap-4">
                <span class="w-10 h-10 rounded-xl bg-[var(--bg-muted)] text-[var(--text-secondary)] flex items-center justify-center shrink-0">
                    <x-admin.icon :name="$fileIcon($document->file)" :size="18" />
                </span>
                <span class="flex-1 min-w-0">
                    <span class="block text-[13px] font-medium text-[var(--text-primary)] truncate">{{ $document->title }}</span>
                    <span class="block text-[11px] text-[var(--text-muted)] mt-0.5">
                        {{ $document->created_at->translatedFormat('d M Y') }}
                        @if ($document->user)
                            · {{ $document->user->name }}
                        @endif
                    </span>
                </span>
                <span class="flex items-center gap-2 text-[var(--text-muted)] shrink-0">
                    <x-admin.icon name="download" :size="16" />
                </span>
            </a>
        @empty
            <div class="csa-panel p-12 text-center">
                <x-admin.icon name="folder-open" :size="32" class="mx-auto text-[var(--text-muted)]" />
                <p class="text-[13px] font-medium text-[var(--text-secondary)] mt-3">Tidak ada dokumen ditemukan</p>
                <p class="text-[12px] text-[var(--text-muted)] mt-1">Coba ubah kriteria pencarian.</p>
            </div>
        @endforelse
    </div>

    @if ($documents->hasPages())
        {{ $documents->links() }}
    @endif
</div>
@endsection
