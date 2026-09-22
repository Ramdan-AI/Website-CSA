@props([
    'name',
    'label',
    'value' => '',
    'type' => 'text',
    'placeholder' => '',
    'required' => false,
    'id' => null,
    'hint' => null,
])

@php
    $fieldId = $id ?? $name;
@endphp

<div>
    <label for="{{ $fieldId }}" class="block text-[13px] font-medium text-[var(--text-secondary)] mb-1.5">
        {{ $label }}
        @if (! $required)
            <span class="text-[var(--text-muted)] font-normal">· Opsional</span>
        @endif
    </label>

    <input
        id="{{ $fieldId }}"
        type="{{ $type }}"
        name="{{ $name }}"
        value="{{ old($name, $value) }}"
        placeholder="{{ $placeholder }}"
        @if ($required) required @endif
        {{ $attributes->merge(['class' => 'csa-input']) }}>

    @if (isset($errors) && $errors->has($name))
        <p class="text-[12px] text-red-500 mt-1.5">{{ $errors->first($name) }}</p>
    @elseif ($hint)
        <p class="text-[12px] text-[var(--text-muted)] mt-1.5">{{ $hint }}</p>
    @endif
</div>
