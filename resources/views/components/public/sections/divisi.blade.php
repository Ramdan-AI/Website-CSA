{{-- Divisi: logo asli, kartu ringkas --}}
<section id="divisi" class="scroll-mt-16" style="background: var(--paper);">
    <div class="max-w-[1200px] mx-auto px-5 sm:px-8 py-16 sm:py-24">

        <div class="max-w-[680px] mb-14">
            <p class="text-[13px] font-medium mb-3" style="color: var(--accent);">Divisi</p>
            <h2 class="text-[clamp(1.7rem,3.8vw,2.4rem)] font-bold leading-[1.22] tracking-[-0.025em]" style="color: var(--ink);">
                Pilih divisi sesuai minatmu
            </h2>
            <p class="text-[16px] leading-[1.8] mt-5" style="color: var(--ink-soft);">
                CSA punya {{ $divisions->count() }} divisi. Setiap divisi punya fokus dan cara belajar sendiri.
            </p>
        </div>

        @php
            $regOpen = Cache::remember('setting.registration_open', 60, fn () => \App\Models\Setting::first()?->registration_open ?? false);
        @endphp

        @if ($divisions->isEmpty())
            <div class="rounded-xl border p-14 text-center" style="background: var(--paper-sunken); border-color: var(--line);">
                <p class="text-[15px]" style="color: var(--ink-soft);">Belum ada divisi yang tersedia.</p>
            </div>
        @else
            <div class="grid sm:grid-cols-2 gap-5">
                @foreach ($divisions as $division)
                    @php $logo = $division->image ? asset('storage/' . $division->image) : null; @endphp

                    <article class="group flex flex-col rounded-xl border p-6 transition-all duration-300 hover:-translate-y-1 hover:border-[var(--line-strong)]"
                        style="background: var(--paper-sunken); border-color: var(--line);">

                        <div class="flex items-start justify-between gap-4">
                            @if ($logo)
                                <img src="{{ $logo }}" alt="{{ $division->name }}" width="44" height="44"
                                    class="w-11 h-11 object-contain transition-transform duration-300 group-hover:scale-105"
                                    loading="lazy" decoding="async">
                            @endif
                            <span class="text-[13px] shrink-0 mt-1" style="color: var(--ink-faint);">{{ $division->members_count ?? 0 }} anggota</span>
                        </div>

                        <h3 class="text-[19px] font-bold mt-5 tracking-[-0.015em]" style="color: var(--ink);">{{ $division->name }}</h3>

                        <p class="text-[15px] leading-[1.8] mt-2.5 flex-1" style="color: var(--ink-soft);">
                            {{ $division->description ?? 'Belum ada deskripsi.' }}
                        </p>

                        @if (!empty($division->skills))
                            <div class="mt-5 flex flex-wrap gap-2">
                                @foreach ($division->skills as $skill)
                                    <span class="rounded-md px-2.5 py-1 text-[13px]" style="background: var(--paper); color: var(--ink-soft);">{{ $skill }}</span>
                                @endforeach
                            </div>
                        @endif

                        <div class="mt-6">
                            @if ($regOpen)
                                <a href="{{ route('register') }}" class="inline-flex items-center gap-2 text-[15px] font-medium hover:underline" style="color: var(--accent);">
                                    Daftar ke divisi ini <i class="fas fa-arrow-right text-[12px] transition-transform group-hover:translate-x-1"></i>
                                </a>
                            @else
                                <button type="button" onclick="window.dispatchEvent(new CustomEvent('open-register-modal'))"
                                    class="inline-flex items-center gap-2 text-[15px] font-medium cursor-pointer" style="color: var(--ink-faint);">
                                    Pendaftaran ditutup
                                </button>
                            @endif
                        </div>
                    </article>
                @endforeach
            </div>
        @endif

    </div>
</section>
