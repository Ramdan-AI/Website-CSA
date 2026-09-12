<section id="divisi" class="relative overflow-hidden bg-[#f7f8fa] py-24 md:py-32 lg:py-36 scroll-mt-24">
    <div class="mx-auto max-w-[1240px] px-5 sm:px-8">

        <div class="mb-14 max-w-[720px] md:mb-18">
            <div class="mb-5 inline-flex items-center gap-2 text-[11px] font-medium uppercase tracking-[0.18em] text-[#3664AF]"
                data-reveal="down">
                <span class="h-1.5 w-1.5 rounded-full bg-[#3664AF]"></span>
                Divisi
            </div>

            <h2 class="text-[clamp(2.25rem,5vw,4rem)] font-semibold leading-[1.02] tracking-[-0.055em] text-[#111318]"
                data-reveal="curtain" data-reveal-delay="0.1">
                Bidang yang kami
                <span class="text-[#3664AF]">fokuskan.</span>
            </h2>

            <p class="mt-6 max-w-[620px] text-[15px] leading-7 text-[#6b7079] sm:text-[17px]" data-reveal="fade"
                data-reveal-delay="0.2">
                {{ $divisions->count() }} jalur pengembangan yang saling melengkapi —
                setiap anggota menemukan ruang untuk berkembang sesuai minat dan potensinya.
            </p>
        </div>

        @if ($divisions->isEmpty())

            <div class="rounded-[24px] border border-black/[0.06] bg-white p-16 text-center">
                <p class="text-sm text-[#8b9099]">
                    Belum ada divisi yang tersedia.
                </p>
            </div>
        @else
            <div class="grid gap-4 md:grid-cols-2 lg:gap-5" data-reveal-stagger>

                @foreach ($divisions as $division)
                    @php
                        $divisionImg = $imageUrl($division, 320);

                        $regOpen = Cache::remember(
                            'setting.registration_open',
                            60,
                            fn() => \App\Models\Setting::first()?->registration_open ?? false,
                        );
                    @endphp

                    <article
                        class="group relative flex min-h-[340px] flex-col overflow-hidden
                           rounded-[24px]
                           border border-black/[0.06]
                           bg-white
                           p-7
                           shadow-[0_4px_20px_-12px_rgba(0,0,0,0.12)]
                           transition-[transform,box-shadow,border-color] duration-500
                           hover:-translate-y-1
                           hover:border-[#3664AF]/15
                           hover:shadow-[0_24px_60px_-28px_rgba(20,40,80,0.22)]
                           sm:p-8">

                        <div class="flex items-start justify-between gap-6">

                            @if ($divisionImg)
                                <div
                                    class="flex h-14 w-14 shrink-0 items-center justify-center
                                       rounded-2xl
                                       bg-[#f5f7fb]
                                       ring-1 ring-black/[0.04]
                                       transition-transform duration-500
                                       group-hover:scale-105">

                                    <img src="{{ $divisionImg }}" alt="{{ $division->name }}"
                                        class="h-9 w-9 object-contain" loading="lazy" decoding="async">
                                </div>
                            @endif

                            <span class="pt-1 text-[11px] font-medium text-[#9a9ea6]">
                                {{ $division->members_count ?? 0 }} anggota
                            </span>
                        </div>

                        <div class="mt-7">
                            <h3
                                class="text-[24px] font-semibold tracking-[-0.035em] text-[#111318]
                                   transition-colors duration-300
                                   group-hover:text-[#3664AF]">
                                {{ $division->name }}
                            </h3>

                            <p class="mt-3 max-w-[560px] text-[14px] leading-6 text-[#737780] sm:text-[15px]">
                                {{ $division->description ?? 'Tidak ada deskripsi' }}
                            </p>
                        </div>

                        @if (!empty($division->skills))
                            <div class="mt-6 flex flex-wrap gap-2">
                                @foreach ($division->skills as $skill)
                                    <span
                                        class="rounded-full bg-[#f5f6f8] px-3 py-1.5
                                           text-[10px] font-medium tracking-[0.04em] text-[#747982]
                                           transition-colors duration-300
                                           group-hover:bg-[#f0f4fb]">
                                        {{ $skill }}
                                    </span>
                                @endforeach
                            </div>
                        @endif

                        <div class="mt-auto flex items-center justify-between gap-4 pt-8">

                            <span class="text-[12px] text-[#a0a4ab]">
                                {{ $division->members_count ?? 0 }} orang sedang berkembang di sini
                            </span>

                            @if ($regOpen)
                                <a href="{{ route('register') }}"
                                    class="inline-flex shrink-0 items-center gap-2
                                       rounded-full
                                       bg-[#111318]
                                       px-4 py-2.5
                                       text-[12px] font-medium text-white
                                       transition-all duration-300
                                       hover:bg-[#3664AF]
                                       active:scale-[0.97]">

                                    Bergabung

                                    <svg class="h-3.5 w-3.5 transition-transform duration-300 group-hover:translate-x-0.5"
                                        viewBox="0 0 16 16" fill="none" aria-hidden="true">
                                        <path d="M3 8h9M8.5 4.5 12 8l-3.5 3.5" stroke="currentColor" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </a>
                            @else
                                <button type="button"
                                    onclick="window.dispatchEvent(new CustomEvent('open-register-modal'))"
                                    class="inline-flex shrink-0 cursor-pointer items-center gap-2
                                       rounded-full
                                       bg-[#111318]
                                       px-4 py-2.5
                                       text-[12px] font-medium text-white
                                       transition-all duration-300
                                       hover:bg-[#3664AF]
                                       active:scale-[0.97]">

                                    Bergabung

                                    <svg class="h-3.5 w-3.5 transition-transform duration-300 group-hover:translate-x-0.5"
                                        viewBox="0 0 16 16" fill="none" aria-hidden="true">
                                        <path d="M3 8h9M8.5 4.5 12 8l-3.5 3.5" stroke="currentColor" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </button>
                            @endif

                        </div>
                    </article>
                @endforeach

            </div>

        @endif

    </div>
    ```

</section>
