{{-- Tentang CSA --}}
<section id="tentang" class="bg-[#f8f9fa] border-y border-gray-200 scroll-mt-16">
    <div class="max-w-[1200px] mx-auto px-5 sm:px-8 py-16 sm:py-20">

        <div class="grid lg:grid-cols-2 gap-10 lg:gap-16 items-center">

            {{-- Foto --}}
            @php $tentangPhoto = $heroPhotos->first(); @endphp
            <div class="order-2 lg:order-1">
                <div class="rounded-xl overflow-hidden bg-gray-200 aspect-[4/3]">
                    @if ($tentangPhoto)
                        <img src="{{ $imageUrl($tentangPhoto, 1280) }}" alt="{{ $tentangPhoto->caption ?? 'Kegiatan CSA' }}"
                            width="1280" height="960" class="w-full h-full object-cover" loading="lazy" decoding="async">
                    @endif
                </div>
            </div>

            {{-- Teks --}}
            <div class="order-1 lg:order-2">
                <h2 class="text-[clamp(1.6rem,3.6vw,2.25rem)] font-bold text-gray-900 leading-[1.25]">
                    Organisasi mahasiswa IT di STMIK Mardira Indonesia
                </h2>
                <p class="text-[16px] text-gray-600 leading-[1.75] mt-5">
                    CSA menyatukan mahasiswa yang tertarik pada pemrograman dan multimedia. Anggotanya belajar bersama,
                    mengerjakan proyek, dan mempersiapkan diri untuk dunia kerja.
                </p>

                <dl class="mt-8 space-y-5">
                    <div class="flex gap-4">
                        <dt class="w-28 shrink-0 text-[14px] font-medium text-gray-500 pt-0.5">Berdiri sejak</dt>
                        <dd class="text-[15px] text-gray-900">2017</dd>
                    </div>
                    <div class="flex gap-4">
                        <dt class="w-28 shrink-0 text-[14px] font-medium text-gray-500 pt-0.5">Divisi aktif</dt>
                        <dd class="text-[15px] text-gray-900">{{ $statDivisi }} divisi</dd>
                    </div>
                    <div class="flex gap-4">
                        <dt class="w-28 shrink-0 text-[14px] font-medium text-gray-500 pt-0.5">Visi</dt>
                        <dd class="text-[15px] text-gray-600 leading-[1.7]">
                            Menjadi komunitas kreatif dan inovatif yang mendorong kolaborasi lintas divisi,
                            sehingga menghasilkan karya berdampak bagi kampus, masyarakat, dan industri.
                        </dd>
                    </div>
                </dl>

                <a href="#cerita" class="inline-flex items-center gap-2 mt-8 text-[15px] font-medium text-[#1a73e8] hover:underline">
                    Lihat perjalanan CSA <i class="fas fa-arrow-right text-[12px]"></i>
                </a>
            </div>

        </div>

    </div>
</section>
