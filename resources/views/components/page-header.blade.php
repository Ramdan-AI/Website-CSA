@props([
    'title',
    'subtitle' => null
])

<div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div>
        <h1 class="page-title">{{ $title }}</h1>

        @if($subtitle)
            <p class="page-subtitle">{{ $subtitle }}</p>
        @endif
    </div>

    @isset($actions)
        <div class="page-header-actions">
            {{ $actions }}
        </div>
    @endisset
</div>