{{-- Reusable public member card — md: portrait feature, sm: horizontal row --}}
@props(['member', 'size' => 'sm'])

@php
    $role = $member->position?->name ?? $member->division?->name ?? 'Anggota';
    $divisionName = $member->division?->name;
    $avatar = $member->photo_url;
    $initials = strtoupper(collect(explode(' ', trim($member->name)))->take(2)->map(fn ($w) => mb_substr($w, 0, 1))->join(''));
@endphp

@if ($size === 'md')
<div {{ $attributes->merge([
    'class' => 'group bg-white border border-gray-200/70 rounded-[24px] overflow-hidden hover:border-[#3664AF]/30 hover:shadow-[0_28px_64px_-36px_rgba(54,100,175,0.4)] hover:-translate-y-1 transition-all duration-300',
]) }}>
    <div class="relative aspect-[4/5] bg-[#EEF3FB] overflow-hidden">
        @if ($avatar)
            <img src="{{ $avatar }}" alt="{{ $member->name }}" class="w-full h-full object-cover group-hover:scale-[1.04] transition-transform duration-500" loading="lazy" decoding="async">
        @else
            <img src="{{ asset('storage/profile/profile-placeholder.png') }}" alt="{{ $member->name }}" class="w-full h-full object-cover group-hover:scale-[1.04] transition-transform duration-500" loading="lazy" decoding="async">
        @endif
    </div>
    <div class="p-6">
        <h3 class="text-lg font-bold text-gray-900 leading-snug">{{ $member->name }}</h3>
        <p class="text-[10px] font-semibold uppercase tracking-[0.18em] text-[#3664AF] mt-2">{{ $role }}</p>
        @if ($divisionName)
            <p class="text-[13px] text-gray-400 mt-1">{{ $divisionName }}</p>
        @endif
        <div class="flex items-center gap-2 pt-4 mt-4 border-t border-gray-100">
            @include('components.public.partials.member-socials', ['member' => $member, 'compact' => true])
        </div>
    </div>
</div>
@else
<div {{ $attributes->merge([
    'class' => 'group bg-white border border-gray-200/70 rounded-2xl px-5 py-5 hover:border-[#3664AF]/30 hover:shadow-[0_18px_44px_-28px_rgba(54,100,175,0.4)] hover:-translate-y-0.5 transition-all duration-300',
]) }}>
    <div class="flex items-center gap-4">
        <div class="w-14 h-14 shrink-0 rounded-xl bg-[#EEF3FB] overflow-hidden flex items-center justify-center">
            @if ($avatar)
                <img src="{{ $avatar }}" alt="{{ $member->name }}" class="w-full h-full object-cover" loading="lazy" decoding="async">
            @else
                <img src="{{ asset('storage/profile/profile-placeholder.png') }}" alt="{{ $member->name }}" class="w-full h-full object-cover" loading="lazy" decoding="async">
            @endif
        </div>
        <div class="min-w-0 flex-1">
            <h3 class="text-[15px] font-bold text-gray-900 truncate">{{ $member->name }}</h3>
            <p class="text-[11px] text-gray-400 truncate mt-0.5">{{ $divisionName ?? $role }}</p>
        </div>
        <div class="flex items-center gap-1.5 shrink-0">
            @include('components.public.partials.member-socials', ['member' => $member, 'compact' => true])
        </div>
    </div>
</div>
@endif
