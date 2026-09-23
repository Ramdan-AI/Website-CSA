{{-- Cerita CSA: timeline, pembina, pendiri --}}
@php
    $pembina = [
        'name' => 'Adi Raharjo, S.Kom., M.M',
        'about' => 'Membimbing arah teknis dan akademik CSA sejak awal berdiri.',
        'education' => 'S2 Magister Manajemen, Universitas Pendidikan Indonesia',
        'experience' => '15+ tahun',
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
        ['2017', 'CSA lahir', 'UKM pemrograman HMJ IF dihidupkan kembali oleh sekelompok mahasiswa sebagai wadah pemrograman dan informatika.'],
        ['2018', 'Divisi terbentuk', 'Multimedia dan pemrograman berjalan berdampingan. Proyek kolaboratif pertama dikerjakan lintas divisi.'],
        ['2023', 'Metode belajar', 'Study club mingguan dan mentoring internal menjadi kebiasaan baru anggota.'],
        ['Kini', 'Tumbuh bersama', 'Workshop, kompetisi, dan proyek nyata membuka jalan anggota ke industri.'],
    ];
@endphp

<section id="cerita" class="scroll-mt-16" style="background: var(--paper);">
    <div class="max-w-[1200px] mx-auto px-5 sm:px-8 py-16 sm:py-24">

        <div class="max-w-[680px] mb-14" data-reveal="up">
            <p class="text-[13px] font-medium mb-3" style="color: var(--accent);">Cerita CSA</p>
            <h2 class="text-[clamp(1.7rem,3.8vw,2.4rem)] font-bold leading-[1.22] tracking-[-0.025em]" style="color: var(--ink);">
                Perjalanan CSA
            </h2>
            <p class="text-[16px] leading-[1.8] mt-5" style="color: var(--ink-soft);">
                Dari sekelompok mahasiswa pada 2017, hingga jadi komunitas kreatif yang terus berkarya.
            </p>
        </div>

        {{-- Timeline vertikal dengan garis yang tumbuh --}}
        <div class="relative pl-8 sm:pl-12" data-timeline>
            <div class="absolute left-[7px] sm:left-[11px] top-2 bottom-2 w-px" style="background: var(--line);"></div>
            <div data-timeline-fill class="absolute left-[7px] sm:left-[11px] top-2 w-px origin-top" style="height: calc(100% - 1rem); background: var(--accent); transform: scaleY(0);"></div>

            <div class="space-y-12">
                @foreach ($milestones as $i => [$year, $title, $desc])
                    <div class="relative" data-timeline-item>
                        <span class="absolute -left-8 sm:-left-12 top-1.5 w-4 h-4 rounded-full border-2 ring-4"
                            style="border-color: var(--accent); background: var(--paper); --tw-ring-color: var(--paper);"></span>
                        <p class="text-[13px] font-medium" style="color: var(--accent);">{{ $year }}</p>
                        <h3 class="text-[19px] sm:text-[21px] font-bold mt-1.5 tracking-[-0.015em]" style="color: var(--ink);">{{ $title }}</h3>
                        <p class="text-[15px] leading-[1.8] mt-2 max-w-[640px]" style="color: var(--ink-soft);">{{ $desc }}</p>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Pembina --}}
        <div class="mt-20 rounded-xl border overflow-hidden" style="background: var(--paper-sunken); border-color: var(--line);" data-reveal="up">
            <div class="grid sm:grid-cols-[200px_1fr]">
                <div class="aspect-square sm:aspect-auto" style="background: var(--line);">
                    @if ($pembinaPhoto)
                        <img src="{{ $pembinaPhoto }}" alt="{{ $pembina['name'] }}" class="w-full h-full object-cover">
                    @else
                        <img src="{{ asset('storage/profile/profile-placeholder.png') }}" alt="{{ $pembina['name'] }}" class="w-full h-full object-cover">
                    @endif
                </div>
                <div class="p-6 sm:p-8 flex flex-col justify-center">
                    <p class="text-[13px] font-medium mb-2" style="color: var(--accent);">Pembina</p>
                    <h3 class="text-[20px] font-bold tracking-[-0.015em]" style="color: var(--ink);">{{ $pembina['name'] }}</h3>
                    <p class="text-[15px] leading-[1.8] mt-3" style="color: var(--ink-soft);">{{ $pembina['about'] }}</p>
                    <div class="flex flex-wrap gap-x-6 gap-y-2 mt-5 pt-5 border-t" style="border-color: var(--line);">
                        <div>
                            <p class="text-[12px]" style="color: var(--ink-faint);">Pendidikan</p>
                            <p class="text-[14px] font-medium mt-0.5" style="color: var(--ink);">{{ $pembina['education'] }}</p>
                        </div>
                        <div>
                            <p class="text-[12px]" style="color: var(--ink-faint);">Pengalaman</p>
                            <p class="text-[14px] font-medium mt-0.5" style="color: var(--ink);">{{ $pembina['experience'] }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Pendiri --}}
        <div class="mt-20">
            <div class="flex items-end justify-between gap-5 mb-8">
                <div>
                    <p class="text-[13px] font-medium mb-2" style="color: var(--ink-faint);">Pendiri</p>
                    <h3 class="text-[24px] font-bold tracking-[-0.02em]" style="color: var(--ink);">Pendiri CSA</h3>
                </div>
                <span class="text-[13px] shrink-0" style="color: var(--ink-faint);">{{ count($founders) }} orang</span>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4" data-reveal-stagger>
                @foreach ($founders as $founder)
                    <article class="group rounded-xl border p-5 text-center transition-transform duration-300 hover:-translate-y-1"
                        style="background: var(--paper); border-color: var(--line);">
                        <div class="w-14 h-14 rounded-full flex items-center justify-center mx-auto text-[16px] font-bold transition-transform duration-300 group-hover:scale-105"
                            style="background: var(--accent-soft); color: var(--accent);">
                            {{ $monogram($founder) }}
                        </div>
                        <h4 class="text-[14px] font-bold mt-3.5 leading-snug" style="color: var(--ink);">{{ $founder }}</h4>
                    </article>
                @endforeach
            </div>
        </div>

    </div>
</section>
