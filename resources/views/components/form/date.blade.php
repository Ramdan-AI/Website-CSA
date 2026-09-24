@props([
    'name',
    'label' => null,
    'value' => null,
    'required' => false,
    'id' => null,
    'hint' => null,
    'withTime' => false,
    'placeholder' => 'Pilih tanggal',
])

@php
    $fieldId = $id ?? $name;
    $current = old($name, $value);
    $hasError = isset($errors) && $errors->has($name);
@endphp

<div x-data="googleDate({
        value: @js((string) ($current ?? '')),
        withTime: @js((bool) $withTime),
        placeholder: @js($placeholder),
    })" {{ $attributes->only('class') }}>
    @if ($label)
        <label class="block text-[13px] font-medium text-[var(--text-secondary)] mb-1.5">
            {{ $label }}
            @if (! $required)
                <span class="text-[var(--text-muted)] font-normal">· Opsional</span>
            @endif
        </label>
    @endif

    <div class="relative" @click.outside="close()">
        <input type="hidden" name="{{ $name }}" id="{{ $fieldId }}" :value="hiddenValue">

        <button type="button" @click="toggle()"
            :class="open ? 'border-[var(--accent)] ring-3 ring-[var(--accent-subtle)]' : 'border-[var(--border-strong)]'"
            class="w-full min-h-[44px] flex items-center gap-2.5 pl-3.5 pr-10 py-2 rounded-xl bg-[var(--bg-card)] border text-left transition-all duration-150 outline-none">
            <x-admin.icon name="calendar-days" :size="17" class="shrink-0 text-[var(--text-muted)]" />
            <span class="flex-1 min-w-0 truncate text-[13px]"
                :class="display ? 'text-[var(--text-primary)]' : 'text-[var(--text-muted)]'"
                x-text="display || placeholder"></span>
        </button>

        <button type="button" x-show="value" x-cloak @click="clear()"
            class="absolute right-2.5 top-1/2 -translate-y-1/2 w-6 h-6 rounded-full flex items-center justify-center text-[var(--text-muted)] hover:bg-[var(--bg-hover)] hover:text-[var(--text-primary)] transition-colors"
            aria-label="Bersihkan tanggal">
            <x-admin.icon name="x" :size="14" />
        </button>

        <div x-show="open" x-cloak x-transition.origin.top.duration.120ms
            class="absolute z-50 mt-1.5 p-3 rounded-xl border border-[var(--border-strong)] bg-[var(--bg-elevated)] shadow-xl w-[288px]">
            <div class="flex items-center justify-between mb-2.5">
                <button type="button" @click="prevMonth()" class="csa-icon-btn !w-8 !h-8" aria-label="Bulan sebelumnya">
                    <x-admin.icon name="chevron-left" :size="16" />
                </button>
                <div class="text-[13px] font-semibold text-[var(--text-primary)]" x-text="monthLabel"></div>
                <button type="button" @click="nextMonth()" class="csa-icon-btn !w-8 !h-8" aria-label="Bulan berikutnya">
                    <x-admin.icon name="chevron-right" :size="16" />
                </button>
            </div>

            <div class="grid grid-cols-7 gap-0.5 text-center mb-1">
                <template x-for="d in ['S','S','R','K','J','S','M']">
                    <span class="text-[10px] font-semibold uppercase text-[var(--text-muted)] py-1" x-text="d"></span>
                </template>
            </div>

            <div class="grid grid-cols-7 gap-0.5">
                <template x-for="(cell, i) in days" :key="i">
                    <button type="button" @click="cell.day && select(cell.day)"
                        :disabled="!cell.day"
                        class="h-8 rounded-lg text-[12px] transition-colors"
                        :class="!cell.day
                            ? 'invisible'
                            : (cell.day === selectedDay
                                ? 'bg-[var(--accent)] text-white font-semibold'
                                : (cell.today ? 'text-[var(--accent-bright)] font-semibold hover:bg-[var(--accent-subtle)]' : 'text-[var(--text-secondary)] hover:bg-[var(--bg-hover)]'))"
                        x-text="cell.day"></button>
                </template>
            </div>

            <template x-if="withTime">
                <div class="mt-3 pt-3 border-t border-[var(--border-soft)] flex items-center gap-2">
                    <span class="text-[12px] text-[var(--text-muted)]">Waktu</span>
                    <input type="time" x-model="time"
                        class="flex-1 h-9 px-3 rounded-lg bg-[var(--bg-muted)] text-[13px] text-[var(--text-primary)] outline-none">
                </div>
            </template>

            <div class="mt-3 pt-3 border-t border-[var(--border-soft)] flex items-center justify-between">
                <button type="button" @click="today()" class="text-[12px] font-medium text-[var(--accent-bright)] hover:underline">Hari ini</button>
                <button type="button" @click="close()" class="text-[12px] font-medium text-[var(--text-muted)] hover:text-[var(--text-primary)]">Tutup</button>
            </div>
        </div>
    </div>

    @if ($hasError)
        <p class="text-[12px] text-red-500 mt-1.5">{{ $errors->first($name) }}</p>
    @elseif ($hint)
        <p class="text-[12px] text-[var(--text-muted)] mt-1.5">{{ $hint }}</p>
    @endif
</div>

@once
    @push('scripts')
        <script>
            window.googleDate = (config = {}) => ({
                value: String(config.value ?? ''),
                withTime: config.withTime ?? false,
                placeholder: config.placeholder ?? 'Pilih tanggal',
                open: false,
                selectedDay: null,
                time: '',
                viewYear: new Date().getFullYear(),
                viewMonth: new Date().getMonth(),
                months: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],

                init() {
                    if (this.value) {
                        const [d, t] = this.value.split('T');
                        const parts = d.split('-');
                        this.viewYear = parseInt(parts[0], 10);
                        this.viewMonth = parseInt(parts[1], 10) - 1;
                        this.selectedDay = parseInt(parts[2], 10);
                        if (t) this.time = t.slice(0, 5);
                    } else if (this.withTime) {
                        this.time = '00:00';
                    }
                },

                get display() {
                    if (!this.value) return '';
                    const [d] = this.value.split('T');
                    const parts = d.split('-');
                    let out = `${parseInt(parts[2], 10)} ${this.months[parseInt(parts[1], 10) - 1]} ${parts[0]}`;
                    if (this.withTime && this.time) out += `, ${this.time}`;
                    return out;
                },

                get hiddenValue() {
                    if (!this.selectedDay) return '';
                    const mm = String(this.viewMonth + 1).padStart(2, '0');
                    const dd = String(this.selectedDay).padStart(2, '0');
                    const date = `${this.viewYear}-${mm}-${dd}`;
                    return this.withTime ? `${date}T${this.time || '00:00'}` : date;
                },

                get monthLabel() {
                    return `${this.months[this.viewMonth]} ${this.viewYear}`;
                },

                get days() {
                    const first = new Date(this.viewYear, this.viewMonth, 1).getDay();
                    const total = new Date(this.viewYear, this.viewMonth + 1, 0).getDate();
                    const today = new Date();
                    const cells = [];
                    for (let i = 0; i < first; i++) cells.push({ day: null });
                    for (let d = 1; d <= total; d++) {
                        cells.push({
                            day: d,
                            today: d === today.getDate() && this.viewMonth === today.getMonth() && this.viewYear === today.getFullYear(),
                        });
                    }
                    return cells;
                },

                toggle() { this.open = !this.open; },
                close() { this.open = false; },
                prevMonth() {
                    if (this.viewMonth === 0) { this.viewMonth = 11; this.viewYear--; }
                    else this.viewMonth--;
                },
                nextMonth() {
                    if (this.viewMonth === 11) { this.viewMonth = 0; this.viewYear++; }
                    else this.viewMonth++;
                },
                select(day) {
                    this.selectedDay = day;
                    this.value = this.hiddenValue;
                    if (!this.withTime) this.close();
                },
                today() {
                    const t = new Date();
                    this.viewYear = t.getFullYear();
                    this.viewMonth = t.getMonth();
                    this.select(t.getDate());
                },
                clear() {
                    this.value = '';
                    this.selectedDay = null;
                    if (this.withTime) this.time = '00:00';
                },
            });
        </script>
    @endpush
@endonce
