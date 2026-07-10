@extends('layouts.app')

@section('title', 'CSA STMIK Mardira — Creative Student Association')
@section('meta_description', 'Platform resmi Creative Student Association STMIK Mardira Indonesia. Wadah kolaborasi, inovasi, dan kreativitas mahasiswa teknologi.')

@section('content')

<x-public.home.hero-section />

<section id="about" class="bg-gray-50 py-24 px-6 border-y border-gray-100">
    <div class="max-w-7xl mx-auto">
        <div class="text-center mb-16">
            <h2 class="text-sm font-bold text-blue-600 tracking-widest uppercase mb-2">Benefit Organisasi</h2>
            <p class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">Mengapa Memilih CSA?</p>
            <p class="mt-4 text-gray-600 max-w-2xl mx-auto">Kami tidak sekadar berdiskusi, kami membangun. Ekosistem yang dirancang untuk mempercepat pertumbuhan karir teknologimu.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            {{-- Bento Card 1 (Span 2) --}}
            <div class="md:col-span-2 group relative overflow-hidden rounded-3xl bg-white border border-gray-200 shadow-sm transition-all hover:shadow-md">
                <div class="absolute inset-0 bg-gradient-to-br from-blue-50/50 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                <div class="p-8 sm:p-10 relative z-10 h-full flex flex-col justify-between">
                    <div class="w-12 h-12 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center mb-6">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" /></svg>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-3">Wadah Karya Nyata</h3>
                        <p class="text-gray-600 leading-relaxed">Berhenti di tahap teori. Di sini, kamu akan dilibatkan dalam proyek nyata lintas divisi. Dari perancangan antarmuka (UI/UX) hingga implementasi kode backend berskala besar.</p>
                    </div>
                </div>
            </div>

            {{-- Bento Card 2 --}}
            <div class="group relative overflow-hidden rounded-3xl bg-white border border-gray-200 shadow-sm transition-all hover:shadow-md">
                <div class="p-8 sm:p-10 h-full flex flex-col justify-between">
                    <div class="w-12 h-12 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center mb-6">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Kolaborasi</h3>
                        <p class="text-sm text-gray-600 leading-relaxed">Jejaring mahasiswa IT terbaik di STMIK Mardira. Belajar bersama, tumbuh bersama.</p>
                    </div>
                </div>
            </div>

            {{-- Bento Card 3 --}}
            <div class="group relative overflow-hidden rounded-3xl bg-white border border-gray-200 shadow-sm transition-all hover:shadow-md">
                <div class="p-8 sm:p-10 h-full flex flex-col justify-between">
                    <div class="w-12 h-12 rounded-xl bg-green-100 text-green-600 flex items-center justify-center mb-6">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Dukungan Kampus</h3>
                        <p class="text-sm text-gray-600 leading-relaxed">Didukung penuh oleh institusi untuk memberikan fasilitas, ruang gerak, dan bimbingan dosen pakar.</p>
                    </div>
                </div>
            </div>

            {{-- Bento Card 4 (Span 2) --}}
            <div class="md:col-span-2 group relative overflow-hidden rounded-3xl bg-gray-900 text-white border border-gray-800 shadow-sm transition-all hover:shadow-md">
                <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_bottom_right,_var(--tw-gradient-stops))] from-blue-900/40 via-transparent to-transparent"></div>
                <div class="p-8 sm:p-10 relative z-10 h-full flex flex-col justify-between">
                    <div class="w-12 h-12 rounded-xl bg-gray-800 border border-gray-700 text-blue-400 flex items-center justify-center mb-6">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold mb-3">Akselerasi Karir</h3>
                        <p class="text-gray-400 leading-relaxed max-w-lg">Program pembinaan kami dirancang seperti inkubator industri. Portofolio yang kamu bangun di CSA akan menjadi daya tawar utamamu saat melamar ke dunia kerja.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="bg-white py-24 px-6">
    <div class="max-w-7xl mx-auto">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-16">
            
            {{-- Left Column: Pembina & Sejarah --}}
            <div class="lg:col-span-1 space-y-12">
                <div>
                    <h2 class="text-3xl font-bold tracking-tight text-gray-900 mb-6">Nakhoda Kami</h2>
                    <div class="rounded-2xl border border-gray-200 bg-gray-50 p-6 flex items-start gap-5">
                        <div class="h-16 w-16 shrink-0 rounded-full bg-white border border-gray-200 overflow-hidden shadow-sm">
                            <img src="https://ui-avatars.com/api/?name=Adi+Raharjo&background=0F172A&color=fff" alt="Adi Raharjo" class="h-full w-full object-cover">
                        </div>
                        <div>
                            <div class="text-[10px] font-bold uppercase tracking-widest text-blue-600 mb-1">Pembina & Founder</div>
                            <h3 class="text-lg font-bold text-gray-900">Adi Raharjo, M.M</h3>
                            <p class="text-sm text-gray-600 mt-2">IT Manager dengan pengalaman 15+ tahun memandu mahasiswa IT merancang ekosistem digital.</p>
                        </div>
                    </div>
                </div>

                <div x-data="{ tab: 'visi' }" class="rounded-2xl border border-gray-200 bg-white shadow-sm overflow-hidden">
                    <div class="flex border-b border-gray-100 bg-gray-50/50">
                        <button @click="tab = 'visi'" :class="tab === 'visi' ? 'border-blue-600 text-blue-700 bg-white' : 'border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-50'" class="flex-1 py-3 text-xs font-bold uppercase tracking-wider border-b-2 focus:outline-none transition-colors">Visi</button>
                        <button @click="tab = 'misi'" :class="tab === 'misi' ? 'border-blue-600 text-blue-700 bg-white' : 'border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-50'" class="flex-1 py-3 text-xs font-bold uppercase tracking-wider border-b-2 focus:outline-none transition-colors">Misi</button>
                        <button @click="tab = 'sejarah'" :class="tab === 'sejarah' ? 'border-blue-600 text-blue-700 bg-white' : 'border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-50'" class="flex-1 py-3 text-xs font-bold uppercase tracking-wider border-b-2 focus:outline-none transition-colors">Sejarah</button>
                    </div>
                    <div class="p-6 h-[200px] overflow-y-auto">
                        <p x-show="tab === 'visi'" class="text-sm text-gray-600 leading-relaxed">{{ $settings['vision_csa'] ?? 'Menjadi epicentrum inovasi teknologi dan ruang inkubasi mahasiswa yang progresif dan berdaya saing global.' }}</p>
                        <p x-show="tab === 'misi'" class="text-sm text-gray-600 leading-relaxed whitespace-pre-line" style="display: none;">{{ $settings['mission_csa'] ?? "1. Akselerasi keahlian teknis secara intensif.\n2. Penciptaan produk digital layak guna.\n3. Membangun relasi strategis dengan entitas industri." }}</p>
                        <p x-show="tab === 'sejarah'" class="text-sm text-gray-600 leading-relaxed" style="display: none;">{{ $settings['history_csa'] ?? 'Berdiri sejak 2017 sebagai kebangkitan dari HMJ IF. Kami merintis jalan baru yang berfokus penuh pada kompetensi hardskill.' }}</p>
                    </div>
                </div>
            </div>

            {{-- Right Column: Anggota & Pengurus Grid --}}
            <div class="lg:col-span-2">
                <div class="flex items-end justify-between mb-8">
                    <div>
                        <h2 class="text-3xl font-bold tracking-tight text-gray-900">Pasukan Penggerak</h2>
                        <p class="mt-2 text-sm text-gray-500">Mengenal talenta di balik layar Creative Student Association.</p>
                    </div>
                    <a href="#" class="hidden sm:inline-flex items-center text-sm font-semibold text-blue-600 hover:text-blue-700">Lihat Semua &rarr;</a>
                </div>

                @if($members->count() > 0)
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                        @foreach($members->take(12) as $member)
                        <div class="group rounded-2xl border border-gray-100 bg-white p-4 text-center hover:border-blue-200 hover:shadow-md transition-all">
                            <div class="mx-auto mb-3 h-16 w-16 overflow-hidden rounded-full bg-gray-100 border border-gray-200 group-hover:ring-2 ring-blue-500 ring-offset-2 transition-all">
                                @if($member->photo)
                                    <img src="{{ Storage::disk('public')->url($member->photo) }}" alt="{{ $member->name }}" class="h-full w-full object-cover">
                                @else
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($member->name) }}&background=F3F4F6&color=4B5563" alt="{{ $member->name }}" class="h-full w-full object-cover">
                                @endif
                            </div>
                            <h3 class="text-sm font-bold text-gray-900 line-clamp-1" title="{{ $member->name }}">{{ $member->name }}</h3>
                            <p class="mt-1 text-[11px] font-semibold text-blue-600 line-clamp-1">{{ $member->position->name ?? 'Anggota' }}</p>
                            <p class="text-[10px] text-gray-400 uppercase tracking-widest mt-1">{{ $member->division->name ?? 'Umum' }}</p>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="rounded-2xl border border-dashed border-gray-300 bg-gray-50 p-12 text-center">
                        <svg class="mx-auto h-8 w-8 text-gray-400 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                        <p class="text-sm text-gray-500 font-medium">Belum ada data anggota yang dipublikasikan.</p>
                    </div>
                @endif
            </div>

        </div>
    </div>
</section>

{{-- ══════════════════════════════════════════════════════════
     DIVISI (Program & Keahlian)
     ══════════════════════════════════════════════════════════ --}}
<section class="relative bg-gray-900 py-24 px-6 text-white overflow-hidden">
    {{-- Decorative grid for dark mode --}}
    <div class="absolute inset-0 opacity-10 bg-[linear-gradient(to_right,#ffffff_1px,transparent_1px),linear-gradient(to_bottom,#ffffff_1px,transparent_1px)] bg-[size:4rem_4rem] [mask-image:radial-gradient(ellipse_80%_50%_at_50%_50%,#000_70%,transparent_100%)]"></div>

    <div class="relative max-w-7xl mx-auto z-10">
        <div class="text-center mb-16">
            <h2 class="text-3xl font-bold tracking-tight sm:text-4xl">Fokus & Keahlian</h2>
            <p class="mt-4 text-gray-400 max-w-xl mx-auto">Tentukan jalur karir teknologimu melalui dua pilar divisi utama kami.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-5xl mx-auto">
            @foreach($divisions as $div)
            <div class="group relative rounded-3xl border border-gray-800 bg-gray-900/50 p-8 sm:p-10 backdrop-blur-sm hover:border-gray-700 transition-colors">
                <div class="absolute top-0 right-0 -mt-4 -mr-4 h-24 w-24 rounded-full bg-blue-500 opacity-20 blur-[40px] group-hover:opacity-40 transition-opacity"></div>
                
                <h3 class="text-2xl font-bold text-white mb-4 flex items-center gap-3">
                    <span class="p-2 rounded-lg bg-gray-800 border border-gray-700">
                        @if(Str::contains(strtolower($div->name), 'program'))
                            <svg class="w-6 h-6 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" /></svg>
                        @else
                            <svg class="w-6 h-6 text-pink-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                        @endif
                    </span>
                    {{ $div->name }}
                </h3>
                <p class="text-gray-400 leading-relaxed mb-8 min-h-[80px]">
                    {{ $div->description }}
                </p>
                <div class="flex flex-wrap gap-2">
                    @if(Str::contains(strtolower($div->name), 'program'))
                        <span class="rounded-full bg-gray-800 px-3 py-1 text-xs font-medium text-gray-300">HTML/CSS</span>
                        <span class="rounded-full bg-gray-800 px-3 py-1 text-xs font-medium text-gray-300">PHP & Laravel</span>
                        <span class="rounded-full bg-gray-800 px-3 py-1 text-xs font-medium text-gray-300">JavaScript/React</span>
                    @else
                        <span class="rounded-full bg-gray-800 px-3 py-1 text-xs font-medium text-gray-300">UI/UX Design</span>
                        <span class="rounded-full bg-gray-800 px-3 py-1 text-xs font-medium text-gray-300">Video Editing</span>
                        <span class="rounded-full bg-gray-800 px-3 py-1 text-xs font-medium text-gray-300">Graphic Arts</span>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ══════════════════════════════════════════════════════════
     BERITA & KEGIATAN
     ══════════════════════════════════════════════════════════ --}}
<section class="bg-gray-50 py-24 px-6">
    <div class="max-w-7xl mx-auto">
        <div class="flex flex-col sm:flex-row items-end justify-between mb-12">
            <div>
                <h2 class="text-3xl font-bold tracking-tight text-gray-900">Rekam Jejak & Berita</h2>
                <p class="mt-2 text-gray-600">Dokumentasi aktivitas, pengumuman, dan artikel teknologi terkini.</p>
            </div>
            <a href="#" class="mt-4 sm:mt-0 inline-flex items-center text-sm font-semibold text-gray-900 hover:text-blue-600 transition-colors">Lihat Semua Arsip &rarr;</a>
        </div>

        @if($news->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach($news as $item)
                <a href="#" class="group flex flex-col rounded-3xl bg-white border border-gray-200 overflow-hidden shadow-sm hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
                    <div class="aspect-[4/3] bg-gray-100 relative overflow-hidden">
                        @if($item->thumbnail)
                            <img src="{{ Storage::disk('public')->url($item->thumbnail) }}" alt="{{ $item->title }}" class="h-full w-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @else
                            {{-- Geometric Placeholder for missing news thumbnails --}}
                            <div class="h-full w-full bg-gray-100 flex items-center justify-center p-8 group-hover:scale-105 transition-transform duration-500">
                                <div class="w-full h-full border-2 border-dashed border-gray-300 rounded-xl flex items-center justify-center text-gray-400 font-mono text-xs uppercase tracking-widest">
                                    No Image Data
                                </div>
                            </div>
                        @endif
                        <div class="absolute top-4 left-4 rounded-full bg-white/90 backdrop-blur-sm px-3 py-1 text-xs font-bold text-gray-900 shadow-sm">
                            {{ $item->category->name ?? 'Update' }}
                        </div>
                    </div>
                    <div class="p-6 sm:p-8 flex-1 flex flex-col">
                        <div class="text-xs font-semibold text-gray-400 mb-3 uppercase tracking-wider">{{ $item->published_at ? $item->published_at->format('M d, Y') : $item->created_at->format('M d, Y') }}</div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3 line-clamp-2 leading-tight group-hover:text-blue-600 transition-colors">{{ $item->title }}</h3>
                        <p class="text-sm text-gray-600 line-clamp-3 mb-6 flex-1 leading-relaxed">
                            {{ Str::limit(strip_tags($item->content), 150) }}
                        </p>
                        <div class="flex items-center gap-3 mt-auto pt-4 border-t border-gray-100">
                            <div class="h-8 w-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-700 font-bold text-xs border border-gray-200">
                                {{ Str::substr($item->author->name ?? 'A', 0, 1) }}
                            </div>
                            <span class="text-xs font-semibold text-gray-900">{{ $item->author->name ?? 'Administrator' }}</span>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        @else
            <div class="rounded-3xl border border-dashed border-gray-300 bg-white p-16 text-center">
                <svg class="mx-auto h-10 w-10 text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" /></svg>
                <p class="text-gray-500 font-medium">Log berita saat ini belum tersedia.</p>
                <p class="text-sm text-gray-400 mt-1">Kegiatan dan artikel terbaru akan muncul di sini.</p>
            </div>
        @endif
    </div>
</section>

@endsection
