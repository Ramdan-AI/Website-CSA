{{-- Apa yang didapat anggota: daftar bernomor editorial --}}
<section id="mengapa" class="scroll-mt-16" style="background: var(--paper);">
    <div class="max-w-[1200px] mx-auto px-5 sm:px-8 py-16 sm:py-24">

        <div class="max-w-[680px] mb-14">
            <p class="text-[13px] font-medium mb-3" style="color: var(--accent);">Keunggulan</p>
            <h2 class="text-[clamp(1.7rem,3.8vw,2.4rem)] font-bold leading-[1.22] tracking-[-0.025em]" style="color: var(--ink);">
                Apa yang didapat anggota
            </h2>
            <p class="text-[16px] leading-[1.8] mt-5" style="color: var(--ink-soft);">
                Tiga hal yang jadi fokus utama CSA.
            </p>
        </div>

        @php
            $reasons = [
                [
                    'title' => 'Belajar lewat proyek',
                    'body' => 'Kerjakan proyek web, mobile, dan multimedia. Dibimbing pengurus yang lebih dulu belajar.',
                ],
                [
                    'title' => 'Kerja dalam tim',
                    'body' => 'Programmer dan desainer mengerjakan satu proyek bersama, lintas divisi.',
                ],
                [
                    'title' => 'Didukung kampus',
                    'body' => 'STMIK Mardira Indonesia menyediakan ruang untuk workshop, pelatihan, dan kompetisi.',
                ],
            ];
        @endphp

        <div class="border-t" style="border-color: var(--line);">
            @foreach ($reasons as $i => $reason)
                <div class="grid lg:grid-cols-12 gap-4 lg:gap-8 py-8 sm:py-10 border-b" style="border-color: var(--line);"
                    data-numbered-item>
                    <div class="lg:col-span-2">
                        <span data-item-number class="block text-[44px] sm:text-[56px] font-extrabold leading-[0.9] tracking-[-0.04em] tabular-nums"
                            style="color: var(--line-strong);">0{{ $i + 1 }}</span>
                    </div>
                    <div class="lg:col-span-10" data-item-content>
                        <h3 class="text-[20px] sm:text-[24px] font-bold tracking-[-0.02em]" style="color: var(--ink);">
                            {{ $reason['title'] }}
                        </h3>
                        <p class="text-[16px] leading-[1.8] mt-2.5 max-w-[640px]" style="color: var(--ink-soft);">
                            {{ $reason['body'] }}
                        </p>
                    </div>
                </div>
            @endforeach
        </div>

    </div>
</section>
