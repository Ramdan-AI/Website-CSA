@props([
    'announcement',
    'actions' => false,
])

<article class="csa-panel csa-panel-hover p-5 {{ $announcement->pinned ? 'border-l-2 border-[var(--accent)]' : '' }}">
    <div class="flex gap-4">
        <x-admin.avatar :user="$announcement->creator" :size="40" class="w-10 h-10 rounded-full shrink-0" />

        <div class="min-w-0 flex-1">
            <div class="flex flex-wrap items-center gap-x-2.5 gap-y-1">
                <span class="text-[13px] font-semibold text-[var(--text-primary)]">{{ $announcement->creator?->name ?? 'Pengurus' }}</span>
                <span class="text-[12px] text-[var(--text-muted)]">· {{ $announcement->created_at->diffForHumans() }}</span>
                <span class="csa-chip {{ $announcement->category_classes }} ml-auto sm:ml-2">{{ $announcement->category_label }}</span>
                @if ($announcement->pinned)
                    <span class="csa-chip csa-chip-violet">Disematkan</span>
                @endif
                @if ($announcement->status === 'draft')
                    <span class="csa-chip csa-chip-amber">Draf</span>
                @endif
            </div>

            <h2 class="text-[15px] font-semibold text-[var(--text-primary)] mt-2 leading-snug">{{ $announcement->title }}</h2>
            <p class="text-[13px] text-[var(--text-secondary)] mt-1.5 leading-[1.7] whitespace-pre-line line-clamp-3">{{ $announcement->body }}</p>

            <div class="flex flex-wrap items-center gap-x-4 gap-y-1 mt-3 text-[12px] text-[var(--text-muted)]">
                @if ($announcement->period_label)
                    <span class="inline-flex items-center gap-1.5">
                        <x-admin.icon name="calendar-days" :size="13" /> {{ $announcement->period_label }}
                    </span>
                @endif
                <span class="inline-flex items-center gap-1.5">
                    <x-admin.icon name="clock" :size="13" /> {{ $announcement->created_at->translatedFormat('d M Y H:i') }}
                </span>
            </div>
        </div>

        @if ($actions)
            <div class="flex items-start gap-1 shrink-0">
                <a href="{{ route('admin.announcements.edit', $announcement) }}" class="csa-icon-btn" title="Edit" aria-label="Edit pengumuman">
                    <x-admin.icon name="pen" :size="16" />
                </a>
                <button type="button" onclick="openDeleteModal('deleteModal', '{{ route('admin.announcements.destroy', $announcement) }}')" class="csa-icon-btn hover:!text-red-500" title="Hapus" aria-label="Hapus pengumuman">
                    <x-admin.icon name="trash-2" :size="16" />
                </button>
            </div>
        @endif
    </div>
</article>
