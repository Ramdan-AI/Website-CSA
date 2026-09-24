@extends('layouts.admin')

@php
    $mode = old('registration_mode', $setting->registration_use_schedule
        ? 'schedule'
        : ($setting->registration_open ? 'open' : 'closed'));
@endphp

@section('content')
<div class="space-y-6 max-w-2xl" x-data="{ mode: @js($mode) }">
    <div>
        <h1 class="text-[26px] sm:text-[30px] font-bold text-[var(--text-primary)] tracking-[-0.03em]">Pendaftaran & Komunitas</h1>
        <p class="text-[13px] text-[var(--text-muted)] mt-1">Atur cara pembukaan pendaftaran anggota dan grup WhatsApp CSA.</p>
    </div>

    @if (session('success'))
        <div class="csa-panel p-3.5 text-[13px] text-emerald-600 dark:text-emerald-400">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
        <div class="csa-panel p-4 border-l-2 border-red-500 space-y-1.5">
            <p class="text-[13px] font-medium text-red-500">Periksa kembali data berikut:</p>
            <ul class="space-y-1">
                @foreach ($errors->all() as $message)
                    <li class="text-[12px] text-red-500">· {{ $message }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.registration.update') }}" method="POST" class="csa-panel p-5 sm:p-6 space-y-6">
        @csrf
        @method('PUT')

        <div>
            <h2 class="text-[14px] font-semibold text-[var(--text-primary)]">Status Pendaftaran</h2>
            <p class="text-[12px] text-[var(--text-muted)] mt-1">Pilih salah satu status. Hanya satu yang berlaku.</p>

            <div class="mt-4 space-y-3">
                {{-- 1. Aktifkan (manual buka) --}}
                <label class="flex items-start justify-between gap-4 cursor-pointer rounded-xl border p-4 transition-colors"
                    :class="mode === 'open' ? 'border-[var(--accent)] bg-[var(--accent-subtle)]' : 'border-[var(--border-soft)]'">
                    <span class="min-w-0">
                        <span class="block text-[14px] font-medium text-[var(--text-primary)]">Aktifkan</span>
                        <span class="block text-[12px] text-[var(--text-muted)] mt-0.5">Pendaftaran dibuka sekarang dan tetap terbuka sampai Anda ubah statusnya.</span>
                    </span>
                    <input type="radio" name="registration_mode" value="open" x-model="mode" class="mt-1 w-4 h-4 accent-[var(--accent)] shrink-0">
                </label>

                {{-- 2. Aktifkan & tutup otomatis lewat tanggal --}}
                <label class="flex items-start justify-between gap-4 cursor-pointer rounded-xl border p-4 transition-colors"
                    :class="mode === 'schedule' ? 'border-[var(--accent)] bg-[var(--accent-subtle)]' : 'border-[var(--border-soft)]'">
                    <span class="min-w-0">
                        <span class="block text-[14px] font-medium text-[var(--text-primary)]">Aktifkan & tutup otomatis lewat tanggal</span>
                        <span class="block text-[12px] text-[var(--text-muted)] mt-0.5">Pendaftaran terbuka & tertutup otomatis mengikuti rentang tanggal di bawah.</span>
                    </span>
                    <input type="radio" name="registration_mode" value="schedule" x-model="mode" class="mt-1 w-4 h-4 accent-[var(--accent)] shrink-0">
                </label>

                {{-- 3. Tutup (manual tutup) --}}
                <label class="flex items-start justify-between gap-4 cursor-pointer rounded-xl border p-4 transition-colors"
                    :class="mode === 'closed' ? 'border-[var(--accent)] bg-[var(--accent-subtle)]' : 'border-[var(--border-soft)]'">
                    <span class="min-w-0">
                        <span class="block text-[14px] font-medium text-[var(--text-primary)]">Tutup</span>
                        <span class="block text-[12px] text-[var(--text-muted)] mt-0.5">Pendaftaran ditutup dan tetap tertutup sampai Anda ubah statusnya.</span>
                    </span>
                    <input type="radio" name="registration_mode" value="closed" x-model="mode" class="mt-1 w-4 h-4 accent-[var(--accent)] shrink-0">
                </label>
            </div>

            {{-- Rentang tanggal (hanya mode otomatis) --}}
            <div x-show="mode === 'schedule'" x-cloak class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-4">
                <x-form.date name="registration_opens_at" id="registration_opens_at" :withTime="true" label="Buka Pendaftaran"
                    :value="old('registration_opens_at', $setting->registration_opens_at?->format('Y-m-d\TH:i'))"
                    hint="Pendaftaran terbuka mulai tanggal ini." />
                <x-form.date name="registration_closes_at" id="registration_closes_at" :withTime="true" label="Tutup Pendaftaran"
                    :value="old('registration_closes_at', $setting->registration_closes_at?->format('Y-m-d\TH:i'))"
                    hint="Pendaftaran tertutup setelah tanggal ini." />
            </div>

            @php $open = $setting->isRegistrationOpen(); @endphp
            <div class="mt-4 flex items-center gap-2 text-[12px] rounded-lg px-3 py-2.5 {{ $open ? 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400' : 'bg-[var(--bg-muted)] text-[var(--text-muted)]' }}">
                <span class="w-2 h-2 rounded-full {{ $open ? 'bg-emerald-500' : 'bg-[var(--text-muted)]' }}"></span>
                Status saat ini: <strong class="font-semibold">{{ $open ? 'Pendaftaran terbuka' : 'Pendaftaran tertutup' }}</strong>
                @if ($setting->registration_use_schedule)
                    (otomatis sesuai tanggal)
                @endif
            </div>
        </div>

        <div class="pt-6 border-t border-[var(--border-soft)]">
            <h2 class="text-[14px] font-semibold text-[var(--text-primary)]">Grup WhatsApp</h2>
            <p class="text-[12px] text-[var(--text-muted)] mt-1 mb-4">Tautan ini tampil di halaman sukses pendaftaran agar anggota baru bisa langsung bergabung.</p>

            <x-form.input name="whatsapp_group_link" id="whatsapp_group_link" type="url"
                label="Link Grup WhatsApp CSA"
                :value="old('whatsapp_group_link', $setting->whatsapp_group_link)"
                placeholder="https://chat.whatsapp.com/..." />
        </div>

        <div class="flex justify-end pt-2 border-t border-[var(--border-soft)]">
            <button type="submit" class="csa-btn csa-btn-primary">
                <x-admin.icon name="save" :size="16" /> Simpan Pengaturan
            </button>
        </div>
    </form>
</div>
@endsection
