@props([
    'href' => '#',
    'icon' => 'circle',
    'label' => '',
    'active' => false,
])

<a href="{{ $href }}" class="csa-nav-item {{ $active ? 'csa-nav-active' : '' }}">
    <span class="csa-nav-icon"><x-admin.icon :name="$icon" :size="17" /></span>
    <span class="min-w-0 flex-1 truncate" x-show="expanded" x-cloak>{{ $label }}</span>
</a>
