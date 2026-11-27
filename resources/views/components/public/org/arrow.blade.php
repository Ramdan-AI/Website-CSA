@props(['head' => true])

<div {{ $attributes->merge(['class' => 'flex flex-col items-center']) }} aria-hidden="true">
    <span class="block w-px h-5 bg-[var(--line-strong)]"></span>
    @if ($head)
        <span class="block w-0 h-0 border-x-4 border-x-transparent border-t-[6px] border-t-[var(--line-strong)]"></span>
    @endif
</div>
