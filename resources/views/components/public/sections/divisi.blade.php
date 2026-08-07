{{-- DIVISI: clean scannable grid — find your path in one glance --}}
<section id="divisi" class="relative py-20 md:py-28 lg:py-32 bg-[#fafafa] scroll-mt-24">
    <div class="max-w-[1200px] mx-auto px-5 sm:px-8">
        {{-- Header --}}
        <div class="max-w-[760px] mb-12 sm:mb-16">
            <p class="text-[11px] sm:text-xs font-semibold text-[#3664AF] tracking-[0.18em] uppercase mb-4 flex items-center gap-2.5" data-reveal="down">
                <span class="w-2 h-2 rounded-[2px] bg-[#3664AF]"></span>
                Divisi &middot; <span class="font-mono tabular-nums">{{ $divisions->count() }}</span>
            </p>
            <h2 class="text-[clamp(1.8rem,4.5vw,3rem)] font-bold text-gray-900 leading-[1.12] tracking-tight" data-reveal="curtain" data-reveal-delay="0.1">
                Bidang yang kami <span class="text-[#3664AF]">fokuskan</span>
            </h2>
            <p class="text-[15px] sm:text-lg text-gray-500 leading-relaxed mt-5" data-reveal="fade" data-reveal-delay="0.25">
                {{ $divisions->count() }} jalur pengembangan yang saling melengkapi — setiap anggota menemukan ruang tumbuh yang sesuai minatnya.
            </p>
        </div>

        @if ($divisions->isEmpty())
            <div class="rounded-2xl border border-gray-200 bg-white py-20 text-center">
                <p class="text-sm text-gray-400">Belum ada divisi yang tersedia.</p>
            </div>
        @else
            <div class="grid sm:grid-cols-2 gap-5 sm:gap-6" data-reveal-stagger>
                @foreach ($divisions as $i => $division)
                    @php
                        $divisionImg = $imageUrl($division, 320);
                        $total = $divisions->count();
                    @endphp
                    <div class="group flex flex-col rounded-2xl border border-gray-100 bg-white p-6 sm:p-8 hover:border-[#3664AF]/25 hover:shadow-[0_20px_48px_-20px_rgba(54,100,175,0.25)] hover:-translate-y-1 transition-all duration-300">
                        {{-- Top row: index + logo --}}
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex items-center gap-4">
                                <span class="font-mono text-xs font-bold text-[#3664AF] tabular-nums">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                                @if ($divisionImg)
                                    <img src="{{ $divisionImg }}" alt="{{ $division->name }}" class="h-12 w-12 object-contain" loading="lazy" decoding="async">
                                @endif
                            </div>
                            <span class="font-mono text-[10px] font-semibold uppercase tracking-[0.18em] text-gray-400 whitespace-nowrap">
                                {{ $division->members_count ?? 0 }} anggota
                            </span>
                        </div>

                        <h3 class="text-xl sm:text-2xl font-bold text-gray-900 tracking-tight mt-5 group-hover:text-[#3664AF] transition-colors duration-300">{{ $division->name }}</h3>

                        <p class="text-sm sm:text-[15px] text-gray-500 leading-relaxed mt-3 flex-1">
                            {{ $division->description ?? 'Tidak ada deskripsi' }}
                        </p>

                        @if (!empty($division->skills))
                            <div class="flex flex-wrap gap-2 mt-5">
                                @foreach ($division->skills as $skill)
                                    <span class="font-mono text-[10px] font-semibold uppercase tracking-[0.14em] text-gray-500 bg-[#f4f7fd] border border-gray-100 rounded-lg px-2.5 py-1.5">{{ $skill }}</span>
                                @endforeach
                            </div>
                        @endif

                        <div class="flex items-center justify-between gap-4 mt-6 pt-5 border-t border-gray-100">
                            <span class="font-mono text-[10px] uppercase tracking-[0.18em] text-gray-400">Divisi {{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }} / {{ str_pad($total, 2, '0', STR_PAD_LEFT) }}</span>
                            <a href="{{ route('register') }}"
                                class="inline-flex items-center gap-2 text-[13px] font-semibold text-[#3664AF] hover:text-gray-900 transition-colors duration-200">
                                Gabung divisi ini
                                <i class="fas fa-arrow-right text-[10px] transition-transform duration-300 group-hover:translate-x-1"></i>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>