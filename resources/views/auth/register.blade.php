@extends('layouts.landing')

@section('content')
<div class="min-h-screen flex items-center justify-center px-4 sm:px-6 py-20">
    <div class="w-full max-w-xl">

        <div class="text-center mb-8">
            <div class="w-14 h-14 rounded-2xl bg-white shadow-[0_4px_16px_-4px_rgba(0,0,0,0.06),inset_0_0.5px_0_rgba(255,255,255,0.8)] flex items-center justify-center mx-auto mb-5">
                <img src="{{ asset('storage/system/logos/csa-logo.png') }}" alt="CSA" class="w-9 h-9 object-contain">
            </div>
            <h1 class="text-[24px] font-bold text-gray-900 tracking-tight">Pendaftaran Anggota Baru</h1>
            <p class="text-[14px] text-gray-400 mt-2">Bergabung dengan CSA STMIK Mardira Indonesia dan mulai berkarya bersama kami.</p>
        </div>

        <div class="relative rounded-[28px] border border-white/40 bg-white/60 backdrop-blur-2xl shadow-[0_12px_40px_-12px_rgba(0,0,0,0.08)] p-6 sm:p-9">
            <div class="absolute inset-0 rounded-[28px] bg-gradient-to-b from-white/40 via-transparent to-transparent pointer-events-none"></div>

            <form method="POST" action="{{ route('register') }}" class="relative space-y-5" x-data="{ selectedDivision: '{{ old('division_id', '') }}' }">
                @csrf

                <div>
                    <label for="name" class="block text-[13px] font-semibold text-gray-700 mb-1.5">Nama Lengkap</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}"
                        placeholder="Nama sesuai identitas / KTM" autocomplete="name" required
                        class="w-full px-4 py-3 rounded-[14px] border border-gray-200/80 text-[13px] focus:outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-400/15 transition-all bg-white/80 @error('name') border-red-300 @enderror">
                    @error('name')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="nim" class="block text-[13px] font-semibold text-gray-700 mb-1.5">NIM <span class="text-red-500">*</span></label>
                        <input type="text" name="nim" id="nim" value="{{ old('nim') }}"
                            placeholder="Contoh: 21130001" required
                            class="w-full px-4 py-3 rounded-[14px] border border-gray-200/80 text-[13px] focus:outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-400/15 transition-all bg-white/80 @error('nim') border-red-300 @enderror">
                        @error('nim')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="whatsapp" class="block text-[13px] font-semibold text-gray-700 mb-1.5">Nomor WhatsApp</label>
                        <input type="tel" name="whatsapp" id="whatsapp" value="{{ old('whatsapp') }}"
                            placeholder="081234567890" pattern="[0-9+\-\s]+"
                            oninput="this.value = this.value.replace(/[^0-9+\-\s]/g, '')" required
                            class="w-full px-4 py-3 rounded-[14px] border border-gray-200/80 text-[13px] focus:outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-400/15 transition-all bg-white/80 @error('whatsapp') border-red-300 @enderror">
                        @error('whatsapp')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="email" class="block text-[13px] font-semibold text-gray-700 mb-1.5">Alamat Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}"
                        placeholder="kamu@stmikmi.ac.id" autocomplete="email" required
                        class="w-full px-4 py-3 rounded-[14px] border border-gray-200/80 text-[13px] focus:outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-400/15 transition-all bg-white/80 @error('email') border-red-300 @enderror">
                    @error('email')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- PEMILIHAN DIVISI --}}
                <div class="pt-2">
                    <div class="flex items-center justify-between mb-2">
                        <label class="block text-[13px] font-semibold text-gray-800">
                            Pilih Divisi Minat <span class="text-red-500">*</span>
                        </label>
                        <span class="text-[11px] text-gray-400">Pilih salah satu</span>
                    </div>
                    <p class="text-[12px] text-gray-400 mb-3">Tentukan divisi yang paling sesuai dengan minat dan jalur pengembanganmu.</p>

                    @if (isset($divisions) && $divisions->count() > 0)
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                            @foreach ($divisions as $division)
                                @php
                                    $divImg = $division->image ? asset('storage/' . $division->image) : asset('storage/system/logos/csa-logo.png');
                                @endphp
                                <label for="div_{{ $division->id }}"
                                    @click="selectedDivision = '{{ $division->id }}'"
                                    :class="selectedDivision == '{{ $division->id }}' 
                                        ? 'border-blue-600 bg-blue-50/40 shadow-[0_4px_20px_-4px_rgba(59,130,246,0.18)] ring-2 ring-blue-500/20' 
                                        : 'border-gray-200/90 bg-white/70 hover:border-gray-300 hover:bg-white/90'"
                                    class="relative group flex flex-col justify-between p-4 rounded-[18px] border cursor-pointer transition-all duration-200 select-none">
                                    
                                    <input type="radio" 
                                           name="division_id" 
                                           id="div_{{ $division->id }}" 
                                           value="{{ $division->id }}"
                                           x-model="selectedDivision"
                                           class="sr-only" required>

                                    <div>
                                        <div class="flex items-start justify-between gap-3 mb-3">
                                            <div class="w-12 h-12 rounded-xl bg-white p-2 flex items-center justify-center border border-gray-100 shadow-sm shrink-0">
                                                <img src="{{ $divImg }}" alt="{{ $division->name }}" class="w-full h-full object-contain">
                                            </div>

                                            <div class="w-5 h-5 rounded-full border flex items-center justify-center transition-colors"
                                                 :class="selectedDivision == '{{ $division->id }}' ? 'bg-blue-600 border-blue-600 text-white' : 'border-gray-300 bg-white'">
                                                <i class="fas fa-check text-[10px]" x-show="selectedDivision == '{{ $division->id }}'"></i>
                                            </div>
                                        </div>

                                        <h4 class="text-[14px] font-bold text-gray-900 leading-snug group-hover:text-blue-600 transition-colors">
                                            {{ $division->name }}
                                        </h4>

                                        @if ($division->description)
                                            <p class="text-[11px] text-gray-500 mt-1 line-clamp-2 leading-relaxed">
                                                {{ $division->description }}
                                            </p>
                                        @endif
                                    </div>

                                    @if ($division->skills && count($division->skills) > 0)
                                        <div class="flex flex-wrap gap-1 mt-3 pt-3 border-t border-gray-100">
                                            @foreach (array_slice($division->skills, 0, 3) as $skill)
                                                <span class="text-[9px] font-medium text-gray-500 bg-gray-100/90 rounded-md px-1.5 py-0.5">
                                                    {{ $skill }}
                                                </span>
                                            @endforeach
                                            @if (count($division->skills) > 3)
                                                <span class="text-[9px] font-medium text-gray-400 self-center">
                                                    +{{ count($division->skills) - 3 }}
                                                </span>
                                            @endif
                                        </div>
                                    @endif
                                </label>
                            @endforeach
                        </div>
                    @else
                        <div class="p-4 rounded-xl border border-gray-200 bg-white text-center text-xs text-gray-400">
                            Divisi sedang disiapkan. Hubungi admin CSA.
                        </div>
                    @endif

                    @error('division_id')
                        <p class="text-xs text-red-500 mt-2 font-medium flex items-center gap-1.5">
                            <i class="fas fa-circle-exclamation text-[11px]"></i>
                            <span>{{ $message }}</span>
                        </p>
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
