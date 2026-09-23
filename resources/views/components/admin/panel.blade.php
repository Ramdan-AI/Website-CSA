@props([
    'title' => null,
    'subtitle' => null,
    'moreRoute' => null,
    'moreLabel' => 'Lihat selengkapnya',
])

{{--
    Kartu bagian bergaya editorial: header ringkas (judul + tautan "lihat
    selengkapnya"), lalu isi apa pun yang di-slot. Menghindari ikon bertint
    dan badge berlebihan agar terasa tenang seperti layout Google.
--}}
<section {{ $attributes->merge(['class' => 'csa-panel']) }}>
    @if ($title)
        <header class="flex items-baseline justify-between gap-4 px-5 pt-5 pb-3">
            <div class="min-w-0">
                <h2 class="text-[15px] font-semibold text-[var(--text-primary)] tracking-[-0.01em]">{{ $title }}</h2>
                @if ($subtitle)
                    <p class="text-[12px] text-[var(--text-muted)] mt-0.5">{{ $subtitle }}</p>
                @endif
            </div>
            @if ($moreRoute)
                <a href="{{ $moreRoute }}"
                    class="shrink-0 inline-flex items-center gap-1 text-[12px] font-medium text-[var(--text-secondary)] hover:text-[var(--accent-bright)] transition-colors">
                    {{ $moreLabel }}
                    <x-admin.icon name="arrow-right" :size="13" />
                </a>
            @endif
        </header>
    @endif

    <div class="px-5 pb-5">
        {{ $slot }}
    </div>
</section>
