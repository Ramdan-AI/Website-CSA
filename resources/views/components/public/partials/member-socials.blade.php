{{-- Member social icon links --}}
@props(['member', 'compact' => false])

@php
    $iconClass = ($compact ?? false)
        ? 'w-6 h-6 rounded-md bg-gray-50 flex items-center justify-center text-gray-400 hover:text-[#3664AF] hover:bg-[#3664AF]/10 transition-colors duration-200'
        : 'w-8 h-8 rounded-lg bg-gray-50 flex items-center justify-center text-gray-400 hover:text-[#3664AF] hover:bg-[#3664AF]/10 transition-colors duration-200';
    $iconSize = ($compact ?? false) ? 'text-[9px]' : 'text-[11px]';
@endphp

@if ($member->instagram)
    <a href="https://instagram.com/{{ $member->instagram }}" target="_blank" rel="noopener noreferrer" aria-label="Instagram {{ $member->name }}" class="{{ $iconClass }}">
        <i class="fab fa-instagram {{ $iconSize }}"></i>
    </a>
@endif
@if ($member->github)
    <a href="https://github.com/{{ $member->github }}" target="_blank" rel="noopener noreferrer" aria-label="GitHub {{ $member->name }}" class="{{ $iconClass }}">
        <i class="fab fa-github {{ $iconSize }}"></i>
    </a>
@endif
@if ($member->linkedin)
    <a href="{{ $member->linkedin }}" target="_blank" rel="noopener noreferrer" aria-label="LinkedIn {{ $member->name }}" class="{{ $iconClass }}">
        <i class="fab fa-linkedin-in {{ $iconSize }}"></i>
    </a>
@endif
