@props([
    'user' => null,
    'size' => 32,
])

@php
    $user = $user ?? auth()->user();
    $path = $user?->avatar ?: $user?->member?->photo;
    $src = $path ? asset('storage/' . $path) : asset('storage/profile/profile-placeholder.png');
    $dimension = (int) $size;
@endphp

<img src="{{ $src }}" alt="{{ $user?->name ?? 'Pengguna' }}"
    width="{{ $dimension }}" height="{{ $dimension }}"
    class="{{ $attributes->get('class') }} object-cover"
    {{ $attributes->except('class') }}>
