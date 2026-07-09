@extends('layouts.app')

@section('title', 'Daftar Akun — CSA STMIKMI')
@section('meta_description', 'Buat akun CSA STMIKMI dan bergabunglah dengan komunitas mahasiswa inovatif STMIKMI.')

@section('content')

{{--
    Register Page
    Layout rationale: Mirrors the login panel structure for UX consistency.
    Four-field form sectioned with a subtle divider between identity and
    credential fields to guide the user's mental model through the form.
--}}
<div class="min-h-[calc(100svh-3.5rem)] flex flex-col items-center justify-center px-6 py-16 relative overflow-hidden">

    {{-- Structural dot-grid texture --}}
    <div class="absolute inset-0 bg-dot-grid opacity-40 pointer-events-none" aria-hidden="true"></div>

    {{-- Single restrained luminance source — top left --}}
    <div
        class="absolute -top-40 -left-40 w-96 h-96 rounded-full pointer-events-none"
        style="background: radial-gradient(circle at center, rgba(37,99,235,0.10) 0%, transparent 70%);"
        aria-hidden="true"
    ></div>

    <div class="relative z-10 w-full">
        <x-auth.card
            title="Buat akun baru"
            subtitle="Bergabung dengan CSA STMIKMI dan mulai berkarya bersama kami."
        >
            <form method="POST" action="{{ route('register') }}" class="space-y-5" novalidate>
                @csrf

                {{-- ── Identity fields ── --}}
                <x-ui.input
                    id="name"
                    label="Nama Lengkap"
                    type="text"
                    name="name"
                    :value="old('name')"
                    placeholder="Nama sesuai identitas"
                    :error="$errors->first('name')"
                    autocomplete="name"
                    required
                />

                <x-ui.input
                    id="email"
                    label="Alamat Email"
                    type="email"
                    name="email"
                    :value="old('email')"
                    placeholder="kamu@stmikmi.ac.id"
                    :error="$errors->first('email')"
                    :mono="true"
                    autocomplete="email"
                    required
                />

                {{-- Divider between identity and credential fields --}}
                <div class="relative flex items-center gap-3">
                    <span class="flex-1 border-t border-zinc-800"></span>
                    <span class="text-[11px] text-zinc-700 font-mono">keamanan akun</span>
                    <span class="flex-1 border-t border-zinc-800"></span>
                </div>

                {{-- ── Credential fields ── --}}
                <x-ui.input
                    id="password"
                    label="Password"
                    type="password"
                    name="password"
                    placeholder="Minimal 8 karakter"
                    :error="$errors->first('password')"
                    hint="Gunakan kombinasi huruf, angka, dan simbol."
                    autocomplete="new-password"
                    required
                />

                <x-ui.input
                    id="password-confirm"
                    label="Konfirmasi Password"
                    type="password"
                    name="password_confirmation"
                    placeholder="Ulangi password kamu"
                    autocomplete="new-password"
                    required
                />

                {{-- Policy note --}}
                <p class="text-[11px] text-zinc-600 leading-relaxed font-mono bg-zinc-900/60 border border-zinc-800/60 rounded px-3 py-2">
                    Dengan mendaftar, kamu menyetujui kebijakan penggunaan dan privasi platform CSA STMIKMI.
                </p>

                {{-- Submit --}}
                <x-ui.button type="submit" variant="primary" size="md" class="w-full">
                    Buat Akun
                    <svg class="w-4 h-4" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.75">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8h10M9 4l4 4-4 4"/>
                    </svg>
                </x-ui.button>

                {{-- Login link --}}
                <p class="text-center text-xs text-zinc-500">
                    Sudah punya akun?
                    <a href="{{ route('login') }}"
                       class="ml-1 font-semibold text-blue-400 hover:text-blue-300 transition-colors focus:outline-none focus-visible:underline">
                        Masuk di sini
                    </a>
                </p>
            </form>
        </x-auth.card>
    </div>
</div>

@endsection
