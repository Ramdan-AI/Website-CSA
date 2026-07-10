{{-- resources/views/pages/admin/members/form.blade.php --}}
@extends('layouts.admin')

@section('content')

@php
    $isEdit = isset($member);
    $title = $isEdit ? 'Edit Anggota' : 'Tambah Anggota';
    $hasPhoto = $isEdit && $member->photo;
    $photoUrl = $hasPhoto ? asset('storage/' . $member->photo) : '#';
    $hasAccount = $isEdit && $member->user;
@endphp

<div class="space-y-6">
    
    <div>
        <p class="text-xs font-semibold text-blue-400 uppercase tracking-widest">Keanggotaan</p>
        <h1 class="text-3xl font-bold text-gray-900 mt-1">{{ $title }}</h1>
        <p class="text-sm text-gray-400 mt-1.5 font-medium">
            {{ $isEdit ? 'Perbarui informasi anggota.' : 'Tambahkan anggota baru beserta akun.' }}
        </p>
    </div>

    <div class="relative bg-white/70 backdrop-blur-xl rounded-[30px] border border-white/50 shadow-[0_2px_16px_-4px_rgba(59,130,246,0.08)] overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-blue-400/5 to-cyan-400/5 pointer-events-none"></div>
        
        <div class="relative p-6">
            <form action="{{ $isEdit ? route('admin.members.update', $member) : route('admin.members.store') }}" 
                  method="POST" 
                  enctype="multipart/form-data" 
                  class="space-y-6">
                @csrf
                @if($isEdit) @method('PUT') @endif

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div class="lg:col-span-1">
                        <label class="block text-xs font-semibold text-gray-500 mb-2">Foto Anggota</label>
                        
                        <div class="relative">
                            <div class="w-full aspect-square rounded-[24px] border-2 border-dashed border-gray-200/80 flex items-center justify-center overflow-hidden bg-gray-50/30 hover:border-blue-300 transition-colors cursor-pointer"
                                 onclick="document.getElementById('photo-input').click()">
                                <img id="photo-preview" 
                                     src="{{ $hasPhoto ? $photoUrl : '#' }}" 
                                     class="{{ $hasPhoto ? 'w-full h-full object-cover' : 'hidden' }}">
                                <div id="photo-placeholder" class="{{ $hasPhoto ? 'hidden' : 'flex flex-col items-center' }}">
                                    <div class="w-12 h-12 rounded-full bg-blue-50 flex items-center justify-center mb-3 shadow-[0_2px_8px_-2px_rgba(59,130,246,0.1)]">
                                        <i class="fas fa-camera text-lg text-blue-400"></i>
                                    </div>
                                    <span class="text-xs text-gray-400 font-medium">Klik untuk upload</span>
                                    <span class="text-[10px] text-gray-300 mt-1">JPG, PNG, WEBP</span>
                                </div>
                            </div>
                        </div>
                        
                        <input type="file" name="photo" id="photo-input" accept="image/jpeg,image/png,image/webp" 
                               class="hidden" onchange="previewPhoto(event)">
                        
                        @error('photo')
                            <p class="text-xs text-red-500 mt-2">{{ $message }}</p>
                        @enderror

                        <p class="text-[11px] text-gray-400 mt-2 font-medium">Maksimal 2MB. Rasio 1:1.</p>
                    </div>

                    <div class="lg:col-span-2 space-y-5">
                        <div class="bg-gray-50/40 rounded-[24px] p-5">
                            <h3 class="text-[13px] font-semibold text-gray-900 mb-4 flex items-center gap-2">
                                <span class="w-1.5 h-5 rounded-full bg-blue-500"></span>
                                Data Anggota
                            </h3>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label for="name" class="block text-[11px] font-semibold text-gray-500 mb-1.5">
                                        Nama <span class="text-red-400">*</span>
                                    </label>
                                    <input type="text" name="name" id="name" 
                                           value="{{ old('name', $member->name ?? '') }}"
                                           class="w-full h-[44px] px-4 rounded-[14px] border border-gray-200/80 text-[13px] focus:outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-400/15 transition-all bg-white/60"
                                           placeholder="Nama lengkap"
                                           required>
                                    @error('name')
                                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="nim" class="block text-[11px] font-semibold text-gray-500 mb-1.5">NIM</label>
                                    <input type="text" name="nim" id="nim" 
                                           value="{{ old('nim', $member->nim ?? '') }}"
                                           class="w-full h-[44px] px-4 rounded-[14px] border border-gray-200/80 text-[13px] focus:outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-400/15 transition-all bg-white/60"
                                           placeholder="Nomor induk">
                                    @error('nim')
                                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
                                <div>
                                    <label for="email" class="block text-[11px] font-semibold text-gray-500 mb-1.5">
                                        Email <span class="text-red-400">*</span>
                                    </label>
                                    <input type="email" name="email" id="email" 
                                           value="{{ old('email', $member->email ?? ($hasAccount ? $member->user->email : '')) }}"
                                           class="w-full h-[44px] px-4 rounded-[14px] border border-gray-200/80 text-[13px] focus:outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-400/15 transition-all {{ $hasAccount ? 'bg-gray-100/60 text-gray-500 cursor-not-allowed' : 'bg-white/60' }}"
                                           placeholder="email@example.com"
                                           {{ $hasAccount ? 'readonly' : '' }}
                                           required>
                                    @if($hasAccount)
                                        <p class="text-[10px] text-gray-400 mt-1 flex items-center gap-1">
                                            <i class="fas fa-lock text-[8px]"></i> 
                                            Tidak dapat diubah
                                        </p>
                                    @else
                                        <p class="text-[10px] text-gray-400 mt-1">
                                            {{ $isEdit ? 'Digunakan untuk login jika akun dibuat' : 'Digunakan sebagai email login' }}
                                        </p>
                                    @endif
                                    @error('email')
                                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="phone" class="block text-[11px] font-semibold text-gray-500 mb-1.5">Telepon</label>
                                    <input type="text" name="phone" id="phone" 
                                           value="{{ old('phone', $member->phone ?? '') }}"
                                           class="w-full h-[44px] px-4 rounded-[14px] border border-gray-200/80 text-[13px] focus:outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-400/15 transition-all bg-white/60"
                                           placeholder="Nomor telepon">
                                    @error('phone')
                                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
                                <div>
                                    <label for="division_id" class="block text-[11px] font-semibold text-gray-500 mb-1.5">
                                        Divisi <span class="text-red-400">*</span>
                                    </label>
                                    <select name="division_id" id="division_id" required
                                            class="w-full h-[44px] px-4 rounded-[14px] border border-gray-200/80 text-[13px] focus:outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-400/15 transition-all bg-white/60">
                                        <option value="">Pilih divisi</option>
                                        @foreach($divisions as $division)
                                            <option value="{{ $division->id }}" {{ old('division_id', $member->division_id ?? '') == $division->id ? 'selected' : '' }}>
                                                {{ $division->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('division_id')
                                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="position_id" class="block text-[11px] font-semibold text-gray-500 mb-1.5">Jabatan</label>
                                    <select name="position_id" id="position_id"
                                            class="w-full h-[44px] px-4 rounded-[14px] border border-gray-200/80 text-[13px] focus:outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-400/15 transition-all bg-white/60">
                                        <option value="">Belum ada</option>
                                        @foreach($positions as $position)
                                            <option value="{{ $position->id }}" {{ old('position_id', $member->position_id ?? '') == $position->id ? 'selected' : '' }}>
                                                {{ $position->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('position_id')
                                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="mt-4">
                                <label for="bio" class="block text-[11px] font-semibold text-gray-500 mb-1.5">Bio</label>
                                <textarea name="bio" id="bio" rows="3"
                                          class="w-full px-4 py-3 rounded-[14px] border border-gray-200/80 text-[13px] focus:outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-400/15 transition-all resize-none bg-white/60"
                                          placeholder="Deskripsi singkat...">{{ old('bio', $member->bio ?? '') }}</textarea>
                                @error('bio')
                                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="bg-gray-50/40 rounded-[24px] p-5">
                            <h3 class="text-[13px] font-semibold text-gray-900 mb-4 flex items-center gap-2">
                                <span class="w-1.5 h-5 rounded-full bg-blue-500"></span>
                                {{ $hasAccount ? 'Akun Login' : ($isEdit ? 'Buat Akun (Opsional)' : 'Password Akun') }}
                            </h3>
                            
                            @if($hasAccount)
                                <div class="flex items-center justify-between p-4 bg-white/60 rounded-[18px] mb-3">
                                    <div>
                                        <p class="text-[13px] font-semibold text-gray-900">{{ $member->user->name }}</p>
                                        <p class="text-[11px] text-gray-500">{{ $member->user->email }}</p>
                                    </div>
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-emerald-50 text-[10px] font-semibold text-emerald-600">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Aktif
                                    </span>
                                </div>
                                
                                <button type="button" 
                                        onclick="document.getElementById('reset-password-section').classList.toggle('hidden')"
                                        class="text-[11px] font-medium text-blue-600 hover:text-blue-700 underline">
                                    <i class="fas fa-key text-[9px] mr-1"></i> Reset Password
                                </button>
                                
                                <div id="reset-password-section" class="hidden mt-3 p-4 bg-white/60 rounded-[18px]">
                                    <p class="text-[11px] text-gray-400 mb-3">Kosongkan jika tidak ingin mengubah.</p>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div>
                                            <label for="password" class="block text-[11px] font-semibold text-gray-500 mb-1.5">Password Baru</label>
                                            <div class="relative">
                                                <input type="password" name="password" id="password" 
                                                       class="w-full h-[44px] px-4 pr-12 rounded-[14px] border border-gray-200/80 text-[13px] focus:outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-400/15 transition-all bg-white/60"
                                                       placeholder="Minimal 8 karakter">
                                                <button type="button" onclick="togglePassword()" 
                                                        class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                                    <i id="eye-icon" class="fas fa-eye text-sm"></i>
                                                </button>
                                            </div>
                                            @error('password')
                                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div>
                                            <label for="password_confirmation" class="block text-[11px] font-semibold text-gray-500 mb-1.5">Konfirmasi Password</label>
                                            <input type="password" name="password_confirmation" id="password_confirmation" 
                                                   class="w-full h-[44px] px-4 rounded-[14px] border border-gray-200/80 text-[13px] focus:outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-400/15 transition-all bg-white/60"
                                                   placeholder="Ulangi password">
                                        </div>
                                    </div>
                                </div>
                            @else
                                @if($isEdit)
                                    <div class="p-4 bg-amber-50/60 rounded-[18px] mb-4">
                                        <p class="text-[11px] text-amber-700 flex items-center gap-2">
                                            <i class="fas fa-info-circle text-xs"></i>
                                            Isi password hanya jika ingin membuatkan akun. Kosongkan jika hanya menyimpan data anggota.
                                        </p>
                                    </div>
                                @else
                                    <p class="text-[11px] text-blue-500/70 mb-4">Password wajib diisi untuk membuat akun.</p>
                                @endif
                                
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label for="password" class="block text-[11px] font-semibold text-gray-500 mb-1.5">
                                            Password 
                                            @if(!$isEdit)<span class="text-red-400">*</span>@else<span class="text-gray-400 font-normal">(opsional)</span>@endif
                                        </label>
                                        <div class="relative">
                                            <input type="password" name="password" id="password" 
                                                   class="w-full h-[44px] px-4 pr-12 rounded-[14px] border border-gray-200/80 text-[13px] focus:outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-400/15 transition-all bg-white/60"
                                                   placeholder="Minimal 8 karakter"
                                                   {{ !$isEdit ? 'required' : '' }}>
                                            <button type="button" onclick="togglePassword()" 
                                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                                <i id="eye-icon" class="fas fa-eye text-sm"></i>
                                            </button>
                                        </div>
                                        @error('password')
                                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="password_confirmation" class="block text-[11px] font-semibold text-gray-500 mb-1.5">
                                            Konfirmasi Password
                                            @if(!$isEdit)<span class="text-red-400">*</span>@endif
                                        </label>
                                        <input type="password" name="password_confirmation" id="password_confirmation" 
                                               class="w-full h-[44px] px-4 rounded-[14px] border border-gray-200/80 text-[13px] focus:outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-400/15 transition-all bg-white/60"
                                               placeholder="Ulangi password"
                                               {{ !$isEdit ? 'required' : '' }}>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="bg-gray-50/40 rounded-[24px] p-5">
                            <h3 class="text-[13px] font-semibold text-gray-900 mb-4 flex items-center gap-2">
                                <span class="w-1.5 h-5 rounded-full bg-blue-500"></span>
                                Media Sosial
                            </h3>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                <div class="relative">
                                    <div class="absolute left-4 top-1/2 -translate-y-1/2 text-blue-600">
                                        <i class="fab fa-linkedin-in text-sm"></i>
                                    </div>
                                    <input type="url" name="linkedin" 
                                           value="{{ old('linkedin', $member->linkedin ?? '') }}"
                                           class="w-full h-[44px] pl-10 pr-4 rounded-[14px] border border-gray-200/80 text-[13px] focus:outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-400/15 transition-all bg-white/60"
                                           placeholder="LinkedIn URL">
                                </div>
                                <div class="relative">
                                    <div class="absolute left-4 top-1/2 -translate-y-1/2 text-pink-600">
                                        <i class="fab fa-instagram text-sm"></i>
                                    </div>
                                    <input type="text" name="instagram" 
                                           value="{{ old('instagram', $member->instagram ?? '') }}"
                                           class="w-full h-[44px] pl-10 pr-4 rounded-[14px] border border-gray-200/80 text-[13px] focus:outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-400/15 transition-all bg-white/60"
                                           placeholder="Instagram">
                                </div>
                                <div class="relative">
                                    <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-700">
                                        <i class="fab fa-github text-sm"></i>
                                    </div>
                                    <input type="text" name="github" 
                                           value="{{ old('github', $member->github ?? '') }}"
                                           class="w-full h-[44px] pl-10 pr-4 rounded-[14px] border border-gray-200/80 text-[13px] focus:outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-400/15 transition-all bg-white/60"
                                           placeholder="GitHub">
                                </div>
                            </div>
                            @error('linkedin')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                            @error('instagram')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                            @error('github')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                        </div>

                        @if($isEdit)
                        <div class="flex items-center gap-4 p-4 bg-gray-50/40 rounded-[24px]">
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="hidden" name="is_active" value="0">
                                <input type="checkbox" name="is_active" value="1" class="sr-only peer" 
                                       {{ old('is_active', $member->is_active) ? 'checked' : '' }}>
                                <div class="w-10 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-500"></div>
                            </label>
                            <div>
                                <p class="text-[13px] font-semibold text-gray-900">
                                    {{ old('is_active', $member->is_active) ? 'Anggota Aktif' : 'Anggota Nonaktif' }}
                                </p>
                                <p class="text-[11px] text-gray-400">Nonaktifkan untuk menyembunyikan</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button type="submit" class="relative inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-blue-500 to-blue-600 text-white text-[13px] font-semibold rounded-[18px] shadow-[0_4px_16px_-4px_rgba(59,130,246,0.3)] hover:shadow-[0_8px_24px_-4px_rgba(59,130,246,0.45)] hover:scale-105 transition-all duration-300 overflow-hidden group">
                        <div class="absolute inset-0 bg-white/20 rounded-[18px] opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        <span class="relative flex items-center gap-2">
                            <i class="fas fa-{{ $isEdit ? 'save' : 'plus' }} text-[10px]"></i>
                            {{ $isEdit ? 'Simpan Perubahan' : 'Tambah Anggota' }}
                        </span>
                    </button>
                    <a href="{{ route('admin.members.index') }}" 
                       class="px-5 py-2.5 bg-gray-100 text-gray-700 text-[13px] font-semibold rounded-[18px] hover:bg-gray-200 transition-colors">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function previewPhoto(event) {
    const file = event.target.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = function(e) {
        document.getElementById('photo-preview').src = e.target.result;
        document.getElementById('photo-preview').classList.remove('hidden');
        document.getElementById('photo-placeholder').classList.add('hidden');
    };
    reader.readAsDataURL(file);
}

function togglePassword() {
    const input = document.getElementById('password');
    const icon = document.getElementById('eye-icon');
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}
</script>
@endpush