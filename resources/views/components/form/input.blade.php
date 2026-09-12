@props(['name', 'label', 'value' => '', 'type' => 'text', 'placeholder' => '', 'required' => false])

<div class="mb-4">
    <label class="custom-label {{ !$required ? 'd-flex justify-content-between align-items-center' : '' }}">
        {{ $label }}
        @if(!$required)
            <span class="text-optional">Opsional</span>
        @endif
    </label>
    
    <input 
        type="{{ $type }}" 
        name="{{ $name }}" 
        class="custom-input @error($name) is-invalid @enderror" 
        value="{{ old($name, $value) }}"
        placeholder="{{ $placeholder }}"
        {{ $required ? 'required' : '' }}
    >
    
    @error($name)
        <div class="text-danger mt-2" style="font-size: 0.85rem; font-weight: 600;">
            <i class="fas fa-exclamation-circle me-1"></i> {{ $message }}
        </div>
    @enderror
</div>