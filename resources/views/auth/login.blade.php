@extends('layouts.app')

@section('title', 'Masuk — CSA STMIKMI')
@section('meta_description', 'Masuk ke akun CSA STMIKMI kamu.')

@section('content')

{{--
    Login Page
    Layout rationale: Single-column centered card isolates the auth task from
    surrounding context. The dot-grid and one restrained light source establish
    spatial depth without distracting from the form controls.
--}}
<div class="min-h-[calc(100svh-3.5rem)] flex flex-col items-center justify-center px-6 py-16 relative overflow-hidden">

    {{-- Structural dot-grid texture --}}
    <div class="absolute inset-0 bg-dot-grid opacity-40 pointer-events-none" aria-hidden="true"></div>

    {{-- Single restrained luminance source — bottom right --}}
    <div
        class="absolute -bottom-40 -right-40 w-96 h-96 rounded-full pointer-events-none"
        style="background: radial-gradient(circle at center, rgba(37,99,235,0.10) 0%, transparent 70%);"
        aria-hidden="true"
    ></div>

    <div class="relative z-10 w-full">
        <x-auth.card
            title="Selamat datang kembali"
            subtitle="Masuk ke akun CSA STMIKMI kamu untuk melanjutkan."
        >
            <form method="POST" action="{{ route('login') }}" class="space-y-5" novalidate>
                @csrf

                {{-- Email --}}
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

                {{-- Password --}}
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
                            class="h-3.5 w-3.5 rounded-sm border-zinc-700 bg-zinc-900 text-blue-600 focus:ring-blue-500 focus:ring-offset-zinc-950 transition-colors"
                        >
                        <span class="text-xs text-zinc-500 group-hover:text-zinc-300 transition-colors select-none">Ingat saya</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}"
                           class="text-xs text-zinc-500 hover:text-zinc-300 transition-colors font-mono focus:outline-none focus-visible:underline">
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
                    <span class="flex-1 border-t border-zinc-800"></span>
                    <span class="text-[11px] text-zinc-600 font-mono">atau</span>
                    <span class="flex-1 border-t border-zinc-800"></span>
                </div>

                {{-- Register link --}}
                <p class="text-center text-xs text-zinc-500">
                    Belum punya akun?
                    <a href="{{ route('register') }}"
                       class="ml-1 font-semibold text-blue-400 hover:text-blue-300 transition-colors focus:outline-none focus-visible:underline">
                        Daftar sekarang
                    </a>
                </p>
            </form>
        </x-auth.card>
    </div>
</div>

@endsection
