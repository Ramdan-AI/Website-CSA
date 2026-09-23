{{-- Tentang CSA --}}
@php
    $years = range(2015, 2026);
    $founded = 2017;
@endphp

<section id="tentang" class="scroll-mt-16" style="background: var(--paper-sunken); border-top: 1px solid var(--line); border-bottom: 1px solid var(--line);">
    <div class="max-w-[1200px] mx-auto px-5 sm:px-8 py-16 sm:py-24">

        <div class="grid lg:grid-cols-12 gap-12 lg:gap-16 items-start">

            {{-- Kolom kiri: teks --}}
            <div class="lg:col-span-6">
                <p class="text-[13px] font-medium mb-3" style="color: var(--accent);">Tentang CSA</p>
                <h2 class="text-[clamp(1.7rem,3.8vw,2.4rem)] font-bold leading-[1.22] tracking-[-0.025em]" style="color: var(--ink);">
                    Organisasi mahasiswa IT di STMIK Mardira Indonesia
                </h2>
                <p class="text-[16px] leading-[1.8] mt-5" style="color: var(--ink-soft);">
                    CSA menyatukan mahasiswa yang tertarik pada pemrograman dan multimedia. Anggotanya belajar bersama,
                    mengerjakan proyek, dan mempersiapkan diri untuk dunia kerja.
                </p>

                {{-- Kartu divisi: logo asli, tanpa kotak ikon --}}
                <div class="grid grid-cols-2 gap-4 mt-9">
                    @foreach ($divisions->take(2) as $division)
                        <a href="#divisi" class="group flex items-center gap-3.5 rounded-xl border p-4 transition-all duration-300 hover:-translate-y-0.5 hover:border-[var(--line-strong)]"
                            style="background: var(--paper); border-color: var(--line);">
                            @php $logo = $division->image ? asset('storage/' . $division->image) : null; @endphp
                            @if ($logo)
                                <img src="{{ $logo }}" alt="{{ $division->name }}" width="40" height="40"
                                    class="w-10 h-10 object-contain shrink-0 transition-transform duration-300 group-hover:scale-105" loading="lazy" decoding="async">
                            @endif
                            <span class="min-w-0">
                                <span class="block text-[15px] font-semibold truncate" style="color: var(--ink);">{{ $division->name }}</span>
                                <span class="block text-[13px] mt-0.5" style="color: var(--ink-faint);">{{ $division->members_count ?? 0 }} anggota</span>
                            </span>
                        </a>
                    @endforeach
                </div>

                {{-- Kalender tahunan: hanya tahun, 2017 disorot --}}
                <div class="mt-9">
                    <p class="text-[13px] font-medium mb-3" style="color: var(--ink-faint);">Berdiri sejak</p>
                    <div data-calendar class="flex flex-wrap gap-x-5 gap-y-2.5">
                        @foreach ($years as $year)
                            <span data-cal-year-cell data-year="{{ $year }}"
                                class="text-[20px] sm:text-[22px] font-bold tabular-nums transition-colors duration-300 {{ $year === $founded ? 'px-3 py-1 rounded-lg' : '' }}"
                                style="color: var(--ink-faint);">{{ $year }}</span>
                        @endforeach
                    </div>
                </div>

                <a href="#cerita"
                    class="inline-flex items-center gap-2 mt-9 px-6 min-h-[48px] text-[var(--on-accent)] text-[15px] font-medium rounded-lg transition-opacity hover:opacity-90"
                    style="background: var(--accent-solid);">
                    Lihat perjalanan CSA <i class="fas fa-arrow-right text-[12px]"></i>
                </a>
            </div>

            {{-- Kolom kanan: foto + kartu cerita --}}
            <div class="lg:col-span-6">
                @php $tentangPhoto = $heroPhotos->first(); @endphp
                @if ($tentangPhoto)
                    <div class="rounded-xl overflow-hidden aspect-[4/3]" style="background: var(--line);" data-reveal="up">
                        <img src="{{ $imageUrl($tentangPhoto, 960) }}" alt="{{ $tentangPhoto->caption ?? 'Kegiatan CSA' }}"
                            width="960" height="720" class="w-full h-full object-cover" loading="lazy" decoding="async">
                    </div>
                @endif

                <div class="grid sm:grid-cols-2 gap-4 mt-6" data-reveal-stagger>
                    @foreach ([
                        ['Sejarah', 'Berawal dari UKM pemrograman HMJ IF yang tidak aktif. Akhir 2017, sekelompok mahasiswa menghidupkannya kembali dengan fokus pemrograman dan informatika.'],
                        ['Visi', 'Menjadi komunitas kreatif dan inovatif yang mendorong kolaborasi lintas divisi, sehingga menghasilkan karya berdampak bagi kampus, masyarakat, dan industri.'],
                        ['Misi', 'Mengembangkan potensi anggota, mendorong inovasi lewat proyek kolaboratif, dan memperkenalkan karya anggota secara luas.'],
                        ['Fokus', 'Dua bidang belajar: pemrograman untuk produk digital, multimedia untuk karya visual dan interaktif.'],
                    ] as [$label, $body])
                        <article class="rounded-xl border p-5 transition-all duration-300 hover:-translate-y-0.5 hover:border-[var(--line-strong)]"
                            style="background: var(--paper); border-color: var(--line);">
                            <h3 class="text-[15px] font-semibold" style="color: var(--ink);">{{ $label }}</h3>
                            <p class="text-[14px] leading-[1.75] mt-2" style="color: var(--ink-soft);">{{ $body }}</p>
                        </article>
                    @endforeach
                </div>
            </div>

        </div>

    </div>
</section>
