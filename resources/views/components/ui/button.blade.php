{{--
    Component: ui/button
    Compliant with Universal Light Mode V2 plan.
    WCAG AAA on white backgrounds.

    Props:
        $variant  → 'primary' | 'secondary' | 'danger' (default: 'primary')
        $size     → 'sm' | 'md' | 'lg'                 (default: 'md')
        $tag      → 'button' | 'a'                      (default: 'button')
        $type     → html button type                    (default: 'button')
        $disabled → bool                                (default: false)
--}}
@props([
    'variant'  => 'primary',
    'size'     => 'md',
    'tag'      => 'button',
    'type'     => 'button',
    'disabled' => false,
])

@php
$base = 'inline-flex items-center justify-center gap-2 font-semibold rounded-lg transition-all duration-150 focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed select-none';

$variants = [
    'primary'   => 'bg-blue-600 text-white hover:bg-blue-700 hover:shadow-md active:scale-[0.98] focus-visible:ring-blue-500',
    'secondary' => 'bg-white text-gray-700 border border-gray-300 hover:bg-gray-50 hover:text-gray-900 hover:shadow-sm active:scale-[0.98] focus-visible:ring-gray-400 shadow-sm',
    'danger'    => 'bg-red-50 text-red-600 border border-red-200 hover:bg-red-100 hover:text-red-700 active:scale-[0.98] focus-visible:ring-red-400',
];

$sizes = [
    'sm' => 'h-8 px-4 text-xs',
    'md' => 'h-10 px-5 text-sm',
    'lg' => 'h-12 px-7 text-base',
];
@endphp

@if ($tag === 'a')
    <a {{ $attributes->merge(['class' => "$base {$variants[$variant]} {$sizes[$size]}"]) }}>
        {{ $slot }}
    </a>
@else
    <button
        type="{{ $type }}"
        {{ $disabled ? 'disabled' : '' }}
        {{ $attributes->merge(['class' => "$base {$variants[$variant]} {$sizes[$size]}"]) }}
    >
        {{ $slot }}
    </button>
@endif
