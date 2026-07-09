{{--
    Component: ui/button
    Engineering justification: A single source of truth for all interactive triggers
    prevents class drift and enforces consistent focus/disabled states across the app.

    Props:
        $variant  → 'primary' | 'ghost' | 'danger'  (default: 'primary')
        $size     → 'sm' | 'md'                      (default: 'md')
        $type     → html button type                  (default: 'button')
        $disabled → bool                              (default: false)
--}}
@props([
    'variant'  => 'primary',
    'size'     => 'md',
    'type'     => 'button',
    'disabled' => false,
])

@php
$base = 'inline-flex items-center justify-center gap-2 font-medium transition-colors duration-100 focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-offset-zinc-950 disabled:opacity-40 disabled:cursor-not-allowed select-none';

$variants = [
    'primary' => 'bg-blue-600 text-white hover:bg-blue-500 focus-visible:ring-blue-500',
    'ghost'   => 'bg-zinc-900 text-zinc-300 border border-zinc-700 hover:bg-zinc-800 hover:text-zinc-100 focus-visible:ring-zinc-600',
    'danger'  => 'bg-red-600/10 text-red-400 border border-red-600/30 hover:bg-red-600/20 focus-visible:ring-red-500',
];

$sizes = [
    'sm' => 'h-8 px-3 text-xs rounded',
    'md' => 'h-10 px-5 text-sm rounded',
];
@endphp

<button
    type="{{ $type }}"
    {{ $disabled ? 'disabled' : '' }}
    {{ $attributes->merge(['class' => "$base {$variants[$variant]} {$sizes[$size]}"]) }}
>
    {{ $slot }}
</button>
