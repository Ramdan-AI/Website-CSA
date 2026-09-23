@props(['announcements' => collect()])

@if ($announcements->isNotEmpty())
    <section class="csa-card overflow-hidden">
        <div class="px-5 py-3.5 border-b border-[var(--border-soft)] flex items-center justify-between">
            <h2 class="text-[14px] font-semibold text-[var(--text-primary)] inline-flex items-center gap-2">
                <x-admin.icon name="megaphone" :size="16" class="text-[var(--text-muted)]" />
                Pengumuman
            </h2>
            @can('manage-announcements')
                <a href="{{ route('admin.announcements.index') }}" class="text-[12px] font-medium hover:underline" style="color: var(--accent);">
                    Kelola
                </a>
            @endcan
        </div>

        <div class="divide-y divide-[var(--border-soft)]">
            @foreach ($announcements as $announcement)
                <article class="p-4 {{ $announcement->pinned ? 'border-l-2 border-[var(--accent)]' : '' }}">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="csa-chip {{ $announcement->category_classes }}">{{ $announcement->category_label }}</span>
                        <span class="text-[12px] text-[var(--text-muted)]">{{ $announcement->created_at->diffForHumans() }}</span>
                    </div>

                    <h3 class="text-[14px] font-medium text-[var(--text-primary)] mt-2">{{ $announcement->title }}</h3>
                    <p class="text-[13px] text-[var(--text-secondary)] leading-[1.7] mt-1.5 whitespace-pre-line">{{ $announcement->body }}</p>

                    <p class="text-[12px] text-[var(--text-muted)] mt-2.5">
                        {{ $announcement->creator?->name ?? 'Pengurus' }}
                        @if ($announcement->period_label)
                            · {{ $announcement->period_label }}
                        @endif
                    </p>
                </article>
            @endforeach
        </div>
    </section>
@endif
