@props([
    'label',
    'value',
    'change' => null,
    'changeLabel' => null,
    'trend' => 'up',
    'tone' => 'blue',
])

@php
    $toneClasses = [
        'blue' => 'bg-blue-500/12 text-blue-600 dark:text-blue-400',
        'green' => 'bg-emerald-500/12 text-emerald-600 dark:text-emerald-400',
        'red' => 'bg-red-500/12 text-red-600 dark:text-red-400',
        'violet' => 'bg-violet-500/12 text-violet-600 dark:text-violet-400',
    ];
    $trendIcon = $trend === 'down' ? 'trending-down' : 'trending-up';
@endphp

<article {{ $attributes->merge(['class' => 'csa-card p-4 sm:p-5']) }}>
    <div class="flex items-start justify-between gap-3">
        <div class="min-w-0">
            <p class="csa-stat-label">{{ $label }}</p>
            <p class="csa-stat-number mt-3 truncate">{{ $value }}</p>
        </div>
        <span class="w-9 h-9 rounded-xl {{ $toneClasses[$tone] ?? $toneClasses['blue'] }} flex items-center justify-center shrink-0">
            <x-admin.icon :name="$trendIcon" :size="18" />
        </span>
    </div>
    <div class="mt-3 min-h-5 flex items-center gap-1.5">
        @if ($change)
            <span class="text-[11px] font-semibold {{ $trend === 'down' ? 'text-red-500' : 'text-emerald-600 dark:text-emerald-400' }}">{{ $change }}</span>
        @endif
        @if ($changeLabel)
            <span class="text-[11px] text-[var(--text-muted)]">{{ $changeLabel }}</span>
        @endif
    </div>
</article>
