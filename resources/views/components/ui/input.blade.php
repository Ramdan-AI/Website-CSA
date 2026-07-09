{{--
    Component: ui/input
    Engineering justification: Centralising input styling allows uniform error-state
    injection and prevents per-field class repetition in form views.

    Props:
        $label       → string, visible label text
        $id          → string, ties label to input via for/id
        $name        → string, form field name (defaults to $id)
        $type        → input type (default: 'text')
        $placeholder → string
        $value       → old() value passthrough
        $error       → error message string (optional)
        $hint        → subtle helper text below field (optional)
        $mono        → bool, use JetBrains Mono for data fields (default: false)
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
$inputBase = 'block w-full h-10 px-3 bg-zinc-900 border text-sm text-zinc-100 placeholder-zinc-600 rounded transition-colors duration-100 focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-1 focus-visible:ring-offset-zinc-950';
$inputState = $error
    ? 'border-red-600 focus-visible:ring-red-500'
    : 'border-zinc-700 hover:border-zinc-600 focus-visible:ring-blue-500';
$fontClass = $mono ? 'font-mono text-xs tracking-tight' : '';
@endphp

<div class="flex flex-col gap-1.5">
    @if($label)
        <label for="{{ $id }}" class="text-xs font-semibold text-zinc-400 tracking-wide uppercase">
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
        <p class="text-xs text-red-400 font-mono">{{ $error }}</p>
    @elseif($hint)
        <p class="text-xs text-zinc-600">{{ $hint }}</p>
    @endif
</div>
