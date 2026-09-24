@props([
    'name',
    'label' => null,
    'value' => null,
    'options' => [],
    'placeholder' => null,
    'required' => false,
    'id' => null,
    'hint' => null,
    'searchable' => false,
])

@php
    $fieldId = $id ?? $name;
    $selected = old($name, $value);

    // onchange inline (mis. "this.form.submit()") dipakai filter di banyak
    // halaman. Karena ini bukan kontrol form native, dijalankan manual saat
    // opsi dipilih.
    $onchange = $attributes->get('onchange');

    $normalized = collect($options)->map(function ($item, $key) {
        if (is_array($item)) {
            return ['value' => (string) ($item['value'] ?? ''), 'label' => (string) ($item['label'] ?? '')];
        }

        return ['value' => (string) $key, 'label' => (string) $item];
    })->values()->all();

    $hasError = isset($errors) && $errors->has($name);
@endphp

<div x-data="googleSelect({
        value: @js((string) ($selected ?? '')),
        options: @js($normalized),
        placeholder: @js($placeholder),
        searchable: @js((bool) $searchable),
        onChange: @js($onchange),
    })" x-modelable="value" @if ($attributes->has('x-model')) x-model="{{ $attributes->get('x-model') }}" @endif
    class="{{ $attributes->get('class') }}">
    @if ($label)
        <label for="{{ $fieldId }}" class="block text-[13px] font-medium text-[var(--text-secondary)] mb-1.5">
            {{ $label }}
            @if (! $required)
                <span class="text-[var(--text-muted)] font-normal">· Opsional</span>
            @endif
        </label>
    @endif

    <div class="relative" @click.outside="close()">
        {{-- Nilai untuk submit form --}}
        <input type="hidden" name="{{ $name }}" id="{{ $fieldId }}" :value="value" @if ($required) data-required="1" @endif>

        {{-- Trigger --}}
        <button type="button" @click="toggle()"
            :class="open ? 'border-[var(--accent)] ring-3 ring-[var(--accent-subtle)]' : 'border-[var(--border-strong)] hover:border-[var(--border-strong)]'"
            class="w-full min-h-[44px] flex items-center gap-2 pl-3.5 pr-3 py-2 rounded-xl bg-[var(--bg-card)] border text-left transition-all duration-150 outline-none">
            <span class="flex-1 min-w-0 truncate text-[13px]"
                :class="selectedLabel ? 'text-[var(--text-primary)]' : 'text-[var(--text-muted)]'"
                x-text="selectedLabel || placeholder || 'Pilih'"></span>
            <x-admin.icon name="chevron-down" :size="16" class="shrink-0 text-[var(--text-muted)] transition-transform duration-200" x-bind:class="open && 'rotate-180'" />
        </button>

        {{-- Panel opsi --}}
        <div x-show="open" x-cloak x-transition.origin.top.duration.120ms
            class="absolute z-50 left-0 right-0 mt-1.5 rounded-xl border border-[var(--border-strong)] bg-[var(--bg-elevated)] shadow-xl overflow-hidden">
            @if ($searchable)
                <div class="p-2 border-b border-[var(--border-soft)]">
                    <input type="text" x-model="query" x-ref="search" placeholder="Cari..."
                        class="w-full h-9 px-3 rounded-lg bg-[var(--bg-muted)] text-[13px] text-[var(--text-primary)] outline-none">
                </div>
            @endif

            <ul class="max-h-64 overflow-y-auto py-1.5" role="listbox">
                <template x-for="option in filteredOptions" :key="option.value">
                    <li>
                        <button type="button" @click="choose(option.value)"
                            class="w-full flex items-center gap-2.5 px-3.5 py-2.5 text-left text-[13px] transition-colors"
                            :class="option.value === value
                                ? 'bg-[var(--accent-subtle)] text-[var(--accent-bright)] font-medium'
                                : 'text-[var(--text-secondary)] hover:bg-[var(--bg-hover)] hover:text-[var(--text-primary)]'">
                            <span class="flex-1 truncate" x-text="option.label"></span>
                            <template x-if="option.value === value">
                                <span class="shrink-0">
                                    <x-admin.icon name="check" :size="15" />
                                </span>
                            </template>
                        </button>
                    </li>
                </template>

                <li x-show="filteredOptions.length === 0" class="px-3.5 py-3 text-[13px] text-[var(--text-muted)] text-center">
                    Tidak ada pilihan
                </li>
            </ul>
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
            /**
             * Dropdown bergaya Google: trigger membulat, panel melayang dengan
             * hover highlight dan centang pada opsi aktif. Mendukung pencarian
             * dalam daftar serta x-modelable agar bisa di-bind dengan Alpine.
             */
            window.googleSelect = (config = {}) => ({
                value: String(config.value ?? ''),
                options: config.options ?? [],
                placeholder: config.placeholder ?? null,
                searchable: config.searchable ?? false,
                onChange: config.onChange ?? null,
                open: false,
                query: '',

                get selectedLabel() {
                    const found = this.options.find((o) => String(o.value) === String(this.value));
                    return found ? found.label : '';
                },

                get filteredOptions() {
                    if (!this.searchable || !this.query) return this.options;
                    const needle = this.query.toLowerCase();
                    return this.options.filter((o) => o.label.toLowerCase().includes(needle));
                },

                toggle() {
                    this.open ? this.close() : this.Open();
                },

                Open() {
                    this.open = true;
                    this.query = '';
                    if (this.searchable) this.$nextTick(() => this.$refs.search?.focus());
                },

                close() {
                    this.open = false;
                },

                choose(val) {
                    this.value = String(val);
                    this.close();
                    this.$dispatch('select-changed', { value: this.value });

                    if (this.onChange) {
                        const form = this.$el.closest('form');
                        this.$nextTick(() => {
                            // Filter AJAX: kirim lewat helper agar data diperbarui
                            // tanpa reload. Fallback ke onchange inline bila lain.
                            if (form && form.hasAttribute('data-ajax-filter') && window.adminAjaxSubmit) {
                                window.adminAjaxSubmit(form);
                            } else if (form && this.onChange.includes('submit()')) {
                                form.requestSubmit();
                            } else {
                                new Function('event', this.onChange).call({ form }, { target: this.$el });
                            }
                        });
                    }
                },
            });
        </script>
    @endpush
@endonce
