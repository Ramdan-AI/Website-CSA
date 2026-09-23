@props(['announcements' => collect()])

@if ($announcements->isNotEmpty())
    <section class="csa-panel overflow-hidden">
        <header class="flex items-center justify-between gap-4 px-5 pt-5 pb-3">
            <h2 class="text-[15px] font-semibold text-[var(--text-primary)] tracking-[-0.01em]">Pengumuman</h2>
            @can('manage-announcements')
                <a href="{{ route('admin.announcements.index') }}"
                    class="inline-flex items-center gap-1 text-[12px] font-medium text-[var(--text-secondary)] hover:text-[var(--accent-bright)] transition-colors">
                    Kelola
                    <x-admin.icon name="arrow-right" :size="13" />
                </a>
            @endcan
        </header>

        <div class="divide-y divide-[var(--border-soft)] border-t border-[var(--border-soft)]">
            @foreach ($announcements as $announcement)
                <article class="px-5 py-4 {{ $announcement->pinned ? 'border-l-2 border-[var(--accent)]' : '' }}">
                    <div class="flex flex-wrap items-center gap-x-3 gap-y-1">
                        <span class="csa-chip {{ $announcement->category_classes }}">{{ $announcement->category_label }}</span>
                        @if ($announcement->pinned)
                            <span class="csa-chip csa-chip-violet">Disematkan</span>
                        @endif
                        <span class="text-[12px] text-[var(--text-muted)]">{{ $announcement->created_at->diffForHumans() }}</span>
                    </div>

                    <h3 class="text-[15px] font-semibold text-[var(--text-primary)] mt-2.5 leading-snug">{{ $announcement->title }}</h3>
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
