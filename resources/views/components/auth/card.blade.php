{{--
    Component: auth/card
    Engineering justification: Wraps the auth form in a consistent glassmorphic panel
    with precise backdrop-filter depth so login and register share identical shell structure.

    Props:
        $title    → heading string
        $subtitle → subtext string (optional)
--}}
@props([
    'title'    => '',
    'subtitle' => '',
])

<div class="relative w-full max-w-sm mx-auto">
    {{-- Depth panel using precise 1px border + backdrop-blur-md, no heavy shadows --}}
    <div class="rounded-lg border border-zinc-800 bg-zinc-900/70 backdrop-blur-md overflow-hidden">

        {{-- Card header strip --}}
        <div class="px-8 pt-8 pb-6 border-b border-zinc-800/60">
            <img
                src="{{ asset('storage/system/logos/csa.png') }}"
                alt="CSA STMIKMI Logo"
                class="h-10 w-auto mb-5 object-contain"
                loading="eager"
            >
            <h1 class="text-xl font-semibold text-zinc-50 tracking-tight">{{ $title }}</h1>
            @if($subtitle)
                <p class="mt-1 text-sm text-zinc-500">{{ $subtitle }}</p>
            @endif
        </div>

        {{-- Form body --}}
        <div class="px-8 py-7">
            {{ $slot }}
        </div>
    </div>
</div>
