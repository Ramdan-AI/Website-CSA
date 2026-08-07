{{-- PEMBINA & FOUNDER --}}
@php
    $pembina = [
        'name' => 'Adi Raharjo, S.Kom., M.M',
        'role' => 'IT MANAGER',
        'about' => 'Seorang akademisi berpengalaman dengan fokus pada pengembangan software dan teknologi informasi.',
        'education' => 'S2 Magister Manajemen - Universitas Pendidikan Indonesia',
        'experience' => '15+ tahun',
        'linkedin' => '#',
    ];
    $founders = [
        'Diansyah, S.Kom',
        'Febri Ridwansyah, S.Kom',
        'Ilman nurhakim, S.Kom',
        'M. Fauzi ramadhan, S.Kom',
        'Yanti mandasari, S.Kom',
    ];
    $pembinaPhoto = \Illuminate\Support\Facades\Storage::disk('public')->exists('pembina/jfod093842jfldswa.png')
        ? asset('storage/pembina/jfod093842jfldswa.png')
        : null;
    $monogram = fn($name) => strtoupper(collect(explode(' ', trim(strtok($name, ','))))->take(2)->map(fn ($w) => mb_substr($w, 0, 1))->join(''));
@endphp
<section id="pembina" class="relative py-20 md:py-32 bg-[#F7FAFF] scroll-mt-24 overflow-hidden">
    <div class="absolute -top-32 right-[-160px] w-[440px] h-[440px] rounded-full bg-[radial-gradient(circle,rgba(54,100,175,0.08)_0%,transparent_70%)] pointer-events-none hidden lg:block" data-parallax="-70"></div>
    <div class="absolute bottom-[-120px] left-[-140px] w-[360px] h-[360px] rounded-full bg-[radial-gradient(circle,rgba(232,163,61,0.06)_0%,transparent_70%)] pointer-events-none hidden lg:block" data-parallax="50"></div>

    <div class="relative max-w-[1200px] mx-auto px-5 sm:px-8" x-data="{ tab: 'pembina' }">
        {{-- Masthead --}}
        <div class="max-w-[820px] mb-12 sm:mb-20" data-parallax="-12">
            <p class="text-[11px] sm:text-xs font-semibold text-[#3664AF] tracking-[0.22em] uppercase mb-5 sm:mb-6" data-reveal="down">Pembina &amp; Founder</p>
            <h2 class="text-[clamp(2.2rem,7.5vw,4.5rem)] font-bold text-gray-900 leading-[0.98] tracking-[-0.04em]" data-reveal="curtain" data-reveal-delay="0.1">
                Pembina &amp;<br>Founder <span class="text-[#3664AF]">CSA</span>
            </h2>
            <p class="text-[15px] sm:text-lg text-gray-500 leading-relaxed mt-6 max-w-[560px]" data-reveal="fade" data-reveal-delay="0.25">
                Bertemu dengan para mentor dan visioner yang membangun dan membimbing perjalanan Creative Student Association.
            </p>
        </div>

        <div class="grid lg:grid-cols-[280px_1fr] gap-10 lg:gap-16">
            {{-- Type index (tabs) --}}
            <div class="lg:sticky lg:top-28 lg:self-start" data-reveal="fade" data-reveal-delay="0.2">
                <button type="button" @click="tab = 'pembina'; $nextTick(() => window.ScrollTrigger && window.ScrollTrigger.refresh())" class="w-full text-left group">
                    <div class="flex items-baseline gap-5 py-6 border-b-2 transition-colors duration-300"
                        :class="tab === 'pembina' ? 'border-gray-900' : 'border-gray-200 group-hover:border-gray-300'">
                        <span class="text-4xl font-extrabold tabular-nums transition-colors duration-300"
                            :class="tab === 'pembina' ? 'text-[#3664AF]' : 'text-gray-200 group-hover:text-gray-300'">01</span>
                        <span>
                            <span class="block text-2xl font-bold tracking-tight transition-colors duration-300"
                                :class="tab === 'pembina' ? 'text-gray-900' : 'text-gray-300 group-hover:text-gray-500'">Pembina</span>
                            <span class="block text-[11px] font-semibold uppercase tracking-[0.2em] mt-1 transition-colors duration-300"
                                :class="tab === 'pembina' ? 'text-[#3664AF]' : 'text-gray-400'">Profil resmi</span>
                        </span>
                    </div>
                </button>
                <button type="button" @click="tab = 'founders'; $nextTick(() => window.ScrollTrigger && window.ScrollTrigger.refresh())" class="w-full text-left group">
                    <div class="flex items-baseline gap-5 py-6 border-b-2 transition-colors duration-300"
                        :class="tab === 'founders' ? 'border-gray-900' : 'border-gray-200 group-hover:border-gray-300'">
                        <span class="text-4xl font-extrabold tabular-nums transition-colors duration-300"
                            :class="tab === 'founders' ? 'text-[#3664AF]' : 'text-gray-200 group-hover:text-gray-300'">02</span>
                        <span>
                            <span class="block text-2xl font-bold tracking-tight transition-colors duration-300"
                                :class="tab === 'founders' ? 'text-gray-900' : 'text-gray-300 group-hover:text-gray-500'">Founders</span>
                            <span class="block text-[11px] font-semibold uppercase tracking-[0.2em] mt-1 transition-colors duration-300"
                                :class="tab === 'founders' ? 'text-[#3664AF]' : 'text-gray-400'">Tim pendiri</span>
                        </span>
                    </div>
                </button>
                <p class="text-xs text-gray-400 leading-relaxed mt-6 hidden lg:block">
                    Mereka yang merintis dan membimbing — dari fondasi organisasi hingga pembinaan yang berkelanjutan.
                </p>
            </div>

            {{-- Panels --}}
            <div class="min-w-0">
                {{-- Pembina spread --}}
                <div x-show="tab === 'pembina'" x-transition:enter="transition ease-out duration-400" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                    <div class="grid lg:grid-cols-[minmax(0,1.1fr)_minmax(0,1fr)] gap-8 sm:gap-10 lg:gap-16 items-center">
                        <div class="relative rounded-[28px] sm:rounded-[36px] overflow-hidden bg-[#EEF3FB] aspect-[4/5] shadow-[0_36px_90px_-44px_rgba(54,100,175,0.5)]" data-reveal="blur" data-reveal-delay="0.1">
                            @if ($pembinaPhoto)
                                <img src="{{ $pembinaPhoto }}" alt="{{ $pembina['name'] }}" class="w-full h-[112%] object-cover" data-parallax="-40" loading="lazy" decoding="async">
                            @else
                                <span class="absolute inset-0 flex items-center justify-center text-8xl font-extrabold text-[#3664AF]/10 select-none">{{ $monogram($pembina['name']) }}</span>
                            @endif
                        </div>

                        <div data-reveal="fade" data-reveal-delay="0.25">
                            <p class="text-[11px] font-semibold uppercase tracking-[0.24em] text-[#E8A33D]">{{ $pembina['role'] }}</p>
                            <h3 class="text-[clamp(1.9rem,5vw,3.25rem)] font-bold text-gray-900 leading-[1.04] tracking-[-0.03em] mt-4">
                                Adi Raharjo,<br>S.Kom., M.M
                            </h3>
                            <div class="w-12 h-[3px] bg-[#3664AF] mt-7"></div>
                            <p class="text-[15px] sm:text-base text-gray-600 leading-relaxed mt-6 max-w-[520px]">{{ $pembina['about'] }}</p>

                            <dl class="mt-9">
                                <div class="grid grid-cols-[140px_1fr] gap-6 py-5 border-t border-gray-200/80 items-baseline">
                                    <dt class="text-[11px] font-bold uppercase tracking-[0.18em] text-gray-400">Pendidikan</dt>
                                    <dd class="text-[15px] font-semibold text-gray-900">{{ $pembina['education'] }}</dd>
                                </div>
                                <div class="grid grid-cols-[140px_1fr] gap-6 py-5 border-t border-gray-200/80 items-baseline">
                                    <dt class="text-[11px] font-bold uppercase tracking-[0.18em] text-gray-400">Pengalaman</dt>
                                    <dd class="text-3xl font-bold text-[#3664AF] tracking-tight">{{ $pembina['experience'] }}</dd>
                                </div>
                            </dl>

                            <div class="mt-9">
                                <a href="{{ $pembina['linkedin'] }}" target="_blank" rel="noopener noreferrer"
                                    class="inline-flex items-center gap-2.5 px-7 py-4 bg-[#3664AF] text-white text-sm font-semibold rounded-xl hover:bg-[#2C538F] active:scale-[0.98] transition-all duration-200">
                                    <i class="fab fa-linkedin-in text-[13px]"></i>
                                    LinkedIn
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Founders wall --}}
                <div x-show="tab === 'founders'" x-cloak x-transition:enter="transition ease-out duration-400" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                    <div class="grid grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6" data-reveal-stagger>
                        @foreach ($founders as $i => $founder)
                            <div class="group relative aspect-[4/5] rounded-[24px] sm:rounded-[28px] overflow-hidden bg-[#EEF3FB] border border-[#3664AF]/10 hover:border-[#3664AF]/30 hover:shadow-[0_28px_64px_-36px_rgba(54,100,175,0.45)] hover:-translate-y-1 transition-all duration-300">
                                <span class="absolute inset-0 flex items-center justify-center text-[clamp(5rem,14vw,8.5rem)] font-extrabold text-[#3664AF]/10 group-hover:text-[#3664AF]/20 group-hover:scale-110 transition-all duration-500 select-none">{{ $monogram($founder) }}</span>
                                <div class="absolute bottom-0 inset-x-0 p-6 sm:p-7">
                                    <p class="text-[10px] font-bold uppercase tracking-[0.24em] text-[#3664AF]/70">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }} &middot; Founder</p>
                                    <h3 class="text-lg sm:text-xl font-bold text-gray-900 mt-2 leading-snug">{{ $founder }}</h3>
                                </div>
                            </div>
                        @endforeach

                        <a href="{{ route('register') }}"
                            class="group relative aspect-[4/5] rounded-[24px] sm:rounded-[28px] overflow-hidden bg-[#0B1B3B] flex flex-col justify-between p-6 sm:p-7 hover:bg-[#122A52] transition-colors duration-300">
                            <p class="text-[10px] font-bold uppercase tracking-[0.24em] text-[#9DB8E8]">Generasi berikutnya</p>
                            <div>
                                <p class="text-xl sm:text-2xl font-bold text-white leading-snug">Jadilah bagian dari cerita CSA.</p>
                                <p class="inline-flex items-center gap-2.5 text-[13px] font-semibold text-[#E8A33D] mt-5">
                                    Daftar sekarang
                                    <i class="fas fa-arrow-right text-[11px] group-hover:translate-x-1 transition-transform duration-300"></i>
                                </p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
