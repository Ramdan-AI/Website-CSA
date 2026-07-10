{{--
    Component: ui/input
    Compliant with Universal Light Mode V2 plan.

    Props:
        $label       → string, visible label text
        $id          → string, ties label to input via for/id
        $name        → string, form field name (defaults to $id)
        $type        → input type (default: 'text')
        $placeholder → string
        $value       → old() value passthrough
        $error       → error message string (optional)
        $hint        → subtle helper text below field (optional)
        $mono        → bool, use mono font for data-type fields (default: false)
--}}
@props([
    'label'       => '',
    'id'          => '',
    'name'        => null,
    'type'        => 'text',
    'placeholder' => '',
    'value'       => '',
    'error'       => null,
    'hint'        => null,
    'mono'        => false,
])

@php
$resolvedName = $name ?? $id;
$inputBase = 'block w-full h-10 px-3 bg-white border text-sm text-gray-900 placeholder-gray-400 rounded-lg transition-shadow duration-150 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500';
$inputState = $error
    ? 'border-red-400 focus:ring-red-500/40 focus:border-red-400'
    : 'border-gray-300 hover:border-gray-400';
$fontClass = $mono ? 'font-mono text-xs tracking-tight' : '';
@endphp

<div class="flex flex-col gap-1.5">
    @if($label)
        <label for="{{ $id }}" class="text-sm font-medium text-gray-700">
            {{ $label }}
        </label>
    @endif

    <input
        id="{{ $id }}"
        type="{{ $type }}"
        name="{{ $resolvedName }}"
        value="{{ $value }}"
        placeholder="{{ $placeholder }}"
        {{ $attributes->merge(['class' => "$inputBase $inputState $fontClass"]) }}
    >

    @if($error)
        <p class="text-xs text-red-500">{{ $error }}</p>
    @elseif($hint)
        <p class="text-xs text-gray-500">{{ $hint }}</p>
    @endif
</div>
