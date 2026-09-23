@extends('layouts.admin')

@section('content')
<div class="space-y-4 sm:space-y-5 max-w-2xl">
    <div>
        <h1 class="text-[26px] sm:text-[30px] font-bold text-[var(--text-primary)] tracking-[-0.03em]">Pendaftaran & Komunitas</h1>
        <p class="text-[13px] text-[var(--text-muted)] mt-1">Atur pembukaan pendaftaran dan grup WhatsApp CSA.</p>
    </div>

    @if (session('success'))
        <div class="csa-card p-3.5 text-[13px] text-emerald-600 dark:text-emerald-400">{{ session('success') }}</div>
    @endif

    <form action="{{ route('admin.registration.update') }}" method="POST" class="csa-card p-5 sm:p-6 space-y-6">
        @csrf
        @method('PUT')

        <div>
            <h2 class="text-[14px] font-semibold text-[var(--text-primary)]">Status Pendaftaran</h2>
            <p class="text-[12px] text-[var(--text-muted)] mt-1">
                Saat aktif, halaman publik menampilkan tombol daftar. Saat nonaktif, pendaftar akan diberi tahu bahwa pendaftaran ditutup.
            </p>

            <label class="mt-4 flex items-center justify-between gap-4 cursor-pointer">
                <span>
                    <span class="block text-[14px] font-medium text-[var(--text-primary)]">
                        {{ $setting->registration_open ? 'Pendaftaran dibuka' : 'Pendaftaran ditutup' }}
                    </span>
                    <span class="block text-[12px] text-[var(--text-muted)] mt-0.5">
                        Ubah status lalu simpan untuk menerapkan.
                    </span>
                </span>
                <input type="hidden" name="registration_open" value="0">
                <input type="checkbox" name="registration_open" value="1" class="peer sr-only"
                    {{ old('registration_open', $setting->registration_open) ? 'checked' : '' }}>
                <span class="relative w-11 h-6 rounded-full bg-[var(--border-strong)] after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:w-5 after:h-5 after:rounded-full after:bg-white after:transition-all peer-checked:bg-[var(--accent)] peer-checked:after:translate-x-5 shrink-0"></span>
            </label>
        </div>

        <div class="pt-6 border-t border-[var(--border-soft)]">
            <h2 class="text-[14px] font-semibold text-[var(--text-primary)]">Grup WhatsApp</h2>
            <p class="text-[12px] text-[var(--text-muted)] mt-1 mb-4">
                Berikan tautan ini di halaman sukses pendaftaran agar anggota baru bisa langsung bergabung.
            </p>

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
