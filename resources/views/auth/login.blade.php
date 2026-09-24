@extends('layouts.landing')

@php
    $regOpen = \App\Models\Setting::registrationOpen();
@endphp

@section('content')
<div class="min-h-screen bg-[var(--paper-sunken)]">
    <div class="max-w-[1080px] mx-auto px-5 sm:px-8 pt-24 sm:pt-28 pb-16">
        <div class="grid lg:grid-cols-2 gap-10 lg:gap-16 items-center">

            {{-- Left: intro --}}
            <div class="hidden lg:block" data-reveal="left">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-2.5 mb-10">
                    <img src="{{ asset('storage/system/logos/csa-logo.png') }}" alt="CSA" class="w-10 h-10 object-contain">
                    <span class="leading-tight">
                        <span class="block text-[15px] font-bold text-[var(--ink)] tracking-tight">CSA</span>
                        <span class="block text-[10px] text-[var(--ink-faint)] -mt-0.5">STMIK Mardira Indonesia</span>
                    </span>
                </a>

                <h1 class="text-[clamp(2rem,3.6vw,2.8rem)] font-bold text-[var(--ink)] leading-[1.12] tracking-[-0.03em]">
                    Selamat datang kembali di komunitas kreatif CSA.
                </h1>
                <p class="text-[15px] text-[var(--ink-soft)] leading-relaxed mt-5 max-w-[440px]">
                    Masuk untuk mengakses kegiatan, presensi, dokumen, dan seluruh aktivitas anggota.
                </p>

                <ul class="mt-8 space-y-3.5">
                    @foreach (['Presensi kegiatan & riwayat kehadiran', 'Akses berita, dokumen, dan galeri', 'Kelola profil dan divisi kamu'] as $item)
                        <li class="flex items-center gap-3 text-[14px] text-[var(--ink-soft)]">
                            <span class="w-6 h-6 rounded-full bg-[var(--accent-soft)] text-[var(--accent)] flex items-center justify-center shrink-0">
                                <i class="fas fa-check text-[10px]"></i>
                            </span>
                            {{ $item }}
                        </li>
                    @endforeach
                </ul>
            </div>

            {{-- Right: form card --}}
            <div class="w-full max-w-md mx-auto lg:mx-0" data-reveal="up">
                <div class="text-center lg:text-left mb-7">
                    <div class="w-12 h-12 rounded-2xl bg-[var(--paper)] border border-[var(--line)] shadow-sm flex items-center justify-center mx-auto lg:mx-0 mb-5 lg:hidden">
                        <img src="{{ asset('storage/system/logos/csa-logo.png') }}" alt="CSA" class="w-7 h-7 object-contain">
                    </div>
                    <h1 class="text-[22px] font-bold text-[var(--ink)] tracking-tight">Masuk ke akun</h1>
                    <p class="text-[14px] text-[var(--ink-faint)] mt-1.5">Lanjutkan aktivitasmu di CSA.</p>
                </div>

                <div class="bg-[var(--paper)] rounded-xl border border-[var(--line)]/80 p-6 sm:p-8">
                    <form method="POST" action="{{ route('login') }}" class="space-y-5" x-data="{ show: false }">
                        @csrf

                        <div>
                            <label for="email" class="block text-[13px] font-medium text-[var(--ink)] mb-1.5">Alamat Email</label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}"
                                placeholder="kamu@stmikmi.ac.id" autocomplete="email" required autofocus
                                class="w-full h-12 px-4 rounded-xl bg-[#f1f3f4] text-[14px] text-[var(--ink)] placeholder:text-[var(--ink-faint)] border border-transparent focus:border-[var(--accent)] focus:bg-[var(--paper)] focus:outline-none transition-colors @error('email') border-red-300 @enderror">
                            @error('email')
                                <p class="text-[12px] text-red-500 mt-1.5">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <label for="password" class="block text-[13px] font-medium text-[var(--ink)]">Password</label>
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}" class="text-[12px] font-medium text-[var(--accent)] hover:underline">Lupa password?</a>
                                @endif
                            </div>
                            <div class="relative">
                                <input :type="show ? 'text' : 'password'" name="password" id="password"
                                    placeholder="Masukkan password" autocomplete="current-password" required
                                    class="w-full h-12 pl-4 pr-12 rounded-xl bg-[#f1f3f4] text-[14px] text-[var(--ink)] placeholder:text-[var(--ink-faint)] border border-transparent focus:border-[var(--accent)] focus:bg-[var(--paper)] focus:outline-none transition-colors @error('password') border-red-300 @enderror">
                                <button type="button" @click="show = !show"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 w-8 h-8 flex items-center justify-center rounded-lg text-[var(--ink-faint)] hover:text-[var(--ink-soft)] transition-colors"
                                    :aria-label="show ? 'Sembunyikan password' : 'Tampilkan password'">
                                    <i class="fas text-[13px]" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                                </button>
                            </div>
                            @error('password')
                                <p class="text-[12px] text-red-500 mt-1.5">{{ $message }}</p>
                            @enderror
                        </div>

                        <label class="flex items-center gap-2.5 cursor-pointer select-none">
                            <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}
                                class="w-4 h-4 rounded border-[var(--line-strong)] text-[var(--accent)] focus:ring-[var(--accent)]/30">
                            <span class="text-[13px] text-[var(--ink-soft)]">Ingat saya di perangkat ini</span>
                        </label>

                        <button type="submit"
                            class="w-full h-12 bg-[var(--accent-solid)] text-[var(--on-accent)] text-[14px] font-semibold rounded-xl hover:opacity-90 active:scale-[0.99] transition-all">
                            Masuk
                        </button>

                        <p class="text-center text-[13px] text-[var(--ink-faint)] pt-1">
                            Belum punya akun?
                            @if ($regOpen)
                                <a href="{{ route('register') }}" class="font-semibold text-[var(--accent)] hover:underline">Daftar sekarang</a>
                            @else
                                <button type="button" onclick="window.dispatchEvent(new CustomEvent('open-register-modal'))" class="font-semibold text-[var(--accent)] hover:underline cursor-pointer bg-transparent border-0 p-0">
                                    Daftar sekarang
                                </button>
                            @endif
                        </p>
                    </form>
                </div>

                <p class="text-center lg:text-left text-[12px] text-[var(--ink-faint)] mt-5">
                    <a href="{{ route('home') }}" class="hover:text-[var(--accent)] transition-colors"><i class="fas fa-arrow-left text-[10px] mr-1"></i>Kembali ke beranda</a>
                </p>
            </div>

        </div>
    </div>
</div>
@endsection
