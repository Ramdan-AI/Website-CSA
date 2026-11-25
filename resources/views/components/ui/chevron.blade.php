@props(['direction' => 'right'])

<svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
    <path d="{{ $direction === 'left' ? 'm15 18-6-6 6-6' : 'm9 18 6-6-6-6' }}" />
</svg>
