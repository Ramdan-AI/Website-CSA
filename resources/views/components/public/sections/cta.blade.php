{{-- DAFTAR CTA: cinematic closing --}}
<section id="daftar" class="relative py-28 md:py-40 lg:py-48 bg-[#0B1B3B] text-white scroll-mt-24 overflow-hidden">
    {{-- Faint vertical hairlines (texture) --}}
    <div class="absolute inset-0 pointer-events-none opacity-50" style="background-image: linear-gradient(to right, rgba(255,255,255,0.04) 1px, transparent 1px); background-size: clamp(56px, 8vw, 96px) 100%;"></div>
    <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-[#3664AF] via-[#E8A33D]/70 to-transparent"></div>
    {{-- Ghost word --}}
    <div class="absolute inset-0 flex items-center justify-center pointer-events-none select-none overflow-hidden" aria-hidden="true">
        <p class="text-[24vw] font-extrabold leading-none tracking-[-0.05em] text-white/[0.03] whitespace-nowrap" data-parallax="-160">CSA</p>
    </div>
    <div class="absolute -top-32 right-[-160px] w-[440px] h-[440px] rounded-full bg-[radial-gradient(circle,rgba(54,100,175,0.3)_0%,transparent_70%)] pointer-events-none hidden lg:block" data-parallax="-120"></div>
    <div class="absolute bottom-[-140px] left-[-140px] w-[380px] h-[380px] rounded-full bg-[radial-gradient(circle,rgba(232,163,61,0.14)_0%,transparent_70%)] pointer-events-none hidden lg:block" data-parallax="80"></div>

    <div class="relative max-w-[1200px] mx-auto px-5 sm:px-8">
        <div class="max-w-[900px]" data-parallax="-20">
            <p class="text-[11px] sm:text-xs font-semibold text-[#E8A33D] tracking-[0.22em] uppercase mb-5 sm:mb-6 flex items-center gap-2.5" data-reveal="down">
                <span class="w-2 h-2 rounded-[2px] bg-[#E8A33D]"></span>
                Bergabung
            </p>
            <h2 class="text-[clamp(2.4rem,8vw,5.5rem)] font-extrabold leading-[0.95] tracking-[-0.04em]" data-reveal="curtain" data-reveal-delay="0.1">
                Siap menjadi bagian<br>dari cerita <span class="text-[#9DB8E8]">CSA</span>?
            </h2>
            <p class="text-[15px] sm:text-lg text-[#A9BDE4]/80 leading-relaxed mt-7 max-w-[520px]" data-reveal="fade" data-reveal-delay="0.25">
                Daftarkan dirimu dan mulai perjalanan sebagai builder dan creator — bersama {{ $members->count() }} mahasiswa kreatif lainnya.
            </p>
        </div>

        <div class="mt-10 sm:mt-12 flex flex-col sm:flex-row sm:items-center gap-4" data-reveal="up" data-reveal-delay="0.35">
            <a href="{{ route('register') }}"
                class="inline-flex items-center justify-center gap-3 px-9 min-h-[60px] bg-[#E8A33D] text-[#0B1B3B] text-sm sm:text-[15px] font-bold rounded-[2px] hover:bg-white transition-colors duration-300">
                Daftar sekarang
                <span>&rarr;</span>
            </a>
            <a href="#kepengurusan"
                class="inline-flex items-center justify-center gap-3 px-9 min-h-[60px] border border-white/15 text-white text-sm sm:text-[15px] font-semibold rounded-[2px] hover:border-[#E8A33D] hover:text-[#E8A33D] transition-colors duration-300">
                Lihat struktur kami
            </a>
        </div>

        <div class="flex flex-wrap items-center gap-x-8 gap-y-3 mt-12 sm:mt-16 pt-6 border-t border-white/10 font-mono text-[10px] uppercase tracking-[0.22em] text-[#8FA7D3]/70">
            <span>Pendaftaran terbuka tiap semester</span>
            <span class="hidden sm:inline text-white/20">/</span>
            <span>Anggota aktif &middot; {{ $members->count() }}</span>
            <span class="hidden sm:inline text-white/20">/</span>
            <span>Berdiri sejak 2017</span>
        </div>
    </div>
</section>
