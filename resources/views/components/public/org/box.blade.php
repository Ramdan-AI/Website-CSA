@props(['box'])

<div {{ $attributes->merge(['class' => 'w-full rounded-xl border border-[var(--line-strong)] bg-[var(--paper)] px-3 py-4 text-center']) }}>
    <p class="text-[11px] font-bold uppercase tracking-[0.08em] leading-relaxed text-[var(--accent-deep)]">{{ $box['title'] }}</p>

    @forelse ($box['people'] as $person)
        <div class="mt-2.5">
            <p class="text-[13px] font-semibold leading-snug text-[var(--ink)]">{{ $person['name'] }}</p>
            @if ($person['identifier'])
                <p class="mt-0.5 text-[11px] text-[var(--ink-faint)]">{{ $person['identifier'] }}</p>
            @endif
        </div>
    @empty
        <p class="mt-2.5 text-[12px] text-[var(--ink-faint)]">Belum diisi</p>
    @endforelse
</div>
