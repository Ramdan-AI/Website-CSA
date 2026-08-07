{{-- STATS BAND (pembatas hero → tentang) --}}
<section class="bg-white"
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
    <div class="max-w-[1200px] mx-auto px-5 sm:px-8 pb-16 md:pb-24">
        <div class="relative rounded-[28px] sm:rounded-[36px] bg-[#0B1B3B] overflow-hidden shadow-[0_40px_90px_-45px_rgba(11,27,59,0.6)]" data-parallax="-16">
            {{-- Faint vertical hairlines (texture, not dots) --}}
            <div class="absolute inset-0 pointer-events-none opacity-60" style="background-image: linear-gradient(to right, rgba(255,255,255,0.04) 1px, transparent 1px); background-size: clamp(56px, 8vw, 96px) 100%;"></div>
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-[#3664AF] via-[#E8A33D]/70 to-transparent"></div>

            <div class="relative grid lg:grid-cols-[1fr_1.4fr] gap-10 lg:gap-16 px-6 sm:px-12 lg:px-16 py-12 sm:py-16 lg:py-20" data-parallax="12">
                {{-- Statement --}}
                <div class="flex flex-col justify-center">
                    <p class="text-[11px] sm:text-xs font-semibold text-[#9DB8E8] tracking-[0.22em] uppercase mb-4 flex items-center gap-2.5" data-reveal="down">
                        Mengenal CSA Lebih Dekat
                    </p>
                    <h2 class="text-[clamp(1.55rem,4vw,2.5rem)] font-bold text-white leading-[1.15] tracking-[-0.02em]" data-reveal="curtain" data-reveal-delay="0.1">
                        Tempat mahasiswa belajar dan berkembang bersama
                    </h2>
                    <p class="text-sm sm:text-[15px] text-[#A9BDE4]/80 leading-relaxed mt-4 max-w-[420px]" data-reveal="fade" data-reveal-delay="0.25">
                        CSA menjadi wadah bagi mahasiswa STMIK Mardira Indonesia untuk belajar, berkolaborasi, dan mengembangkan kemampuan melalui berbagai kegiatan organisasi.
                    </p>
                </div>

                {{-- Stats --}}
                <div class="flex flex-col justify-center" data-reveal-stagger>
                    <div class="py-6 sm:py-7 first:pt-0 last:pb-0 border-t border-white/10 first:border-t-0">
                        <div class="flex items-baseline justify-between gap-6">
                            <p class="text-5xl sm:text-6xl font-semibold tracking-tight text-white tabular-nums">
                                <span x-ref="anggota">0</span><span class="text-[#E8A33D]">+</span>
                            </p>
                            <div class="text-right">
                                <p class="text-[11px] font-semibold uppercase tracking-[0.2em] text-white/65">Anggota</p>
                                <p class="text-xs text-[#8FA7D3]/60 mt-1">Mahasiswa yang aktif mengikuti kegiatan dan pengembangan diri.</p>
                            </div>
                        </div>
                    </div>
                    <div class="py-6 sm:py-7 first:pt-0 last:pb-0 border-t border-white/10 first:border-t-0">
                        <div class="flex items-baseline justify-between gap-6">
                            <p class="text-5xl sm:text-6xl font-semibold tracking-tight text-white tabular-nums">
                                <span x-ref="divisi">0</span>
                            </p>
                            <div class="text-right">
                                <p class="text-[11px] font-semibold uppercase tracking-[0.2em] text-white/65">Divisi</p>
                                <p class="text-xs text-[#8FA7D3]/60 mt-1">Programming dan Design sebagai ruang belajar sesuai minat.</p>
                            </div>
                        </div>
                    </div>
                    <div class="py-6 sm:py-7 first:pt-0 last:pb-0 border-t border-white/10 first:border-t-0">
                        <div class="flex items-baseline justify-between gap-6">
                            <p class="text-5xl sm:text-6xl font-semibold tracking-tight text-white tabular-nums">
                                <span x-ref="pengurus">0</span><span class="text-[#E8A33D]">+</span>
                            </p>
                            <div class="text-right">
                                <p class="text-[11px] font-semibold uppercase tracking-[0.2em] text-white/65">Pengurus</p>
                                <p class="text-xs text-[#8FA7D3]/60 mt-1">Mahasiswa yang mengelola berbagai kegiatan organisasi.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
