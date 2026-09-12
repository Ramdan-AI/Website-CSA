@php
    $setting = Cache::remember('setting.footer', 60, fn () => \App\Models\Setting::first());
@endphp

<footer id="kontak" class="relative pt-10 sm:pt-16 pb-4 sm:pb-6">
    <div class="max-w-[1200px] mx-auto px-4 sm:px-6">
        <div class="relative rounded-[28px] sm:rounded-[32px] bg-white border border-gray-100 overflow-hidden shadow-[0_24px_80px_-24px_rgba(54,100,175,0.18)]">
            {{-- Glow accents --}}
            <div class="absolute -top-24 -right-16 w-[420px] h-[420px] rounded-full bg-[radial-gradient(circle,rgba(54,100,175,0.10)_0%,transparent_65%)] pointer-events-none"></div>
            <div class="absolute -bottom-32 -left-20 w-[460px] h-[460px] rounded-full bg-[radial-gradient(circle,rgba(67,183,73,0.08)_0%,transparent_65%)] pointer-events-none"></div>
            <div class="absolute top-1/3 left-1/2 -translate-x-1/2 w-[600px] h-[300px] rounded-full bg-[radial-gradient(circle,rgba(54,100,175,0.05)_0%,transparent_60%)] pointer-events-none"></div>

            {{-- Main grid --}}
            <div class="relative grid gap-10 sm:gap-8 md:grid-cols-[1.4fr_1fr_1.2fr] p-8 sm:p-10 lg:p-14">
                {{-- Brand --}}
                <div class="max-w-[340px]">
                    <a href="{{ route('home') }}" class="inline-flex items-center gap-3">
                        <div class="w-11 h-11 rounded-2xl bg-[#eef3fb] ring-1 ring-[#3664AF]/10 flex items-center justify-center shrink-0">
                            <img src="{{ asset('storage/system/logos/csa-logo.png') }}" alt="CSA" class="w-7 h-7 object-contain">
                        </div>
                        <div class="leading-tight">
                            <span class="text-[15px] font-bold text-gray-900 tracking-tight">Creative Student Association</span>
                            <span class="block text-[10px] text-gray-400 font-medium -mt-0.5">STMIK Mardira Indonesia</span>
                        </div>
                    </a>
                    <p class="text-[13px] leading-relaxed text-gray-500 mt-5">
                        Organisasi mahasiswa yang fokus pada pengembangan kreativitas, teknologi, dan inovasi di lingkungan kampus.
                    </p>

                    {{-- Socials --}}
                    <div class="flex items-center gap-2 mt-6">
                        <span class="text-[11px] font-semibold text-gray-900 uppercase tracking-[0.15em] mr-1">Follow Us</span>
                        @if ($setting->instagram ?? null)
                            <a href="{{ $setting->instagram }}" target="_blank" rel="noopener noreferrer" aria-label="Instagram"
                                class="w-9 h-9 rounded-xl bg-gray-50 ring-1 ring-gray-100 flex items-center justify-center text-gray-400 hover:text-[#3664AF] hover:bg-[#eef3fb] active:scale-95 transition-all duration-200">
                                <i class="fab fa-instagram text-sm"></i>
                            </a>
                        @endif
                        @if ($setting->youtube ?? null)
                            <a href="{{ $setting->youtube }}" target="_blank" rel="noopener noreferrer" aria-label="YouTube"
                                class="w-9 h-9 rounded-xl bg-gray-50 ring-1 ring-gray-100 flex items-center justify-center text-gray-400 hover:text-[#3664AF] hover:bg-[#eef3fb] active:scale-95 transition-all duration-200">
                                <i class="fab fa-youtube text-sm"></i>
                            </a>
                        @endif
                        @if ($setting->linkedin ?? null)
                            <a href="{{ $setting->linkedin }}" target="_blank" rel="noopener noreferrer" aria-label="LinkedIn"
                                class="w-9 h-9 rounded-xl bg-gray-50 ring-1 ring-gray-100 flex items-center justify-center text-gray-400 hover:text-[#3664AF] hover:bg-[#eef3fb] active:scale-95 transition-all duration-200">
                                <i class="fab fa-linkedin-in text-sm"></i>
                            </a>
                        @endif
                    </div>
                </div>

                {{-- Navigasi --}}
                <div>
                    <h3 class="text-[11px] font-semibold text-gray-900 uppercase tracking-[0.15em] mb-4">Navigasi</h3>
                    <ul class="space-y-2.5">
                        @php
                            $footerMenus = [
                                ['label' => 'Beranda', 'url' => route('home')],
                                ['label' => 'Tentang', 'url' => '#tentang'],
                                ['label' => 'Divisi', 'url' => '#divisi'],
                                ['label' => 'Kegiatan', 'url' => '#kegiatan'],
                                ['label' => 'Anggota', 'url' => route('anggota')],
                                ['label' => 'Kontak', 'url' => '#kontak'],
                            ];
                        @endphp
                        @foreach ($footerMenus as $menu)
                            <li>
                                <a href="{{ $menu['url'] }}"
                                    class="inline-flex items-center min-h-[36px] text-[13px] font-medium text-gray-500 hover:text-[#3664AF] transition-colors duration-200">
                                    {{ $menu['label'] }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                {{-- Kontak --}}
                <div>
                    <h3 class="text-[11px] font-semibold text-gray-900 uppercase tracking-[0.15em] mb-4">Kontak Kami</h3>
                    <ul class="space-y-3.5">
                        <li class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-lg bg-[#eef3fb] ring-1 ring-[#3664AF]/10 flex items-center justify-center shrink-0">
                                <i class="fas fa-headset text-[10px] text-[#3664AF]"></i>
                            </div>
                            <div class="pt-1">
                                <p class="text-[11px] font-semibold text-gray-900 leading-tight">Humas I</p>
                                <a href="tel:+6285795686906" class="text-[13px] text-gray-500 hover:text-[#3664AF] transition-colors duration-200">
                                    +62 857 9568 6906
                                </a>
                            </div>
                        </li>
                        <li class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-lg bg-[#eef3fb] ring-1 ring-[#3664AF]/10 flex items-center justify-center shrink-0">
                                <i class="fas fa-headset text-[10px] text-[#3664AF]"></i>
                            </div>
                            <div class="pt-1">
                                <p class="text-[11px] font-semibold text-gray-900 leading-tight">Humas II</p>
                                <a href="tel:+6282126619180" class="text-[13px] text-gray-500 hover:text-[#3664AF] transition-colors duration-200">
                                    +62 821 2661 9180
                                </a>
                            </div>
                        </li>
                        @if ($setting->email ?? null)
                            <li class="flex items-start gap-3">
                                <div class="w-8 h-8 rounded-lg bg-[#eef3fb] ring-1 ring-[#3664AF]/10 flex items-center justify-center shrink-0">
                                    <i class="fas fa-envelope text-[10px] text-[#3664AF]"></i>
                                </div>
                                <a href="mailto:{{ $setting->email }}" class="text-[13px] text-gray-500 hover:text-[#3664AF] break-all transition-colors duration-200 pt-1.5">
                                    {{ $setting->email }}
                                </a>
                            </li>
                        @endif
                        @if ($setting->address ?? null)
                            <li class="flex items-start gap-3">
                                <div class="w-8 h-8 rounded-lg bg-[#eef3fb] ring-1 ring-[#3664AF]/10 flex items-center justify-center shrink-0">
                                    <i class="fas fa-map-pin text-[10px] text-[#3664AF]"></i>
                                </div>
                                <p class="text-[13px] text-gray-500 leading-relaxed pt-1.5">{{ $setting->address }}</p>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>

            {{-- Bottom bar --}}
            <div class="relative border-t border-gray-100 px-8 sm:px-10 lg:px-14 py-5 flex flex-col sm:flex-row items-center justify-between gap-3">
                <p class="text-[12px] text-gray-500">
                    &copy; 2025 Made with <i class="fas fa-heart text-[#e11d48] text-[10px] mx-0.5"></i> by <span class="font-semibold text-gray-900">Creative Student Association</span>
                </p>
                <div class="flex items-center gap-4">
                    <a href="#" class="text-[12px] text-gray-400 hover:text-[#3664AF] transition-colors duration-200">Privacy Policy</a>
                    <span class="text-gray-200">|</span>
                    <a href="#" class="text-[12px] text-gray-400 hover:text-[#3664AF] transition-colors duration-200">Terms of Service</a>
                </div>
            </div>
        </div>
    </div>
</footer>
