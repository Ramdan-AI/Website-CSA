{{-- HERO --}}
<section data-hero class="relative overflow-hidden bg-[#fcfcfc]">
    <div class="absolute top-32 left-[-140px] w-[420px] h-[420px] rounded-full bg-[radial-gradient(circle,rgba(54,100,175,0.06)_0%,transparent_70%)] pointer-events-none hidden lg:block" data-parallax="-80"></div>
    <div class="absolute bottom-0 right-[-100px] w-[320px] h-[320px] rounded-full bg-[radial-gradient(circle,rgba(54,100,175,0.05)_0%,transparent_70%)] pointer-events-none hidden lg:block" data-parallax="60"></div>

    <div class="relative max-w-[1200px] mx-auto px-5 sm:px-8 w-full pt-24 pb-16 sm:pt-28 lg:min-h-screen lg:flex lg:items-center lg:py-40">
        <div class="grid lg:grid-cols-2 gap-10 lg:gap-16 items-center w-full min-w-0">

            <div class="max-w-[540px] w-full min-w-0" data-parallax="18">
                <p class="text-[11px] sm:text-xs font-semibold text-[#3664AF] tracking-[0.15em] uppercase mb-4 lg:mb-8" data-reveal="down" data-reveal-delay="0.1">
                    Creative Student Association
                </p>

                <h1 class="text-[clamp(1.85rem,7.5vw,4rem)] font-bold text-gray-900 leading-[1.15] sm:leading-[1.12] lg:leading-[1.08] tracking-[-0.03em] mb-5 lg:mb-8 break-words" data-reveal="blurUp" data-reveal-delay="0.25">
                    Belajar Lebih Dari Sekadar Perkuliahan
                </h1>

                <p class="text-[15px] sm:text-lg text-gray-500 leading-relaxed mb-8 lg:mb-10 max-w-[440px]" data-reveal="fade" data-reveal-delay="0.45">
                    CSA adalah komunitas mahasiswa STMIK Mardira Indonesia yang menjadi tempat untuk belajar dan membangun karya bersama.
                </p>

                @php
                    $regOpen = Cache::remember('setting.registration_open', 60, fn () => \App\Models\Setting::first()?->registration_open ?? false);
                @endphp

                <div class="flex flex-col sm:flex-row sm:items-center gap-3 w-full" data-reveal="up" data-reveal-delay="0.6">
                    @if ($regOpen)
                        <a href="{{ route('register') }}"
                            class="inline-flex items-center justify-center gap-2 px-7 min-h-[52px] bg-gray-900 text-white text-sm sm:text-[15px] font-semibold rounded-xl hover:bg-gray-800 active:scale-[0.98] transition-all duration-200 w-full sm:w-auto text-center">
                            Daftar sekarang
                        </a>
                    @else
                        <button type="button" onclick="window.dispatchEvent(new CustomEvent('open-register-modal'))"
                            class="inline-flex items-center justify-center gap-2 px-7 min-h-[52px] bg-gray-900 text-white text-sm sm:text-[15px] font-semibold rounded-xl hover:bg-gray-800 active:scale-[0.98] transition-all duration-200 w-full sm:w-auto text-center cursor-pointer">
                            Daftar sekarang
                        </button>
                    @endif
                    <a href="#tentang"
                        class="inline-flex items-center justify-center gap-2 px-7 min-h-[52px] sm:min-h-0 sm:py-2 bg-white/70 sm:bg-transparent border border-gray-200 sm:border-0 rounded-xl sm:rounded-none text-sm sm:text-[15px] font-medium text-gray-500 hover:text-gray-900 transition-colors duration-200 w-full sm:w-auto text-center">
                        Pelajari lebih lanjut &rarr;
                    </a>
                </div>
            </div>

            {{-- Hero photos: bento mosaic, max 6 (lazy-loaded for performance) --}}
            <div class="relative w-full min-w-0 max-w-full overflow-hidden" data-hero-art>
                <div class="lg:hidden -mx-5 px-5 flex gap-3 overflow-x-auto snap-x snap-mandatory no-scrollbar py-2">
                    @foreach($heroPhotos->take(5) as $photo)
                        <div data-hero-tile class="snap-center shrink-0 w-[60vw] max-w-[220px] aspect-[3/4] rounded-2xl bg-gray-50 border border-gray-100 overflow-hidden shadow-sm">
                            <img src="{{ $imageUrl($photo, 480) }}" alt="{{ $photo->caption ?? 'Foto kegiatan CSA' }}"
                                class="w-full h-full object-cover" loading="lazy" decoding="async">
                        </div>
                    @endforeach
                </div>

                <div class="hidden lg:grid grid-cols-12 grid-rows-[repeat(3,minmax(0,1fr))] gap-3 h-[430px] xl:h-[470px]">
                    @foreach($heroPhotos->take(6) as $i => $photo)
                        <div data-hero-tile class="{{ match ($i) {
                            0 => 'col-span-7 row-span-2',
                            1 => 'col-span-5 row-span-1',
                            2 => 'col-span-5 row-span-1',
                            3 => 'col-span-4 row-span-1',
                            4 => 'col-span-4 row-span-1',
                            5 => 'col-span-4 row-span-1',
                            default => 'col-span-4 row-span-1',
                        } }} rounded-2xl bg-gray-50 border border-gray-100 overflow-hidden group">
                            @if ($i === 0)
                                <img src="{{ $imageUrl($photo, 960) }}" alt="{{ $photo->caption ?? 'Foto kegiatan CSA' }}"
                                    class="w-full h-full object-cover group-hover:scale-[1.03] transition-transform duration-500" fetchpriority="high" decoding="async">
                            @else
                                <img src="{{ $imageUrl($photo, 960) }}" alt="{{ $photo->caption ?? 'Foto kegiatan CSA' }}"
                                    class="w-full h-full object-cover group-hover:scale-[1.03] transition-transform duration-500" loading="lazy" decoding="async">
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
