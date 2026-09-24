@extends('layouts.landing')

@php
    $setting = \App\Models\Setting::first();
    $regOpen = $setting?->isRegistrationOpen() ?? false;
    $levelDivisionIds = isset($divisions) ? $divisions->where('has_levels', true)->pluck('id')->map(fn ($id) => (string) $id)->all() : [];
@endphp

@section('content')
<div class="min-h-screen bg-[var(--paper-sunken)]">
    <div class="max-w-[1080px] mx-auto px-5 sm:px-8 pt-24 sm:pt-28 pb-16">
        <div class="grid lg:grid-cols-[1fr_minmax(0,520px)] gap-10 lg:gap-16 items-start">

            {{-- Left: intro --}}
            <div class="hidden lg:block lg:sticky lg:top-28" data-reveal="left">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-2.5 mb-10">
                    <img src="{{ asset('storage/system/logos/csa-logo.png') }}" alt="CSA" class="w-10 h-10 object-contain">
                    <span class="leading-tight">
                        <span class="block text-[15px] font-bold text-[var(--ink)] tracking-tight">CSA</span>
                        <span class="block text-[10px] text-[var(--ink-faint)] -mt-0.5">STMIK Mardira Indonesia</span>
                    </span>
                </a>

                <h1 class="text-[clamp(2rem,3.6vw,2.8rem)] font-bold text-[var(--ink)] leading-[1.12] tracking-[-0.03em]">
                    Mulai perjalananmu di komunitas kreatif CSA.
                </h1>
                <p class="text-[15px] text-[var(--ink-soft)] leading-relaxed mt-5 max-w-[440px]">
                    Isi data diri, pilih divisi minat, lalu atur keamanan akunmu. Hanya butuh satu menit.
                </p>

                <ol class="mt-10 space-y-6">
                    @foreach ([
                        ['01', 'Data diri', 'Nama, NIM, WhatsApp, dan email aktif.'],
                        ['02', 'Divisi minat', 'Pilih divisi yang paling sesuai jalur pengembanganmu.'],
                        ['03', 'Keamanan akun', 'Buat password yang kuat untuk akunmu.'],
                    ] as [$no, $title, $desc])
                        <li class="flex gap-4">
                            <span class="w-9 h-9 rounded-xl bg-[var(--paper)] border border-[var(--line)] flex items-center justify-center text-[12px] font-bold text-[var(--accent)] shrink-0">{{ $no }}</span>
                            <span>
                                <span class="block text-[14px] font-semibold text-[var(--ink)]">{{ $title }}</span>
                                <span class="block text-[13px] text-[var(--ink-soft)] mt-0.5 leading-relaxed">{{ $desc }}</span>
                            </span>
                        </li>
                    @endforeach
                </ol>
            </div>

            {{-- Right: form card --}}
            <div class="w-full max-w-xl mx-auto lg:mx-0" data-reveal="up">
                <div class="text-center lg:text-left mb-7">
                    <div class="w-12 h-12 rounded-2xl bg-[var(--paper)] border border-[var(--line)] shadow-sm flex items-center justify-center mx-auto lg:mx-0 mb-5 lg:hidden">
                        <img src="{{ asset('storage/system/logos/csa-logo.png') }}" alt="CSA" class="w-7 h-7 object-contain">
                    </div>
                    <h1 class="text-[22px] font-bold text-[var(--ink)] tracking-tight">Pendaftaran anggota baru</h1>
                    <p class="text-[14px] text-[var(--ink-faint)] mt-1.5">Lengkapi data berikut untuk membuat akun.</p>
                </div>

                @if (! $regOpen)
                    <div class="mb-5 rounded-2xl bg-amber-50 border border-amber-100 px-4 py-3.5 text-[13px] text-amber-700">
                        Pendaftaran sedang ditutup. Kamu masih bisa melihat formulir, namun pengiriman akan ditolak sampai admin membuka pendaftaran.
                    </div>
                @endif

                <div class="bg-[var(--paper)] rounded-xl border border-[var(--line)]/80 p-6 sm:p-8">
                    <form method="POST" action="{{ route('register') }}" class="space-y-6"
                        x-data="{
                            selectedDivision: '{{ old('division_id', '') }}',
                            selectedLevel: '{{ old('level', '') }}',
                            showPassword: false,
                            levelDivisions: @js($levelDivisionIds),
                            get needsLevel() { return this.levelDivisions.includes(String(this.selectedDivision)); }
                        }">
                        @csrf

                        {{-- Step 1 --}}
                        <fieldset class="space-y-5">
                            <legend class="text-[14px] font-semibold text-[var(--accent)]">Data diri</legend>

                            <div>
                                <label for="name" class="block text-[13px] font-medium text-[var(--ink)] mb-1.5">Nama Lengkap</label>
                                <input type="text" name="name" id="name" value="{{ old('name') }}"
                                    placeholder="Nama sesuai KTM" autocomplete="name" required
                                    class="w-full h-12 px-4 rounded-xl bg-[#f1f3f4] text-[14px] text-[var(--ink)] placeholder:text-[var(--ink-faint)] border border-transparent focus:border-[var(--accent)] focus:bg-[var(--paper)] focus:outline-none transition-colors @error('name') border-red-300 @enderror">
                                @error('name')<p class="text-[12px] text-red-500 mt-1.5">{{ $message }}</p>@enderror
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div>
                                    <label for="nim" class="block text-[13px] font-medium text-[var(--ink)] mb-1.5">NIM</label>
                                    <input type="text" name="nim" id="nim" value="{{ old('nim') }}" placeholder="21130001"
                                        inputmode="numeric" pattern="[0-9]*" oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                        autocomplete="off" required
                                        class="w-full h-12 px-4 rounded-xl bg-[#f1f3f4] text-[14px] text-[var(--ink)] placeholder:text-[var(--ink-faint)] border border-transparent focus:border-[var(--accent)] focus:bg-[var(--paper)] focus:outline-none transition-colors @error('nim') border-red-300 @enderror">
                                    @error('nim')<p class="text-[12px] text-red-500 mt-1.5">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label for="semester" class="block text-[13px] font-medium text-[var(--ink)] mb-1.5">Semester</label>
                                    <select name="semester" id="semester" required
                                        class="w-full h-12 px-4 rounded-xl bg-[#f1f3f4] text-[14px] text-[var(--ink)] border border-transparent focus:border-[var(--accent)] focus:bg-[var(--paper)] focus:outline-none transition-colors @error('semester') border-red-300 @enderror">
                                        <option value="" disabled @selected(old('semester') === null)>Pilih semester</option>
                                        @for ($i = 1; $i <= 8; $i++)
                                            <option value="{{ $i }}" @selected((int) old('semester') === $i)>Semester {{ $i }}</option>
                                        @endfor
                                    </select>
                                    @error('semester')<p class="text-[12px] text-red-500 mt-1.5">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label for="whatsapp" class="block text-[13px] font-medium text-[var(--ink)] mb-1.5">Nomor WhatsApp</label>
                                    <input type="tel" name="whatsapp" id="whatsapp" value="{{ old('whatsapp') }}" placeholder="081234567890"
                                        pattern="[0-9+\-\s]+" oninput="this.value = this.value.replace(/[^0-9+\-\s]/g, '')" required
                                        class="w-full h-12 px-4 rounded-xl bg-[#f1f3f4] text-[14px] text-[var(--ink)] placeholder:text-[var(--ink-faint)] border border-transparent focus:border-[var(--accent)] focus:bg-[var(--paper)] focus:outline-none transition-colors @error('whatsapp') border-red-300 @enderror">
                                    @error('whatsapp')<p class="text-[12px] text-red-500 mt-1.5">{{ $message }}</p>@enderror
                                </div>
                            </div>

                            <div>
                                <label for="email" class="block text-[13px] font-medium text-[var(--ink)] mb-1.5">Alamat Email</label>
                                <input type="email" name="email" id="email" value="{{ old('email') }}" placeholder="kamu@stmikmi.ac.id" autocomplete="email" required
                                    class="w-full h-12 px-4 rounded-xl bg-[#f1f3f4] text-[14px] text-[var(--ink)] placeholder:text-[var(--ink-faint)] border border-transparent focus:border-[var(--accent)] focus:bg-[var(--paper)] focus:outline-none transition-colors @error('email') border-red-300 @enderror">
                                @error('email')<p class="text-[12px] text-red-500 mt-1.5">{{ $message }}</p>@enderror
                            </div>
                        </fieldset>

                        {{-- Step 2 --}}
                        <fieldset class="space-y-4 pt-2">
                            <legend class="text-[14px] font-semibold text-[var(--accent)]">Divisi minat</legend>

                            @if (isset($divisions) && $divisions->count() > 0)
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    @foreach ($divisions as $division)
                                        @php $divImg = $division->image ? asset('storage/' . $division->image) : asset('storage/system/logos/csa-logo.png'); @endphp
                                        <label for="div_{{ $division->id }}" @click="selectedDivision = '{{ $division->id }}'"
                                            :class="selectedDivision == '{{ $division->id }}'
                                                ? 'border-[var(--accent)] bg-[var(--accent-soft)] ring-2 ring-[var(--accent)]/15'
                                                : 'border-[var(--line)] bg-[var(--paper)] hover:border-[var(--line-strong)]'"
                                            class="relative flex flex-col p-4 rounded-2xl border cursor-pointer transition-all duration-200 select-none">
                                            <input type="radio" name="division_id" id="div_{{ $division->id }}" value="{{ $division->id }}"
                                                x-model="selectedDivision" class="sr-only" required>

                                            <div class="flex items-start justify-between gap-3">
                                                <div class="w-11 h-11 rounded-xl bg-[var(--paper)] p-2 flex items-center justify-center border border-[var(--line)] shrink-0">
                                                    <img src="{{ $divImg }}" alt="{{ $division->name }}" class="w-full h-full object-contain">
                                                </div>
                                                <div class="w-5 h-5 rounded-full border flex items-center justify-center transition-colors"
                                                    :class="selectedDivision == '{{ $division->id }}' ? 'bg-[var(--accent-solid)] border-[var(--accent)] text-[var(--on-accent)]' : 'border-[var(--line-strong)] bg-[var(--paper)]'">
                                                    <i class="fas fa-check text-[10px]" x-show="selectedDivision == '{{ $division->id }}'"></i>
                                                </div>
                                            </div>

                                            <h4 class="text-[14px] font-bold text-[var(--ink)] mt-3">{{ $division->name }}</h4>
                                            @if ($division->description)
                                                <p class="text-[12px] text-[var(--ink-soft)] mt-1 line-clamp-2 leading-relaxed">{{ $division->description }}</p>
                                            @endif

                                            @if ($division->skills && count($division->skills) > 0)
                                                <div class="flex flex-wrap gap-1.5 mt-3 pt-3 border-t border-[var(--line)]">
                                                    @foreach (array_slice($division->skills, 0, 3) as $skill)
                                                        <span class="text-[10px] font-medium text-[var(--ink-soft)] bg-[var(--paper-sunken)] rounded-md px-1.5 py-0.5">{{ $skill }}</span>
                                                    @endforeach
                                                    @if (count($division->skills) > 3)
                                                        <span class="text-[10px] font-medium text-[var(--ink-faint)] self-center">+{{ count($division->skills) - 3 }}</span>
                                                    @endif
                                                </div>
                                            @endif
                                        </label>
                                    @endforeach
                                </div>
                            @else
                                <div class="p-4 rounded-xl border border-[var(--line)] bg-[var(--paper-sunken)] text-center text-[13px] text-[var(--ink-faint)]">
                                    Divisi sedang disiapkan. Hubungi admin CSA.
                                </div>
                            @endif

                            @error('division_id')
                                <p class="text-[12px] text-red-500 flex items-center gap-1.5">
                                    <i class="fas fa-circle-exclamation text-[11px]"></i> {{ $message }}
                                </p>
                            @enderror
                        </fieldset>

                        {{-- Step 2b: Tingkatan (hanya divisi bertingkat seperti Pemrograman) --}}
                        <fieldset class="space-y-4 pt-2" x-show="needsLevel" x-cloak>
                            <legend class="text-[14px] font-semibold text-[var(--accent)]">Tingkatan</legend>
                            <p class="text-[12px] text-[var(--ink-soft)] leading-relaxed">
                                Divisi ini punya tingkatan sesuai kemampuan. Pilih yang paling sesuai — bisa dinaikkan nanti setelah kamu bergabung.
                            </p>

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                @foreach (\App\Models\Member::LEVELS as $value => $level)
                                    <label for="level_{{ $value }}" @click="selectedLevel = '{{ $value }}'"
                                        :class="selectedLevel == '{{ $value }}'
                                            ? 'border-[var(--accent)] bg-[var(--accent-soft)] ring-2 ring-[var(--accent)]/15'
                                            : 'border-[var(--line)] bg-[var(--paper)] hover:border-[var(--line-strong)]'"
                                        class="relative flex flex-col p-4 rounded-2xl border cursor-pointer transition-all duration-200 select-none">
                                        <input type="radio" name="level" id="level_{{ $value }}" value="{{ $value }}"
                                            x-model="selectedLevel" class="sr-only" :required="needsLevel">
                                        <span class="text-[13px] font-bold text-[var(--ink)]">{{ $level['label'] }}</span>
                                        <span class="text-[12px] font-semibold text-[var(--accent)] mt-0.5">{{ $level['name'] }}</span>
                                        <span class="text-[11px] text-[var(--ink-soft)] mt-1 leading-relaxed">{{ $level['description'] }}</span>
                                    </label>
                                @endforeach
                            </div>

                            @error('level')
                                <p class="text-[12px] text-red-500 flex items-center gap-1.5">
                                    <i class="fas fa-circle-exclamation text-[11px]"></i> {{ $message }}
                                </p>
                            @enderror
                        </fieldset>

                        {{-- Step 3 --}}
                        <fieldset class="space-y-5 pt-2">
                            <legend class="text-[14px] font-semibold text-[var(--accent)]">Keamanan akun</legend>

                            <div>
                                <label for="password" class="block text-[13px] font-medium text-[var(--ink)] mb-1.5">Password</label>
                                <div class="relative">
                                    <input :type="showPassword ? 'text' : 'password'" name="password" id="password"
                                        placeholder="Minimal 8 karakter" autocomplete="new-password" required
                                        class="w-full h-12 pl-4 pr-12 rounded-xl bg-[#f1f3f4] text-[14px] text-[var(--ink)] placeholder:text-[var(--ink-faint)] border border-transparent focus:border-[var(--accent)] focus:bg-[var(--paper)] focus:outline-none transition-colors @error('password') border-red-300 @enderror">
                                    <button type="button" @click="showPassword = !showPassword"
                                        class="absolute right-3 top-1/2 -translate-y-1/2 w-8 h-8 flex items-center justify-center rounded-lg text-[var(--ink-faint)] hover:text-[var(--ink-soft)] transition-colors"
                                        :aria-label="showPassword ? 'Sembunyikan password' : 'Tampilkan password'">
                                        <i class="fas text-[13px]" :class="showPassword ? 'fa-eye-slash' : 'fa-eye'"></i>
                                    </button>
                                </div>
                                <p class="text-[11px] text-[var(--ink-faint)] mt-1.5">Gunakan kombinasi huruf, angka, dan simbol.</p>
                                @error('password')<p class="text-[12px] text-red-500 mt-1.5">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label for="password-confirm" class="block text-[13px] font-medium text-[var(--ink)] mb-1.5">Konfirmasi Password</label>
                                <input :type="showPassword ? 'text' : 'password'" name="password_confirmation" id="password-confirm"
                                    placeholder="Ulangi password" autocomplete="new-password" required
                                    class="w-full h-12 px-4 rounded-xl bg-[#f1f3f4] text-[14px] text-[var(--ink)] placeholder:text-[var(--ink-faint)] border border-transparent focus:border-[var(--accent)] focus:bg-[var(--paper)] focus:outline-none transition-colors">
                            </div>

                            <p class="text-[12px] text-[var(--ink-faint)] leading-relaxed bg-[var(--paper-sunken)] border border-[var(--line)] rounded-2xl px-4 py-3">
                                Dengan mendaftar, kamu menyetujui kebijakan penggunaan dan privasi platform CSA.
                            </p>
                        </fieldset>

                        <button type="submit"
                            class="w-full h-12 bg-[var(--accent-solid)] text-[var(--on-accent)] text-[14px] font-semibold rounded-xl hover:opacity-90 active:scale-[0.99] transition-all">
                            Buat akun
                        </button>

                        <p class="text-center text-[13px] text-[var(--ink-faint)]">
                            Sudah punya akun?
                            <a href="{{ route('login') }}" class="font-semibold text-[var(--accent)] hover:underline">Masuk di sini</a>
                        </p>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
