<section class="relative overflow-hidden bg-white pt-24 pb-32">
    {{-- MagicUI Grid Background --}}
    <div class="absolute inset-0 -z-10 h-full w-full bg-white bg-[linear-gradient(to_right,#8080800a_1px,transparent_1px),linear-gradient(to_bottom,#8080800a_1px,transparent_1px)] bg-[size:14px_24px]">
        <div class="absolute left-0 right-0 top-0 -z-10 m-auto h-[310px] w-[310px] rounded-full bg-blue-500 opacity-[0.15] blur-[100px]"></div>
    </div>

    <div class="max-w-7xl mx-auto px-6 text-center">
        {{-- Animated Badge --}}
        <div class="mx-auto mb-8 inline-flex items-center gap-2 rounded-full border border-gray-200 bg-white/50 backdrop-blur-sm px-3 py-1.5 text-xs font-medium text-gray-600 shadow-sm transition-colors hover:bg-gray-50">
            <span class="relative flex h-2 w-2">
              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
              <span class="relative inline-flex rounded-full h-2 w-2 bg-blue-500"></span>
            </span>
            <span>Creative Student Association</span>
            <span class="h-4 w-px bg-gray-200"></span>
            <span class="text-blue-600">STMIK Mardira Indonesia</span>
        </div>

        {{-- Headline --}}
        <h1 class="mx-auto max-w-4xl text-5xl font-extrabold tracking-tighter text-gray-900 sm:text-7xl">
            Tindakan Nyata, <br class="hidden sm:block">
            <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-cyan-500">
                Bukan Sekadar Teori.
            </span>
        </h1>

        {{-- Quote & Sub --}}
        <div class="mx-auto mt-8 max-w-2xl text-lg text-gray-600 leading-relaxed">
            <p class="font-medium italic text-gray-800 mb-2">"Talk is cheap. Show me the code." <span class="text-sm font-normal text-gray-500">— Linus Torvalds</span></p>
            <p class="text-base sm:text-lg">Tingkatkan skill, bangun inovasi, dan berkolaborasi dalam komunitas teknologi terdepan di kampus.</p>
        </div>

        {{-- CTA --}}
        <div class="mt-10 flex flex-wrap justify-center gap-4">
            @guest
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="group relative inline-flex items-center justify-center gap-2 rounded-xl bg-gray-900 px-8 py-3.5 text-sm font-semibold text-white shadow-lg transition-all hover:bg-gray-800 hover:shadow-xl hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-gray-900 focus:ring-offset-2">
                        Daftar Sekarang
                        <svg class="h-4 w-4 transition-transform group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </a>
                @endif
                <a href="#about" class="inline-flex items-center justify-center gap-2 rounded-xl border border-gray-200 bg-white px-8 py-3.5 text-sm font-semibold text-gray-700 shadow-sm transition-all hover:bg-gray-50 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-200 focus:ring-offset-2">
                    Pelajari Lebih Lanjut
                </a>
            @else
                <a href="{{ route('pages.admin.dashboard') }}" class="group relative inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-8 py-3.5 text-sm font-semibold text-white shadow-lg transition-all hover:bg-blue-700 hover:shadow-xl hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2">
                    Masuk Dashboard
                    <svg class="h-4 w-4 transition-transform group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </a>
            @endguest
        </div>
    </div>

</section>