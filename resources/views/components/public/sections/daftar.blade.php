{{-- Ajakan bergabung --}}
@php
    $regOpen = Cache::remember('setting.registration_open', 60, fn () => \App\Models\Setting::first()?->registration_open ?? false);
@endphp

<section class="scroll-mt-16" style="background: var(--paper-sunken); border-top: 1px solid var(--line);">
    <div class="max-w-[1200px] mx-auto px-5 sm:px-8 py-16 sm:py-24">

        <div class="relative overflow-hidden rounded-2xl border px-6 py-14 sm:px-14 sm:py-20 text-center"
            style="background: var(--paper); border-color: var(--line);" data-reveal="up">

            {{-- Aksen sudut, bukan gradient penuh --}}
            <span class="absolute top-0 left-1/2 -translate-x-1/2 h-[3px] w-32 rounded-full" style="background: var(--accent);"></span>

            <p class="text-[13px] font-medium" style="color: var(--accent);">Bergabung</p>

            <h2 class="text-[clamp(1.8rem,4.4vw,2.8rem)] font-bold leading-[1.2] tracking-[-0.03em] mt-4 max-w-[720px] mx-auto"
                style="color: var(--ink);">
                Be creative, be inovatif, be productive
            </h2>

            <p class="text-[16px] sm:text-[17px] leading-[1.8] mt-5 max-w-[520px] mx-auto" style="color: var(--ink-soft);">
                Bangun portofolio bersama {{ $members->count() }} anggota aktif CSA di STMIK Mardira Indonesia.
            </p>

            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-center gap-3 mt-9">
                @if ($regOpen)
                    <a href="{{ route('register') }}"
                        class="inline-flex items-center justify-center px-7 min-h-[52px] text-[var(--on-accent)] text-[15px] font-medium rounded-lg transition-opacity hover:opacity-90" style="background: var(--accent-solid);">
                        Daftar sekarang
                    </a>
                @else
                    <button type="button" onclick="window.dispatchEvent(new CustomEvent('open-register-modal'))"
                        class="inline-flex items-center justify-center px-7 min-h-[52px] text-[var(--on-accent)] text-[15px] font-medium rounded-lg transition-opacity hover:opacity-90 cursor-pointer" style="background: var(--accent-solid);">
                        Daftar sekarang
                    </button>
                @endif
                <a href="{{ route('anggota') }}"
                    class="inline-flex items-center justify-center px-7 min-h-[52px] text-[15px] font-medium rounded-lg border transition-colors"
                    style="color: var(--ink); border-color: var(--line-strong);">
                    Lihat anggota
                </a>
            </div>

            <div class="flex flex-wrap items-center justify-center gap-x-6 gap-y-2 mt-10 pt-8 border-t text-[13px]"
                style="border-color: var(--line); color: var(--ink-faint);">
                <span>Pendaftaran tiap semester</span>
                <span class="hidden sm:inline">·</span>
                <span>{{ $members->count() }} anggota aktif</span>
                <span class="hidden sm:inline">·</span>
                <span>Berdiri sejak 2017</span>
            </div>
        </div>

    </div>
</section>
