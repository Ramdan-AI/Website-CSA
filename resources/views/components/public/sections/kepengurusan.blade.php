<section id="kepengurusan" class="scroll-mt-16" style="background: var(--paper-sunken); border-top: 1px solid var(--line); border-bottom: 1px solid var(--line);">
    @php
        $officers = $members
            ->reject(fn ($m) => in_array($m->position?->name, ['Ketua', 'Wakil Ketua', 'Anggota', null], true))
            ->sortBy('id')
            ->values();

        $anggotaPreview = $members
            ->filter(fn ($m) => in_array($m->position?->name, ['Anggota', null], true))
            ->take(8)
            ->values();

        $officerCaption = fn ($m) => $m->position?->name === 'Wakil Ketua Divisi' ? 'Wakil Divisi' : $m->position?->name;
        $memberCaption = fn ($m) => $m->division?->name ?? 'Anggota';
    @endphp

    <div class="max-w-[1200px] mx-auto px-5 sm:px-8 py-16 sm:py-24">

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
            <div class="rounded-xl border border-[var(--line)] p-4 sm:p-8" data-reveal="fade">
                <x-public.org.chart
                    :chart="new \App\Support\OrganizationChart($members)"
                    :advisors="config('organization.advisors')" />
            </div>

            @if ($officers->isNotEmpty())
                <x-public.roster-slider eyebrow="Tim inti" title="Pengurus" :members="$officers" :caption="$officerCaption" />
            @endif

            @if ($anggotaPreview->isNotEmpty())
                <x-public.roster-slider eyebrow="Komunitas" title="Anggota CSA" :members="$anggotaPreview" :caption="$memberCaption">
                    <div class="flex justify-center mt-8">
                        <a href="{{ route('anggota') }}" class="inline-flex items-center gap-2 text-[13px] font-medium text-[var(--accent)] hover:underline">
                            Lihat semua anggota <i class="fas fa-arrow-right text-[11px]"></i>
                        </a>
                    </div>
                </x-public.roster-slider>
            @endif
        @endif

    </div>
</section>
