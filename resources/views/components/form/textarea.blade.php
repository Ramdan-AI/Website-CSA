@props([
    'name',
    'label',
    'value' => '',
    'placeholder' => '',
    'required' => false,
    'id' => null,
    'hint' => null,
    'rows' => 4,
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

    <textarea
        id="{{ $fieldId }}"
        name="{{ $name }}"
        rows="{{ $rows }}"
        placeholder="{{ $placeholder }}"
        @if ($required) required @endif
        {{ $attributes->merge(['class' => 'csa-input']) }}>{{ old($name, $value) }}</textarea>

    @if (isset($errors) && $errors->has($name))
        <p class="text-[12px] text-red-500 mt-1.5">{{ $errors->first($name) }}</p>
    @elseif ($hint)
        <p class="text-[12px] text-[var(--text-muted)] mt-1.5">{{ $hint }}</p>
    @endif
</div>
