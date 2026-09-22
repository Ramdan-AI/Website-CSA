{{-- Stats --}}
<section class="bg-white">
    <div class="max-w-[1200px] mx-auto px-5 sm:px-8 pb-14 sm:pb-20"
        x-data="{
            init() {
                const io = new IntersectionObserver((entries) => {
                    if (!entries[0].isIntersecting) return;
                    io.disconnect();
                    const opts = { duration: 2, separator: '' };
                    new countUp.CountUp(this.$refs.anggota, {{ $statAnggota }}, opts).start();
                    new countUp.CountUp(this.$refs.divisi, {{ $statDivisi }}, opts).start();
                    new countUp.CountUp(this.$refs.pengurus, {{ $statPengurus }}, opts).start();
                }, { threshold: 0.35 });
                io.observe(this.$el);
            }
        }">
        <div class="border-t border-gray-200 pt-10 sm:pt-12">
            <div class="grid lg:grid-cols-[1fr_1.2fr] gap-8 lg:gap-16">
                <div>
                    <h2 class="text-[22px] sm:text-[26px] font-bold text-gray-900 leading-[1.3]">
                        CSA dalam angka
                    </h2>
                    <p class="text-[15px] text-gray-600 leading-[1.7] mt-3 max-w-[420px]">
                        Data anggota dan pengurus yang aktif sampai hari ini.
                    </p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 sm:gap-4">
                    @foreach ([
                        ['anggota', $statAnggota, '+', 'Anggota aktif'],
                        ['divisi', $statDivisi, '', 'Divisi'],
                        ['pengurus', $statPengurus, '+', 'Pengurus'],
                    ] as [$ref, $value, $suffix, $label])
                        <div>
                            <p class="text-[36px] sm:text-[40px] font-bold text-gray-900 tabular-nums leading-none">
                                <span x-ref="{{ $ref }}">0</span>@if($suffix)<span class="text-[#1a73e8]">{{ $suffix }}</span>@endif
                            </p>
                            <p class="text-[14px] text-gray-600 mt-2">{{ $label }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
