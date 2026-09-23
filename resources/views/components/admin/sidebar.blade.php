@inject('nav', 'App\Support\AdminNavigation')

@php
    $groups = $nav->groups();
    $settings = $nav->settings();
    $isActive = fn (string $pattern) => request()->routeIs($pattern);
@endphp

<div x-data="{ mobileOpen: false }"
     @sidebar-mobile-toggle.window="mobileOpen = !mobileOpen; document.body.classList.toggle('overflow-hidden', mobileOpen)">
    <div x-show="mobileOpen" x-transition.opacity.duration.200
        class="fixed inset-0 z-40 bg-black/60 backdrop-blur-sm lg:hidden"
        @click="mobileOpen = false; document.body.classList.remove('overflow-hidden')"></div>

    <aside x-data="{
            expanded: JSON.parse(localStorage.getItem('sidebar-expanded') ?? 'true'),
            animated: false,
            toggle() {
                this.animated = true;
                this.expanded = !this.expanded;
            }
        }"
        @sidebar-mobile-close.window="mobileOpen = false; document.body.classList.remove('overflow-hidden')"
        :class="[
            mobileOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0',
            expanded ? 'lg:w-[240px]' : 'lg:w-[72px]',
            animated ? 'transition-all duration-300' : ''
        ]"
        x-effect="localStorage.setItem('sidebar-expanded', expanded); $dispatch('sidebar-toggled', { expanded })"
        class="csa-sidebar fixed inset-y-0 left-0 z-50 flex flex-col">

        <div class="flex items-center gap-2.5 px-4 h-14 shrink-0">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5 min-w-0 flex-1">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0"
                    style="background: var(--sidebar-logo-surface); border: 1px solid var(--sidebar-border);">
                    @php
                        $appSetting = \App\Models\Setting::first();
                        $logoPath = ($appSetting && $appSetting->logo) ? asset('storage/' . $appSetting->logo) : asset('storage/system/logos/csa-logo.png');
                    @endphp
                    <img src="{{ $logoPath }}" alt="CSA" class="w-5 h-5 object-contain">
                </div>
                <div class="overflow-hidden whitespace-nowrap" x-show="expanded" x-cloak>
                    <p class="text-[13px] font-bold tracking-tight leading-none" style="color: var(--sidebar-text-strong);">CSA</p>
                    <p class="text-[10px] mt-0.5" style="color: var(--sidebar-group);">Administration</p>
                </div>
            </a>
            <button @click="toggle()"
                class="csa-sidebar-toggle hidden lg:flex w-7 h-7 rounded-md items-center justify-center transition-colors shrink-0"
                aria-label="Toggle sidebar">
                <x-admin.icon name="panel-left" :size="14" />
            </button>
        </div>

        <nav id="csa-sidebar-nav" class="flex-1 overflow-y-auto no-scrollbar px-2 py-3">
            @foreach ($groups as $group)
                <div class="mb-3">
                    <p class="csa-sidebar-group" x-show="expanded" x-cloak>{{ $group['label'] }}</p>
                    <div class="flex flex-col gap-0.5">
                        @foreach ($group['items'] as $item)
                            <x-admin.sidebar-item
                                :href="route($item['route'])"
                                :icon="$item['icon']"
                                :label="$item['label']"
                                :active="$isActive($item['pattern'])" />
                        @endforeach
                    </div>
                </div>
            @endforeach

            @if (count($settings))
                <div class="mb-3">
                    <p class="csa-sidebar-group" x-show="expanded" x-cloak>Pengaturan</p>
                    <div class="flex flex-col gap-0.5">
                        @foreach ($settings as $item)
                            <x-admin.sidebar-item
                                :href="route($item['route'])"
                                :icon="$item['icon']"
                                :label="$item['label']"
                                :active="$isActive($item['pattern'])" />
                        @endforeach
                    </div>
                </div>
            @endif
        </nav>
    </aside>
</div>

<style>
    [x-cloak] { display: none !important; }
</style>

<script>
    (() => {
        const nav = document.getElementById('csa-sidebar-nav');
        if (!nav) return;
        const pos = sessionStorage.getItem('csa_sidebar_scroll');
        if (pos !== null) nav.scrollTop = parseInt(pos, 10);
        nav.addEventListener('scroll', () => sessionStorage.setItem('csa_sidebar_scroll', nav.scrollTop));
    })();
</script>
