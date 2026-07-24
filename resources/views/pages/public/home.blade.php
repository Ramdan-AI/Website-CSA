{{-- resources/views/landing/home.blade.php --}}
@extends('layouts.landing')

@section('content')

{{-- HERO --}}
<section class="relative min-h-screen flex items-center bg-[#fcfcfc]">
    <div class="absolute top-0 right-0 w-[60%] h-full bg-[radial-gradient(ellipse_at_30%_40%,rgba(59,130,246,0.04)_0%,transparent_70%)]"></div>
    
    <div class="relative max-w-[1200px] mx-auto px-8 w-full py-40">
        <div class="grid lg:grid-cols-2 gap-16 items-center">
            
            <div class="max-w-[540px]">
                <p class="text-xs font-semibold text-gray-400 tracking-[0.15em] uppercase mb-8">Creative Student Association</p>
                
                <h1 class="text-[64px] font-bold text-gray-900 leading-[1.08] tracking-[-0.03em] mb-8">
                    Bangun masa depan dari kode dan kreativitas
                </h1>
                
                <p class="text-lg text-gray-500 leading-relaxed mb-10 max-w-[440px]">
                    Komunitas mahasiswa STMIK Mardira Indonesia yang fokus pada pengembangan perangkat lunak, desain, dan inovasi digital.
                </p>

                <div class="flex items-center gap-4">
                    <a href="{{ route('register') }}" class="inline-flex items-center gap-2 px-7 py-3.5 bg-gray-900 text-white text-sm font-semibold rounded-xl hover:bg-gray-800 transition-colors">
                        Daftar sekarang
                    </a>
                    <a href="#tentang" class="text-sm font-medium text-gray-500 hover:text-gray-900 transition-colors">
                        Pelajari lebih lanjut &rarr;
                    </a>
                </div>

                <div class="flex items-center gap-12 mt-16 pt-12 border-t border-gray-100">
                    <div>
                        <p class="text-2xl font-bold text-gray-900">25+</p>
                        <p class="text-xs text-gray-400 mt-1">Pengurus aktif</p>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-900">2</p>
                        <p class="text-xs text-gray-400 mt-1">Divisi</p>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-900">2017</p>
                        <p class="text-xs text-gray-400 mt-1">Berdiri</p>
                    </div>
                </div>
            </div>

            <div class="relative">
                <div class="grid grid-cols-4 gap-3">
                    @php
                        $images = [
                            ['color' => 'from-blue-100 to-blue-50', 'icon' => 'fa-code', 'iconColor' => 'text-blue-400'],
                            ['color' => 'from-emerald-100 to-emerald-50', 'icon' => 'fa-paintbrush', 'iconColor' => 'text-emerald-400'],
                            ['color' => 'from-sky-100 to-sky-50', 'icon' => 'fa-users', 'iconColor' => 'text-sky-400'],
                            ['color' => 'from-violet-100 to-violet-50', 'icon' => 'fa-lightbulb', 'iconColor' => 'text-violet-400'],
                            ['color' => 'from-amber-100 to-amber-50', 'icon' => 'fa-rocket', 'iconColor' => 'text-amber-400'],
                            ['color' => 'from-rose-100 to-rose-50', 'icon' => 'fa-camera', 'iconColor' => 'text-rose-400'],
                            ['color' => 'from-teal-100 to-teal-50', 'icon' => 'fa-star', 'iconColor' => 'text-teal-400'],
                            ['color' => 'from-indigo-100 to-indigo-50', 'icon' => 'fa-heart', 'iconColor' => 'text-indigo-400'],
                        ];
                    @endphp
                    
                    <div class="space-y-3 pt-6">
                        @foreach(array_slice($images, 0, 3) as $img)
                            <div class="aspect-[3/4] rounded-2xl bg-white border border-gray-100 shadow-[0_4px_16px_-4px_rgba(0,0,0,0.02)] overflow-hidden hover:shadow-[0_8px_24px_-8px_rgba(0,0,0,0.06)] transition-shadow duration-300">
                                <div class="w-full h-full bg-gradient-to-b {{ $img['color'] }} flex items-center justify-center">
                                    <i class="fas {{ $img['icon'] }} text-2xl {{ $img['iconColor'] }}/40"></i>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="space-y-3">
                        @foreach(array_slice($images, 3, 3) as $img)
                            <div class="aspect-[3/4] rounded-2xl bg-white border border-gray-100 shadow-[0_4px_16px_-4px_rgba(0,0,0,0.02)] overflow-hidden hover:shadow-[0_8px_24px_-8px_rgba(0,0,0,0.06)] transition-shadow duration-300">
                                <div class="w-full h-full bg-gradient-to-b {{ $img['color'] }} flex items-center justify-center">
                                    <i class="fas {{ $img['icon'] }} text-2xl {{ $img['iconColor'] }}/40"></i>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="space-y-3 pt-8">
                        @foreach(array_slice($images, 0, 2) as $img)
                            <div class="aspect-[3/4] rounded-2xl bg-white border border-gray-100 shadow-[0_4px_16px_-4px_rgba(0,0,0,0.02)] overflow-hidden hover:shadow-[0_8px_24px_-8px_rgba(0,0,0,0.06)] transition-shadow duration-300">
                                <div class="w-full h-full bg-gradient-to-b {{ $img['color'] }} flex items-center justify-center">
                                    <i class="fas {{ $img['icon'] }} text-2xl {{ $img['iconColor'] }}/40"></i>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="space-y-3 pt-16">
                        @foreach(array_slice($images, 5, 3) as $img)
                            <div class="aspect-[3/4] rounded-2xl bg-white border border-gray-100 shadow-[0_4px_16px_-4px_rgba(0,0,0,0.02)] overflow-hidden hover:shadow-[0_8px_24px_-8px_rgba(0,0,0,0.06)] transition-shadow duration-300">
                                <div class="w-full h-full bg-gradient-to-b {{ $img['color'] }} flex items-center justify-center">
                                    <i class="fas {{ $img['icon'] }} text-2xl {{ $img['iconColor'] }}/40"></i>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ABOUT --}}
<section id="tentang" class="relative py-40 bg-white">
    <div class="max-w-[1200px] mx-auto px-8">
        <div class="max-w-[680px]">
            <p class="text-xs font-semibold text-blue-500 tracking-[0.15em] uppercase mb-6">Tentang CSA</p>
            <h2 class="text-[48px] font-bold text-gray-900 leading-[1.1] tracking-[-0.03em] mb-8">
                Organisasi yang lahir dari kebutuhan untuk mencipta
            </h2>
            <p class="text-lg text-gray-500 leading-relaxed mb-6">
                CSA (Creative Student Association) adalah organisasi kemahasiswaan berbasis IT di STMIK Mardira Indonesia. Kami menyatukan dua dunia: pemrograman dan multimedia — karena kami percaya teknologi yang hebat membutuhkan sentuhan kreatif.
            </p>
            <p class="text-lg text-gray-500 leading-relaxed">
                Berawal dari non-aktifnya HMJ IF, sekelompok mahasiswa di akhir 2017 memutuskan untuk membangun kembali wadah yang fokus pada pengembangan skill teknis dan kreativitas. Sejak saat itu, CSA terus tumbuh sebagai rumah bagi para builder dan creator.
            </p>
        </div>
    </div>
</section>

{{-- WHY CSA --}}
<section class="relative py-40 bg-[#fafafa]">
    <div class="max-w-[1200px] mx-auto px-8">
        <p class="text-xs font-semibold text-emerald-500 tracking-[0.15em] uppercase mb-6">Mengapa CSA</p>
        <h2 class="text-[48px] font-bold text-gray-900 leading-[1.1] tracking-[-0.03em] mb-16 max-w-[600px]">
            Lebih dari sekadar organisasi kampus
        </h2>

        <div class="grid md:grid-cols-3 gap-12">
            <div>
                <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center mb-5">
                    <i class="fas fa-code text-blue-500 text-sm"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-3">Skill teknis yang relevan</h3>
                <p class="text-sm text-gray-500 leading-relaxed">Belajar pemrograman web, mobile, dan teknologi terkini langsung dari praktik — bukan cuma teori di kelas.</p>
            </div>
            <div>
                <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center mb-5">
                    <i class="fas fa-users text-emerald-500 text-sm"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-3">Kolaborasi lintas disiplin</h3>
                <p class="text-sm text-gray-500 leading-relaxed">Programmer dan desainer bekerja bersama dalam proyek nyata — persis seperti di industri.</p>
            </div>
            <div>
                <div class="w-10 h-10 rounded-xl bg-sky-50 flex items-center justify-center mb-5">
                    <i class="fas fa-building text-sky-500 text-sm"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-3">Didukung penuh oleh kampus</h3>
                <p class="text-sm text-gray-500 leading-relaxed">STMIK Mardira Indonesia memfasilitasi setiap inisiatif kami — dari workshop hingga kompetisi.</p>
            </div>
        </div>
    </div>
</section>

{{-- DIVISI --}}
<section id="divisi" class="relative py-40 bg-white">
    <div class="max-w-[1200px] mx-auto px-8">
        <p class="text-xs font-semibold text-blue-500 tracking-[0.15em] uppercase mb-6">Divisi</p>
        <h2 class="text-[48px] font-bold text-gray-900 leading-[1.1] tracking-[-0.03em] mb-16 max-w-[600px]">
            Dua bidang yang kami fokuskan
        </h2>

        <div class="grid md:grid-cols-2 gap-8">
            @forelse($divisions as $division)
                <div class="p-8 rounded-[24px] bg-[#f8fafc] border border-gray-100">
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-blue-50 to-blue-100 flex items-center justify-center mb-5">
                        <i class="fas fa-code text-blue-500 text-lg"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">{{ $division->name }}</h3>
                    <p class="text-sm text-gray-500 leading-relaxed">{{ $division->description ?? 'Tidak ada deskripsi' }}</p>
                </div>
            @empty
                <div class="md:col-span-2 text-center py-16">
                    <p class="text-sm text-gray-400">Belum ada divisi yang tersedia.</p>
                </div>
            @endforelse
        </div>
    </div>
</section>

{{-- KEGIATAN --}}
<section id="kegiatan" class="relative py-40 bg-[#fafafa]">
    <div class="max-w-[1200px] mx-auto px-8">
        <p class="text-xs font-semibold text-emerald-500 tracking-[0.15em] uppercase mb-6">Kegiatan</p>
        <h2 class="text-[48px] font-bold text-gray-900 leading-[1.1] tracking-[-0.03em] mb-16 max-w-[600px]">
            Berita dan aktivitas terkini
        </h2>

        <div class="grid md:grid-cols-3 gap-8">
            @forelse($news as $item)
                <div class="rounded-[24px] bg-white border border-gray-100 overflow-hidden hover:shadow-[0_8px_32px_-12px_rgba(0,0,0,0.08)] transition-shadow duration-300">
                    <div class="h-48 bg-gradient-to-br from-gray-50 to-gray-100 flex items-center justify-center">
                        @if($item->thumbnail)
                            <img src="{{ asset('storage/' . $item->thumbnail) }}" alt="{{ $item->title }}" class="w-full h-full object-cover">
                        @else
                            <i class="fas fa-newspaper text-4xl text-gray-300"></i>
                        @endif
                    </div>
                    <div class="p-6">
                        @if($item->category)
                            <span class="text-[10px] font-semibold text-emerald-500 uppercase tracking-wider">{{ $item->category->name }}</span>
                        @endif
                        <h3 class="text-[15px] font-bold text-gray-900 mt-1.5 mb-2 leading-snug">{{ $item->title }}</h3>
                        <p class="text-xs text-gray-400 leading-relaxed">{{ Str::limit(strip_tags($item->content), 120) }}</p>
                    </div>
                </div>
            @empty
                <div class="md:col-span-3 text-center py-16">
                    <p class="text-sm text-gray-400">Belum ada berita yang dipublikasikan.</p>
                </div>
            @endforelse
        </div>
    </div>
</section>

{{-- ANGGOTA --}}
<section id="anggota" class="relative py-40 bg-white">
    <div class="max-w-[1200px] mx-auto px-8">
        <p class="text-xs font-semibold text-violet-500 tracking-[0.15em] uppercase mb-6">Pengurus</p>
        <h2 class="text-[48px] font-bold text-gray-900 leading-[1.1] tracking-[-0.03em] mb-16 max-w-[600px]">
            Tim pengurus CSA
        </h2>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($members->take(6) as $member)
                <div class="flex items-center gap-4 p-5 rounded-[20px] bg-[#f8fafc] border border-gray-100">
                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-violet-50 to-violet-100 flex items-center justify-center shrink-0">
                        <span class="text-sm font-bold text-violet-500">{{ strtoupper(substr($member->name, 0, 1)) }}</span>
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm font-bold text-gray-900 truncate">{{ $member->name }}</p>
                        <p class="text-[11px] text-gray-400">{{ $member->position->name ?? $member->division->name ?? 'Anggota' }}</p>
                    </div>
                </div>
            @empty
                <div class="lg:col-span-3 text-center py-16">
                    <p class="text-sm text-gray-400">Belum ada anggota terdaftar.</p>
                </div>
            @endforelse
        </div>
    </div>
</section>

{{-- KONTAK --}}
<section id="kontak" class="relative py-40 bg-[#fafafa]">
    <div class="max-w-[1200px] mx-auto px-8">
        <p class="text-xs font-semibold text-amber-500 tracking-[0.15em] uppercase mb-6">Kontak</p>
        <h2 class="text-[48px] font-bold text-gray-900 leading-[1.1] tracking-[-0.03em] mb-16 max-w-[600px]">
            Hubungi kami
        </h2>

        <div class="grid md:grid-cols-3 gap-8">
            <div class="p-6 rounded-[20px] bg-white border border-gray-100">
                <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center mb-4">
                    <i class="fas fa-envelope text-amber-500 text-sm"></i>
                </div>
                <h3 class="text-sm font-bold text-gray-900 mb-2">Email</h3>
                <p class="text-sm text-gray-500">{{ $settings['email'] ?? 'Belum diatur' }}</p>
            </div>
            <div class="p-6 rounded-[20px] bg-white border border-gray-100">
                <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center mb-4">
                    <i class="fas fa-phone text-amber-500 text-sm"></i>
                </div>
                <h3 class="text-sm font-bold text-gray-900 mb-2">Telepon</h3>
                <p class="text-sm text-gray-500">{{ $settings['phone'] ?? 'Belum diatur' }}</p>
            </div>
            <div class="p-6 rounded-[20px] bg-white border border-gray-100">
                <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center mb-4">
                    <i class="fas fa-map-pin text-amber-500 text-sm"></i>
                </div>
                <h3 class="text-sm font-bold text-gray-900 mb-2">Alamat</h3>
                <p class="text-sm text-gray-500">{{ $settings['address'] ?? 'Belum diatur' }}</p>
            </div>
        </div>
    </div>
</section>

{{-- DAFTAR CTA --}}
<section id="daftar" class="relative py-40 bg-white">
    <div class="max-w-[1200px] mx-auto px-8 text-center">
        <p class="text-xs font-semibold text-blue-500 tracking-[0.15em] uppercase mb-6">Bergabung</p>
        <h2 class="text-[48px] font-bold text-gray-900 leading-[1.1] tracking-[-0.03em] mb-8">
            Siap menjadi bagian dari CSA?
        </h2>
        <p class="text-lg text-gray-500 leading-relaxed mb-10 max-w-[480px] mx-auto">
            Daftarkan dirimu dan mulai perjalananmu sebagai builder dan creator bersama kami.
        </p>
        <a href="{{ route('register') }}" class="inline-flex items-center gap-2 px-8 py-4 bg-gray-900 text-white text-[14px] font-semibold rounded-[18px] shadow-[0_8px_30px_-8px_rgba(0,0,0,0.12)] hover:shadow-[0_14px_40px_-10px_rgba(0,0,0,0.18)] hover:scale-[1.01] transition-all duration-300">
            Daftar sekarang
            <span>&rarr;</span>
        </a>
    </div>
</section>

@endsection