@props([
    'href' => '#',
    'icon' => '',
    'label' => '',
    'active' => false,
    'color' => 'gray',
    'hoverId' => '',
])

@php
    $palette = config("admin.colors.{$color}", config('admin.colors.gray'));
@endphp

<a href="{{ $href }}"
    @mouseenter="hoveredItem = '{{ $hoverId }}'"
    @mouseleave="hoveredItem = null"
    class="relative h-11 rounded-[14px] flex items-center gap-3.5 px-3 transition-all duration-300 outline-none mb-0.5 group/nav"
    :class="expanded ? '' : 'justify-center px-0 w-11 mx-auto'">

    @if ($active)
        <span class="absolute inset-0 rounded-[14px] bg-gradient-to-r {{ $palette['bg'] }} opacity-90"
            style="box-shadow: 0 4px 16px {{ $palette['glow'] }};"></span>
    @endif

    <span class="absolute inset-0 rounded-[14px] bg-gray-50 opacity-0 group-hover/nav:opacity-100 transition-opacity duration-200 {{ $active ? '!opacity-0' : '' }}"></span>

    <span class="relative w-5 flex items-center justify-center shrink-0 z-10">
        <i class="fas {{ $icon }} text-[15px] transition-all duration-300 {{ $active ? 'text-white drop-shadow-sm' : 'text-gray-400 group-hover/nav:text-gray-700' }}"></i>
    </span>

    <span class="relative z-10 font-semibold text-[13px] whitespace-nowrap transition-all duration-300 {{ $active ? 'text-white' : 'text-gray-600 group-hover/nav:text-gray-900' }}"
        x-show="expanded" x-transition:enter="transition delay-75">
        {{ $label }}
    </span>

    @if ($active)
        <span class="absolute -right-1 top-1/2 -translate-y-1/2 w-1.5 h-1.5 rounded-full bg-white/90 shadow-sm" x-show="!expanded"></span>
    @endif
</a>
