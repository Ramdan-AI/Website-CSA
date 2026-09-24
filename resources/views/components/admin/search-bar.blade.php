@props([
    'name' => 'search',
    'value' => null,
    'placeholder' => 'Cari...',
    'action' => null,
    'label' => null,
])

@php
    $current = old($name, $value ?? request($name, ''));
@endphp

<form method="GET" action="{{ $action ?? url()->current() }}"
    data-ajax-filter
    x-data="{ q: @js($current), get hasValue() { return this.q.length > 0; } }"
    class="{{ $attributes->get('class') }}"
    role="search">
    {{-- Pertahankan parameter filter lain saat submit pencarian. --}}
    @foreach (request()->except([$name, 'page']) as $key => $val)
        @if (is_scalar($val))
            <input type="hidden" name="{{ $key }}" value="{{ $val }}">
        @endif
    @endforeach

    <div class="relative">
        @if ($label)
            <label for="{{ $name }}-search" class="block text-[13px] font-medium text-[var(--text-secondary)] mb-1.5">{{ $label }}</label>
        @endif

        <div class="relative group">
            <span class="pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-[var(--text-muted)] transition-colors">
                <x-admin.icon name="search" :size="18" />
            </span>

            <input type="text" name="{{ $name }}" id="{{ $name }}-search" x-model="q" x-ref="searchInput"
                placeholder="{{ $placeholder }}" autocomplete="off" role="searchbox"
                class="csa-input !pl-[48px] !pr-12 !rounded-full csa-search-input">

            <button type="button" x-show="hasValue" x-cloak @click="q = ''; $refs.searchInput.focus()"
                class="absolute right-3 top-1/2 -translate-y-1/2 w-7 h-7 rounded-full flex items-center justify-center text-[var(--text-muted)] hover:bg-[var(--bg-hover)] hover:text-[var(--text-primary)] transition-colors"
                aria-label="Bersihkan pencarian">
                <x-admin.icon name="x" :size="15" />
            </button>
        </div>
    </div>

    {{ $slot }}
</form>
