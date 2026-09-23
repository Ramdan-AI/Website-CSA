{{-- Hero: wordmark center + slogan rotator + strip foto bergerak --}}
<section class="relative overflow-hidden" style="background: var(--paper);">

    <div class="max-w-[1200px] mx-auto px-5 sm:px-8 pt-16 sm:pt-20 lg:pt-24">
        <div class="max-w-[1000px] mx-auto text-center">

            <h1 data-hero-title class="select-none">
                <span class="line-mask">
                    <span class="block text-[clamp(2.4rem,7.6vw,6rem)] font-extrabold leading-[0.98] tracking-[-0.045em]"
                        style="color: var(--ink);">Creative</span>
                </span>
                <span class="line-mask">
                    <span class="block text-[clamp(2.4rem,7.6vw,6rem)] font-extrabold leading-[0.98] tracking-[-0.045em]"
                        style="color: var(--ink);">Student</span>
                </span>
                <span class="line-mask">
                    <span class="block text-[clamp(2.4rem,7.6vw,6rem)] font-extrabold leading-[0.98] tracking-[-0.045em]"
                        style="color: var(--accent);">Association</span>
                </span>
            </h1>

            {{-- Slogan: kata setelah "be" berganti otomatis --}}
            <div class="mt-8 flex justify-center" data-reveal="up" data-reveal-delay="0.35">
                <div class="inline-flex items-center gap-3 sm:gap-4 rounded-full border pl-5 pr-2 py-2"
                    style="border-color: var(--line); background: var(--paper-sunken);"
                    x-data="{
                        index: 0,
                        words: ['creative', 'inovatif', 'productive'],
                        init() {
                            setInterval(() => {
                                this.index = (this.index + 1) % this.words.length;
                            }, 2200);
                        }
                    }">
                    <span class="text-[15px] sm:text-[17px] font-medium whitespace-nowrap" style="color: var(--ink);">Be</span>

                    <span class="relative inline-grid overflow-hidden h-[34px] sm:h-[38px] items-center">
                        <template x-for="(word, i) in words" :key="word">
                            <span class="col-start-1 row-start-1 text-[15px] sm:text-[17px] font-bold whitespace-nowrap transition-all duration-500"
                                :class="index === i ? 'opacity-100 translate-y-0' : 'opacity-0 -translate-y-full pointer-events-none'"
                                x-text="word"></span>
                        </template>
                    </span>

                    <span class="inline-flex items-center justify-center h-[34px] sm:h-[38px] px-4 rounded-full text-[var(--on-accent)] text-[13px] sm:text-[14px] font-semibold" style="background: var(--accent-solid);">
                        CSA
                    </span>
                </div>
            </div>

            <p class="text-[16px] sm:text-[17px] leading-[1.7] mt-7 mx-auto max-w-[600px]"
                data-reveal="up" data-reveal-delay="0.45" style="color: var(--ink-soft);">
                Komunitas teknologi mahasiswa STMIK Mardira Indonesia. Belajar pemrograman dan multimedia lewat proyek nyata.
            </p>

            @php
                $regOpen = Cache::remember('setting.registration_open', 60, fn () => \App\Models\Setting::first()?->registration_open ?? false);
            @endphp

            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-center gap-3 mt-8"
                data-reveal="up" data-reveal-delay="0.55">
                @if ($regOpen)
                    <a href="{{ route('register') }}"
                        class="inline-flex items-center justify-center px-6 min-h-[48px] text-[var(--on-accent)] text-[15px] font-medium rounded-lg transition-opacity hover:opacity-90" style="background: var(--accent-solid);">
                        Daftar sekarang
                    </a>
                @else
                    <button type="button" onclick="window.dispatchEvent(new CustomEvent('open-register-modal'))"
                        class="inline-flex items-center justify-center px-6 min-h-[48px] text-[var(--on-accent)] text-[15px] font-medium rounded-lg transition-opacity hover:opacity-90 cursor-pointer" style="background: var(--accent-solid);">
                        Daftar sekarang
                    </button>
                @endif
                <a href="#tentang"
                    class="inline-flex items-center justify-center px-6 min-h-[48px] text-[15px] font-medium rounded-lg border transition-colors"
                    style="color: var(--ink); border-color: var(--line-strong);">
                    Pelajari CSA
                </a>
            </div>
        </div>
    </div>

    {{-- Strip foto: satu baris bergerak mulus --}}
    @if ($heroPhotos->isNotEmpty())
        <div class="mt-14 sm:mt-20" data-photo-strip>
            <div data-marquee data-marquee-speed="1">
                <div class="flex gap-4 w-max" data-marquee-track>
                    @foreach ($heroPhotos->concat($heroPhotos) as $i => $photo)
                        <figure class="shrink-0 w-[260px] sm:w-[340px] h-[170px] sm:h-[220px] rounded-xl overflow-hidden border"
                            style="background: var(--paper-sunken); border-color: var(--line);">
                            <img src="{{ $imageUrl($photo, 640) }}" alt="{{ $photo->caption ?? 'Kegiatan CSA' }}"
                                width="640" height="420" class="w-full h-full object-cover"
                                @if ($i === 0) fetchpriority="high" @else loading="lazy" @endif decoding="async" draggable="false">
                        </figure>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
</section>
