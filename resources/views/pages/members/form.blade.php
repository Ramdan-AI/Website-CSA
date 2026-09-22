@extends('layouts.admin')

@php
    $isEdit = isset($member);
    $title = $isEdit ? 'Edit Anggota' : 'Tambah Anggota';
    $hasPhoto = $isEdit && $member->photo;
    $photoUrl = $hasPhoto ? asset('storage/' . $member->photo) : '#';
    $hasAccount = $isEdit && $member->user;
@endphp

@section('content')
<div class="space-y-4 sm:space-y-5">
    <div>
        <a href="{{ route('admin.members.index') }}" class="inline-flex items-center gap-1.5 text-[12px] text-[var(--text-muted)] hover:text-[var(--text-primary)] transition-colors">
            <x-admin.icon name="arrow-left" :size="14" /> Kembali ke Anggota
        </a>
        <h1 class="text-[26px] sm:text-[30px] font-bold text-[var(--text-primary)] tracking-[-0.03em] mt-2">{{ $title }}</h1>
        <p class="text-[13px] text-[var(--text-muted)] mt-1">
            {{ $isEdit ? 'Perbarui informasi anggota.' : 'Tambahkan anggota baru beserta akun.' }}
        </p>
    </div>

    <form action="{{ $isEdit ? route('admin.members.update', $member) : route('admin.members.store') }}"
        method="POST" enctype="multipart/form-data" class="space-y-4">
        @csrf
        @if($isEdit) @method('PUT') @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
            <div class="lg:col-span-1">
                <div class="csa-card p-5">
                    <label class="block text-[13px] font-medium text-[var(--text-secondary)] mb-2">Foto Anggota</label>
                    <div class="w-full aspect-square rounded-2xl border border-dashed border-[var(--border-strong)] flex items-center justify-center overflow-hidden bg-[var(--bg-muted)] cursor-pointer hover:border-[var(--accent)] transition-colors"
                        onclick="document.getElementById('photo-input').click()">
                        <img id="photo-preview" src="{{ $hasPhoto ? $photoUrl : '#' }}" alt="Pratinjau foto" class="{{ $hasPhoto ? 'w-full h-full object-cover' : 'hidden' }}">
                        <div id="photo-placeholder" class="{{ $hasPhoto ? 'hidden' : 'flex' }} flex-col items-center text-[var(--text-muted)]">
                            <x-admin.icon name="camera" :size="24" />
                            <span class="text-[12px] font-medium mt-2">Klik untuk upload</span>
                            <span class="text-[11px] mt-0.5">JPG, PNG, WEBP</span>
                        </div>
                    </div>
                    <input type="file" name="photo" id="photo-input" accept="image/jpeg,image/png,image/webp" class="hidden" onchange="previewPhoto(event)">
                    @error('photo')<p class="text-[12px] text-red-500 mt-2">{{ $message }}</p>@enderror
                    <p class="text-[11px] text-[var(--text-muted)] mt-2">Maksimal 2MB. Rasio 1:1.</p>
                </div>
            </div>

            <div class="lg:col-span-2 space-y-4">
                <section class="csa-card p-5">
                    <h3 class="text-[14px] font-semibold text-[var(--text-primary)] mb-4">Data Anggota</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <x-form.input name="name" id="name" label="Nama" :value="old('name', $member->name ?? '')" placeholder="Nama lengkap" required />
                        <x-form.input name="nim" id="nim" label="NIM" :value="old('nim', $member->nim ?? '')" placeholder="Nomor induk" />
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
                        <div>
                            <label for="email" class="block text-[13px] font-medium text-[var(--text-secondary)] mb-1.5">Email</label>
                            <input type="email" name="email" id="email" value="{{ old('email', $member->email ?? ($hasAccount ? $member->user->email : '')) }}"
                                class="csa-input {{ $hasAccount ? 'opacity-60 cursor-not-allowed' : '' }}" placeholder="email@example.com" @readonly($hasAccount) required>
                            @if($hasAccount)
                                <p class="text-[11px] text-[var(--text-muted)] mt-1.5">Tidak dapat diubah.</p>
                            @else
                                <p class="text-[11px] text-[var(--text-muted)] mt-1.5">{{ $isEdit ? 'Digunakan untuk login jika akun dibuat.' : 'Digunakan sebagai email login.' }}</p>
                            @endif
                            @error('email')<p class="text-[12px] text-red-500 mt-1.5">{{ $message }}</p>@enderror
                        </div>
                        <x-form.input name="phone" id="phone" label="Telepon" :value="old('phone', $member->phone ?? '')" placeholder="Nomor telepon" />
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
                        <div>
                            <label for="division_id" class="block text-[13px] font-medium text-[var(--text-secondary)] mb-1.5">Divisi</label>
                            <select name="division_id" id="division_id" required class="csa-input">
                                <option value="">Pilih divisi</option>
                                @foreach($divisions as $division)
                                    <option value="{{ $division->id }}" @selected(old('division_id', $member->division_id ?? '') == $division->id)>{{ $division->name }}</option>
                                @endforeach
                            </select>
                            @error('division_id')<p class="text-[12px] text-red-500 mt-1.5">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="position_id" class="block text-[13px] font-medium text-[var(--text-secondary)] mb-1.5">Jabatan</label>
                            <select name="position_id" id="position_id" class="csa-input">
                                <option value="">Belum ada</option>
                                @foreach($positions as $position)
                                    <option value="{{ $position->id }}" @selected(old('position_id', $member->position_id ?? '') == $position->id)>{{ $position->name }}</option>
                                @endforeach
                            </select>
                            @error('position_id')<p class="text-[12px] text-red-500 mt-1.5">{{ $message }}</p>@enderror
                        </div>
                    </div>
                    <div class="mt-4">
                        <x-form.textarea name="bio" id="bio" label="Bio" :value="old('bio', $member->bio ?? '')" placeholder="Deskripsi singkat..." rows="3" />
                    </div>
                </section>

                <section class="csa-card p-5">
                    <h3 class="text-[14px] font-semibold text-[var(--text-primary)] mb-4">
                        {{ $hasAccount ? 'Akun Login' : ($isEdit ? 'Buat Akun (Opsional)' : 'Password Akun') }}
                    </h3>

                    @if($hasAccount)
                        <div class="flex items-center justify-between p-4 rounded-xl bg-[var(--bg-muted)]">
                            <div class="min-w-0">
                                <p class="text-[13px] font-medium text-[var(--text-primary)] truncate">{{ $member->user->name }}</p>
                                <p class="text-[12px] text-[var(--text-muted)] truncate">{{ $member->user->email }}</p>
                            </div>
                            <span class="csa-chip csa-chip-green shrink-0">Aktif</span>
                        </div>
                        <button type="button" onclick="document.getElementById('reset-password-section').classList.toggle('hidden')" class="text-[12px] font-medium text-[var(--accent-bright)] hover:underline mt-3 inline-flex items-center gap-1.5">
                            <x-admin.icon name="key-round" :size="13" /> Reset Password
                        </button>
                        <div id="reset-password-section" class="hidden mt-3 p-4 rounded-xl bg-[var(--bg-muted)]">
                            <p class="text-[11px] text-[var(--text-muted)] mb-3">Kosongkan jika tidak ingin mengubah.</p>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <x-form.input name="password" id="member-password" type="password" label="Password Baru" placeholder="Minimal 8 karakter" />
                                <x-form.input name="password_confirmation" id="member-password-confirm" type="password" label="Konfirmasi Password" placeholder="Ulangi password" />
                            </div>
                        </div>
                    @else
                        @if($isEdit)
                            <div class="p-4 rounded-xl bg-[var(--bg-muted)] mb-4">
                                <p class="text-[12px] text-[var(--text-secondary)] inline-flex items-center gap-2">
                                    <x-admin.icon name="info" :size="14" /> Isi password hanya jika ingin membuatkan akun.
                                </p>
                            </div>
                        @else
                            <p class="text-[12px] text-[var(--text-muted)] mb-4">Password wajib diisi untuk membuat akun.</p>
                        @endif
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <x-form.input name="password" id="member-password" type="password" label="Password" placeholder="Minimal 8 karakter" :required="!$isEdit" />
                            <x-form.input name="password_confirmation" id="member-password-confirm" type="password" label="Konfirmasi Password" placeholder="Ulangi password" :required="!$isEdit" />
                        </div>
                    @endif
                </section>

                <section class="csa-card p-5">
                    <h3 class="text-[14px] font-semibold text-[var(--text-primary)] mb-4">Media Sosial</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <x-form.input name="linkedin" id="linkedin" type="url" label="LinkedIn" :value="old('linkedin', $member->linkedin ?? '')" placeholder="LinkedIn URL" />
                        <x-form.input name="instagram" id="instagram" label="Instagram" :value="old('instagram', $member->instagram ?? '')" placeholder="Instagram" />
                        <x-form.input name="github" id="github" label="GitHub" :value="old('github', $member->github ?? '')" placeholder="GitHub" />
                    </div>
                </section>

                @if($isEdit)
                    <section class="csa-card p-4">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" name="is_active" value="1" class="peer sr-only" {{ old('is_active', $member->is_active) ? 'checked' : '' }}>
                            <span class="relative w-10 h-6 rounded-full bg-[var(--border-strong)] after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:w-5 after:h-5 after:rounded-full after:bg-white after:transition-all peer-checked:bg-[var(--accent)] peer-checked:after:translate-x-4"></span>
                            <span>
                                <span class="block text-[13px] font-medium text-[var(--text-primary)]">{{ old('is_active', $member->is_active) ? 'Anggota Aktif' : 'Anggota Nonaktif' }}</span>
                                <span class="block text-[12px] text-[var(--text-muted)] mt-0.5">Nonaktifkan untuk menyembunyikan anggota.</span>
                            </span>
                        </label>
                    </section>
                @endif
            </div>
        </div>

        <div class="flex items-center gap-3">
            <button type="submit" class="csa-btn csa-btn-primary">
                <x-admin.icon :name="$isEdit ? 'save' : 'plus'" :size="16" />
                {{ $isEdit ? 'Simpan Perubahan' : 'Tambah Anggota' }}
            </button>
            <a href="{{ route('admin.members.index') }}" class="csa-btn csa-btn-ghost">Batal</a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    function previewPhoto(event) {
        const file = event.target.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = (e) => {
            const preview = document.getElementById('photo-preview');
            preview.src = e.target.result;
            preview.classList.remove('hidden');
            document.getElementById('photo-placeholder').classList.add('hidden');
        };
        reader.readAsDataURL(file);
    }
</script>
@endpush
