@props([
    'title'    => '',
    'subtitle' => '',
])

<div class="relative w-full max-w-md mx-auto">
    <div class="bg-white rounded-2xl border border-gray-200 shadow-xl shadow-gray-200/60 overflow-hidden">

        {{-- Card header strip --}}
        <div class="px-8 pt-8 pb-6 border-b border-gray-100">
            <img
                src="{{ asset('storage/system/logos/csa.png') }}"
                alt="CSA STMIKMI Logo"
                class="h-9 w-auto mb-5 object-contain"
                loading="eager"
            >
            <h1 class="text-xl font-semibold text-gray-900 tracking-tight">{{ $title }}</h1>
            @if($subtitle)
                <p class="mt-1 text-sm text-gray-500">{{ $subtitle }}</p>
            @endif
        </div>

        {{-- Form body --}}
        <div class="px-8 py-7">
            {{ $slot }}
        </div>
    </div>
</div>
