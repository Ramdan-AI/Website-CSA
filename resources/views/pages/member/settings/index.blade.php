@extends('layouts.admin')

@section('content')
<div class="space-y-4 sm:space-y-5">

    <div>
        <h1 class="text-[26px] sm:text-[30px] font-bold text-[var(--text-primary)] tracking-[-0.03em]">Pengaturan Akun</h1>
        <p class="text-[13px] text-[var(--text-muted)] mt-1">Kelola profil, keamanan, dan preferensi pribadi Anda.</p>
    </div>

    @if (session('status'))
        <div class="csa-card p-3.5 text-[13px] text-emerald-600 dark:text-emerald-400">{{ session('status') }}</div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <div class="csa-card p-6 text-center h-fit">
            <div class="w-20 h-20 rounded-full overflow-hidden mx-auto bg-[var(--bg-muted)]">
                @if($member && $member->photo)
                    <img src="{{ asset('storage/' . $member->photo) }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                @else
                    <img src="{{ asset('storage/profile/profile-placeholder.png') }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                @endif
            </div>
            <h2 class="text-[15px] font-semibold text-[var(--text-primary)] mt-4">{{ $user->name }}</h2>
            <p class="text-[12px] text-[var(--text-muted)] mt-1">{{ $user->email }}</p>
            <p class="inline-flex items-center gap-1.5 text-[12px] text-[var(--text-secondary)] mt-2">
                <x-admin.icon name="shield" :size="13" /> {{ $user->role->name ?? 'Anggota' }}
            </p>
            <div class="mt-4 pt-4 border-t border-[var(--border-soft)]">
                <a href="{{ route('admin.member.calendar') }}" class="csa-btn csa-btn-ghost w-full">
                    <x-admin.icon name="calendar-days" :size="16" /> Presensi Saya
                </a>
            </div>
        </div>

        <div class="lg:col-span-2 space-y-4">
            <section class="csa-card overflow-hidden">
                <div class="px-5 py-4 border-b border-[var(--border-soft)]">
                    <h3 class="text-[14px] font-semibold text-[var(--text-primary)]">Ubah Profil</h3>
                </div>
                <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data" class="p-5 space-y-4">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <x-form.input name="name" id="name" label="Nama Lengkap" :value="old('name', $user->name)" required />
                        <x-form.input name="email" id="email" type="email" label="Email" :value="old('email', $user->email)" required />
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <x-form.input name="whatsapp" id="whatsapp" type="tel" label="Nomor WhatsApp" :value="old('whatsapp', $user->whatsapp)" />
                        <x-form.input name="nim" id="nim" label="NIM" :value="old('nim', $member?->nim)" />
                    </div>

                    <x-form.textarea name="bio" id="bio" label="Bio" :value="old('bio', $member?->bio)" rows="3" />

                    <div>
                        <label for="photo" class="block text-[13px] font-medium text-[var(--text-secondary)] mb-1.5">Foto Profil</label>
                        <input type="file" name="photo" id="photo" accept="image/*" class="csa-input py-2.5 file:mr-3 file:rounded-lg file:border-0 file:bg-[var(--bg-muted)] file:px-3 file:py-1.5 file:text-[12px] file:font-medium file:text-[var(--text-secondary)]">
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="csa-btn csa-btn-primary">Simpan Profil</button>
                    </div>
                </form>
            </section>

            <section class="csa-card overflow-hidden">
                <div class="px-5 py-4 border-b border-[var(--border-soft)]">
                    <h3 class="text-[14px] font-semibold text-[var(--text-primary)]">Ubah Password</h3>
                </div>
                <form action="{{ route('admin.profile.password') }}" method="POST" class="p-5 space-y-4">
                    @csrf
                    @method('PUT')

                    <x-form.input name="current_password" id="current_password" type="password" label="Password Saat Ini" required />

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <x-form.input name="password" id="new_password" type="password" label="Password Baru" hint="Minimal 8 karakter" required />
                        <x-form.input name="password_confirmation" id="password_confirmation" type="password" label="Konfirmasi Password" required />
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="csa-btn csa-btn-primary">Ubah Password</button>
                    </div>
                </form>
            </section>

            <section class="csa-card overflow-hidden">
                <div class="px-5 py-4 border-b border-[var(--border-soft)]">
                    <h3 class="text-[14px] font-semibold text-[var(--text-primary)]">Akses & Hak</h3>
                </div>
                <div class="p-5 divide-y divide-[var(--border-soft)]">
                    @foreach ([
                        ['Role', 'Hak akses Anda saat ini', $user->role->name ?? 'Anggota'],
                        ['Divisi', 'Divisi Anda tergabung', $member?->division?->name ?? 'Tidak ada'],
                        ['Jabatan', 'Jabatan Anda di divisi', $member?->position?->name ?? 'Anggota'],
                    ] as [$label, $hint, $value])
                        <div class="flex items-center justify-between py-3 first:pt-0 last:pb-0 gap-4">
                            <div>
                                <p class="text-[13px] font-medium text-[var(--text-primary)]">{{ $label }}</p>
                                <p class="text-[12px] text-[var(--text-muted)] mt-0.5">{{ $hint }}</p>
                            </div>
                            <span class="text-[13px] font-medium text-[var(--text-secondary)] text-right">{{ $value }}</span>
                        </div>
                    @endforeach
                </div>
            </section>
        </div>
    </div>
</div>
@endsection
