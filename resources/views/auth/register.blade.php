@extends('layouts.app')

@section('title', 'Daftar Akun — CSA STMIKMI')
@section('meta_description', 'Buat akun CSA STMIKMI dan bergabunglah dengan komunitas mahasiswa inovatif STMIKMI.')

@section('content')

<div class="min-h-[calc(100svh-3.5rem)] bg-gray-50 flex flex-col items-center justify-center px-6 py-16">

    <div class="w-full">
        <x-auth.card
            title="Buat akun baru"
            subtitle="Bergabung dengan CSA STMIKMI dan mulai berkarya bersama kami."
        >
            <form method="POST" action="{{ route('register') }}" class="space-y-5" novalidate>
                @csrf

                {{-- Identity fields --}}
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

                {{-- Divider antara identitas dan keamanan akun --}}
                <div class="relative flex items-center gap-3">
                    <span class="flex-1 border-t border-gray-200"></span>
                    <span class="text-xs text-gray-400">keamanan akun</span>
                    <span class="flex-1 border-t border-gray-200"></span>
                </div>

                {{-- Credential fields --}}
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
                <p class="text-xs text-gray-500 leading-relaxed bg-gray-50 border border-gray-200 rounded-lg px-4 py-3">
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
                <p class="text-center text-sm text-gray-500">
                    Sudah punya akun?
                    <a href="{{ route('login') }}"
                       class="ml-1 font-semibold text-blue-600 hover:text-blue-700 transition-colors focus:outline-none focus-visible:underline">
                        Masuk di sini
                    </a>
                </p>
            </form>
        </x-auth.card>
    </div>
</div>

@endsection
