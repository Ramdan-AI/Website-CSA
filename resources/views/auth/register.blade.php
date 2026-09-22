@extends('layouts.landing')

@php
    $regOpen = Cache::remember('setting.registration_open', 60, fn () => \App\Models\Setting::first()?->registration_open ?? false);
@endphp

@section('content')
<div class="min-h-screen bg-[#f8fafc]">
    <div class="max-w-[1080px] mx-auto px-5 sm:px-8 pt-24 sm:pt-28 pb-16">
        <div class="grid lg:grid-cols-[1fr_minmax(0,520px)] gap-10 lg:gap-16 items-start">

            {{-- Left: intro --}}
            <div class="hidden lg:block lg:sticky lg:top-28" data-reveal="left">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-2.5 mb-10">
                    <img src="{{ asset('storage/system/logos/csa-logo.png') }}" alt="CSA" class="w-10 h-10 object-contain">
                    <span class="leading-tight">
                        <span class="block text-[15px] font-bold text-gray-900 tracking-tight">CSA</span>
                        <span class="block text-[10px] text-gray-400 -mt-0.5">STMIK Mardira Indonesia</span>
                    </span>
                </a>

                <h1 class="text-[clamp(2rem,3.6vw,2.8rem)] font-bold text-gray-900 leading-[1.12] tracking-[-0.03em]">
                    Mulai perjalananmu di komunitas kreatif CSA.
                </h1>
                <p class="text-[15px] text-gray-500 leading-relaxed mt-5 max-w-[440px]">
                    Isi data diri, pilih divisi minat, lalu atur keamanan akunmu. Hanya butuh satu menit.
                </p>

                <ol class="mt-10 space-y-6">
                    @foreach ([
                        ['01', 'Data diri', 'Nama, NIM, WhatsApp, dan email aktif.'],
                        ['02', 'Divisi minat', 'Pilih divisi yang paling sesuai jalur pengembanganmu.'],
                        ['03', 'Keamanan akun', 'Buat password yang kuat untuk akunmu.'],
                    ] as [$no, $title, $desc])
                        <li class="flex gap-4">
                            <span class="w-9 h-9 rounded-xl bg-white border border-gray-200 flex items-center justify-center text-[12px] font-bold text-[#1a73e8] shrink-0">{{ $no }}</span>
                            <span>
                                <span class="block text-[14px] font-semibold text-gray-900">{{ $title }}</span>
                                <span class="block text-[13px] text-gray-500 mt-0.5 leading-relaxed">{{ $desc }}</span>
                            </span>
                        </li>
                    @endforeach
                </ol>
            </div>

            {{-- Right: form card --}}
            <div class="w-full max-w-xl mx-auto lg:mx-0" data-reveal="up">
                <div class="text-center lg:text-left mb-7">
                    <div class="w-12 h-12 rounded-2xl bg-white border border-gray-100 shadow-sm flex items-center justify-center mx-auto lg:mx-0 mb-5 lg:hidden">
                        <img src="{{ asset('storage/system/logos/csa-logo.png') }}" alt="CSA" class="w-7 h-7 object-contain">
                    </div>
                    <h1 class="text-[22px] font-bold text-gray-900 tracking-tight">Pendaftaran anggota baru</h1>
                    <p class="text-[14px] text-gray-400 mt-1.5">Lengkapi data berikut untuk membuat akun.</p>
                </div>

                @if (! $regOpen)
                    <div class="mb-5 rounded-2xl bg-amber-50 border border-amber-100 px-4 py-3.5 text-[13px] text-amber-700">
                        Pendaftaran sedang ditutup. Kamu masih bisa melihat formulir, namun pengiriman akan ditolak sampai admin membuka pendaftaran.
                    </div>
                @endif

                <div class="bg-white rounded-xl border border-gray-200/80 p-6 sm:p-8">
                    <form method="POST" action="{{ route('register') }}" class="space-y-6"
                        x-data="{ selectedDivision: '{{ old('division_id', '') }}', showPassword: false }">
                        @csrf

                        {{-- Step 1 --}}
                        <fieldset class="space-y-5">
                            <legend class="text-[14px] font-semibold text-[#1a73e8]">Data diri</legend>

                            <div>
                                <label for="name" class="block text-[13px] font-medium text-gray-700 mb-1.5">Nama Lengkap</label>
                                <input type="text" name="name" id="name" value="{{ old('name') }}"
                                    placeholder="Nama sesuai KTM" autocomplete="name" required
                                    class="w-full h-12 px-4 rounded-xl bg-[#f1f3f4] text-[14px] text-gray-900 placeholder:text-gray-400 border border-transparent focus:border-[#1a73e8]/40 focus:bg-white focus:outline-none transition-colors @error('name') border-red-300 @enderror">
                                @error('name')<p class="text-[12px] text-red-500 mt-1.5">{{ $message }}</p>@enderror
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label for="nim" class="block text-[13px] font-medium text-gray-700 mb-1.5">NIM</label>
                                    <input type="text" name="nim" id="nim" value="{{ old('nim') }}" placeholder="Contoh: 21130001" required
                                        class="w-full h-12 px-4 rounded-xl bg-[#f1f3f4] text-[14px] text-gray-900 placeholder:text-gray-400 border border-transparent focus:border-[#1a73e8]/40 focus:bg-white focus:outline-none transition-colors @error('nim') border-red-300 @enderror">
                                    @error('nim')<p class="text-[12px] text-red-500 mt-1.5">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label for="whatsapp" class="block text-[13px] font-medium text-gray-700 mb-1.5">Nomor WhatsApp</label>
                                    <input type="tel" name="whatsapp" id="whatsapp" value="{{ old('whatsapp') }}" placeholder="081234567890"
                                        pattern="[0-9+\-\s]+" oninput="this.value = this.value.replace(/[^0-9+\-\s]/g, '')" required
                                        class="w-full h-12 px-4 rounded-xl bg-[#f1f3f4] text-[14px] text-gray-900 placeholder:text-gray-400 border border-transparent focus:border-[#1a73e8]/40 focus:bg-white focus:outline-none transition-colors @error('whatsapp') border-red-300 @enderror">
                                    @error('whatsapp')<p class="text-[12px] text-red-500 mt-1.5">{{ $message }}</p>@enderror
                                </div>
                            </div>

                            <div>
                                <label for="email" class="block text-[13px] font-medium text-gray-700 mb-1.5">Alamat Email</label>
                                <input type="email" name="email" id="email" value="{{ old('email') }}" placeholder="kamu@stmikmi.ac.id" autocomplete="email" required
                                    class="w-full h-12 px-4 rounded-xl bg-[#f1f3f4] text-[14px] text-gray-900 placeholder:text-gray-400 border border-transparent focus:border-[#1a73e8]/40 focus:bg-white focus:outline-none transition-colors @error('email') border-red-300 @enderror">
                                @error('email')<p class="text-[12px] text-red-500 mt-1.5">{{ $message }}</p>@enderror
                            </div>
                        </fieldset>

                        {{-- Step 2 --}}
                        <fieldset class="space-y-4 pt-2">
                            <legend class="text-[14px] font-semibold text-[#1a73e8]">Divisi minat</legend>

                            @if (isset($divisions) && $divisions->count() > 0)
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    @foreach ($divisions as $division)
                                        @php $divImg = $division->image ? asset('storage/' . $division->image) : asset('storage/system/logos/csa-logo.png'); @endphp
                                        <label for="div_{{ $division->id }}" @click="selectedDivision = '{{ $division->id }}'"
                                            :class="selectedDivision == '{{ $division->id }}'
                                                ? 'border-[#1a73e8] bg-[#e8f0fe] ring-2 ring-[#1a73e8]/15'
                                                : 'border-gray-200 bg-white hover:border-gray-300'"
                                            class="relative flex flex-col p-4 rounded-2xl border cursor-pointer transition-all duration-200 select-none">
                                            <input type="radio" name="division_id" id="div_{{ $division->id }}" value="{{ $division->id }}"
                                                x-model="selectedDivision" class="sr-only" required>

                                            <div class="flex items-start justify-between gap-3">
                                                <div class="w-11 h-11 rounded-xl bg-white p-2 flex items-center justify-center border border-gray-100 shrink-0">
                                                    <img src="{{ $divImg }}" alt="{{ $division->name }}" class="w-full h-full object-contain">
                                                </div>
                                                <div class="w-5 h-5 rounded-full border flex items-center justify-center transition-colors"
                                                    :class="selectedDivision == '{{ $division->id }}' ? 'bg-[#1a73e8] border-[#1a73e8] text-white' : 'border-gray-300 bg-white'">
                                                    <i class="fas fa-check text-[10px]" x-show="selectedDivision == '{{ $division->id }}'"></i>
                                                </div>
                                            </div>

                                            <h4 class="text-[14px] font-bold text-gray-900 mt-3">{{ $division->name }}</h4>
                                            @if ($division->description)
                                                <p class="text-[12px] text-gray-500 mt-1 line-clamp-2 leading-relaxed">{{ $division->description }}</p>
                                            @endif

                                            @if ($division->skills && count($division->skills) > 0)
                                                <div class="flex flex-wrap gap-1.5 mt-3 pt-3 border-t border-gray-100">
                                                    @foreach (array_slice($division->skills, 0, 3) as $skill)
                                                        <span class="text-[10px] font-medium text-gray-500 bg-gray-100 rounded-md px-1.5 py-0.5">{{ $skill }}</span>
                                                    @endforeach
                                                    @if (count($division->skills) > 3)
                                                        <span class="text-[10px] font-medium text-gray-400 self-center">+{{ count($division->skills) - 3 }}</span>
                                                    @endif
                                                </div>
                                            @endif
                                        </label>
                                    @endforeach
                                </div>
                            @else
                                <div class="p-4 rounded-xl border border-gray-200 bg-[#F8FAFC] text-center text-[13px] text-gray-400">
                                    Divisi sedang disiapkan. Hubungi admin CSA.
                                </div>
                            @endif

                            @error('division_id')
                                <p class="text-[12px] text-red-500 flex items-center gap-1.5">
                                    <i class="fas fa-circle-exclamation text-[11px]"></i> {{ $message }}
                                </p>
                            @enderror
                        </fieldset>

                        {{-- Step 3 --}}
                        <fieldset class="space-y-5 pt-2">
                            <legend class="text-[14px] font-semibold text-[#1a73e8]">Keamanan akun</legend>

                            <div>
                                <label for="password" class="block text-[13px] font-medium text-gray-700 mb-1.5">Password</label>
                                <div class="relative">
                                    <input :type="showPassword ? 'text' : 'password'" name="password" id="password"
                                        placeholder="Minimal 8 karakter" autocomplete="new-password" required
                                        class="w-full h-12 pl-4 pr-12 rounded-xl bg-[#f1f3f4] text-[14px] text-gray-900 placeholder:text-gray-400 border border-transparent focus:border-[#1a73e8]/40 focus:bg-white focus:outline-none transition-colors @error('password') border-red-300 @enderror">
                                    <button type="button" @click="showPassword = !showPassword"
                                        class="absolute right-3 top-1/2 -translate-y-1/2 w-8 h-8 flex items-center justify-center rounded-lg text-gray-400 hover:text-gray-600 transition-colors"
                                        :aria-label="showPassword ? 'Sembunyikan password' : 'Tampilkan password'">
                                        <i class="fas text-[13px]" :class="showPassword ? 'fa-eye-slash' : 'fa-eye'"></i>
                                    </button>
                                </div>
                                <p class="text-[11px] text-gray-400 mt-1.5">Gunakan kombinasi huruf, angka, dan simbol.</p>
                                @error('password')<p class="text-[12px] text-red-500 mt-1.5">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label for="password-confirm" class="block text-[13px] font-medium text-gray-700 mb-1.5">Konfirmasi Password</label>
                                <input :type="showPassword ? 'text' : 'password'" name="password_confirmation" id="password-confirm"
                                    placeholder="Ulangi password" autocomplete="new-password" required
                                    class="w-full h-12 px-4 rounded-xl bg-[#f1f3f4] text-[14px] text-gray-900 placeholder:text-gray-400 border border-transparent focus:border-[#1a73e8]/40 focus:bg-white focus:outline-none transition-colors">
                            </div>

                            <p class="text-[12px] text-gray-400 leading-relaxed bg-[#F8FAFC] border border-gray-100 rounded-2xl px-4 py-3">
                                Dengan mendaftar, kamu menyetujui kebijakan penggunaan dan privasi platform CSA.
                            </p>
                        </fieldset>

                        <button type="submit"
                            class="w-full h-12 bg-[#1a73e8] text-white text-[14px] font-semibold rounded-xl hover:bg-[#1557b0] active:scale-[0.99] transition-all">
                            Buat akun
                        </button>

                        <p class="text-center text-[13px] text-gray-400">
                            Sudah punya akun?
                            <a href="{{ route('login') }}" class="font-semibold text-[#1a73e8] hover:underline">Masuk di sini</a>
                        </p>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
