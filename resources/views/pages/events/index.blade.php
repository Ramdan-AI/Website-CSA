@extends('layouts.admin')

@php
    use Illuminate\Support\Str;

    $statusChip = fn (?string $status) => match ($status) {
        'published' => 'csa-chip-green',
        'draft' => 'csa-chip-amber',
        default => '',
    };
@endphp

@section('content')
<div class="space-y-4 sm:space-y-5">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h1 class="text-[26px] sm:text-[30px] font-bold text-[var(--text-primary)] tracking-[-0.03em]">Kegiatan</h1>
            <p class="text-[13px] text-[var(--text-muted)] mt-1">Acara terjadwal dan study club rutin UKM CSA.</p>
        </div>
        @can('manage-events')
            <a href="{{ route('admin.events.create') }}" class="csa-btn csa-btn-primary">
                <x-admin.icon name="plus" :size="16" /> Tambah Kegiatan
            </a>
        @endcan
    </div>

    <div class="inline-flex items-center gap-1 p-1 rounded-xl bg-[var(--bg-muted)] w-fit">
        @foreach (['' => 'Semua', 'acara' => 'Acara', 'study_club' => 'Study Club'] as $value => $label)
            <a href="{{ route('admin.events.index', array_filter(['type' => $value])) }}"
                class="px-3.5 py-1.5 rounded-lg text-[12px] font-medium transition-colors {{ $type === $value ? 'bg-[var(--bg-card)] text-[var(--text-primary)] shadow-sm' : 'text-[var(--text-muted)] hover:text-[var(--text-primary)]' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($events as $event)
            <article class="csa-panel csa-panel-hover overflow-hidden flex flex-col">
                @if($event->banner_url)
                    <div class="h-40 w-full bg-[var(--bg-muted)] overflow-hidden">
                        <img src="{{ $event->banner_url }}" alt="{{ $event->title }}" class="w-full h-full object-cover" loading="lazy">
                    </div>
                @else
                    <div class="h-40 w-full bg-[var(--bg-muted)] flex items-center justify-center text-[var(--text-muted)]">
                        <x-admin.icon name="calendar-days" :size="30" />
                    </div>
                @endif
                <div class="p-4 flex-1">
                    <div class="flex items-center justify-between gap-2 mb-1.5">
                        <span class="text-[12px] font-medium text-[var(--text-secondary)]">{{ $event->event_date?->translatedFormat('d M Y') }}</span>
                        <div class="flex items-center gap-1.5">
                            <span class="csa-chip {{ $event->type === 'study_club' ? 'csa-chip-violet' : 'csa-chip-blue' }}">
                                {{ \App\Models\Event::TYPES[$event->type] ?? 'Acara' }}
                            </span>
                            <span class="csa-chip {{ $statusChip($event->status) }}">{{ ucfirst($event->status) }}</span>
                        </div>
                    </div>
                    <h3 class="text-[14px] font-semibold text-[var(--text-primary)] line-clamp-2">
                        <a href="{{ route('admin.events.show', $event) }}" class="hover:text-[var(--accent-bright)] transition-colors">{{ $event->title }}</a>
                    </h3>
                    <p class="text-[12px] text-[var(--text-muted)] line-clamp-2 mt-2">{{ Str::limit(strip_tags($event->description), 110) }}</p>
                    @if($event->location)
                        <p class="text-[12px] text-[var(--text-muted)] mt-2 inline-flex items-center gap-1.5">
                            <x-admin.icon name="map-pin" :size="13" /> {{ $event->location }}
                        </p>
                    @endif
                </div>
                <div class="px-4 py-3 border-t border-[var(--border-soft)] flex items-center justify-between">
                    <a href="{{ route('admin.events.show', $event) }}" class="text-[12px] font-medium text-[var(--accent-bright)] hover:underline">Lihat Detail</a>
                    @can('manage-events')
                        <div class="flex items-center gap-1">
                            <a href="{{ route('admin.events.edit', $event) }}" class="csa-icon-btn" title="Edit" aria-label="Edit acara">
                                <x-admin.icon name="pen" :size="16" />
                            </a>
                            <button type="button" onclick="openDeleteModal('deleteModal', '{{ route('admin.events.destroy', $event) }}')" class="csa-icon-btn hover:!text-red-500" title="Hapus" aria-label="Hapus acara">
                                <x-admin.icon name="trash-2" :size="16" />
                            </button>
                        </div>
                    @endcan
                </div>
            </article>
        @empty
            <div class="col-span-full csa-panel p-12 text-center">
                <x-admin.icon name="calendar-x" :size="32" class="mx-auto text-[var(--text-muted)]" />
                <p class="text-[13px] font-medium text-[var(--text-secondary)] mt-3">Belum ada acara tercatat</p>
            </div>
        @endforelse
    </div>

    @if ($events->hasPages())
        {{ $events->links() }}
    @endif
</div>
@endsection
