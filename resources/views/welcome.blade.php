@extends('layouts.app')

@section('title', 'CSA STMIKMI — Creative Student Association')
@section('meta_description', 'Platform resmi Creative Student Association STMIKMI. Wadah kolaborasi, inovasi, dan kreativitas mahasiswa teknologi.')

@section('content')

{{-- ══════════════════════════════════════════════════════════
     HERO SECTION
     Layout rationale: Full-viewport hero with dot-grid texture
     establishes clear visual hierarchy. Content is vertically
     centered with intentional breathing room (py-28/py-36) so
     the headline reads first, then CTA, then data strip.
     ══════════════════════════════════════════════════════════ --}}
<section class="relative min-h-[calc(100svh-3.5rem)] flex flex-col items-center justify-center overflow-hidden px-6 py-28 sm:py-36">

    {{-- Dot-grid texture: structural depth, not decorative noise --}}
    <div class="absolute inset-0 bg-dot-grid opacity-40 pointer-events-none" aria-hidden="true"></div>

    {{-- Subtle top-left luminance: one restrained light source, not a neon blob --}}
    <div
        class="absolute -top-32 -left-32 w-96 h-96 rounded-full pointer-events-none"
        style="background: radial-gradient(circle at center, rgba(37,99,235,0.12) 0%, transparent 70%);"
        aria-hidden="true"
    ></div>

    <div class="relative z-10 w-full max-w-4xl mx-auto flex flex-col items-center text-center">

        {{-- Eyebrow badge — semantic status indicator --}}
        <div class="mb-8 inline-flex items-center gap-2 px-3 py-1 rounded border border-zinc-800 bg-zinc-900/80 backdrop-blur-sm text-xs text-zinc-500 font-mono">
            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500 animate-pulse shrink-0"></span>
            Open Recruitment 2025 — CSA STMIKMI
        </div>

        {{-- Headline --}}
        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-semibold tracking-tight text-zinc-50 leading-tight">
            Komunitas Mahasiswa<br>
            <span class="text-zinc-400 font-light">yang Berkarya Nyata</span>
        </h1>

        <p class="mt-6 max-w-xl text-base text-zinc-400 leading-relaxed">
            Creative Student Association adalah ruang kolaborasi, inovasi, dan pengembangan teknologi
            untuk mahasiswa STMIKMI yang ingin menciptakan dampak nyata.
        </p>

        {{-- CTA cluster --}}
        <div class="mt-10 flex flex-wrap items-center justify-center gap-3">
            @guest
                @if (Route::has('register'))
                    <x-ui.button
                        tag="a"
                        href="{{ route('register') }}"
                        variant="primary"
                        size="md"
                        class="!px-7"
                    >
                        Bergabung Sekarang
                        <svg class="w-4 h-4" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.75">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 8h10M9 4l4 4-4 4"/>
                        </svg>
                    </x-ui.button>
                @endif
                @if (Route::has('login'))
                    <a href="{{ route('login') }}"
                       class="h-10 px-6 inline-flex items-center text-sm font-medium text-zinc-400 hover:text-zinc-100 bg-zinc-900 border border-zinc-800 hover:border-zinc-700 rounded transition-colors duration-100 focus:outline-none focus-visible:ring-2 focus-visible:ring-zinc-500">
                        Sudah punya akun
                    </a>
                @endif
            @else
                <a href="{{ route('home') }}"
                   class="h-10 px-7 inline-flex items-center gap-2 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-500 rounded transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-400">
                    Masuk ke Dashboard
                    <svg class="w-4 h-4" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.75">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8h10M9 4l4 4-4 4"/>
                    </svg>
                </a>
            @endguest
        </div>

        {{-- Stats strip — JetBrains Mono, functional data --}}
        <div class="mt-16 flex flex-wrap justify-center gap-x-10 gap-y-4 font-mono text-xs">
            <div class="flex flex-col items-center gap-1">
                <span class="text-2xl font-semibold text-zinc-100">120+</span>
                <span class="text-zinc-600 uppercase tracking-widest text-[10px]">Anggota Aktif</span>
            </div>
            <div class="w-px bg-zinc-800 hidden sm:block self-stretch"></div>
            <div class="flex flex-col items-center gap-1">
                <span class="text-2xl font-semibold text-zinc-100">8</span>
                <span class="text-zinc-600 uppercase tracking-widest text-[10px]">Divisi</span>
            </div>
            <div class="w-px bg-zinc-800 hidden sm:block self-stretch"></div>
            <div class="flex flex-col items-center gap-1">
                <span class="text-2xl font-semibold text-zinc-100">40+</span>
                <span class="text-zinc-600 uppercase tracking-widest text-[10px]">Proyek Selesai</span>
            </div>
            <div class="w-px bg-zinc-800 hidden sm:block self-stretch"></div>
            <div class="flex flex-col items-center gap-1">
                <span class="text-2xl font-semibold text-emerald-400">Aktif</span>
                <span class="text-zinc-600 uppercase tracking-widest text-[10px]">Status Organisasi</span>
            </div>
        </div>
    </div>

    {{-- Scroll hint --}}
    <div class="absolute bottom-8 left-1/2 -translate-x-1/2 flex flex-col items-center gap-1 text-zinc-700 text-xs font-mono animate-bounce" aria-hidden="true">
        <svg class="w-4 h-4" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M8 3v10M4 9l4 4 4-4"/>
        </svg>
    </div>
</section>


{{-- ══════════════════════════════════════════════════════════
     DIVISION CARDS SECTION
     Layout rationale: 3-column card grid uses 1px border only
     for depth — absolutely no box-shadow or gradient overlays.
     Hover state is a single border-color shift for snappy response.
     ══════════════════════════════════════════════════════════ --}}
<section class="border-t border-zinc-900 py-24 px-6">
    <div class="max-w-5xl mx-auto">

        {{-- Section label --}}
        <div class="flex items-center gap-4 mb-12">
            <div>
                <p class="font-mono text-[10px] uppercase tracking-widest text-zinc-600 mb-1">Struktur Organisasi</p>
                <h2 class="text-2xl font-semibold tracking-tight text-zinc-100">Divisi & Unit Kerja</h2>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">

            @php
            $divisions = [
                ['code' => 'DIV.01', 'name' => 'Riset & Pengembangan', 'desc' => 'Eksplorasi teknologi terapan, prototipe solusi digital, dan inkubasi ide inovatif lintas platform.', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9.75 3.104v5.714a2.25 2.25 0 0 1-.659 1.591L5 14.5M9.75 3.104c-.251.023-.501.05-.75.082m.75-.082a24.301 24.301 0 0 1 4.5 0m0 0v5.714c0 .597.237 1.17.659 1.591L19.8 15.3M14.25 3.104c.251.023.501.05.75.082M19.8 15.3l-1.57.393A9.065 9.065 0 0 1 12 15a9.065 9.065 0 0 0-6.23-.693L5 14.5m14.8.8 1.402 1.402c1.232 1.232.65 3.318-1.067 3.611A48.309 48.309 0 0 1 12 21c-2.773 0-5.491-.235-8.135-.687-1.718-.293-2.3-2.379-1.067-3.61L5 14.5"/>'],
                ['code' => 'DIV.02', 'name' => 'Desain & Kreasi', 'desc' => 'Produksi visual, desain UI/UX, identitas brand, dan aset digital untuk kebutuhan internal maupun publik.', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9.53 16.122a3 3 0 0 0-5.78 1.128 2.25 2.25 0 0 1-2.4 2.245 4.5 4.5 0 0 0 8.4-2.245c0-.399-.078-.78-.22-1.128Zm0 0a15.998 15.998 0 0 0 3.388-1.62m-5.043-.025a15.994 15.994 0 0 1 1.622-3.395m3.42 3.42a15.995 15.995 0 0 0 4.764-4.648l3.876-5.814a1.151 1.151 0 0 0-1.597-1.597L14.146 6.32a15.996 15.996 0 0 0-4.649 4.763m3.42 3.42a6.776 6.776 0 0 0-3.42-3.42"/>'],
                ['code' => 'DIV.03', 'name' => 'Pengembangan Web & App', 'desc' => 'Membangun produk digital — dari web app Laravel hingga aplikasi mobile untuk ekosistem kampus.', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M17.25 6.75 22.5 12l-5.25 5.25m-10.5 0L1.5 12l5.25-5.25m7.5-3-4.5 16.5"/>'],
                ['code' => 'DIV.04', 'name' => 'Konten & Media', 'desc' => 'Strategi konten, media sosial, dokumentasi event, dan storytelling digital organisasi.', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="m15.75 10.5 4.72-4.72a.75.75 0 0 1 1.28.53v11.38a.75.75 0 0 1-1.28.53l-4.72-4.72M4.5 18.75h9a2.25 2.25 0 0 0 2.25-2.25v-9a2.25 2.25 0 0 0-2.25-2.25h-9A2.25 2.25 0 0 0 2.25 7.5v9a2.25 2.25 0 0 0 2.25 2.25Z"/>'],
                ['code' => 'DIV.05', 'name' => 'Event & Program', 'desc' => 'Koordinasi kegiatan, workshop, kompetisi, dan program pengembangan anggota secara berkala.', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 9v7.5"/>'],
                ['code' => 'DIV.06', 'name' => 'Hubungan Eksternal', 'desc' => 'Membangun kemitraan strategis, relasi industri, dan kolaborasi antar komunitas teknologi.', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 0 1 1.242 7.244l-4.5 4.5a4.5 4.5 0 0 1-6.364-6.364l1.757-1.757m13.35-.622 1.757-1.757a4.5 4.5 0 0 0-6.364-6.364l-4.5 4.5a4.5 4.5 0 0 0 1.242 7.244"/>'],
            ];
            @endphp

            @foreach($divisions as $div)
            <article class="group bg-zinc-950 border border-zinc-800 rounded-lg p-6 flex flex-col gap-4 hover:border-zinc-700 hover:bg-zinc-900/50 transition-colors duration-150 cursor-default">
                <div class="flex items-start justify-between">
                    <div class="h-9 w-9 flex items-center justify-center bg-zinc-900 border border-zinc-800 rounded group-hover:border-blue-900 group-hover:bg-blue-950/30 transition-colors">
                        <svg class="w-4 h-4 text-zinc-500 group-hover:text-blue-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            {!! $div['icon'] !!}
                        </svg>
                    </div>
                    <span class="font-mono text-[10px] text-zinc-700">{{ $div['code'] }}</span>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-zinc-100 mb-2">{{ $div['name'] }}</h3>
                    <p class="text-xs text-zinc-500 leading-relaxed">{{ $div['desc'] }}</p>
                </div>
            </article>
            @endforeach
        </div>
    </div>
</section>


{{-- ══════════════════════════════════════════════════
     CTA BANNER — bottom of page
     Simple, high-contrast call to action strip.
     ══════════════════════════════════════════════════ --}}
@guest
<section class="border-t border-zinc-900 py-20 px-6 relative overflow-hidden">
    <div
        class="absolute inset-0 pointer-events-none"
        style="background: radial-gradient(ellipse 60% 50% at 50% 100%, rgba(37,99,235,0.08) 0%, transparent 100%);"
        aria-hidden="true"
    ></div>
    <div class="relative z-10 max-w-2xl mx-auto text-center">
        <h2 class="text-2xl font-semibold tracking-tight text-zinc-100">Siap bergabung?</h2>
        <p class="mt-3 text-sm text-zinc-500">Daftarkan dirimu sekarang dan jadilah bagian dari ekosistem kreativitas CSA STMIKMI.</p>
        <div class="mt-8 flex flex-wrap justify-center gap-3">
            @if (Route::has('register'))
                <a href="{{ route('register') }}"
                   class="h-10 px-7 inline-flex items-center gap-2 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-500 rounded transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-400">
                    Daftar Sekarang
                </a>
            @endif
            @if (Route::has('login'))
                <a href="{{ route('login') }}"
                   class="h-10 px-6 inline-flex items-center text-sm font-medium text-zinc-400 hover:text-zinc-100 bg-zinc-900 border border-zinc-800 hover:border-zinc-700 rounded transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-zinc-500">
                    Sudah punya akun
                </a>
            @endif
        </div>
    </div>
</section>
@endguest

@endsection
