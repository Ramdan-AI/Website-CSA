{{-- Tautan sosial anggota: Instagram & GitHub (jika tersedia) --}}
@props(['member'])

@php
    $linkClass = 'w-10 h-10 rounded-xl bg-[#f1f3f4] flex items-center justify-center text-gray-500 hover:text-[#1a73e8] hover:bg-[#e8f0fe] transition-colors duration-200';
@endphp

@if ($member->instagram || $member->github)
    <div class="flex items-center justify-center gap-2.5">
        @if ($member->instagram)
            <a href="https://instagram.com/{{ $member->instagram }}" target="_blank" rel="noopener noreferrer"
                aria-label="Instagram {{ $member->name }}" class="{{ $linkClass }}">
                <i class="fab fa-instagram text-[15px]"></i>
            </a>
        @endif

        @if ($member->github)
            <a href="https://github.com/{{ $member->github }}" target="_blank" rel="noopener noreferrer"
                aria-label="GitHub {{ $member->name }}" class="{{ $linkClass }}">
                <i class="fab fa-github text-[15px]"></i>
            </a>
        @endif
    </div>
@endif
