@extends('layouts.landing')

@section('content')
<div class="min-h-screen flex items-center justify-center px-4 sm:px-6 py-20">
    <div class="w-full max-w-xl">

        {{-- Top Success Animation / Icon --}}
        <div class="text-center mb-8">
            <div class="relative w-20 h-20 mx-auto mb-5">
                <div class="absolute inset-0 rounded-3xl bg-emerald-400/20 blur-xl animate-pulse"></div>
                <div class="relative w-20 h-20 rounded-3xl bg-gradient-to-tr from-emerald-500 to-teal-400 shadow-[0_10px_30px_-6px_rgba(16,185,129,0.4)] flex items-center justify-center text-white text-3xl">
                    <i class="fas fa-check"></i>
                </div>
            </div>

            <span class="inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full text-[11px] font-bold tracking-wider uppercase bg-emerald-50 text-emerald-700 border border-emerald-200/70 mb-3">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-ping"></span>
                Pendaftaran Berhasil
            </span>

            <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight">
                Selamat Datang di UKM CSA!
            </h1>
            <p class="text-[14px] sm:text-[15px] text-gray-500 mt-2 max-w-md mx-auto leading-relaxed">
                Akunmu telah aktif. Kamu kini resmi bergabung dalam perjalanan berkarya bersama Creative Student Association.
            </p>
        </div>

        {{-- Main Container Card --}}
        <div class="relative rounded-[32px] border border-white/50 bg-white/70 backdrop-blur-2xl shadow-[0_16px_48px_-16px_rgba(0,0,0,0.08)] p-6 sm:p-9 space-y-6">
            <div class="absolute inset-0 rounded-[32px] bg-gradient-to-b from-white/60 via-transparent to-transparent pointer-events-none"></div>

            {{-- Recap Card: Division & Info --}}
            <div class="relative bg-gray-50/70 border border-gray-100/90 rounded-[22px] p-5">
                <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-3.5">
                    Data Pendaftaran Anda
                </p>

                <div class="space-y-3">
                    {{-- Selected Division --}}
                    @if ($member && $member->division)
                        <div class="flex items-center gap-3.5 bg-white rounded-2xl p-3 border border-gray-100 shadow-sm">
                            <div class="w-12 h-12 rounded-xl bg-[#3664AF]/5 border border-[#3664AF]/10 p-2 flex items-center justify-center shrink-0">
                                @if ($member->division->image)
                                    <img src="{{ asset('storage/' . $member->division->image) }}" alt="{{ $member->division->name }}" class="w-full h-full object-contain">
                                @else
                                    <img src="{{ asset('storage/system/logos/csa-logo.png') }}" alt="{{ $member->division->name }}" class="w-full h-full object-contain">
                                @endif
                            </div>
                            <div class="min-w-0 flex-1">
                                <span class="inline-block text-[10px] font-bold tracking-wider text-blue-600 uppercase">Divisi Terpilih</span>
                                <h3 class="text-[14px] font-bold text-gray-900 truncate">{{ $member->division->name }}</h3>
                            </div>
                            <span class="text-xs font-semibold px-2.5 py-1 rounded-lg bg-blue-50 text-blue-700 border border-blue-100 shrink-0">
                                Anggota
                            </span>
                        </div>
                    @endif

                    {{-- Name, Email & WhatsApp --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-[12px] pt-1">
                        <div class="bg-white/90 rounded-xl px-3.5 py-2.5 border border-gray-100/80">
                            <span class="text-gray-400 block text-[10px] uppercase font-semibold">Nama Lengkap</span>
                            <span class="font-semibold text-gray-800">{{ $user->name }}</span>
                        </div>
                        <div class="bg-white/90 rounded-xl px-3.5 py-2.5 border border-gray-100/80">
                            <span class="text-gray-400 block text-[10px] uppercase font-semibold">Nomor WhatsApp</span>
                            <span class="font-semibold text-gray-800">{{ $user->whatsapp ?? '-' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- WHATSAPP GROUP CALLOUT --}}
            <div class="relative overflow-hidden rounded-[26px] bg-gradient-to-br from-emerald-500/10 via-emerald-500/5 to-teal-500/10 border border-emerald-500/20 p-6 text-center">
                <div class="absolute top-0 right-0 -mr-6 -mt-6 w-28 h-28 bg-emerald-400/15 rounded-full blur-2xl pointer-events-none"></div>

                <div class="w-14 h-14 rounded-2xl bg-emerald-500 text-white flex items-center justify-center text-2xl shadow-[0_8px_20px_-4px_rgba(16,185,129,0.45)] mx-auto mb-4">
                    <i class="fab fa-whatsapp"></i>
                </div>

                <h3 class="text-[17px] font-bold text-gray-900 leading-snug">
                    Langkah Wajib: Masuk Grup WhatsApp CSA
                </h3>
                <p class="text-[13px] text-gray-600 leading-relaxed mt-1.5 max-w-md mx-auto">
                    Bergabunglah ke grup WhatsApp resmi UKM CSA untuk mendapatkan info orientasi, jadwal kumpul perdana, dan diskusi bersama pengurus serta sesama anggota.
                </p>

                <div class="mt-5">
                    @if ($whatsappGroupLink)
                        <a href="{{ $whatsappGroupLink }}" target="_blank" rel="noopener noreferrer"
                           class="inline-flex items-center justify-center gap-2.5 w-full py-3.5 px-6 bg-emerald-500 hover:bg-emerald-600 active:scale-[0.98] text-white text-[14px] font-bold rounded-2xl shadow-[0_6px_24px_-4px_rgba(16,185,129,0.38)] hover:shadow-[0_10px_30px_-4px_rgba(16,185,129,0.5)] transition-all duration-200">
                            <i class="fab fa-whatsapp text-xl"></i>
                            <span>Gabung Grup WhatsApp Sekarang</span>
                            <i class="fas fa-arrow-up-right-from-square text-[11px] ml-1 opacity-80"></i>
                        </a>
                        <p class="text-[11px] text-gray-400 mt-2">
                            Tautan resmi terverifikasi &middot; Dibuka di aplikasi WhatsApp
                        </p>
                    @else
                        <div class="p-3.5 rounded-xl bg-white/80 border border-emerald-200/60 text-xs text-gray-600 leading-relaxed">
                            <i class="fas fa-info-circle text-emerald-500 mr-1"></i>
                            Link grup WhatsApp sedang diperbarui oleh pengurus. Silakan pantau pesan WhatsApp yang akan dikirimkan ke nomor Anda.
                        </div>
                    @endif
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="pt-2 flex flex-col sm:flex-row items-center gap-3">
                <a href="{{ route('admin.dashboard', ['roleSlug' => $user->role->slug ?? 'anggota']) }}"
                   class="inline-flex items-center justify-center gap-2 w-full py-3.5 px-6 bg-gray-900 hover:bg-gray-800 text-white text-[13px] font-semibold rounded-2xl shadow-[0_4px_16px_-4px_rgba(0,0,0,0.15)] hover:scale-[1.01] active:scale-[0.99] transition-all duration-200">
                    <span>Lanjut ke Dashboard Saya</span>
                    <i class="fas fa-arrow-right text-[11px]"></i>
                </a>

                <a href="{{ route('home') }}"
                   class="inline-flex items-center justify-center py-3.5 px-5 text-gray-500 hover:text-gray-900 text-[13px] font-semibold rounded-2xl hover:bg-gray-100 transition-all duration-200 w-full sm:w-auto">
                    Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
