{{-- Divisi --}}
<section id="divisi" class="bg-[#f8f9fa] border-y border-gray-200 scroll-mt-16">
    <div class="max-w-[1200px] mx-auto px-5 sm:px-8 py-16 sm:py-20">

        <div class="max-w-[680px] mb-12">
            <h2 class="text-[clamp(1.6rem,3.6vw,2.25rem)] font-bold text-gray-900 leading-[1.25]">
                Pilih divisi sesuai minatmu
            </h2>
            <p class="text-[16px] text-gray-600 leading-[1.75] mt-4">
                CSA punya {{ $divisions->count() }} divisi. Setiap divisi punya fokus dan cara belajar sendiri.
            </p>
        </div>

        @if ($divisions->isEmpty())
            <div class="rounded-xl border border-gray-200 bg-white p-14 text-center">
                <p class="text-[15px] text-gray-500">Belum ada divisi yang tersedia.</p>
            </div>
        @else
            <div class="grid gap-5 md:grid-cols-2">
                @foreach ($divisions as $division)
                    @php
                        $divisionImg = $imageUrl($division, 320);
                        $regOpen = Cache::remember(
                            'setting.registration_open',
                            60,
                            fn() => \App\Models\Setting::first()?->registration_open ?? false,
                        );
                    @endphp

                    <div class="rounded-xl border border-gray-200 bg-white p-6">
                        <div class="flex items-center gap-4">
                            @if ($divisionImg)
                                <span class="w-11 h-11 shrink-0 rounded-lg bg-gray-100 flex items-center justify-center">
                                    <img src="{{ $divisionImg }}" alt="{{ $division->name }}"
                                        width="32" height="32" class="w-8 h-8 object-contain" loading="lazy" decoding="async">
                                </span>
                            @endif
                            <div>
                                <h3 class="text-[18px] font-semibold text-gray-900">{{ $division->name }}</h3>
                                <p class="text-[13px] text-gray-500">{{ $division->members_count ?? 0 }} anggota</p>
                            </div>
                        </div>

                        <p class="mt-4 text-[15px] leading-[1.7] text-gray-600">
                            {{ $division->description ?? 'Belum ada deskripsi.' }}
                        </p>

                        @if (!empty($division->skills))
                            <div class="mt-5 flex flex-wrap gap-2">
                                @foreach ($division->skills as $skill)
                                    <span class="rounded-md bg-gray-100 px-2.5 py-1 text-[13px] text-gray-600">{{ $skill }}</span>
                                @endforeach
                            </div>
                        @endif

                        <div class="mt-6">
                            @if ($regOpen)
                                <a href="{{ route('register') }}"
                                    class="inline-flex items-center gap-2 text-[15px] font-medium text-[#1a73e8] hover:underline">
                                    Daftar ke divisi ini <i class="fas fa-arrow-right text-[12px]"></i>
                                </a>
                            @else
                                <button type="button" onclick="window.dispatchEvent(new CustomEvent('open-register-modal'))"
                                    class="inline-flex items-center gap-2 text-[15px] font-medium text-gray-400 cursor-pointer">
                                    Pendaftaran ditutup
                                </button>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

    </div>
</section>
