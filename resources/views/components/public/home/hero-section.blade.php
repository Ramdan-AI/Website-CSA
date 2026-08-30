<section class="relative min-h-screen flex items-center bg-[#fafafa] overflow-hidden">
    <div class="absolute top-[-30%] right-[-15%] w-[900px] h-[900px] rounded-full bg-[radial-gradient(circle,rgba(59,130,246,0.04)_0%,transparent_60%)] blur-[60px]"></div>
    <div class="absolute bottom-[-20%] left-[-10%] w-[700px] h-[700px] rounded-full bg-[radial-gradient(circle,rgba(16,185,129,0.04)_0%,transparent_60%)] blur-[60px]"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] rounded-full bg-[radial-gradient(circle,rgba(59,130,246,0.02)_0%,transparent_50%)] blur-[40px]"></div>

    <div class="relative max-w-[1280px] mx-auto px-8 w-full py-32">
        <div class="grid lg:grid-cols-2 gap-24 items-center">
            
            <div class="max-w-[560px]">
                <div class="inline-flex items-center gap-2.5 px-4 py-2.5 rounded-full bg-white/70 backdrop-blur-2xl border border-white/50 shadow-[0_4px_16px_-6px_rgba(0,0,0,0.04),0_0_0_0.5px_rgba(0,0,0,0.03)] mb-10">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 shadow-[0_0_8px_rgba(16,185,129,0.4)]"></span>
                    <span class="text-[11px] font-semibold text-gray-500 tracking-wide">Creative Student Association</span>
                </div>

                <h1 class="text-[68px] font-extrabold text-gray-900 leading-[1.06] tracking-[-0.04em] mb-8">
                    Dari ide<br>menjadi <span class="text-blue-500">realita</span>
                </h1>

                <p class="text-[17px] text-gray-500 leading-relaxed mb-12 max-w-[460px]">
                    Kami mewadahi mahasiswa yang ingin mencipta — aplikasi, visual, dan ekosistem teknologi yang berdampak.
                </p>

                @php
                    $regOpen = Cache::remember('setting.registration_open', 60, fn () => \App\Models\Setting::first()?->registration_open ?? false);
                @endphp
                <div class="flex items-center gap-3">
                    @if ($regOpen)
                        <a href="{{ route('register') }}"
                           class="group inline-flex items-center gap-2.5 px-7 py-4 bg-gray-900 text-white text-[14px] font-semibold rounded-[18px] shadow-[0_8px_30px_-8px_rgba(0,0,0,0.12),0_0_0_0.5px_rgba(255,255,255,0.1)] hover:shadow-[0_14px_40px_-10px_rgba(0,0,0,0.18)] hover:scale-[1.01] active:scale-[0.99] transition-all duration-300">
                            Bergabung sekarang
                            <span class="group-hover:translate-x-0.5 transition-transform duration-300">&rarr;</span>
                        </a>
                    @else
                        <button type="button" onclick="window.dispatchEvent(new CustomEvent('open-register-modal'))"
                           class="group inline-flex items-center gap-2.5 px-7 py-4 bg-gray-900 text-white text-[14px] font-semibold rounded-[18px] shadow-[0_8px_30px_-8px_rgba(0,0,0,0.12),0_0_0_0.5px_rgba(255,255,255,0.1)] hover:shadow-[0_14px_40px_-10px_rgba(0,0,0,0.18)] hover:scale-[1.01] active:scale-[0.99] transition-all duration-300 cursor-pointer">
                            Bergabung sekarang
                            <span class="group-hover:translate-x-0.5 transition-transform duration-300">&rarr;</span>
                        </button>
                    @endif
                    <a href="#tentang"
                       class="px-7 py-4 text-[14px] font-medium text-gray-500 hover:text-gray-900 rounded-[18px] hover:bg-white/40 transition-all duration-300">
                        Selengkapnya
                    </a>
                </div>
            </div>

            <div class="relative">
                <div class="grid grid-cols-3 gap-3">
                    <div class="space-y-3 pt-8">
                        <div class="aspect-[3/4] rounded-[24px] bg-white/60 backdrop-blur-xl shadow-[0_20px_50px_-20px_rgba(0,0,0,0.08),0_0_0_0.5px_rgba(0,0,0,0.04)] overflow-hidden">
                            <div class="w-full h-full bg-gradient-to-b from-blue-100/50 to-blue-50/30 flex items-center justify-center">
                                <i class="fas fa-code text-3xl text-blue-400/60"></i>
                            </div>
                        </div>
                        <div class="aspect-[3/4] rounded-[24px] bg-white/60 backdrop-blur-xl shadow-[0_20px_50px_-20px_rgba(0,0,0,0.08),0_0_0_0.5px_rgba(0,0,0,0.04)] overflow-hidden">
                            <div class="w-full h-full bg-gradient-to-b from-emerald-100/50 to-emerald-50/30 flex items-center justify-center">
                                <i class="fas fa-paintbrush text-3xl text-emerald-400/60"></i>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <div class="aspect-[3/4] rounded-[24px] bg-white/60 backdrop-blur-xl shadow-[0_20px_50px_-20px_rgba(0,0,0,0.08),0_0_0_0.5px_rgba(0,0,0,0.04)] overflow-hidden">
                            <div class="w-full h-full bg-gradient-to-b from-sky-100/50 to-sky-50/30 flex items-center justify-center">
                                <i class="fas fa-users text-3xl text-sky-400/60"></i>
                            </div>
                        </div>
                        <div class="aspect-[3/4] rounded-[24px] bg-white/60 backdrop-blur-xl shadow-[0_20px_50px_-20px_rgba(0,0,0,0.08),0_0_0_0.5px_rgba(0,0,0,0.04)] overflow-hidden">
                            <div class="w-full h-full bg-gradient-to-b from-violet-100/50 to-violet-50/30 flex items-center justify-center">
                                <i class="fas fa-rocket text-3xl text-violet-400/60"></i>
                            </div>
                        </div>
                        <div class="aspect-[3/4] rounded-[24px] bg-white/60 backdrop-blur-xl shadow-[0_20px_50px_-20px_rgba(0,0,0,0.08),0_0_0_0.5px_rgba(0,0,0,0.04)] overflow-hidden">
                            <div class="w-full h-full bg-gradient-to-b from-amber-100/50 to-amber-50/30 flex items-center justify-center">
                                <i class="fas fa-lightbulb text-3xl text-amber-400/60"></i>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-3 pt-12">
                        <div class="aspect-[3/4] rounded-[24px] bg-white/60 backdrop-blur-xl shadow-[0_20px_50px_-20px_rgba(0,0,0,0.08),0_0_0_0.5px_rgba(0,0,0,0.04)] overflow-hidden">
                            <div class="w-full h-full bg-gradient-to-b from-rose-100/50 to-rose-50/30 flex items-center justify-center">
                                <i class="fas fa-camera text-3xl text-rose-400/60"></i>
                            </div>
                        </div>
                        <div class="aspect-[3/4] rounded-[24px] bg-white/60 backdrop-blur-xl shadow-[0_20px_50px_-20px_rgba(0,0,0,0.08),0_0_0_0.5px_rgba(0,0,0,0.04)] overflow-hidden">
                            <div class="w-full h-full bg-gradient-to-b from-teal-100/50 to-teal-50/30 flex items-center justify-center">
                                <i class="fas fa-star text-3xl text-teal-400/60"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[120%] h-[120%] rounded-full bg-[radial-gradient(circle,rgba(59,130,246,0.02)_0%,transparent_50%)] blur-[80px] pointer-events-none"></div>
            </div>
        </div>
    </div>
</section>