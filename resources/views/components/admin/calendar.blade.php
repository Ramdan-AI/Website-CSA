@props([
    'events' => [],
    'initialDate' => null,
])

@php
    $payload = collect($events)->map(fn ($e) => [
        'title' => $e['title'] ?? '',
        'date' => $e['date'] ?? ($e['event_date'] ?? null),
        'active' => (bool) ($e['active'] ?? ($e['attendance_enabled'] ?? false)),
    ])->filter(fn ($e) => $e['date'])->values();
@endphp

<div x-data="googleCalendar({ events: @js($payload), initial: @js($initialDate ?? now()->toDateString()) })" x-init="init()"
    {{ $attributes->merge(['class' => 'csa-panel p-4']) }}>
    {{-- Header navigasi --}}
    <div class="flex items-center justify-between gap-3 mb-4">
        <div class="flex items-center gap-1.5">
            <button type="button" @click="prev()" class="csa-icon-btn" aria-label="Sebelumnya">
                <x-admin.icon name="chevron-left" :size="18" />
            </button>
            <button type="button" @click="next()" class="csa-icon-btn" aria-label="Berikutnya">
                <x-admin.icon name="chevron-right" :size="18" />
            </button>
            <button type="button" @click="today()"
                class="ml-1 px-3 py-1.5 rounded-lg text-[12px] font-medium text-[var(--text-secondary)] border border-[var(--border-strong)] hover:bg-[var(--bg-hover)] transition-colors">
                Hari Ini
            </button>
        </div>
        <h3 class="text-[15px] font-semibold text-[var(--text-primary)]" x-text="monthLabel"></h3>
    </div>

    {{-- Nama hari --}}
    <div class="grid grid-cols-7 gap-px mb-1">
        <template x-for="d in ['Sen','Sel','Rab','Kam','Jum','Sab','Min']">
            <div class="text-center text-[11px] font-semibold uppercase tracking-wide text-[var(--text-muted)] py-2" x-text="d"></div>
        </template>
    </div>

    {{-- Grid tanggal --}}
    <div class="grid grid-cols-7 gap-px bg-[var(--border-soft)] rounded-xl overflow-hidden border border-[var(--border-soft)]">
        <template x-for="(cell, i) in cells" :key="i">
            <div class="min-h-[92px] bg-[var(--bg-card)] p-1.5 flex flex-col gap-1 transition-colors"
                :class="!cell.day ? 'bg-[var(--bg-muted)]' : (cell.isToday ? 'bg-[var(--accent-subtle)]' : '')">
                <template x-if="cell.day">
                    <div class="flex items-center justify-between">
                        <span class="text-[12px] font-medium w-6 h-6 flex items-center justify-center rounded-full"
                            :class="cell.isToday ? 'bg-[var(--accent)] text-white' : 'text-[var(--text-secondary)]'"
                            x-text="cell.day"></span>
                    </div>
                </template>
                <template x-for="ev in cell.events" :key="ev.title">
                    <div class="flex items-center gap-1.5 px-1.5 py-1 rounded-md text-[11px] font-medium truncate"
                        :class="ev.active ? 'bg-[var(--accent-subtle)] text-[var(--accent-bright)]' : 'bg-[var(--bg-hover)] text-[var(--text-secondary)]'"
                        :title="ev.title">
                        <span class="w-1.5 h-1.5 rounded-full shrink-0" :class="ev.active ? 'bg-[var(--accent)]' : 'bg-[var(--text-muted)]'"></span>
                        <span class="truncate" x-text="ev.title"></span>
                    </div>
                </template>
            </div>
        </template>
    </div>
</div>

@once
    @push('scripts')
        <script>
            window.googleCalendar = (config = {}) => ({
                events: config.events ?? [],
                year: 0,
                month: 0,
                months: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],

                init() {
                    const base = config.initial ? new Date(config.initial) : new Date();
                    this.year = base.getFullYear();
                    this.month = base.getMonth();
                },

                get monthLabel() {
                    return `${this.months[this.month]} ${this.year}`;
                },

                get cells() {
                    const first = new Date(this.year, this.month, 1);
                    const startOffset = (first.getDay() + 6) % 7; // Senin = 0
                    const total = new Date(this.year, this.month + 1, 0).getDate();
                    const today = new Date();
                    const cells = [];

                    for (let i = 0; i < startOffset; i++) cells.push({ day: null, events: [] });

                    for (let d = 1; d <= total; d++) {
                        const iso = `${this.year}-${String(this.month + 1).padStart(2, '0')}-${String(d).padStart(2, '0')}`;
                        cells.push({
                            day: d,
                            isToday: d === today.getDate() && this.month === today.getMonth() && this.year === today.getFullYear(),
                            events: this.events.filter((e) => String(e.date).slice(0, 10) === iso),
                        });
                    }

                    return cells;
                },

                prev() {
                    if (this.month === 0) { this.month = 11; this.year--; } else this.month--;
                },
                next() {
                    if (this.month === 11) { this.month = 0; this.year++; } else this.month++;
                },
                today() {
                    const t = new Date();
                    this.year = t.getFullYear();
                    this.month = t.getMonth();
                },
            });
        </script>
    @endpush
@endonce
