@extends('layouts.admin')

@section('content')
<div class="space-y-4 sm:space-y-5">
    <div>
        <h1 class="text-[26px] sm:text-[30px] font-bold text-[var(--text-primary)] tracking-[-0.03em]">Profile Saya</h1>
        <p class="text-[13px] text-[var(--text-muted)] mt-1">Kelola informasi dan pengaturan akun pribadi Anda.</p>
    </div>

    @if (session('status'))
        <div class="csa-card p-3.5 text-[13px] text-emerald-600 dark:text-emerald-400">{{ session('status') }}</div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <div class="csa-card p-6 text-center h-fit">
            <div class="w-24 h-24 rounded-full overflow-hidden mx-auto bg-[var(--bg-muted)]">
                @if ($member && $member->photo)
                    <img src="{{ asset('storage/' . $member->photo) }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                @else
                    <img src="{{ asset('storage/profile/profile-placeholder.png') }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                @endif
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
            <section class="csa-card p-5">
                <h3 class="text-[14px] font-semibold text-[var(--text-primary)] mb-4">Edit Profile</h3>
                <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <x-form.input name="name" id="name" label="Nama Lengkap" :value="old('name', $user->name)" required />
                        <x-form.input name="email" id="email" type="email" label="Email" :value="old('email', $user->email)" required />
                        <x-form.input name="whatsapp" id="whatsapp" type="tel" label="Nomor WhatsApp" :value="old('whatsapp', $user->whatsapp)" required />
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

                        <div>
                            <label for="photo" class="block text-[13px] font-medium text-[var(--text-secondary)] mb-1.5">Foto Profile</label>
                            <input type="file" name="photo" id="photo" accept="image/jpeg,image/png,image/jpg,image/webp" class="csa-input py-2.5 file:mr-3 file:rounded-lg file:border-0 file:bg-[var(--bg-muted)] file:px-3 file:py-1.5 file:text-[12px] file:font-medium file:text-[var(--text-secondary)]">
                            <p class="text-[11px] text-[var(--text-muted)] mt-1.5">Maksimal 2MB. Format JPG, PNG, WEBP.</p>
                            @error('photo')<p class="text-[12px] text-red-500 mt-1.5">{{ $message }}</p>@enderror
                        </div>
                    @endif

                    <div class="flex justify-end">
                        <button type="submit" class="csa-btn csa-btn-primary">
                            <x-admin.icon name="save" :size="16" /> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </section>

            <section class="csa-card p-5">
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
