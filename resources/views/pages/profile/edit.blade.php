@extends('layouts.admin')

@section('content')
<div class="space-y-4 sm:space-y-5">
    <div>
        <h1 class="text-[26px] sm:text-[30px] font-bold text-[var(--text-primary)] tracking-[-0.03em]">Profile Saya</h1>
        <p class="text-[13px] text-[var(--text-muted)] mt-1">Kelola informasi dan pengaturan akun pribadi Anda.</p>
    </div>

    @if (session('status'))
        <div class="csa-panel p-3.5 text-[13px] text-emerald-600 dark:text-emerald-400">{{ session('status') }}</div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <div class="csa-panel p-6 text-center h-fit">
            <div class="w-24 h-24 rounded-full overflow-hidden mx-auto bg-[var(--bg-muted)]">
                <x-admin.avatar :user="$user" :size="96" class="w-full h-full" />
            </div>
            <h2 class="text-[15px] font-semibold text-[var(--text-primary)] mt-4">{{ $user->name }}</h2>
            <p class="text-[12px] text-[var(--text-muted)] mt-1">{{ $user->email }}</p>
            <span class="csa-chip csa-chip-blue mt-3">{{ $user->role->name }}</span>

            <div class="mt-4 pt-4 border-t border-[var(--border-soft)] space-y-3 text-left">
                @foreach (array_filter([
                    ['Nomor WhatsApp', $user->whatsapp],
                    ['NIM', $member?->nim],
                    ['Divisi', $member?->division?->name],
                ]) as [$label, $value])
                    <div>
                        <p class="text-[11px] text-[var(--text-muted)]">{{ $label }}</p>
                        <p class="text-[13px] text-[var(--text-primary)]">{{ $value ?? '-' }}</p>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="lg:col-span-2 space-y-4">
            <section class="csa-panel p-5">
                <h3 class="text-[14px] font-semibold text-[var(--text-primary)] mb-4">Edit Profile</h3>
                <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <x-form.input name="name" id="name" label="Nama Lengkap" :value="old('name', $user->name)" required />
                        <x-form.input name="email" id="email" type="email" label="Email" :value="old('email', $user->email)" required />
                        <x-form.input name="whatsapp" id="whatsapp" type="tel" label="Nomor WhatsApp" :value="old('whatsapp', $user->whatsapp)" />
                        @if ($member)
                            <x-form.input name="nim" id="nim" label="NIM" :value="old('nim', $member->nim)" />
                        @endif
                    </div>

                    @if ($member)
                        <x-form.textarea name="bio" id="bio" label="Bio" :value="old('bio', $member->bio)" rows="3" />

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <x-form.input name="linkedin" id="linkedin" type="url" label="LinkedIn" :value="old('linkedin', $member->linkedin)" placeholder="https://linkedin.com/in/..." />
                            <x-form.input name="instagram" id="instagram" type="url" label="Instagram" :value="old('instagram', $member->instagram)" placeholder="https://instagram.com/..." />
                            <x-form.input name="github" id="github" type="url" label="GitHub" :value="old('github', $member->github)" placeholder="https://github.com/..." />
                        </div>
                    @endif

                    <div>
                        <x-form.file-crop
                            name="photo"
                            label="Foto Profile"
                            :current="$user->avatar ? asset('storage/' . $user->avatar) : ($member?->photo ? asset('storage/' . $member->photo) : null)"
                            :aspect="1"
                            preview-size="w-16 h-16" />
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="csa-btn csa-btn-primary">
                            <x-admin.icon name="save" :size="16" /> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </section>

            <section class="csa-panel p-5">
                <h3 class="text-[14px] font-semibold text-[var(--text-primary)] mb-4">Ubah Password</h3>
                <form action="{{ route('admin.profile.password') }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <x-form.input name="current_password" id="current_password" type="password" label="Password Saat Ini" required />

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <x-form.input name="password" id="password" type="password" label="Password Baru" hint="Minimal 8 karakter" required />
                        <x-form.input name="password_confirmation" id="password_confirmation" type="password" label="Konfirmasi Password" required />
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="csa-btn csa-btn-primary">
                            <x-admin.icon name="lock" :size="16" /> Ubah Password
                        </button>
                    </div>
                </form>
            </section>
        </div>
    </div>
</div>
@endsection
