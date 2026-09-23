@php
    $setting = Cache::remember('setting.footer', 60, fn () => \App\Models\Setting::first());
    $footerMenus = [
        ['label' => 'Beranda', 'url' => route('home')],
        ['label' => 'Tentang', 'url' => url('/') . '#tentang'],
        ['label' => 'Divisi', 'url' => url('/') . '#divisi'],
        ['label' => 'Berita', 'url' => route('berita')],
        ['label' => 'Anggota', 'url' => route('anggota')],
        ['label' => 'Cerita', 'url' => url('/') . '#cerita'],
    ];
@endphp

<footer id="kontak" class="bg-[var(--paper)] border-t border-[var(--line)]">
    <div class="max-w-[1200px] mx-auto px-5 sm:px-8 py-14">
        <div class="grid gap-10 md:grid-cols-[1.4fr_1fr_1.2fr]">

            {{-- Brand --}}
            <div class="max-w-[360px]">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-3">
                    <img src="{{ asset('storage/system/logos/csa-logo.png') }}" alt="CSA" class="w-9 h-9 object-contain">
                    <span class="text-[15px] font-semibold text-[var(--ink)]">Creative Student Association</span>
                </a>
                <p class="text-[14px] leading-[1.7] text-[var(--ink-soft)] mt-4">
                    Organisasi mahasiswa STMIK Mardira Indonesia yang fokus pada pemrograman, multimedia, dan teknologi.
                </p>

                <div class="flex items-center gap-2 mt-5">
                    @if ($setting->instagram ?? null)
                        <a href="{{ $setting->instagram }}" target="_blank" rel="noopener noreferrer" aria-label="Instagram"
                            class="w-9 h-9 rounded-lg border border-[var(--line)] flex items-center justify-center text-[var(--ink-faint)] hover:text-[var(--accent)] hover:border-[var(--accent)] transition-colors">
                            <i class="fab fa-instagram text-[14px]"></i>
                        </a>
                    @endif
                    @if ($setting->youtube ?? null)
                        <a href="{{ $setting->youtube }}" target="_blank" rel="noopener noreferrer" aria-label="YouTube"
                            class="w-9 h-9 rounded-lg border border-[var(--line)] flex items-center justify-center text-[var(--ink-faint)] hover:text-[var(--accent)] hover:border-[var(--accent)] transition-colors">
                            <i class="fab fa-youtube text-[14px]"></i>
                        </a>
                    @endif
                    @if ($setting->linkedin ?? null)
                        <a href="{{ $setting->linkedin }}" target="_blank" rel="noopener noreferrer" aria-label="LinkedIn"
                            class="w-9 h-9 rounded-lg border border-[var(--line)] flex items-center justify-center text-[var(--ink-faint)] hover:text-[var(--accent)] hover:border-[var(--accent)] transition-colors">
                            <i class="fab fa-linkedin-in text-[14px]"></i>
                        </a>
                    @endif
                </div>
            </div>

            {{-- Navigasi --}}
            <div>
                <h3 class="text-[13px] font-semibold text-[var(--ink)] mb-4">Navigasi</h3>
                <ul class="space-y-2.5">
                    @foreach ($footerMenus as $menu)
                        <li>
                            <a href="{{ $menu['url'] }}" class="text-[14px] text-[var(--ink-soft)] hover:text-[var(--accent)] transition-colors">
                                {{ $menu['label'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            {{-- Kontak --}}
            <div>
                <h3 class="text-[13px] font-semibold text-[var(--ink)] mb-4">Kontak</h3>
                <ul class="space-y-3">
                    <li>
                        <p class="text-[13px] text-[var(--ink-faint)]">Humas I</p>
                        <a href="tel:+6285795686906" class="text-[14px] text-[var(--ink)] hover:text-[var(--accent)] transition-colors">
                            +62 857 9568 6906
                        </a>
                    </li>
                    <li>
                        <p class="text-[13px] text-[var(--ink-faint)]">Humas II</p>
                        <a href="tel:+6282126619180" class="text-[14px] text-[var(--ink)] hover:text-[var(--accent)] transition-colors">
                            +62 821 2661 9180
                        </a>
                    </li>
                    @if ($setting->email ?? null)
                        <li>
                            <a href="mailto:{{ $setting->email }}" class="text-[14px] text-[var(--ink)] hover:text-[var(--accent)] break-all transition-colors">
                                {{ $setting->email }}
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>

        <div class="mt-12 pt-6 border-t border-[var(--line)] flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <p class="text-[13px] text-[var(--ink-faint)]">
                &copy; {{ date('Y') }} Creative Student Association
            </p>
            <div class="flex items-center gap-5">
                <a href="#" class="text-[13px] text-[var(--ink-faint)] hover:text-[var(--accent)] transition-colors">Kebijakan Privasi</a>
                <a href="#" class="text-[13px] text-[var(--ink-faint)] hover:text-[var(--accent)] transition-colors">Ketentuan Layanan</a>
            </div>
        </div>
    </div>
</footer>
