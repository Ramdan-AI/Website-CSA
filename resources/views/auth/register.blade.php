@extends('layouts.landing')

@section('content')
<div class="min-h-screen flex items-center justify-center px-6 py-24">
    <div class="w-full max-w-md">

        <div class="text-center mb-8">
            <div class="w-14 h-14 rounded-2xl bg-white shadow-[0_4px_16px_-4px_rgba(0,0,0,0.06),inset_0_0.5px_0_rgba(255,255,255,0.8)] flex items-center justify-center mx-auto mb-5">
                <img src="{{ asset('storage/system/logos/csa-logo.png') }}" alt="CSA" class="w-9 h-9 object-contain">
            </div>
            <h1 class="text-[22px] font-bold text-gray-900 tracking-tight">Buat akun baru</h1>
            <p class="text-[14px] text-gray-400 mt-2">Bergabung dengan CSA dan mulai berkarya bersama kami.</p>
        </div>

        <div class="relative rounded-[28px] border border-white/20 bg-white/40 backdrop-blur-2xl shadow-[0_8px_32px_-8px_rgba(0,0,0,0.08)] p-8">
            <div class="absolute inset-0 rounded-[28px] bg-gradient-to-b from-white/30 via-transparent to-transparent pointer-events-none"></div>

            <form method="POST" action="{{ route('register') }}" class="relative space-y-5">
                @csrf

                <div>
                    <label for="name" class="block text-[13px] font-semibold text-gray-700 mb-1.5">Nama Lengkap</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}"
                        placeholder="Nama sesuai identitas" autocomplete="name" required
                        class="w-full px-4 py-3 rounded-[14px] border border-gray-200/80 text-[13px] focus:outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-400/15 transition-all bg-white/70 @error('name') border-red-300 @enderror">
                    @error('name')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-[13px] font-semibold text-gray-700 mb-1.5">Alamat Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}"
                        placeholder="kamu@stmikmi.ac.id" autocomplete="email" required
                        class="w-full px-4 py-3 rounded-[14px] border border-gray-200/80 text-[13px] focus:outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-400/15 transition-all bg-white/70 @error('email') border-red-300 @enderror">
                    @error('email')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="whatsapp" class="block text-[13px] font-semibold text-gray-700 mb-1.5">Nomor WhatsApp</label>
                    <input type="text" name="whatsapp" id="whatsapp" value="{{ old('whatsapp') }}"
                        placeholder="Contoh: 081234567890" required
                        class="w-full px-4 py-3 rounded-[14px] border border-gray-200/80 text-[13px] focus:outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-400/15 transition-all bg-white/70 @error('whatsapp') border-red-300 @enderror">
                    @error('whatsapp')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="relative flex items-center gap-3">
                    <span class="flex-1 border-t border-gray-100"></span>
                    <span class="text-xs text-gray-300 font-medium">keamanan akun</span>
                    <span class="flex-1 border-t border-gray-100"></span>
                </div>

                <div>
                    <label for="password" class="block text-[13px] font-semibold text-gray-700 mb-1.5">Password</label>
                    <input type="password" name="password" id="password"
                        placeholder="Minimal 8 karakter" autocomplete="new-password" required
                        class="w-full px-4 py-3 rounded-[14px] border border-gray-200/80 text-[13px] focus:outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-400/15 transition-all bg-white/70 @error('password') border-red-300 @enderror">
                    <p class="text-[11px] text-gray-400 mt-1.5">Gunakan kombinasi huruf, angka, dan simbol.</p>
                    @error('password')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password-confirm" class="block text-[13px] font-semibold text-gray-700 mb-1.5">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" id="password-confirm"
                        placeholder="Ulangi password kamu" autocomplete="new-password" required
                        class="w-full px-4 py-3 rounded-[14px] border border-gray-200/80 text-[13px] focus:outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-400/15 transition-all bg-white/70">
                </div>

                <p class="text-xs text-gray-400 leading-relaxed bg-gray-50/40 border border-gray-100/80 rounded-2xl px-4 py-3">
                    Dengan mendaftar, kamu menyetujui kebijakan penggunaan dan privasi platform CSA.
                </p>

                <button type="submit"
                    class="w-full py-2.5 bg-gray-900 text-white text-[13px] font-semibold rounded-2xl shadow-[0_4px_16px_-4px_rgba(0,0,0,0.15)] hover:shadow-[0_8px_24px_-6px_rgba(0,0,0,0.25)] hover:scale-[1.02] active:scale-[0.98] transition-all duration-300">
                    Buat Akun
                </button>

                <div class="relative flex items-center gap-3">
                    <span class="flex-1 border-t border-gray-100"></span>
                    <span class="text-xs text-gray-300 font-medium">atau</span>
                    <span class="flex-1 border-t border-gray-100"></span>
                </div>

                <p class="text-center text-[13px] text-gray-400">
                    Sudah punya akun?
                    <a href="{{ route('login') }}" class="font-semibold text-gray-900 hover:text-blue-600 transition-colors">
                        Masuk di sini
                    </a>
                </p>
            </form>
        </div>
    </div>
</div>
@endsection
