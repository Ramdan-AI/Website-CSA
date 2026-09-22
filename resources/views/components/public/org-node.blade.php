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
        $frame = 'border-2 border-[#1a73e8] bg-[#f1f3f4]';
    } elseif ($isDashed) {
        $frame = 'border border-dashed border-gray-300 bg-[#FAFBFC] hover:border-[#1a73e8]/40';
    } else {
        $frame = 'border border-gray-200 bg-white hover:border-[#1a73e8]/40';
    }

    $avatarSize = $compact ? 'w-9 h-9' : 'w-11 h-11';
    $padding = $bare ? 'p-2' : 'p-3.5';
    $labelTone = $isDashed ? 'text-gray-400' : 'text-[#1a73e8]';
@endphp

<div {{ $attributes->merge([
    'class' => 'w-full rounded-2xl flex items-center gap-3 transition-colors ' . $padding . ' ' . $frame,
]) }}>
    <div class="{{ $avatarSize }} rounded-full overflow-hidden bg-[#e8f0fe] shrink-0">
        <img src="{{ $photo }}" alt="{{ $member->name }}" width="44" height="44"
            class="w-full h-full object-cover" loading="lazy"
            onerror="this.onerror=null;this.src='{{ asset('storage/profile/profile-placeholder.png') }}';">
    </div>
    <div class="min-w-0 flex-1">
        <p class="text-[10px] font-bold uppercase tracking-[0.1em] {{ $labelTone }}">{{ $label }}</p>
        <p class="text-[13px] font-bold text-gray-900 truncate">{{ $member->name }}</p>
        @if ($showDivision && $divisionName)
            <p class="text-[11px] text-gray-400 truncate">{{ $divisionName }}</p>
        @endif
    </div>
</div>
