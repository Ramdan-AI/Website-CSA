{{-- TENTANG: simple two-column — understand CSA at a glance --}}
<section id="tentang" class="relative py-20 md:py-28 lg:py-32 bg-white scroll-mt-24 overflow-hidden">

    <div class="max-w-[1200px] mx-auto px-5 sm:px-8">
        {{-- Header --}}
        <div class="max-w-[760px] mb-12 sm:mb-16">
            <p class="text-[11px] sm:text-xs font-semibold text-[#3664AF] tracking-[0.18em] uppercase mb-4" data-reveal="down">Tentang CSA</p>
            <h2 class="text-[clamp(1.8rem,4.5vw,3rem)] font-bold text-gray-900 leading-[1.12] tracking-tight" data-reveal="curtain" data-reveal-delay="0.1">
                Organisasi mahasiswa IT yang lahir dari kebutuhan untuk <span class="text-[#3664AF]">mencipta</span>
            </h2>
            <p class="text-[15px] sm:text-lg text-gray-500 leading-relaxed mt-5" data-reveal="fade" data-reveal-delay="0.25">
                CSA (Creative Student Association) adalah organisasi kemahasiswaan berbasis IT di STMIK Mardira Indonesia yang berdiri sejak <span class="font-semibold text-gray-900">2017</span> — menyatukan pemrograman dan multimedia.
            </p>
        </div>

        <div class="grid lg:grid-cols-2 gap-10 lg:gap-16 items-center">
            {{-- Photo --}}
            <div class="relative" data-reveal="up">
                <div class="relative rounded-3xl overflow-hidden bg-[#eef3fb] border border-gray-100 aspect-[4/3] shadow-[0_24px_64px_-24px_rgba(54,100,175,0.25)]">
                    @php $tentangPhoto = $heroPhotos->first(); @endphp
                    @if ($tentangPhoto)
                        <img src="{{ $imageUrl($tentangPhoto, 1280) }}" alt="{{ $tentangPhoto->caption ?? 'Kegiatan CSA' }}"
                            class="w-full h-[130%] object-cover"
                            data-parallax-deep="150" loading="lazy" decoding="async">
                    @else
                        <div class="w-full h-full flex items-center justify-center">
                            <i class="fas fa-users text-6xl text-[#3664AF]/30"></i>
                        </div>
                    @endif
                </div>

                <div class="absolute -bottom-5 left-6 sm:left-8 flex items-center gap-4 bg-white border border-gray-100 rounded-2xl px-6 py-4 shadow-[0_16px_48px_-16px_rgba(0,0,0,0.15)]" data-reveal="up" data-reveal-delay="0.3">
                    <p class="text-3xl sm:text-4xl font-extrabold text-[#3664AF] tracking-tight leading-none">2017</p>
                    <div>
                        <p class="text-[10px] font-semibold uppercase tracking-[0.2em] text-gray-400">Berdiri sejak</p>
                        <p class="text-[13px] text-gray-600 mt-0.5 leading-snug">Diawali dari HMJ IF yang non-aktif</p>
                    </div>
                </div>
            </div>

            {{-- Sejarah / Visi / Misi --}}
            <div class="grid gap-5" data-reveal-stagger>
                <div class="rounded-2xl border border-gray-100 bg-[#fcfcfc] p-6 sm:p-7 hover:border-[#3664AF]/25 hover:bg-white hover:shadow-[0_12px_32px_-16px_rgba(54,100,175,0.25)] transition-all duration-300">
                    <div class="flex items-center gap-4">
                        <div class="w-11 h-11 rounded-xl bg-[#3664AF]/10 flex items-center justify-center shrink-0">
                            <i class="fas fa-seedling text-[#3664AF] text-lg"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 tracking-tight">Sejarah</h3>
                    </div>
                    <p class="text-sm sm:text-[15px] text-gray-600 leading-relaxed mt-4">
                        Berawal dari non-aktifnya UKM pemrograman HMJ IF. Di akhir <span class="font-semibold text-gray-900">2017</span>, sekumpulan mahasiswa kreatif mengaktifkan kembali organisasi yang berfokus pada pemrograman dan informatika — dan CSA lahir.
                    </p>
                </div>

                <div class="rounded-2xl border border-gray-100 bg-[#fcfcfc] p-6 sm:p-7 hover:border-[#3664AF]/25 hover:bg-white hover:shadow-[0_12px_32px_-16px_rgba(54,100,175,0.25)] transition-all duration-300">
                    <div class="flex items-center gap-4">
                        <div class="w-11 h-11 rounded-xl bg-[#3664AF]/10 flex items-center justify-center shrink-0">
                            <i class="fas fa-bullseye text-[#3664AF] text-lg"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 tracking-tight">Visi</h3>
                    </div>
                    <p class="text-sm sm:text-[15px] text-gray-600 leading-relaxed mt-4">
                        Menjadi komunitas kreatif dan inovatif yang mendorong kolaborasi lintas divisi untuk menghasilkan karya unggulan yang berdampak nyata bagi kampus, masyarakat, dan industri.
                    </p>
                </div>

                <div class="rounded-2xl border border-gray-100 bg-[#fcfcfc] p-6 sm:p-7 hover:border-[#3664AF]/25 hover:bg-white hover:shadow-[0_12px_32px_-16px_rgba(54,100,175,0.25)] transition-all duration-300">
                    <div class="flex items-center gap-4">
                        <div class="w-11 h-11 rounded-xl bg-[#3664AF]/10 flex items-center justify-center shrink-0">
                            <i class="fas fa-list-check text-[#3664AF] text-lg"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 tracking-tight">Misi</h3>
                    </div>
                    <ul class="mt-4 space-y-2.5">
                        @foreach (['Mengembangkan potensi anggota secara kreatif', 'Mendorong inovasi lewat proyek kolaboratif', 'Memperkenalkan karya anggota secara luas', 'Menciptakan lingkungan kolaboratif yang inspiratif', 'Menguatkan solidaritas antaranggota'] as $misi)
                            <li class="flex items-start gap-3 text-sm sm:text-[15px] text-gray-600 leading-relaxed">
                                <i class="fas fa-check text-[#3664AF] text-xs mt-1 shrink-0"></i>
                                <span>{{ $misi }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>