{{-- KEPENGURUSAN & ANGGOTA --}}
<section id="kepengurusan" class="relative py-20 md:py-28 bg-white scroll-mt-24 overflow-hidden">

    @php
        $roleNameOf = fn ($m) =>
            $m->position?->name ??
            $m->division?->name ??
            'Anggota';

        $rosterLabelOf = fn ($m) => match ($m->position?->name) {
            'Ketua Divisi' => 'Koordinator',
            'Wakil Ketua Divisi' => 'Wakil Koordinator',
            default => $m->position?->name ?? 'Anggota',
        };

        $pengurus = $pengurusInti->concat($koordinator)->values();

        /*
         * Ambil foto dari database.
         * Mendukung beberapa kemungkinan nama field foto tanpa
         * mengubah struktur database.
         */
        $memberImageOf = function ($member) {
            $possibleFields = [
                'image',
                'photo',
                'profile_photo',
                'profile_image',
                'avatar',
                'avatar_path',
            ];

            foreach ($possibleFields as $field) {
                $value = $member->getAttribute($field);

                if (!empty($value)) {
                    return \Illuminate\Support\Facades\Storage::disk('public')->url($value);
                }
            }

            return asset('storage/profile/profile-placeholder.png');
        };

        $regOpen = Cache::remember(
            'setting.registration_open',
            60,
            fn () => \App\Models\Setting::first()?->registration_open ?? false
        );
    @endphp

    <div class="relative max-w-[1200px] mx-auto px-5 sm:px-8">

        {{-- HEADER --}}
        <div class="max-w-3xl mb-12 md:mb-16" data-parallax="-10">

            <div
                class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-[#EEF3FB] text-[#3664AF] text-[10px] sm:text-[11px] font-bold uppercase tracking-[0.18em] mb-5"
                data-reveal="down"
            >
                <span class="w-1.5 h-1.5 rounded-full bg-[#3664AF]"></span>
                Struktur Organisasi
            </div>

            <h2
                class="text-[clamp(2.4rem,7vw,4.8rem)] font-bold text-gray-900 leading-[0.95] tracking-[-0.055em]"
                data-reveal="curtain"
                data-reveal-delay="0.1"
            >
                Mereka yang
                <span class="text-[#3664AF]">menggerakkan</span>
                CSA.
            </h2>

            <p
                class="text-[15px] sm:text-lg text-gray-500 leading-relaxed mt-6 max-w-[600px]"
                data-reveal="fade"
                data-reveal-delay="0.2"
            >
                Kenali orang-orang di balik kegiatan, program, dan komunitas
                yang membuat CSA terus berkembang.
            </p>

        </div>

        @if ($members->isEmpty())

            <div class="rounded-3xl bg-[#F7F9FC] border border-gray-100 py-20 text-center">
                <p class="text-sm text-gray-400">
                    Belum ada pengurus maupun anggota terdaftar.
                </p>
            </div>

        @else

            {{-- PIMPINAN --}}
            <div class="grid md:grid-cols-2 gap-5 md:gap-6">

                {{-- KETUA --}}
                @if ($ketua)
                    <article
                        class="group relative overflow-hidden rounded-[28px] bg-[#0B1B3B] min-h-[470px] sm:min-h-[520px]"
                        data-reveal="fade"
                    >

                        <img
                            src="{{ $memberImageOf($ketua) }}"
                            alt="{{ $ketua->name }}"
                            class="absolute inset-0 w-full h-full object-cover object-center transition-transform duration-700 ease-out group-hover:scale-[1.04]"
                            onerror="this.onerror=null;this.src='{{ asset('storage/profile-placeholder.png') }}';"
                        >

                        <div class="absolute inset-0 bg-gradient-to-t from-[#071329] via-[#071329]/35 to-transparent"></div>

                        <div class="absolute top-5 left-5">
                            <span class="inline-flex items-center rounded-full bg-white/90 backdrop-blur-sm px-3 py-1.5 text-[10px] font-bold uppercase tracking-[0.16em] text-[#0B1B3B]">
                                Ketua
                            </span>
                        </div>

                        <div class="absolute top-5 right-5">
                            <div class="w-9 h-9 rounded-full bg-white/15 backdrop-blur-md flex items-center justify-center text-white">
                                <i class="fas fa-arrow-up-right-from-square text-[11px]"></i>
                            </div>
                        </div>

                        <div class="absolute left-5 right-5 bottom-5 sm:left-7 sm:right-7 sm:bottom-7 text-white">

                            <div class="flex items-end justify-between gap-4">

                                <div class="min-w-0">

                                    <p class="text-[10px] uppercase tracking-[0.18em] text-white/60 mb-2">
                                        Memimpin arah & strategi
                                    </p>

                                    <h3 class="text-[clamp(1.8rem,4vw,2.8rem)] font-bold tracking-[-0.04em] leading-none truncate">
                                        {{ $ketua->name }}
                                    </h3>

                                    @if ($ketua->division?->name)
                                        <p class="text-sm text-white/65 mt-2">
                                            {{ $ketua->division->name }}
                                        </p>
                                    @endif

                                </div>

                                <div class="shrink-0 flex items-center gap-2">
                                    @include('components.public.partials.member-socials', [
                                        'member' => $ketua
                                    ])
                                </div>

                            </div>

                        </div>

                    </article>
                @endif


                {{-- WAKIL --}}
                @if ($wakil)
                    <article
                        class="group relative overflow-hidden rounded-[28px] bg-[#EEF3FB] min-h-[470px] sm:min-h-[520px]"
                        data-reveal="fade"
                        data-reveal-delay="0.1"
                    >

                        <img
                            src="{{ $memberImageOf($wakil) }}"
                            alt="{{ $wakil->name }}"
                            class="absolute inset-0 w-full h-full object-cover object-center transition-transform duration-700 ease-out group-hover:scale-[1.04]"
                            onerror="this.onerror=null;this.src='{{ asset('storage/profile-placeholder.png') }}';"
                        >

                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/15 to-transparent"></div>

                        <div class="absolute top-5 left-5">
                            <span class="inline-flex items-center rounded-full bg-white/90 backdrop-blur-sm px-3 py-1.5 text-[10px] font-bold uppercase tracking-[0.16em] text-[#3664AF]">
                                Wakil Ketua
                            </span>
                        </div>

                        <div class="absolute top-5 right-5">
                            <div class="w-9 h-9 rounded-full bg-white/20 backdrop-blur-md flex items-center justify-center text-white">
                                <i class="fas fa-arrow-up-right-from-square text-[11px]"></i>
                            </div>
                        </div>

                        <div class="absolute left-5 right-5 bottom-5 sm:left-7 sm:right-7 sm:bottom-7 text-white">

                            <div class="flex items-end justify-between gap-4">

                                <div class="min-w-0">

                                    <p class="text-[10px] uppercase tracking-[0.18em] text-white/65 mb-2">
                                        Operasional & pendampingan
                                    </p>

                                    <h3 class="text-[clamp(1.8rem,4vw,2.8rem)] font-bold tracking-[-0.04em] leading-none truncate">
                                        {{ $wakil->name }}
                                    </h3>

                                    @if ($wakil->division?->name)
                                        <p class="text-sm text-white/65 mt-2">
                                            {{ $wakil->division->name }}
                                        </p>
                                    @endif

                                </div>

                                <div class="shrink-0 flex items-center gap-2">
                                    @include('components.public.partials.member-socials', [
                                        'member' => $wakil
                                    ])
                                </div>

                            </div>

                        </div>

                    </article>
                @endif

            </div>


            {{-- PENGURUS --}}
            @if ($pengurus->isNotEmpty())

                <div class="mt-16 md:mt-24">

                    <div class="flex items-end justify-between gap-5 mb-6">

                        <div>
                            <p class="text-[11px] font-bold uppercase tracking-[0.2em] text-[#3664AF] mb-2">
                                Tim inti
                            </p>

                            <h3 class="text-2xl sm:text-3xl font-bold tracking-[-0.035em] text-gray-900">
                                Pengurus
                            </h3>
                        </div>

                        <span class="text-sm text-gray-400">
                            {{ $pengurus->count() }} orang
                        </span>

                    </div>


                    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">

                        @foreach ($pengurus as $member)

                            <article
                                class="group relative overflow-hidden rounded-[22px] bg-[#F5F7FA] hover:bg-[#0B1B3B] transition-all duration-300"
                                data-reveal="fade"
                            >

                                <div class="aspect-[4/3] overflow-hidden">

                                    <img
                                        src="{{ $memberImageOf($member) }}"
                                        alt="{{ $member->name }}"
                                        class="w-full h-full object-cover transition-transform duration-500 ease-out group-hover:scale-[1.04]"
                                        onerror="this.onerror=null;this.src='{{ asset('storage/profile-placeholder.png') }}';"
                                    >

                                </div>

                                <div class="p-5">

                                    <div class="flex items-start justify-between gap-4">

                                        <div class="min-w-0">

                                            <p class="text-[10px] font-bold uppercase tracking-[0.16em] text-[#3664AF] group-hover:text-[#9DB8E8] mb-2 transition-colors">
                                                {{ $rosterLabelOf($member) }}
                                            </p>

                                            <h4 class="text-lg sm:text-xl font-bold tracking-[-0.025em] text-gray-900 group-hover:text-white transition-colors truncate">
                                                {{ $member->name }}
                                            </h4>

                                            @if ($member->division?->name)
                                                <p class="text-xs text-gray-400 group-hover:text-white/55 mt-1.5 transition-colors truncate">
                                                    {{ $member->division->name }}
                                                </p>
                                            @endif

                                        </div>

                                        <div class="w-8 h-8 shrink-0 rounded-full bg-white group-hover:bg-white/10 flex items-center justify-center transition-colors">
                                            <i class="fas fa-arrow-up-right text-[10px] text-gray-400 group-hover:text-white"></i>
                                        </div>

                                    </div>

                                </div>

                            </article>

                        @endforeach

                    </div>

                </div>

            @endif


            {{-- ANGGOTA --}}
            @if ($anggotaPreview->isNotEmpty())

                <div class="mt-16 md:mt-24">

                    <div class="flex items-end justify-between gap-5 mb-6">

                        <div>
                            <p class="text-[11px] font-bold uppercase tracking-[0.2em] text-[#3664AF] mb-2">
                                Community
                            </p>

                            <h3 class="text-2xl sm:text-3xl font-bold tracking-[-0.035em] text-gray-900">
                                Anggota CSA
                            </h3>
                        </div>

                        <div class="hidden sm:flex items-center gap-2 text-xs text-gray-400">
                            <span>Geser</span>
                            <i class="fas fa-arrow-right text-[10px]"></i>
                        </div>

                    </div>


                    <div
                        class="flex gap-4 overflow-x-auto no-scrollbar snap-x snap-mandatory pb-3"
                        data-reveal-stagger
                    >

                        @foreach ($anggotaPreview as $member)

                            <article
                                class="group relative shrink-0 w-[220px] sm:w-[250px] snap-start overflow-hidden rounded-[24px] bg-[#F5F7FA]"
                            >

                                <div class="aspect-[4/5] overflow-hidden">

                                    <img
                                        src="{{ $memberImageOf($member) }}"
                                        alt="{{ $member->name }}"
                                        class="w-full h-full object-cover transition-transform duration-500 ease-out group-hover:scale-[1.04]"
                                        onerror="this.onerror=null;this.src='{{ asset('storage/profile-placeholder.png') }}';"
                                    >

                                </div>

                                <div class="absolute inset-x-0 bottom-0 p-5 pt-16 bg-gradient-to-t from-black/75 to-transparent text-white">

                                    <p class="text-[9px] font-bold uppercase tracking-[0.16em] text-white/60 mb-1.5">
                                        {{ $roleNameOf($member) }}
                                    </p>

                                    <h4 class="text-lg font-bold tracking-[-0.025em] leading-tight">
                                        {{ $member->name }}
                                    </h4>

                                </div>

                            </article>

                        @endforeach


                        {{-- JOIN CARD --}}
                        @if ($regOpen)

                            <a
                                href="{{ route('register') }}"
                                class="group shrink-0 w-[220px] sm:w-[250px] aspect-[4/5] snap-start rounded-[24px] bg-[#0B1B3B] p-6 flex flex-col justify-between overflow-hidden transition-transform duration-300 hover:-translate-y-1"
                            >

                                <div class="flex items-center justify-between">

                                    <span class="text-[10px] font-bold uppercase tracking-[0.16em] text-[#9DB8E8]">
                                        Open Recruitment
                                    </span>

                                    <span class="w-9 h-9 rounded-full bg-white/10 flex items-center justify-center text-white transition-transform duration-300 group-hover:translate-x-1 group-hover:-translate-y-1">
                                        <i class="fas fa-arrow-up-right text-[11px]"></i>
                                    </span>

                                </div>

                                <div>

                                    <p class="text-3xl font-bold tracking-[-0.04em] text-white leading-tight">
                                        Jadi bagian
                                        <span class="text-[#9DB8E8]">CSA.</span>
                                    </p>

                                    <p class="text-sm text-white/50 mt-3 leading-relaxed">
                                        Tumbuh, berkarya, dan buat sesuatu bersama.
                                    </p>

                                </div>

                            </a>

                        @else

                            <button
                                type="button"
                                onclick="window.dispatchEvent(new CustomEvent('open-register-modal'))"
                                class="group text-left shrink-0 w-[220px] sm:w-[250px] aspect-[4/5] snap-start rounded-[24px] bg-[#0B1B3B] p-6 flex flex-col justify-between overflow-hidden transition-transform duration-300 hover:-translate-y-1"
                            >

                                <div class="flex items-center justify-between">

                                    <span class="text-[10px] font-bold uppercase tracking-[0.16em] text-[#9DB8E8]">
                                        Community
                                    </span>

                                    <span class="w-9 h-9 rounded-full bg-white/10 flex items-center justify-center text-white transition-transform duration-300 group-hover:translate-x-1 group-hover:-translate-y-1">
                                        <i class="fas fa-arrow-up-right text-[11px]"></i>
                                    </span>

                                </div>

                                <div>

                                    <p class="text-3xl font-bold tracking-[-0.04em] text-white leading-tight">
                                        Jadi bagian
                                        <span class="text-[#9DB8E8]">CSA.</span>
                                    </p>

                                    <p class="text-sm text-white/50 mt-3 leading-relaxed">
                                        Tumbuh, berkarya, dan buat sesuatu bersama.
                                    </p>

                                </div>

                            </button>

                        @endif

                    </div>

                </div>

            @endif


            {{-- LINK SELURUH ANGGOTA --}}
            <div class="flex justify-center mt-12 md:mt-16">

                <a
                    href="{{ route('anggota') }}"
                    class="group inline-flex items-center gap-3 px-5 py-3 rounded-full border border-gray-200 text-[11px] font-bold uppercase tracking-[0.16em] text-gray-600 hover:bg-[#0B1B3B] hover:border-[#0B1B3B] hover:text-white transition-all duration-300"
                    data-reveal="fade"
                >
                    Lihat seluruh anggota

                    <span class="w-6 h-6 rounded-full bg-gray-100 group-hover:bg-white/10 flex items-center justify-center transition-colors">
                        <i class="fas fa-arrow-right text-[9px] transition-transform duration-300 group-hover:translate-x-0.5"></i>
                    </span>
                </a>

            </div>

        @endif

    </div>

</section>