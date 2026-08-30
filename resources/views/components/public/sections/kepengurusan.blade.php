{{-- KEPENGURUSAN & ANGGOTA --}}
<section id="kepengurusan" class="relative py-20 md:py-32 bg-white scroll-mt-24 overflow-hidden">
    <div class="absolute top-24 right-[-40px] hidden xl:block pointer-events-none select-none" data-parallax="-70">
        <p class="text-[15rem] font-extrabold leading-none text-[#3664AF]/[0.06] tracking-tighter tabular-nums">{{ $members->count() }}</p>
    </div>

    @php
        $roleNameOf = fn($m) => $m->position?->name ?? $m->division?->name ?? 'Anggota';
        $rosterLabelOf = fn($m) => match ($m->position?->name) {
            'Ketua Divisi' => 'Koordinator',
            'Wakil Ketua Divisi' => 'Wakil Koordinator',
            default => $m->position?->name ?? 'Anggota',
        };
        $pengurus = $pengurusInti->concat($koordinator)->values();
    @endphp

    <div class="relative max-w-[1200px] mx-auto px-5 sm:px-8">
        {{-- Masthead --}}
        <div class="max-w-[820px] mb-12 sm:mb-20" data-parallax="-12">
            <p class="text-[11px] sm:text-xs font-semibold text-[#3664AF] tracking-[0.22em] uppercase mb-5 sm:mb-6" data-reveal="down">Struktur Organisasi</p>
            <h2 class="text-[clamp(2.2rem,7.5vw,4.5rem)] font-bold text-gray-900 leading-[0.98] tracking-[-0.04em]" data-reveal="curtain" data-reveal-delay="0.1">
                Mereka yang<br>menggerakkan <span class="text-[#3664AF]">CSA</span>
            </h2>
            <p class="text-[15px] sm:text-lg text-gray-500 leading-relaxed mt-6 max-w-[560px]" data-reveal="fade" data-reveal-delay="0.25">
                Dari pimpinan hingga anggota, setiap orang punya peran nyata dalam menghidupkan ekosistem kreatif CSA.
            </p>
        </div>

        @if ($members->isEmpty())
            <div class="text-center py-20">
                <p class="text-sm text-gray-400">Belum ada pengurus maupun anggota terdaftar.</p>
            </div>
        @else
            {{-- PIMPINAN: monument rows --}}
            @if ($ketua)
            <div class="border-t-2 border-gray-900">
                <div class="grid lg:grid-cols-[220px_1fr_auto] gap-6 lg:gap-10 items-end pt-10 sm:pt-14 pb-6">
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-[0.24em] text-[#E8A33D]">01 &middot; Ketua</p>
                        <p class="font-mono text-[10px] uppercase tracking-[0.2em] text-gray-400 mt-2.5">Memimpin arah &amp; strategi</p>
                    </div>
                    <h3 class="text-[clamp(2.2rem,6vw,4.5rem)] font-extrabold tracking-[-0.03em] leading-none" data-parallax="10">{{ $ketua->name }}</h3>
                    <div class="flex items-center gap-2 pb-2 justify-start lg:justify-end">
                        @include('components.public.partials.member-socials', ['member' => $ketua])
                    </div>
                </div>
                <p class="text-[15px] text-gray-500 leading-relaxed max-w-[620px] pb-10 sm:pb-14" data-reveal="fade" data-reveal-delay="0.1">
                    Memimpin arah, strategi, dan representasi CSA di lingkungan kampus.
                </p>
            </div>
            @endif

            @if ($wakil)
            <div class="border-t border-gray-200">
                <div class="grid lg:grid-cols-[auto_1fr_220px] gap-6 lg:gap-10 items-end pt-10 sm:pt-14 pb-6">
                    <div class="flex items-center gap-2 pb-2 order-2 lg:order-1">
                        @include('components.public.partials.member-socials', ['member' => $wakil])
                    </div>
                    <h3 class="text-[clamp(1.8rem,5vw,3.25rem)] font-extrabold tracking-[-0.03em] leading-none order-1 lg:order-2" data-parallax="-8">{{ $wakil->name }}</h3>
                    <div class="order-3 lg:text-right">
                        <p class="text-[11px] font-bold uppercase tracking-[0.24em] text-[#3664AF]">02 &middot; Wakil Ketua</p>
                        <p class="font-mono text-[10px] uppercase tracking-[0.2em] text-gray-400 mt-2.5">Operasional &amp; pendampingan</p>
                    </div>
                </div>
                <p class="text-[15px] text-gray-500 leading-relaxed max-w-[620px] pb-10 sm:pb-14" data-reveal="fade" data-reveal-delay="0.1">
                    Mendampingi ketua dan menjaga kelancaran operasional organisasi.
                </p>
            </div>
            @endif

            {{-- PENGURUS: roster readable --}}
            @if ($pengurus->isNotEmpty())
            <div class="mt-16 sm:mt-24">
                <div class="flex items-end justify-between border-b-2 border-gray-900 pb-4">
                    <h3 class="text-[13px] font-bold uppercase tracking-[0.25em] text-gray-900">Sekretaris, Bendahara &amp; Koordinator</h3>
                    <span class="font-mono text-sm font-bold text-[#3664AF] tabular-nums">{{ $pengurus->count() }}</span>
                </div>
                <div data-parallax="-10">
                    @foreach ($pengurus as $i => $member)
                        <div class="group grid grid-cols-[36px_auto_1fr] sm:grid-cols-[56px_210px_1fr_auto] gap-x-4 sm:gap-x-8 items-baseline border-b border-gray-200 py-5 sm:py-6 px-2 sm:px-4 -mx-2 sm:-mx-4 hover:bg-[#0B1B3B] transition-colors duration-300">
                            <span class="font-mono text-[11px] font-bold text-[#3664AF]/70 group-hover:text-[#E8A33D] transition-colors duration-300 tabular-nums">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                            <span class="text-[10px] font-bold uppercase tracking-[0.22em] text-gray-400 group-hover:text-[#9DB8E8] transition-colors duration-300">{{ $rosterLabelOf($member) }}</span>
                            <span class="text-base sm:text-xl font-bold tracking-[-0.01em] text-gray-900 group-hover:text-white transition-colors duration-300 truncate min-w-0">{{ $member->name }}</span>
                            <span class="hidden sm:block text-[10px] uppercase tracking-[0.18em] text-gray-400 group-hover:text-[#9DB8E8] transition-colors duration-300 text-right">{{ $member->division?->name }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- TEAM: flag wall (anggota) --}}
            @if ($anggotaPreview->isNotEmpty())
            <div class="mt-16 sm:mt-24">
                <div class="flex items-end justify-between border-b-2 border-gray-900 pb-4">
                    <h3 class="text-[13px] font-bold uppercase tracking-[0.25em] text-gray-900">Anggota</h3>
                    <div class="flex items-center gap-6">
                        <span class="font-mono text-[11px] uppercase tracking-[0.2em] text-gray-400 hidden sm:inline">Geser untuk menjelajahi</span>
                        <span class="font-mono text-sm font-bold text-[#3664AF] tabular-nums">{{ $anggotaList->count() }}</span>
                    </div>
                </div>

                <div class="grid grid-flow-col auto-cols-[76px] sm:auto-cols-[84px] gap-3 sm:gap-4 overflow-x-auto no-scrollbar snap-x snap-proximity pt-8 pb-2" data-reveal-stagger data-parallax="-6">
                    @foreach ($anggotaPreview as $member)
                        <div class="group snap-start flex flex-col justify-between h-72 sm:h-[300px] rounded-[2px] bg-[#EEF3FB] border border-[#3664AF]/10 hover:bg-[#0B1B3B] hover:border-[#0B1B3B] transition-colors duration-300 p-4 overflow-hidden">
                            <p class="font-mono text-[10px] font-semibold text-[#3664AF]/60 group-hover:text-[#E8A33D] transition-colors duration-300 tabular-nums">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</p>
                            <p class="flex-1 flex items-center justify-center text-center text-[14px] font-extrabold uppercase leading-[1.2] tracking-[0.08em] text-gray-900 group-hover:text-white transition-colors duration-300 [writing-mode:vertical-rl]">{{ $member->name }}</p>
                            <p class="text-[9px] font-bold uppercase tracking-[0.18em] text-[#3664AF]/70 group-hover:text-[#9DB8E8] transition-colors duration-300">{{ $roleNameOf($member) }}</p>
                        </div>
                    @endforeach

                    @php
                        $regOpen = Cache::remember('setting.registration_open', 60, fn () => \App\Models\Setting::first()?->registration_open ?? false);
                    @endphp
                    @if ($regOpen)
                        <a href="{{ route('register') }}"
                            class="group snap-start flex flex-col justify-between h-72 sm:h-[300px] rounded-[2px] bg-[#0B1B3B] hover:bg-[#122A52] transition-colors duration-300 p-4 overflow-hidden">
                            <p class="font-mono text-[10px] font-semibold uppercase tracking-[0.18em] text-[#9DB8E8]">Generasi berikutnya</p>
                            <p class="flex-1 flex items-center justify-center text-center text-[14px] font-extrabold uppercase leading-[1.2] tracking-[0.08em] text-white [writing-mode:vertical-rl]">Bergabung</p>
                            <p class="text-[9px] font-bold uppercase tracking-[0.18em] text-[#E8A33D] transition-transform duration-300 group-hover:translate-x-0.5">Daftar &rarr;</p>
                        </a>
                    @else
                        <button type="button" onclick="window.dispatchEvent(new CustomEvent('open-register-modal'))"
                            class="group snap-start flex flex-col justify-between h-72 sm:h-[300px] rounded-[2px] bg-[#0B1B3B] hover:bg-[#122A52] transition-colors duration-300 p-4 overflow-hidden text-left cursor-pointer">
                            <p class="font-mono text-[10px] font-semibold uppercase tracking-[0.18em] text-[#9DB8E8]">Generasi berikutnya</p>
                            <p class="flex-1 flex items-center justify-center text-center text-[14px] font-extrabold uppercase leading-[1.2] tracking-[0.08em] text-white [writing-mode:vertical-rl]">Bergabung</p>
                            <p class="text-[9px] font-bold uppercase tracking-[0.18em] text-[#E8A33D] transition-transform duration-300 group-hover:translate-x-0.5">Daftar &rarr;</p>
                        </button>
                    @endif
                </div>
            </div>
            @endif

            {{-- Closing --}}
            <div class="flex justify-center mt-14">
                <a href="{{ route('anggota') }}"
                    class="group inline-flex items-center gap-3 font-mono text-[12px] font-semibold uppercase tracking-[0.25em] text-[#3664AF] hover:text-gray-900 transition-colors duration-200" data-reveal="fade" data-reveal-delay="0.1">
                    Lihat seluruh anggota
                    <i class="fas fa-arrow-right text-[10px] transition-transform duration-300 group-hover:translate-x-1"></i>
                </a>
            </div>
        @endif
    </div>
</section>
