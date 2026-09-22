{{-- Cerita CSA: perjalanan, pembina, pendiri, ajakan --}}
@php
    $pembina = [
        'name' => 'Adi Raharjo, S.Kom., M.M',
        'role' => 'Pembina',
        'about' => 'Membimbing arah teknis dan akademik CSA sejak awal berdiri.',
        'education' => 'S2 Magister Manajemen, Universitas Pendidikan Indonesia',
        'experience' => '15+ tahun',
        'linkedin' => '#',
    ];
    $founders = [
        'Diansyah, S.Kom',
        'Febri Ridwansyah, S.Kom',
        'Ilman Nurhakim, S.Kom',
        'M. Fauzi Ramadhan, S.Kom',
        'Yanti Mandasari, S.Kom',
    ];
    $pembinaPhoto = \Illuminate\Support\Facades\Storage::disk('public')->exists('pembina/jfod093842jfldswa.png')
        ? asset('storage/pembina/jfod093842jfldswa.png')
        : null;
    $monogram = fn ($name) => strtoupper(collect(explode(' ', trim(strtok($name, ','))))->take(2)->map(fn ($w) => mb_substr($w, 0, 1))->join(''));
    $regOpen = Cache::remember('setting.registration_open', 60, fn () => \App\Models\Setting::first()?->registration_open ?? false);

    $milestones = [
        ['2017', 'CSA lahir', 'HMJ IF yang non-aktif dihidupkan kembali oleh sekelompok mahasiswa sebagai wadah pemrograman dan informatika.'],
        ['2018', 'Divisi terbentuk', 'Multimedia dan pemrograman berjalan berdampingan. Proyek kolaboratif pertama dikerjakan lintas divisi.'],
        ['2023', 'Metode belajar', 'Study club mingguan dan mentoring internal menjadi kebiasaan baru anggota.'],
        ['Kini', 'Tumbuh bersama', 'Workshop, kompetisi, dan proyek nyata membuka jalan anggota ke industri.'],
    ];
@endphp

<section id="cerita" class="relative py-20 md:py-28 bg-[#F7FAFF] scroll-mt-24">
    <div class="max-w-[1200px] mx-auto px-5 sm:px-8">

        <div class="max-w-[720px] mb-12 md:mb-16" data-reveal="up">
            <p class="text-[13px] font-semibold text-[#1a73e8] mb-3">Cerita CSA</p>
            <h2 class="text-[clamp(1.8rem,4.4vw,2.75rem)] font-bold text-gray-900 leading-[1.2]">
                Perjalanan CSA
            </h2>
            <p class="text-[16px] sm:text-[17px] text-gray-500 leading-[1.7] mt-5">
                Dari sekelompok mahasiswa pada 2017, hingga jadi komunitas kreatif yang terus berkarya.
            </p>
        </div>

        {{-- Timeline --}}
        <ol class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-16 md:mb-24" data-reveal-stagger>
            @foreach ($milestones as [$year, $title, $desc])
                <li class="bg-white rounded-xl border border-gray-200 p-6">
                    <span class="text-[24px] font-bold text-[#1a73e8] leading-none">{{ $year }}</span>
                    <h3 class="text-[15px] font-bold text-gray-900 mt-3.5">{{ $title }}</h3>
                    <p class="text-[14px] text-gray-500 leading-[1.7] mt-2">{{ $desc }}</p>
                </li>
            @endforeach
        </ol>

        {{-- Pembina --}}
        <div class="rounded-xl bg-white border border-gray-200 p-6 sm:p-8 mb-16 md:mb-24" data-reveal="up">
            <p class="text-[13px] font-semibold text-gray-400 mb-5">Pembina</p>
            <div class="flex flex-col sm:flex-row sm:items-center gap-6">
                <div class="w-20 h-20 rounded-full overflow-hidden bg-[#e8f0fe] shrink-0">
                    @if ($pembinaPhoto)
                        <img src="{{ $pembinaPhoto }}" alt="{{ $pembina['name'] }}" width="80" height="80"
                            class="w-full h-full object-cover" loading="lazy" decoding="async">
                    @else
                        <img src="{{ asset('storage/profile/profile-placeholder.png') }}" alt="{{ $pembina['name'] }}" class="w-full h-full object-cover">
                    @endif
                </div>
                <div class="min-w-0 flex-1">
                    <h3 class="text-[19px] font-bold text-gray-900">{{ $pembina['name'] }}</h3>
                    <p class="text-[14px] text-gray-500 mt-1.5 leading-[1.7]">{{ $pembina['about'] }}</p>
                    <p class="text-[13px] text-gray-400 mt-2">{{ $pembina['education'] }} · {{ $pembina['experience'] }}</p>
                </div>
            </div>
        </div>

        {{-- Pendiri --}}
        <div class="mb-16 md:mb-24">
            <div class="flex items-end justify-between gap-5 mb-8">
                <div>
                    <p class="text-[13px] font-semibold text-gray-400 mb-2">Pendiri</p>
                    <h3 class="text-[22px] sm:text-[26px] font-bold text-gray-900 tracking-[-0.02em]">Pendiri CSA</h3>
                </div>
                <span class="text-[13px] text-gray-400 shrink-0">{{ count($founders) }} orang</span>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
                @foreach ($founders as $founder)
                    <article class="bg-white rounded-xl border border-gray-200 p-5 text-center">
                        <div class="w-14 h-14 rounded-full bg-[#e8f0fe] text-[#1a73e8] flex items-center justify-center mx-auto text-[16px] font-bold">
                            {{ $monogram($founder) }}
                        </div>
                        <h4 class="text-[14px] font-bold text-gray-900 mt-3.5 leading-snug">{{ $founder }}</h4>
                    </article>
                @endforeach
            </div>
        </div>

        {{-- CTA --}}
        <div class="rounded-xl border border-gray-200 bg-white p-8 sm:p-12 text-center">
            <div class="max-w-[560px] mx-auto">
                <h3 class="text-[clamp(1.5rem,3.6vw,2rem)] font-bold text-gray-900 leading-[1.25]">
                    Jadi bagian dari CSA
                </h3>
                <p class="text-[16px] text-gray-600 leading-[1.75] mt-4">
                    Bangun portofolio bersama {{ $members->count() }} anggota aktif lainnya.
                </p>

                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-center gap-3 mt-7">
                    @if ($regOpen)
                        <a href="{{ route('register') }}"
                            class="inline-flex items-center justify-center px-6 min-h-[48px] bg-[#1a73e8] text-white text-[15px] font-medium rounded-lg hover:bg-[#1557b0] transition-colors">
                            Daftar sekarang
                        </a>
                    @else
                        <button type="button" onclick="window.dispatchEvent(new CustomEvent('open-register-modal'))"
                            class="inline-flex items-center justify-center px-6 min-h-[48px] bg-[#1a73e8] text-white text-[15px] font-medium rounded-lg hover:bg-[#1557b0] transition-colors cursor-pointer">
                            Daftar sekarang
                        </button>
                    @endif
                    <a href="#kepengurusan"
                        class="inline-flex items-center justify-center px-6 min-h-[48px] bg-white border border-gray-300 text-gray-700 text-[15px] font-medium rounded-lg hover:bg-gray-50 transition-colors">
                        Lihat struktur
                    </a>
                </div>
            </div>
        </div>

    </div>
</section>
