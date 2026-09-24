@props(['announcements' => collect()])

@if ($announcements->isNotEmpty())
    <section class="csa-panel overflow-hidden" x-data="{ open: false, current: null }">
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
                <button type="button"
                    @click="current = {
                        title: @js($announcement->title),
                        body: @js($announcement->body),
                        label: @js($announcement->category_label),
                        classes: @js($announcement->category_classes),
                        pinned: @js((bool) $announcement->pinned),
                        author: @js($announcement->creator?->name ?? 'Pengurus'),
                        time: @js($announcement->created_at->diffForHumans()),
                        period: @js($announcement->period_label),
                    }; open = true"
                    class="w-full text-left px-5 py-4 hover:bg-[var(--bg-hover)] transition-colors {{ $announcement->pinned ? 'border-l-2 border-[var(--accent)]' : '' }}">
                    <div class="flex flex-wrap items-center gap-x-3 gap-y-1">
                        <span class="csa-chip {{ $announcement->category_classes }}">{{ $announcement->category_label }}</span>
                        @if ($announcement->pinned)
                            <span class="csa-chip csa-chip-violet">Disematkan</span>
                        @endif
                        <span class="text-[12px] text-[var(--text-muted)]">{{ $announcement->created_at->diffForHumans() }}</span>
                    </div>

                    <h3 class="text-[15px] font-semibold text-[var(--text-primary)] mt-2.5 leading-snug">{{ $announcement->title }}</h3>
                    <p class="text-[13px] text-[var(--text-secondary)] leading-[1.7] mt-1.5 line-clamp-2 whitespace-pre-line">{{ $announcement->body }}</p>

                    <span class="inline-flex items-center gap-1 text-[12px] font-medium mt-2" style="color: var(--accent-bright);">
                        Baca selengkapnya <x-admin.icon name="arrow-right" :size="12" />
                    </span>
                </button>
            @endforeach
        </div>

        {{-- Modal detail pengumuman --}}
        <div x-show="open" x-cloak class="fixed inset-0 z-[95] flex items-start justify-center p-4 sm:p-6 overflow-y-auto"
            style="background: rgba(0,0,0,.5); backdrop-filter: blur(4px);" @click.self="open = false"
            @keydown.escape.window="open = false">
            <div x-show="open" x-transition.opacity.duration.150ms
                class="w-full max-w-lg mt-[8vh] rounded-2xl border border-[var(--border-strong)] bg-[var(--bg-elevated)] shadow-2xl overflow-hidden"
                x-data="{}">
                <template x-if="current">
                    <div>
                        <div class="flex items-start justify-between gap-4 px-5 pt-5 pb-4 border-b border-[var(--border-soft)]">
                            <div class="min-w-0">
                                <div class="flex flex-wrap items-center gap-2">
                                    <span class="csa-chip" :class="current.classes" x-text="current.label"></span>
                                    <template x-if="current.pinned">
                                        <span class="csa-chip csa-chip-violet">Disematkan</span>
                                    </template>
                                </div>
                                <h3 class="text-[17px] font-semibold text-[var(--text-primary)] mt-2.5 leading-snug" x-text="current.title"></h3>
                            </div>
                            <button type="button" @click="open = false" class="csa-icon-btn shrink-0" aria-label="Tutup">
                                <x-admin.icon name="x" :size="18" />
                            </button>
                        </div>

                        <div class="px-5 py-5 max-h-[60vh] overflow-y-auto">
                            <p class="text-[14px] text-[var(--text-secondary)] leading-[1.8] whitespace-pre-line" x-text="current.body"></p>
                        </div>

                        <div class="px-5 py-3.5 border-t border-[var(--border-soft)] flex flex-wrap items-center gap-x-3 gap-y-1 text-[12px] text-[var(--text-muted)]">
                            <span x-text="current.author"></span>
                            <span>·</span>
                            <span x-text="current.time"></span>
                            <template x-if="current.period">
                                <span>· <span x-text="current.period"></span></span>
                            </template>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </section>
@endif
