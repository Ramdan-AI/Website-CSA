{{-- Mengapa CSA --}}
<section id="mengapa" class="bg-white scroll-mt-16">
    <div class="max-w-[1200px] mx-auto px-5 sm:px-8 py-16 sm:py-20">

        <div class="max-w-[680px] mb-12">
            <h2 class="text-[clamp(1.6rem,3.6vw,2.25rem)] font-bold text-gray-900 leading-[1.25]">
                Apa yang didapat anggota
            </h2>
            <p class="text-[16px] text-gray-600 leading-[1.75] mt-4">
                Tiga hal yang jadi fokus utama CSA.
            </p>
        </div>

        @php
            $reasons = [
                [
                    'icon' => 'fa-code',
                    'title' => 'Belajar lewat proyek',
                    'body' => 'Kerjakan proyek web, mobile, dan multimedia. Dibimbing pengurus yang lebih dulu belajar.',
                ],
                [
                    'icon' => 'fa-people-group',
                    'title' => 'Kerja dalam tim',
                    'body' => 'Programmer dan desainer mengerjakan satu proyek bersama, lintas divisi.',
                ],
                [
                    'icon' => 'fa-building-columns',
                    'title' => 'Didukung kampus',
                    'body' => 'STMIK Mardira Indonesia menyediakan ruang untuk workshop, pelatihan, dan kompetisi.',
                ],
            ];
        @endphp

        <div class="grid md:grid-cols-3 gap-6">
            @foreach ($reasons as $reason)
                <div class="bg-white rounded-xl border border-gray-200 p-6">
                    <span class="w-10 h-10 rounded-lg bg-[#e8f0fe] flex items-center justify-center text-[#1a73e8]">
                        <i class="fas {{ $reason['icon'] }} text-[16px]"></i>
                    </span>
                    <h3 class="text-[18px] font-semibold text-gray-900 mt-4">{{ $reason['title'] }}</h3>
                    <p class="text-[15px] text-gray-600 leading-[1.7] mt-2">{{ $reason['body'] }}</p>
                </div>
            @endforeach
        </div>

    </div>
</section>
