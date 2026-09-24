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
<div class="space-y-6">
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
            <a href="{{ route('admin.events.index', array_filter(['type' => $value])) }}" data-ajax-link
                class="px-3.5 py-1.5 rounded-lg text-[12px] font-medium transition-colors {{ $type === $value ? 'bg-[var(--bg-card)] text-[var(--text-primary)] shadow-sm' : 'text-[var(--text-muted)] hover:text-[var(--text-primary)]' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($events as $event)
            <article class="csa-panel overflow-hidden flex flex-col group">
                <div class="relative h-44 w-full bg-[var(--bg-muted)] overflow-hidden">
                    @if($event->banner_url)
                        <img src="{{ $event->banner_url }}" alt="{{ $event->title }}"
                            class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-[1.04]" loading="lazy">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-[var(--text-muted)]">
                            <x-admin.icon name="calendar-days" :size="30" />
                        </div>
                    @endif

                    <div class="absolute top-3 left-3 flex items-center gap-1.5">
                        <span class="csa-chip {{ $event->type === 'study_club' ? 'csa-chip-violet' : 'csa-chip-blue' }} shadow-sm">
                            {{ \App\Models\Event::TYPES[$event->type] ?? 'Acara' }}
                        </span>
                        <span class="csa-chip {{ $statusChip($event->status) }} shadow-sm">{{ ucfirst($event->status) }}</span>
                    </div>

                    <div class="absolute bottom-3 left-3 rounded-lg bg-black/55 backdrop-blur-sm text-white px-2.5 py-1.5 text-center leading-none">
                        <span class="block text-[15px] font-bold">{{ $event->event_date?->format('d') }}</span>
                        <span class="block text-[10px] font-medium uppercase mt-0.5">{{ $event->event_date?->translatedFormat('M') }}</span>
                    </div>
                </div>

                <div class="p-4 flex-1 flex flex-col">
                    <h3 class="text-[15px] font-semibold text-[var(--text-primary)] line-clamp-2 leading-snug">
                        <a href="{{ route('admin.events.show', $event) }}" class="hover:text-[var(--accent-bright)] transition-colors">{{ $event->title }}</a>
                    </h3>
                    <p class="text-[12px] text-[var(--text-muted)] line-clamp-2 mt-2 leading-relaxed">{{ Str::limit(strip_tags($event->description), 110) }}</p>
                    <div class="mt-auto pt-3 flex flex-wrap items-center gap-x-4 gap-y-1.5 text-[12px] text-[var(--text-muted)]">
                        <span class="inline-flex items-center gap-1.5"><x-admin.icon name="calendar-days" :size="13" /> {{ $event->event_date?->translatedFormat('d M Y') }}</span>
                        @if($event->location)
                            <span class="inline-flex items-center gap-1.5"><x-admin.icon name="map-pin" :size="13" /> {{ $event->location }}</span>
                        @endif
                    </div>
                </div>

                <div class="px-4 py-3 border-t border-[var(--border-soft)] flex items-center justify-between gap-2">
                    @can('manage-attendances')
                        <a href="{{ route('admin.attendances.event', $event) }}" class="csa-btn csa-btn-ghost !min-h-[36px] !px-3 !text-[12px]">
                            <x-admin.icon name="clipboard-check" :size="15" /> Kelola Presensi
                        </a>
                    @else
                        <a href="{{ route('admin.events.show', $event) }}" class="text-[12px] font-medium text-[var(--accent-bright)] hover:underline">Lihat Detail</a>
                    @endcan

                    @can('manage-events')
                        <div class="flex items-center gap-1 shrink-0">
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
