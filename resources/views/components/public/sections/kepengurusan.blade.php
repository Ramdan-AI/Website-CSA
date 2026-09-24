{{-- STRUKTUR ORGANISASI: diagram denah bertingkat + slider pengurus & anggota --}}
<section id="kepengurusan" class="scroll-mt-16" style="background: var(--paper-sunken); border-top: 1px solid var(--line); border-bottom: 1px solid var(--line);">
    @php
        $roleNameOf = fn ($m) => $m->position?->name ?? $m->division?->name ?? 'Anggota';

        $rosterLabelOf = fn ($m) => match ($m->position?->name) {
            'Ketua Divisi' => 'Ketua Divisi',
            'Wakil Ketua Divisi' => 'Wakil Divisi',
            default => $m->position?->name ?? 'Anggota',
        };

        // Pakai accessor Member agar URL mengikuti host request.
        $memberImageOf = fn ($member) => $member->photo_url
            ?: asset('storage/profile/profile-placeholder.png');

        $pengurusSlider = $pengurusInti
            ->concat($koordinator)
            ->reject(fn ($m) => $m->is($ketua ?? null) || $m->is($wakil ?? null))
            ->values();

        $regOpen = \App\Models\Setting::registrationOpen();
    @endphp

    <div class="max-w-[1200px] mx-auto px-5 sm:px-8 py-16 sm:py-24">

        {{-- Header --}}
        <div class="max-w-[680px] mb-14" data-reveal="up">
            <p class="text-[13px] font-medium mb-3" style="color: var(--accent);">Struktur organisasi</p>
            <h2 class="text-[clamp(1.7rem,3.8vw,2.4rem)] font-bold leading-[1.22] tracking-[-0.025em]" style="color: var(--ink);">
                Kepengurusan CSA
            </h2>
            <p class="text-[16px] leading-[1.8] mt-5" style="color: var(--ink-soft);">
                Susunan pengurus yang menangani program, kegiatan, dan komunitas CSA.
            </p>
        </div>

        @if ($members->isEmpty())
            <div class="rounded-xl bg-[var(--paper)] border border-[var(--line)] py-20 text-center">
                <p class="text-[15px] text-[var(--ink-faint)]">Belum ada pengurus maupun anggota terdaftar.</p>
            </div>
        @else

            {{-- Bagan organisasi --}}
            <div class="bg-[var(--paper)] rounded-xl border border-[var(--line)] p-4 sm:p-10 overflow-x-auto" data-reveal="fade">
                <div class="min-w-0 sm:min-w-[720px]">

                    {{-- Baris 1: Ketua --}}
                    <div class="flex justify-center">
                        @if ($ketua)
                            <div class="w-full max-w-[300px] sm:w-[260px]">
                                <x-public.org-node
                                    :member="$ketua"
                                    label="Ketua"
                                    :image="$memberImageOf($ketua)"
                                    variant="dashed"
                                    emphasis="primary" />
                            </div>
                        @endif
                    </div>

                    {{-- Konektor vertikal --}}
                    <div class="flex justify-center">
                        <div class="w-px h-6 sm:h-8 bg-[var(--line-strong)]"></div>
                    </div>

                    {{-- Baris 2: Wakil Ketua --}}
                    @if ($wakil)
                        <div class="flex justify-center">
                            <div class="w-full max-w-[300px] sm:w-[260px]">
                                <x-public.org-node
                                    :member="$wakil"
                                    label="Wakil Ketua"
                                    :image="$memberImageOf($wakil)"
                                    variant="dashed" />
                            </div>
                        </div>

                        <div class="flex justify-center">
                            <div class="w-px h-6 sm:h-8 bg-[var(--line-strong)]"></div>
                        </div>
                    @endif

                    {{-- Baris 3: Sekretaris + Bendahara --}}
                    @php
                        $sekretaris = $pengurusInti->firstWhere(fn ($m) => $m->position?->name === 'Sekretaris');
                        $bendahara = $pengurusInti->firstWhere(fn ($m) => $m->position?->name === 'Bendahara');
                        $rowThree = collect([
                            ['label' => 'Sekretaris', 'member' => $sekretaris],
                            ['label' => 'Bendahara', 'member' => $bendahara],
                        ])->filter(fn ($n) => $n['member']);
                    @endphp

                    @if ($rowThree->isNotEmpty())
                        <div class="relative">
                            <div class="hidden sm:block absolute top-0 left-1/2 -translate-x-1/2 h-px bg-[var(--line-strong)]" style="width: 40%;"></div>
                            <div class="grid grid-cols-1 sm:flex sm:justify-center gap-4 sm:gap-5">
                                @foreach ($rowThree as $node)
                                    <div class="relative sm:pt-8 w-full sm:w-[240px]">
                                        <div class="hidden sm:block absolute top-0 left-1/2 -translate-x-1/2 w-px h-8 bg-[var(--line-strong)]"></div>
                                        <x-public.org-node
                                            :member="$node['member']"
                                            :label="$node['label']"
                                            :image="$memberImageOf($node['member'])" />
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Konektor ke koordinator --}}
                    @if ($koordinator->isNotEmpty())
                        <div class="flex justify-center">
                            <div class="w-px h-8 bg-[var(--line-strong)]"></div>
                        </div>

                        {{-- Baris 4: Ketua Divisi -> Wakil Divisi (per divisi) --}}
                        @php
                            $divisionCharts = $koordinator
                                ->groupBy(fn ($m) => $m->division?->name ?? $m->division?->id ?? 'Divisi')
                                ->map(fn ($group) => [
                                    'ketua' => $group->first(fn ($m) => $m->position?->name === 'Ketua Divisi'),
                                    'wakil' => $group->first(fn ($m) => $m->position?->name === 'Wakil Ketua Divisi'),
                                ])
                                ->filter(fn ($row) => $row['ketua'] || $row['wakil']);
                        @endphp

                        <div class="relative">
                            <div class="hidden sm:block absolute top-0 left-1/2 -translate-x-1/2 h-px bg-[var(--line-strong)]" style="width: 80%;"></div>
                            <div class="flex flex-wrap justify-center gap-8 sm:gap-12">
                                @foreach ($divisionCharts as $divisionName => $row)
                                    <div class="relative sm:pt-8 w-full sm:w-[240px] flex flex-col items-center">
                                        <div class="hidden sm:block absolute top-0 left-1/2 -translate-x-1/2 w-px h-8 bg-[var(--line-strong)]"></div>

                                        <p class="text-[12px] font-bold text-[var(--ink)] text-center mb-3">{{ $divisionName }}</p>

                                        <div class="w-full flex flex-col items-center gap-3">
                                            @if ($row['ketua'])
                                                <div class="w-full max-w-[280px]">
                                                    <x-public.org-node
                                                        :member="$row['ketua']"
                                                        label="Ketua Divisi"
                                                        :image="$memberImageOf($row['ketua'])"
                                                        variant="dashed" />
                                                </div>
                                            @endif

                                            @if ($row['wakil'])
                                                <div class="w-px h-5 bg-[var(--line-strong)]"></div>
                                                <div class="w-full max-w-[280px]">
                                                    <x-public.org-node
                                                        :member="$row['wakil']"
                                                        label="Wakil Divisi"
                                                        :image="$memberImageOf($row['wakil'])"
                                                        variant="dashed" />
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                </div>
            </div>

            {{-- ============ SLIDER PENGURUS ============ --}}
            @if ($pengurusSlider->isNotEmpty())
                <div class="mt-20">
                    <div class="flex items-end justify-between gap-5 mb-6">
                        <div>
                            <p class="text-[13px] text-[var(--ink-faint)] mb-1">Tim inti</p>
                            <h3 class="text-[20px] font-semibold text-[var(--ink)]">Pengurus</h3>
                        </div>
                        @if ($pengurusSlider->count() > 2)
                            <div class="flex items-center gap-2 shrink-0">
                                <button type="button" data-slider-prev aria-label="Geser ke kiri"
                                    class="w-10 h-10 rounded-full bg-[var(--paper)] border border-[var(--line)] flex items-center justify-center text-[var(--ink-faint)] hover:text-[var(--accent)] hover:border-[var(--line-strong)] transition-colors">
                                    <i class="fas fa-chevron-left text-[12px]"></i>
                                </button>
                                <button type="button" data-slider-next aria-label="Geser ke kanan"
                                    class="w-10 h-10 rounded-full bg-[var(--paper)] border border-[var(--line)] flex items-center justify-center text-[var(--ink-faint)] hover:text-[var(--accent)] hover:border-[var(--line-strong)] transition-colors">
                                    <i class="fas fa-chevron-right text-[12px]"></i>
                                </button>
                            </div>
                        @endif
                    </div>

                    <div data-slider>
                        <div class="flex gap-3 sm:gap-4 overflow-x-auto no-scrollbar pb-2" data-slider-track>
                            @foreach ($pengurusSlider as $member)
                                <article class="group shrink-0 w-[164px] sm:w-[220px] bg-[var(--paper)] rounded-xl sm:rounded-xl border border-[var(--line)] p-4 sm:p-5 text-center hover:border-[var(--line-strong)] transition-all duration-300">
                                    <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-full overflow-hidden bg-[var(--accent-soft)] mx-auto">
                                        <img src="{{ $memberImageOf($member) }}" alt="{{ $member->name }}" width="80" height="80" class="w-full h-full object-cover" loading="lazy" onerror="this.onerror=null;this.src='{{ asset('storage/profile/profile-placeholder.png') }}';">
                                    </div>
                                    <h4 class="text-[13px] sm:text-[14px] font-bold text-[var(--ink)] mt-3 sm:mt-3.5 leading-snug">{{ $member->name }}</h4>
                                    <p class="text-[11px] text-[var(--ink-faint)] mt-0.5 truncate">{{ $rosterLabelOf($member) }}</p>
                                    <div class="flex justify-center mt-3 sm:mt-4 pt-3 sm:pt-4 border-t border-[var(--line)]">
                                        @include('components.public.partials.member-socials', ['member' => $member])
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            {{-- ============ SLIDER ANGGOTA ============ --}}
            @if ($anggotaPreview->isNotEmpty())
                <div class="mt-20">
                    <div class="flex items-end justify-between gap-5 mb-6">
                        <div>
                            <p class="text-[13px] text-[var(--ink-faint)] mb-1">Komunitas</p>
                            <h3 class="text-[20px] font-semibold text-[var(--ink)]">Anggota CSA</h3>
                        </div>
                        <div class="flex items-center gap-2 shrink-0">
                            @if ($anggotaPreview->count() > 2)
                                <button type="button" data-slider-prev aria-label="Geser ke kiri"
                                    class="w-10 h-10 rounded-full bg-[var(--paper)] border border-[var(--line)] flex items-center justify-center text-[var(--ink-faint)] hover:text-[var(--accent)] hover:border-[var(--line-strong)] transition-colors">
                                    <i class="fas fa-chevron-left text-[12px]"></i>
                                </button>
                                <button type="button" data-slider-next aria-label="Geser ke kanan"
                                    class="w-10 h-10 rounded-full bg-[var(--paper)] border border-[var(--line)] flex items-center justify-center text-[var(--ink-faint)] hover:text-[var(--accent)] hover:border-[var(--line-strong)] transition-colors">
                                    <i class="fas fa-chevron-right text-[12px]"></i>
                                </button>
                            @endif
                        </div>
                    </div>

                    <div data-slider>
                        <div class="flex gap-3 sm:gap-4 overflow-x-auto no-scrollbar pb-2" data-slider-track>
                            @foreach ($anggotaPreview as $member)
                                <article class="group shrink-0 w-[164px] sm:w-[220px] bg-[var(--paper)] rounded-xl sm:rounded-xl border border-[var(--line)] p-4 sm:p-5 text-center hover:border-[var(--line-strong)] transition-all duration-300">
                                    <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-full overflow-hidden bg-[var(--accent-soft)] mx-auto">
                                        <img src="{{ $memberImageOf($member) }}" alt="{{ $member->name }}" width="80" height="80" class="w-full h-full object-cover" loading="lazy" onerror="this.onerror=null;this.src='{{ asset('storage/profile/profile-placeholder.png') }}';">
                                    </div>
                                    <h4 class="text-[13px] sm:text-[14px] font-bold text-[var(--ink)] mt-3 sm:mt-3.5 leading-snug">{{ $member->name }}</h4>
                                    <p class="text-[11px] text-[var(--ink-faint)] mt-0.5 truncate">{{ $member->division?->name ?? 'Anggota' }}</p>
                                    <div class="flex justify-center mt-3 sm:mt-4 pt-3 sm:pt-4 border-t border-[var(--line)]">
                                        @include('components.public.partials.member-socials', ['member' => $member])
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    </div>

                    <div class="flex justify-center mt-8">
                        <a href="{{ route('anggota') }}" class="inline-flex items-center gap-2 text-[13px] font-medium text-[var(--accent)] hover:underline">
                            Lihat semua anggota <i class="fas fa-arrow-right text-[11px]"></i>
                        </a>
                    </div>
                </div>
            @endif

        @endif

    </div>
</section>
