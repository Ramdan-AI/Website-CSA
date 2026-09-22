{{-- Hero: konten center + marquee foto berjalan di bawah --}}
<section class="bg-white overflow-hidden">
    <div class="max-w-[1200px] mx-auto px-5 sm:px-8 pt-14 sm:pt-20 lg:pt-24">
        <div class="max-w-[760px] mx-auto text-center">
            <h1 class="text-[clamp(2rem,4.8vw,3.25rem)] font-bold text-gray-900 leading-[1.15] tracking-[-0.02em]">
                Komunitas teknologi untuk mahasiswa yang ingin membangun
            </h1>

            <p class="text-[17px] sm:text-[18px] text-gray-600 leading-[1.7] mt-5 mx-auto max-w-[620px]">
                Creative Student Association adalah organisasi mahasiswa STMIK Mardira Indonesia. Anggotanya belajar pemrograman dan multimedia lewat proyek nyata.
            </p>

            @php
                $regOpen = Cache::remember('setting.registration_open', 60, fn () => \App\Models\Setting::first()?->registration_open ?? false);
            @endphp

            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-center gap-3 mt-8">
                @if ($regOpen)
                    <a href="{{ route('register') }}"
                        class="inline-flex items-center justify-center px-6 min-h-[48px] bg-[#1a73e8] text-white text-[15px] font-medium rounded-lg hover:bg-[#1557b0] transition-colors">
                        Daftar sekarang
                    </a>
                @else
                    <button type="button" onclick="window.dispatchEvent(new CustomEvent('open-register-modal'))"
                        class="inline-flex items-center justify-center px-6 min-h-[48px] bg-[#1a73e8] text-white text-[15px] font-medium rounded-lg hover:bg-[#1557b0] transition-colors cursor-pointer">
                        Daftar sekarang
                    </button>
                @endif
                <a href="#tentang"
                    class="inline-flex items-center justify-center px-6 min-h-[48px] bg-white border border-gray-300 text-gray-700 text-[15px] font-medium rounded-lg hover:bg-gray-50 transition-colors">
                    Pelajari CSA
                </a>
            </div>
        </div>
    </div>

    {{-- Marquee foto: bergeser dari depan (kiri ke kanan) --}}
    @if ($heroPhotos->isNotEmpty())
        <div class="mt-14 sm:mt-16 relative" data-marquee>
            <div class="flex gap-4 w-max" data-marquee-track>
                @foreach ($heroPhotos->concat($heroPhotos) as $i => $photo)
                    <div class="shrink-0 w-[260px] sm:w-[320px] h-[180px] sm:h-[220px] rounded-xl bg-gray-100 overflow-hidden">
                        <img src="{{ $imageUrl($photo, 640) }}" alt="{{ $photo->caption ?? 'Kegiatan CSA' }}"
                            width="640" height="440" class="w-full h-full object-cover"
                            @if ($i === 0) fetchpriority="high" @else loading="lazy" @endif decoding="async">
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</section>
