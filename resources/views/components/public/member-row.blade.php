{{-- Editorial directory row — used for pengurus & anggota rosters --}}
@props(['member', 'size' => 'md', 'index' => null])

@php
    $role = $member->position?->name ?? $member->division?->name ?? 'Anggota';
    $divisionName = $member->division?->name;
    $avatar = $member->photo_url;
    $initials = strtoupper(collect(explode(' ', trim($member->name)))->take(2)->map(fn ($w) => mb_substr($w, 0, 1))->join(''));
    $compact = $size === 'sm';
@endphp

<div {{ $attributes->merge([
    'class' => 'group grid items-center gap-5 md:gap-7 border-b border-gray-200 hover:bg-[#F8FAFF] transition-colors duration-300 px-2 sm:px-4 -mx-2 sm:-mx-4 '
        . ($compact ? 'grid-cols-[48px_1fr] md:grid-cols-[56px_48px_1fr_auto] py-4 sm:py-5'
                    : 'grid-cols-[56px_1fr] md:grid-cols-[64px_64px_1fr_auto] py-6 sm:py-7'),
]) }}>
    @if ($index)
        <span data-parallax="-12"
            class="hidden md:block {{ $compact ? 'text-lg' : 'text-2xl' }} font-extrabold text-[#3664AF]/10 group-hover:text-[#3664AF]/25 transition-colors duration-300 tabular-nums select-none">{{ $index }}</span>
    @endif

    <div class="{{ $compact ? 'w-12 h-12' : 'w-16 h-16' }} rounded-2xl bg-[#EEF3FB] overflow-hidden flex items-center justify-center shrink-0">
        @if ($avatar)
            <img src="{{ $avatar }}" alt="{{ $member->name }}" class="w-full h-full object-cover" loading="lazy" decoding="async">
        @else
            <span class="{{ $compact ? 'text-lg' : 'text-2xl' }} font-bold text-[#3664AF]/50">{{ $initials }}</span>
        @endif
    </div>

    <div class="min-w-0">
        <h3 class="{{ $compact ? 'text-base sm:text-lg' : 'text-xl sm:text-2xl' }} font-bold text-gray-900 tracking-tight truncate">{{ $member->name }}</h3>
        <p class="text-[11px] font-semibold uppercase tracking-[0.2em] text-[#3664AF] mt-1.5">{{ $role }}</p>
        <div class="flex items-center gap-2 mt-3 md:hidden">
            @include('components.public.partials.member-socials', ['member' => $member, 'compact' => true])
        </div>
    </div>

    <div class="hidden md:flex flex-col items-end gap-2.5">
        @if ($divisionName)
            <p class="{{ $compact ? 'text-[13px]' : 'text-sm' }} text-gray-400">{{ $divisionName }}</p>
        @endif
        <div class="flex items-center gap-1.5">
            @include('components.public.partials.member-socials', ['member' => $member, 'compact' => true])
        </div>
    </div>
</div>
