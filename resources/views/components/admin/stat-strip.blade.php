@props([
    'items' => [],
    'lead' => false,
])


<div {{ $attributes->merge(['class' => 'flex flex-col divide-y divide-[var(--border-soft)] sm:flex-row sm:divide-x sm:divide-y-0']) }}>
    @foreach ($items as $item)
        @php
            $isLead = $lead && $loop->first;
        @endphp
        <div class="flex-1 min-w-0 py-4 first:pt-0 last:pb-0 sm:py-0 sm:px-6 sm:first:pl-0 sm:last:pr-0">
            <p class="text-[11px] font-medium uppercase tracking-[0.08em] text-[var(--text-muted)]">{{ $item['label'] }}</p>
            <p class="mt-1.5 truncate tabular-nums text-[var(--text-primary)] {{ $isLead ? 'text-[30px] sm:text-[34px] font-bold tracking-[-0.03em] leading-none' : 'text-[20px] font-semibold leading-tight' }}">
                {{ $item['value'] }}
            </p>
            @if (! empty($item['note']))
                <p class="mt-1 text-[12px] text-[var(--text-muted)]">{{ $item['note'] }}</p>
            @endif
        </div>
    @endforeach
</div>
