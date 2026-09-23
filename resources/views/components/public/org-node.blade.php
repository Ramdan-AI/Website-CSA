@props([
    'member',
    'label',
    'image' => null,
    'variant' => 'solid',
    'emphasis' => null,
    'compact' => false,
    'bare' => false,
    'showDivision' => false,
])

@php
    $photo = $image ?: ($member->photo_url ?: asset('storage/profile/profile-placeholder.png'));
    $isDashed = $variant === 'dashed';
    $isPrimary = $emphasis === 'primary';
    $divisionName = $member->division?->name ?? null;

    if ($bare) {
        $frame = 'bg-transparent';
    } elseif ($isPrimary) {
        $frame = 'border-2 border-[var(--accent)] bg-[var(--accent-soft)]';
    } elseif ($isDashed) {
        $frame = 'border border-dashed border-[var(--line-strong)] bg-[var(--paper-sunken)] hover:border-[var(--line-strong)]';
    } else {
        $frame = 'border border-[var(--line)] bg-[var(--paper)] hover:border-[var(--line-strong)]';
    }

    $avatarSize = $compact ? 'w-9 h-9' : 'w-11 h-11';
    $padding = $bare ? 'p-2' : 'p-3.5';
    $labelTone = $isDashed ? 'text-[var(--ink-faint)]' : 'text-[var(--accent)]';
@endphp

<div {{ $attributes->merge([
    'class' => 'w-full rounded-2xl flex items-center gap-3 transition-colors ' . $padding . ' ' . $frame,
]) }}>
    <div class="{{ $avatarSize }} rounded-full overflow-hidden bg-[var(--accent-soft)] shrink-0">
        <img src="{{ $photo }}" alt="{{ $member->name }}" width="44" height="44"
            class="w-full h-full object-cover" loading="lazy"
            onerror="this.onerror=null;this.src='{{ asset('storage/profile/profile-placeholder.png') }}';">
    </div>
    <div class="min-w-0 flex-1">
        <p class="text-[10px] font-bold uppercase tracking-[0.1em] {{ $labelTone }}">{{ $label }}</p>
        <p class="text-[13px] font-bold text-[var(--ink)] truncate">{{ $member->name }}</p>
        @if ($showDivision && $divisionName)
            <p class="text-[11px] text-[var(--ink-faint)] truncate">{{ $divisionName }}</p>
        @endif
    </div>
</div>
