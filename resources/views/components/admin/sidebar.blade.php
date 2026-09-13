@inject('nav', 'App\Support\AdminNavigation')

@php
    $groups = $nav->groups();
    $settings = $nav->settings();
    $isActive = fn (string $pattern) => request()->routeIs($pattern);
@endphp

<aside
    x-data="{
        expanded: JSON.parse(localStorage.getItem('sidebar-expanded') ?? 'true'),
        hoveredItem: null,
        toggle() {
            this.expanded = !this.expanded;
            localStorage.setItem('sidebar-expanded', this.expanded);
            this.$dispatch('sidebar-toggled', { expanded: this.expanded });
            document.documentElement.classList.toggle('sidebar-collapsed', !this.expanded);
        }
    }"
    class="csa-sidebar-root fixed left-4 top-4 h-[calc(100vh-32px)] z-50 flex flex-col gap-3"
    :class="expanded ? 'w-[264px]' : 'w-[80px]'"
    x-effect="localStorage.setItem('sidebar-expanded', expanded)">

    <button @click="toggle()"
        class="absolute top-1/2 -translate-y-1/2 -right-3 w-6 h-12 rounded-full bg-white border border-gray-200 flex items-center justify-center cursor-pointer z-50 shadow-sm hover:bg-gray-50 transition-colors duration-200 focus:outline-none group/toggle">
        <span class="w-1 h-3 rounded-full bg-gray-300 group-hover/toggle:bg-gray-500 transition-colors duration-200"></span>
    </button>

    <div class="shrink-0 rounded-2xl bg-white border border-gray-200 shadow-sm h-[72px] flex items-center overflow-hidden">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3.5 px-4 w-full">
            <div class="w-10 h-10 flex items-center justify-center shrink-0 rounded-xl bg-white/70 shadow-sm">
                @php
                    $appSetting = \App\Models\Setting::first();
                    $logoPath = ($appSetting && $appSetting->logo) ? asset('storage/' . $appSetting->logo) : asset('storage/system/logos/csa-logo.png');
                @endphp
                <img src="{{ $logoPath }}" alt="CSA" class="w-7 h-7 object-contain">
            </div>
            <div class="csa-sidebar-hideable overflow-hidden whitespace-nowrap"
                x-show="expanded" x-transition:enter="transition delay-75" x-transition:leave="transition duration-150">
                <span class="font-extrabold text-[15px] text-gray-900 tracking-tight block leading-none">CSA</span>
                <span class="text-[10px] text-gray-400 font-medium">Panel Admin</span>
            </div>
        </a>
    </div>

    <nav id="csa-sidebar-nav"
        onscroll="sessionStorage.setItem('csa_sidebar_scroll', this.scrollTop)"
        class="flex-1 overflow-y-auto no-scrollbar rounded-2xl bg-white border border-gray-200 shadow-sm p-3"
        data-lenis-prevent>

        <div class="flex flex-col gap-5 py-1">
            @foreach ($groups as $group)
                <div>
                    <div class="csa-sidebar-nav-header px-3 h-5 overflow-hidden mb-1.5"
                        x-show="expanded" x-transition:enter="transition delay-75">
                        <span class="text-[10px] font-bold uppercase tracking-[0.15em] text-gray-400 select-none">
                            {{ $group['label'] }}
                        </span>
                    </div>

                    @foreach ($group['items'] as $item)
                        <x-admin.sidebar-item
                            :href="route($item['route'])"
                            :icon="$item['icon']"
                            :label="$item['label']"
                            :active="$isActive($item['pattern'])"
                            :color="$group['color']"
                            hoverId="{{ $group['id'] }}-{{ $loop->index }}" />
                    @endforeach
                </div>
            @endforeach
        </div>
    </nav>

    @if (count($settings) > 0)
        <div class="shrink-0 rounded-2xl bg-white border border-gray-200 shadow-sm flex flex-col items-stretch overflow-hidden pt-1 pb-1.5">
            @foreach ($settings as $item)
                <x-admin.sidebar-item
                    :href="route($item['route'])"
                    :icon="$item['icon']"
                    :label="$item['label']"
                    :active="$isActive($item['pattern'])"
                    color="gray"
                    hoverId="settings-{{ $loop->index }}" />
            @endforeach
        </div>
    @endif
</aside>

<script>
    (() => {
        const nav = document.getElementById('csa-sidebar-nav');
        if (!nav) return;
        const pos = sessionStorage.getItem('csa_sidebar_scroll');
        if (pos !== null) nav.scrollTop = parseInt(pos, 10);
    })();
</script>

<style>
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    .no-scrollbar::-webkit-scrollbar { display: none; }

    .csa-sidebar-root {
        transition: width 0.3s ease;
    }
    .csa-sidebar-hideable {
        transition: opacity 0.2s ease, transform 0.2s ease;
    }
</style>
