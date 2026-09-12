{{-- resources/views/pages/public/anggota.blade.php --}}
@extends('layouts.landing')

@section('content')

@php
    $baseUrl = route('anggota');
    $filtered = $q !== '' || $divisionSlug !== '';
@endphp

{{-- HERO: editorial masthead --}}
<section class="relative overflow-hidden bg-[#fcfcfc]">
    <div class="absolute top-32 left-[-140px] w-[420px] h-[420px] rounded-full bg-[radial-gradient(circle,rgba(54,100,175,0.06)_0%,transparent_70%)] pointer-events-none hidden lg:block" data-parallax="-80"></div>
    <div class="absolute bottom-0 right-[-100px] w-[320px] h-[320px] rounded-full bg-[radial-gradient(circle,rgba(54,100,175,0.05)_0%,transparent_70%)] pointer-events-none hidden lg:block" data-parallax="60"></div>

    {{-- Ghost word --}}
    <div class="absolute inset-0 flex items-center justify-center pointer-events-none select-none overflow-hidden" aria-hidden="true">
        <p class="text-[22vw] font-extrabold leading-none tracking-[-0.04em] text-[#3664AF]/[0.035] whitespace-nowrap" data-parallax="-150">ANGGOTA</p>
    </div>

    <div class="relative max-w-[1200px] mx-auto px-5 sm:px-8 w-full pt-32 sm:pt-40 pb-14 sm:pb-20">
        <div class="max-w-[820px]" data-parallax="-26">
            <p class="text-[11px] sm:text-xs font-semibold text-[#3664AF] tracking-[0.22em] uppercase mb-5 sm:mb-6" data-reveal="down">Direktori Anggota</p>
            <h1 class="text-[clamp(2.2rem,7.5vw,4.5rem)] font-bold text-gray-900 leading-[0.98] tracking-[-0.04em]" data-reveal="curtain" data-reveal-delay="0.1">
                Wajah-wajah di balik<br><span class="text-[#3664AF]">setiap karya</span>
            </h1>
            <p class="text-[15px] sm:text-lg text-gray-500 leading-relaxed mt-6 max-w-[560px]" data-reveal="fade" data-reveal-delay="0.25">
                Builder dan creator yang aktif membangun ekosistem kreatif di STMIK Mardira Indonesia.
            </p>
        </div>

        {{-- Mono stat strip --}}
        <div class="mt-14 sm:mt-20 border-t-2 border-gray-900 max-w-[640px]" data-reveal-stagger>
            <div class="flex items-baseline justify-between gap-6 py-5 sm:py-6 border-b border-gray-200">
                <p class="text-4xl sm:text-5xl font-semibold tracking-tight text-gray-900 tabular-nums">
                    {{ $totalMembers }}<span class="text-[#E8A33D]">+</span>
                </p>
                <p class="text-[11px] font-semibold uppercase tracking-[0.2em] text-gray-400 text-right">Total Anggota<br class="sm:hidden"><span class="text-gray-500">Aktif berkarya</span></p>
            </div>
            <div class="flex items-baseline justify-between gap-6 py-5 sm:py-6 border-b border-gray-200">
                <p class="text-4xl sm:text-5xl font-semibold tracking-tight text-gray-900 tabular-nums">{{ $divisions->count() }}</p>
                <p class="text-[11px] font-semibold uppercase tracking-[0.2em] text-gray-400 text-right">Divisi Aktif<br class="sm:hidden"><span class="text-gray-500">Cabang minat</span></p>
            </div>
            <div class="flex items-baseline justify-between gap-6 py-5 sm:py-6 border-b border-gray-200">
                <p class="text-4xl sm:text-5xl font-semibold tracking-tight text-gray-900 tabular-nums">2017</p>
                <p class="text-[11px] font-semibold uppercase tracking-[0.2em] text-gray-400 text-right">Berdiri Sejak<br class="sm:hidden"><span class="text-gray-500">STMIK Mardira Indonesia</span></p>
            </div>
        </div>
    </div>
</section>

{{-- DIRECTORY: sticky filter rail + editorial roster --}}
<section class="relative py-16 md:py-24 lg:py-28 bg-white overflow-hidden">
    <div class="absolute top-24 right-[-120px] w-[380px] h-[380px] rounded-full bg-[radial-gradient(circle,rgba(54,100,175,0.05)_0%,transparent_70%)] pointer-events-none hidden lg:block" data-parallax="-120"></div>

    <div class="relative max-w-[1200px] mx-auto px-5 sm:px-8">
        <div class="grid lg:grid-cols-[280px_1fr] gap-12 lg:gap-16 items-start">
            {{-- Filter rail (sticky) --}}
            <aside
                class="lg:sticky lg:top-28"
                x-data="{
                    q: {{ Illuminate\Support\Js::from($q) }},
                    divisi: {{ Illuminate\Support\Js::from($divisionSlug) }},
                    go() {
                        const params = new URLSearchParams();
                        if (this.q) params.set('q', this.q);
                        if (this.divisi) params.set('divisi', this.divisi);
                        const qs = params.toString();
                        window.location = @js($baseUrl) + (qs ? '?' + qs : '');
                    }
                }"
                x-init="
                    $watch('divisi', () => go());
                    let qTimer = null;
                    $watch('q', (val) => { clearTimeout(qTimer); qTimer = setTimeout(() => go(), 500); });
                ">
                <p class="text-[11px] sm:text-xs font-semibold text-[#3664AF] tracking-[0.22em] uppercase mb-5" data-reveal="down">Filter</p>

                <div class="relative mb-8" data-reveal="fade" data-reveal-delay="0.15">
                    <i class="fas fa-search absolute left-0 top-1/2 -translate-y-1/2 text-gray-300 text-sm pointer-events-none"></i>
                    <input type="text" x-model="q" placeholder="Cari nama..."
                        class="w-full bg-transparent border-b-2 border-gray-900 pb-3 pl-7 text-sm text-gray-900 placeholder:text-gray-300 focus:outline-none transition-colors duration-200">
                </div>

                <nav class="flex flex-col" data-reveal-stagger>
                    <button type="button" @click="divisi = ''" class="w-full text-left group">
                        <div class="flex items-baseline gap-5 py-3.5 border-b-2 transition-colors duration-300"
                            :class="divisi === '' ? 'border-gray-900' : 'border-gray-200 group-hover:border-gray-300'">
                            <span class="font-mono text-[12px] font-bold tabular-nums transition-colors duration-300"
                                :class="divisi === '' ? 'text-[#E8A33D]' : 'text-gray-200 group-hover:text-gray-300'">ALL</span>
                            <span class="text-base sm:text-lg font-bold tracking-tight transition-colors duration-300"
                                :class="divisi === '' ? 'text-gray-900' : 'text-gray-400 group-hover:text-gray-600'">Semua Divisi</span>
                        </div>
                    </button>
                    @foreach ($divisions as $division)
                        <button type="button" @click="divisi = {{ Illuminate\Support\Js::from($division->slug) }}" class="w-full text-left group">
                            <div class="flex items-baseline gap-5 py-3.5 border-b-2 transition-colors duration-300"
                                :class="divisi === {{ Illuminate\Support\Js::from($division->slug) }} ? 'border-gray-900' : 'border-gray-200 group-hover:border-gray-300'">
                                <span class="font-mono text-[12px] font-bold tabular-nums transition-colors duration-300"
                                    :class="divisi === {{ Illuminate\Support\Js::from($division->slug) }} ? 'text-[#E8A33D]' : 'text-gray-200 group-hover:text-gray-300'">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                                <span class="text-base sm:text-lg font-bold tracking-tight transition-colors duration-300"
                                    :class="divisi === {{ Illuminate\Support\Js::from($division->slug) }} ? 'text-gray-900' : 'text-gray-400 group-hover:text-gray-600'">{{ $division->name }}</span>
                            </div>
                        </button>
                    @endforeach
                </nav>

                @if ($filtered)
                    <a href="{{ $baseUrl }}" class="inline-flex items-center gap-2 mt-6 text-[12px] font-semibold text-[#3664AF] hover:underline" data-reveal="fade" data-reveal-delay="0.2">
                        <i class="fas fa-rotate-left text-[10px]"></i> Reset filter
                    </a>
                @endif
            </aside>

            {{-- Roster --}}
            <div>
                <div class="flex items-baseline justify-between gap-4 mb-6 sm:mb-8" data-reveal="fade">
                    <p class="font-mono text-[11px] uppercase tracking-[0.22em] text-gray-400">
                        Menampilkan <span class="text-gray-900 font-bold">{{ $members->total() }}</span>
                        @if ($filtered) hasil filter @else dari {{ $totalMembers }} anggota @endif
                    </p>
                    @if ($filtered)
                        <a href="{{ $baseUrl }}" class="text-[12px] font-semibold text-[#3664AF] hover:underline lg:hidden">Reset filter</a>
                    @endif
                </div>

                @if ($members->isEmpty())
                    <div class="relative border-t-2 border-gray-900 py-24 text-center" data-reveal="fade">
                        <div class="absolute inset-0 flex items-center justify-center pointer-events-none select-none overflow-hidden" aria-hidden="true">
                            <p class="text-[30vw] lg:text-[16vw] font-extrabold leading-none tracking-[-0.04em] text-[#3664AF]/[0.04]">0</p>
                        </div>
                        <div class="relative">
                            <p class="font-mono text-[10px] uppercase tracking-[0.22em] text-[#3664AF] mb-3">Tidak ditemukan</p>
                            <p class="text-xl font-bold text-gray-900 tracking-tight">Tidak ada anggota yang cocok dengan pencarianmu.</p>
                            <a href="{{ $baseUrl }}" class="inline-flex items-center gap-2 mt-6 text-[12px] font-semibold text-[#3664AF] hover:underline">
                                <i class="fas fa-rotate-left text-[10px]"></i> Reset filter
                            </a>
                        </div>
                    </div>
                @else
                    <div class="border-t-2 border-gray-900" data-reveal-stagger>
                        @foreach ($members as $member)
                            <x-public.member-row :member="$member" :index="str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT)" />
                        @endforeach
                    </div>

                    <div class="mt-10 sm:mt-14">
                        {{ $members->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

@endsection