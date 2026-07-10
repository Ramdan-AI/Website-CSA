@extends('layouts.app')

@section('title', 'Masuk — CSA STMIKMI')
@section('meta_description', 'Masuk ke akun CSA STMIKMI kamu.')

@section('content')

<div class="min-h-[calc(100svh-3.5rem)] bg-gray-50 flex flex-col items-center justify-center px-6 py-16">

    <div class="w-full">
        <x-auth.card
            title="Selamat datang kembali"
            subtitle="Masuk ke akun CSA STMIKMI kamu untuk melanjutkan."
        >
            <form method="POST" action="{{ route('login') }}" class="space-y-5" novalidate>
                @csrf

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

                <x-ui.input
                    id="password"
                    label="Password"
                    type="password"
                    name="password"
                    placeholder="••••••••••"
                    :error="$errors->first('password')"
                    autocomplete="current-password"
                    required
                />

                {{-- Remember + Forgot --}}
                <div class="flex items-center justify-between pt-1">
                    <label class="flex items-center gap-2 cursor-pointer group" for="remember">
                        <input
                            type="checkbox"
                            id="remember"
                            name="remember"
                            {{ old('remember') ? 'checked' : '' }}
                            class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500 transition-colors"
                        >
                        <span class="text-sm text-gray-600 group-hover:text-gray-900 transition-colors select-none">Ingat saya</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}"
                           class="text-sm text-blue-600 hover:text-blue-700 transition-colors focus:outline-none focus-visible:underline">
                            Lupa password?
                        </a>
                    @endif
                </div>

                {{-- Submit --}}
                <x-ui.button type="submit" variant="primary" size="md" class="w-full mt-2">
                    Masuk ke Akun
                    <svg class="w-4 h-4" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.75">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8h10M9 4l4 4-4 4"/>
                    </svg>
                </x-ui.button>

                {{-- Divider --}}
                <div class="relative flex items-center gap-3 py-1">
                    <span class="flex-1 border-t border-gray-200"></span>
                    <span class="text-xs text-gray-400">atau</span>
                    <span class="flex-1 border-t border-gray-200"></span>
                </div>

                {{-- Register link --}}
                <p class="text-center text-sm text-gray-500">
                    Belum punya akun?
                    <a href="{{ route('register') }}"
                       class="ml-1 font-semibold text-blue-600 hover:text-blue-700 transition-colors focus:outline-none focus-visible:underline">
                        Daftar sekarang
                    </a>
                </p>
            </form>
        </x-auth.card>
    </div>
</div>

@endsection
