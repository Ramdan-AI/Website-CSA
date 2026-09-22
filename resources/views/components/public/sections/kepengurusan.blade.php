{{-- STRUKTUR ORGANISASI: diagram denah bertingkat + slider pengurus & anggota --}}
<section id="kepengurusan" class="relative py-20 md:py-28 bg-[#f8f9fa] scroll-mt-24">
    @php
        $roleNameOf = fn ($m) => $m->position?->name ?? $m->division?->name ?? 'Anggota';

        $rosterLabelOf = fn ($m) => match ($m->position?->name) {
            'Ketua Divisi' => 'Ketua Divisi',
            'Wakil Ketua Divisi' => 'Wakil Divisi',
            default => $m->position?->name ?? 'Anggota',
        };

        $memberImageOf = function ($member) {
            foreach (['image', 'photo', 'profile_photo', 'profile_image', 'avatar', 'avatar_path'] as $field) {
                $value = $member->getAttribute($field);
                if (!empty($value)) {
                    return \Illuminate\Support\Facades\Storage::disk('public')->url($value);
                }
            }
            return $member->photo_url ?: asset('storage/profile/profile-placeholder.png');
        };

        $pengurusSlider = $pengurusInti
            ->concat($koordinator)
            ->reject(fn ($m) => $m->is($ketua ?? null) || $m->is($wakil ?? null))
            ->values();

        $regOpen = Cache::remember(
            'setting.registration_open',
            60,
            fn () => \App\Models\Setting::first()?->registration_open ?? false
        );
    @endphp

    <div class="max-w-[1200px] mx-auto px-5 sm:px-8 py-16 sm:py-20">

        {{-- Header --}}
        <div class="max-w-[680px] mb-12" data-reveal="up">
            <h2 class="text-[clamp(1.6rem,3.6vw,2.25rem)] font-bold text-gray-900 leading-[1.25]">
                Kepengurusan CSA
            </h2>
            <p class="text-[16px] text-gray-600 leading-[1.75] mt-4">
                Susunan pengurus yang menangani program, kegiatan, dan komunitas CSA.
            </p>
        </div>

        @if ($members->isEmpty())
            <div class="rounded-xl bg-white border border-gray-200 py-20 text-center">
                <p class="text-[15px] text-gray-500">Belum ada pengurus maupun anggota terdaftar.</p>
            </div>
        @else

            {{-- Bagan organisasi --}}
            <div class="bg-white rounded-xl border border-gray-200 p-4 sm:p-10 overflow-x-auto" data-reveal="fade">
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
                        <div class="w-px h-6 sm:h-8 bg-[#1a73e8]/40"></div>
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
                            <div class="w-px h-6 sm:h-8 bg-[#1a73e8]/40"></div>
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
                            <div class="hidden sm:block absolute top-0 left-1/2 -translate-x-1/2 h-px bg-[#1a73e8]/30" style="width: 40%;"></div>
                            <div class="grid grid-cols-1 sm:flex sm:justify-center gap-4 sm:gap-5">
                                @foreach ($rowThree as $node)
                                    <div class="relative sm:pt-8 w-full sm:w-[240px]">
                                        <div class="hidden sm:block absolute top-0 left-1/2 -translate-x-1/2 w-px h-8 bg-[#1a73e8]/30"></div>
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
                            <div class="w-px h-8 bg-[#1a73e8]/25"></div>
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
                            <div class="hidden sm:block absolute top-0 left-1/2 -translate-x-1/2 h-px bg-[#1a73e8]/20" style="width: 80%;"></div>
                            <div class="flex flex-wrap justify-center gap-6 sm:gap-10">
                                @foreach ($divisionCharts as $divisionName => $row)
                                    <div class="relative sm:pt-8 w-full sm:w-[240px] flex flex-col items-center">
                                        <div class="hidden sm:block absolute top-0 left-1/2 -translate-x-1/2 w-px h-8 bg-[#1a73e8]/20"></div>

                                        <p class="text-[12px] font-bold text-gray-900 text-center mb-3">{{ $divisionName }}</p>

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
                                                <div class="w-px h-5 bg-[#1a73e8]/25"></div>
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
                <div class="mt-14 md:mt-20">
                    <div class="flex items-end justify-between gap-5 mb-6">
                        <div>
                            <p class="text-[13px] text-gray-500 mb-1">Tim inti</p>
                            <h3 class="text-[20px] font-semibold text-gray-900">Pengurus</h3>
                        </div>
                        @if ($pengurusSlider->count() > 2)
                            <div class="flex items-center gap-2 shrink-0">
                                <button type="button" data-slider-prev aria-label="Geser ke kiri"
                                    class="w-10 h-10 rounded-full bg-white border border-gray-200 flex items-center justify-center text-gray-500 hover:text-[#1a73e8] hover:border-[#1a73e8]/40 transition-colors">
                                    <i class="fas fa-chevron-left text-[12px]"></i>
                                </button>
                                <button type="button" data-slider-next aria-label="Geser ke kanan"
                                    class="w-10 h-10 rounded-full bg-white border border-gray-200 flex items-center justify-center text-gray-500 hover:text-[#1a73e8] hover:border-[#1a73e8]/40 transition-colors">
                                    <i class="fas fa-chevron-right text-[12px]"></i>
                                </button>
                            </div>
                        @endif
                    </div>

                    <div data-slider>
                        <div class="flex gap-3 sm:gap-4 overflow-x-auto no-scrollbar pb-2" data-slider-track>
                            @foreach ($pengurusSlider as $member)
                                <article class="group shrink-0 w-[164px] sm:w-[220px] bg-white rounded-xl sm:rounded-xl border border-gray-200/80 p-4 sm:p-5 text-center hover:border-[#1a73e8]/30 transition-all duration-300">
                                    <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-full overflow-hidden bg-[#e8f0fe] mx-auto">
                                        <img src="{{ $memberImageOf($member) }}" alt="{{ $member->name }}" width="80" height="80" class="w-full h-full object-cover" loading="lazy" onerror="this.onerror=null;this.src='{{ asset('storage/profile/profile-placeholder.png') }}';">
                                    </div>
                                    <h4 class="text-[13px] sm:text-[14px] font-bold text-gray-900 mt-3 sm:mt-3.5 leading-snug">{{ $member->name }}</h4>
                                    <p class="text-[11px] text-gray-400 mt-0.5 truncate">{{ $rosterLabelOf($member) }}</p>
                                    <div class="flex justify-center mt-3 sm:mt-4 pt-3 sm:pt-4 border-t border-gray-100">
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
                <div class="mt-14 md:mt-20">
                    <div class="flex items-end justify-between gap-5 mb-6">
                        <div>
                            <p class="text-[13px] text-gray-500 mb-1">Komunitas</p>
                            <h3 class="text-[20px] font-semibold text-gray-900">Anggota CSA</h3>
                        </div>
                        <div class="flex items-center gap-2 shrink-0">
                            @if ($anggotaPreview->count() > 2)
                                <button type="button" data-slider-prev aria-label="Geser ke kiri"
                                    class="w-10 h-10 rounded-full bg-white border border-gray-200 flex items-center justify-center text-gray-500 hover:text-[#1a73e8] hover:border-[#1a73e8]/40 transition-colors">
                                    <i class="fas fa-chevron-left text-[12px]"></i>
                                </button>
                                <button type="button" data-slider-next aria-label="Geser ke kanan"
                                    class="w-10 h-10 rounded-full bg-white border border-gray-200 flex items-center justify-center text-gray-500 hover:text-[#1a73e8] hover:border-[#1a73e8]/40 transition-colors">
                                    <i class="fas fa-chevron-right text-[12px]"></i>
                                </button>
                            @endif
                        </div>
                    </div>

                    <div data-slider>
                        <div class="flex gap-3 sm:gap-4 overflow-x-auto no-scrollbar pb-2" data-slider-track>
                            @foreach ($anggotaPreview as $member)
                                <article class="group shrink-0 w-[164px] sm:w-[220px] bg-white rounded-xl sm:rounded-xl border border-gray-200/80 p-4 sm:p-5 text-center hover:border-[#1a73e8]/30 transition-all duration-300">
                                    <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-full overflow-hidden bg-[#e8f0fe] mx-auto">
                                        <img src="{{ $memberImageOf($member) }}" alt="{{ $member->name }}" width="80" height="80" class="w-full h-full object-cover" loading="lazy" onerror="this.onerror=null;this.src='{{ asset('storage/profile/profile-placeholder.png') }}';">
                                    </div>
                                    <h4 class="text-[13px] sm:text-[14px] font-bold text-gray-900 mt-3 sm:mt-3.5 leading-snug">{{ $member->name }}</h4>
                                    <p class="text-[11px] text-gray-400 mt-0.5 truncate">{{ $member->division?->name ?? 'Anggota' }}</p>
                                    <div class="flex justify-center mt-3 sm:mt-4 pt-3 sm:pt-4 border-t border-gray-100">
                                        @include('components.public.partials.member-socials', ['member' => $member])
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    </div>

                    <div class="flex justify-center mt-8">
                        <a href="{{ route('anggota') }}" class="inline-flex items-center gap-2 text-[13px] font-medium text-[#1a73e8] hover:underline">
                            Lihat semua anggota <i class="fas fa-arrow-right text-[11px]"></i>
                        </a>
                    </div>
                </div>
            @endif

        @endif

    </div>
</section>
