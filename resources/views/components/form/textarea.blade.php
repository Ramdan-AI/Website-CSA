@props(['name', 'label', 'value' => '', 'placeholder' => '', 'required' => false])

<div class="mb-4">
    <label class="custom-label {{ !$required ? 'd-flex justify-content-between align-items-center' : '' }}">
        {{ $label }}
        @if(!$required)
            <span class="text-optional">Opsional</span>
        @endif
    </label>
    
    <textarea 
        name="{{ $name }}" 
        class="custom-textarea @error($name) is-invalid @enderror"
        placeholder="{{ $placeholder }}"
        {{ $required ? 'required' : '' }}
    >{{ old($name, $value) }}</textarea>
    
    @error($name)
        <div class="text-danger mt-2" style="font-size: 0.85rem; font-weight: 600;">
            <i class="fas fa-exclamation-circle me-1"></i> {{ $message }}
        </div>
    @enderror
</div>