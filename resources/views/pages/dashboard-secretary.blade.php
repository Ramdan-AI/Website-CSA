@extends('layouts.admin')

@section('content')
@php
    $statItems = [
        ['label' => 'Surat Tercatat', 'value' => number_format($totalLetters), 'note' => 'Masuk & keluar'],
        ['label' => 'Notulen Rapat', 'value' => number_format($totalMinutes), 'note' => 'Tersimpan'],
        ['label' => 'Dokumen', 'value' => number_format($totalDocuments), 'note' => 'Arsip'],
    ];
@endphp
<div class="space-y-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h1 class="text-[26px] sm:text-[30px] font-bold text-[var(--text-primary)] tracking-[-0.03em]">Dashboard Sekretaris</h1>
            <p class="text-[13px] text-[var(--text-muted)] mt-1">Administrasi surat, notulen, dan arsip organisasi.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.letters.create') }}" class="csa-btn csa-btn-primary !min-h-[42px] !text-[13px]">
                <x-admin.icon name="plus" :size="15" /> Catat Surat
            </a>
            <a href="{{ route('admin.minutes.create') }}" class="csa-btn csa-btn-ghost !min-h-[42px] !text-[13px]">
                <x-admin.icon name="plus" :size="15" /> Notulen
            </a>
        </div>
    </div>

    <x-admin.announcement-feed :announcements="$announcements" />

    <section class="csa-panel p-5 sm:p-6">
        <x-admin.stat-strip :items="$statItems" />
    </section>

    <section class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        {{-- Surat terbaru --}}
        <div class="csa-panel overflow-hidden">
            <div class="px-5 py-3.5 border-b border-[var(--border-soft)] flex items-center justify-between">
                <h2 class="text-[14px] font-semibold text-[var(--text-primary)]">Surat Terbaru</h2>
                <a href="{{ route('admin.letters.index') }}" class="text-[12px] font-medium hover:underline" style="color: var(--accent);">Lihat semua</a>
            </div>
            <div class="divide-y divide-[var(--border-soft)]">
                @forelse ($recentLetters as $letter)
                    <div class="flex items-center justify-between gap-3 p-4">
                        <div class="min-w-0">
                            <p class="text-[13px] font-medium text-[var(--text-primary)] truncate">{{ $letter->subject }}</p>
                            <p class="text-[12px] text-[var(--text-muted)] font-mono">{{ $letter->reference_number }}</p>
                        </div>
                        <span class="csa-chip {{ $letter->type === 'incoming' ? 'csa-chip-blue' : 'csa-chip-green' }} shrink-0">{{ $letter->type_label }}</span>
                    </div>
                @empty
                    <p class="p-8 text-center text-[13px] text-[var(--text-muted)]">Belum ada surat tercatat.</p>
                @endforelse
            </div>
        </div>

        {{-- Notulen terbaru --}}
        <div class="csa-panel overflow-hidden">
            <div class="px-5 py-3.5 border-b border-[var(--border-soft)] flex items-center justify-between">
                <h2 class="text-[14px] font-semibold text-[var(--text-primary)]">Notulen Terbaru</h2>
                <a href="{{ route('admin.minutes.index') }}" class="text-[12px] font-medium hover:underline" style="color: var(--accent);">Lihat semua</a>
            </div>
            <div class="divide-y divide-[var(--border-soft)]">
                @forelse ($recentMinutes as $minute)
                    <div class="flex items-center justify-between gap-3 p-4">
                        <div class="min-w-0">
                            <p class="text-[13px] font-medium text-[var(--text-primary)] truncate">{{ $minute->title }}</p>
                            <p class="text-[12px] text-[var(--text-muted)]">{{ $minute->meeting_date->translatedFormat('d M Y') }}</p>
                        </div>
                        <span class="csa-chip csa-chip-blue shrink-0">{{ $minute->attendees_count }} peserta</span>
                    </div>
                @empty
                    <p class="p-8 text-center text-[13px] text-[var(--text-muted)]">Belum ada notulen.</p>
                @endforelse
            </div>
        </div>
    </section>

    <div class="csa-panel overflow-hidden">
        <div class="px-5 py-3.5 border-b border-[var(--border-soft)] flex items-center justify-between">
            <h2 class="text-[14px] font-semibold text-[var(--text-primary)]">Dokumen Terbaru</h2>
            <a href="{{ route('admin.documents.index') }}" class="text-[12px] font-medium hover:underline" style="color: var(--accent);">Lihat semua</a>
        </div>
        <div class="divide-y divide-[var(--border-soft)]">
            @forelse ($recentDocuments as $document)
                <div class="flex items-center justify-between gap-3 p-4">
                    <div class="flex items-center gap-3 min-w-0">
                        <span class="w-9 h-9 rounded-lg bg-[var(--bg-muted)] flex items-center justify-center text-[var(--text-secondary)] shrink-0">
                            <x-admin.icon name="file-text" :size="16" />
                        </span>
                        <div class="min-w-0">
                            <p class="text-[13px] font-medium text-[var(--text-primary)] truncate">{{ $document->title }}</p>
                            <p class="text-[12px] text-[var(--text-muted)]">{{ $document->user?->name ?? '-' }}</p>
                        </div>
                    </div>
                    <span class="text-[12px] text-[var(--text-muted)] shrink-0">{{ $document->created_at->translatedFormat('d M Y') }}</span>
                </div>
            @empty
                <p class="p-8 text-center text-[13px] text-[var(--text-muted)]">Belum ada dokumen.</p>
            @endforelse
        </div>
    </div>

    <x-admin.activity-feed :logs="$systemActivity" />
</div>
@endsection
