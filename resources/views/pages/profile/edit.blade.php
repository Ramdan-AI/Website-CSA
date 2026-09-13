@extends('layouts.admin')

@section('content')
<div class="space-y-6">

    <div>
        <p class="text-xs font-semibold text-blue-400 uppercase tracking-widest">Akun</p>
        <h1 class="text-3xl font-bold text-gray-900 mt-1">Profile Saya</h1>
        <p class="text-sm text-gray-400 mt-1.5 font-medium">Kelola informasi dan pengaturan akun pribadi Anda.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Profile Card --}}
        <div class="relative bg-white/80 backdrop-blur-xl rounded-[28px] border border-white/60 shadow-[0_8px_32px_rgba(59,130,246,0.06)] overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-blue-400/5 to-purple-400/5 pointer-events-none"></div>
            
            <div class="relative p-6 text-center">
                <div class="relative shrink-0 mx-auto mb-4">
                    <div class="w-24 h-24 rounded-full bg-white flex items-center justify-center overflow-hidden ring-4 ring-white shadow-lg mx-auto">
                        @if ($member && $member->photo)
                            <img src="{{ asset('storage/' . $member->photo) }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                        @else
                            <img src="{{ asset('storage/profile/profile-placeholder.png') }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                        @endif
                    </div>
                </div>

                <h2 class="text-lg font-bold text-gray-900 mt-4">{{ $user->name }}</h2>
                <p class="text-xs text-gray-400 mt-1">{{ $user->email }}</p>
                <span class="inline-block mt-2 px-3 py-1 text-xs font-semibold rounded-full bg-blue-50 text-blue-600 border border-blue-200">{{ $user->role->name }}</span>

                <div class="mt-5 pt-5 border-t border-gray-100 space-y-2 text-sm">
                    <div>
                        <p class="text-[11px] text-gray-400 font-semibold">NOMOR WHATSAPP</p>
                        <p class="text-gray-900 font-medium">{{ $user->whatsapp }}</p>
                    </div>
                    @if ($member)
                        <div>
                            <p class="text-[11px] text-gray-400 font-semibold">NIM</p>
                            <p class="text-gray-900 font-medium">{{ $member->nim ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-[11px] text-gray-400 font-semibold">DIVISI</p>
                            <p class="text-gray-900 font-medium">{{ $member->division->name }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Edit Forms --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Edit Profile Form --}}
            <div class="relative bg-white/80 backdrop-blur-xl rounded-[28px] border border-white/60 shadow-[0_8px_32px_rgba(59,130,246,0.06)] overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-blue-400/5 to-purple-400/5 pointer-events-none"></div>
                
                <div class="relative p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-5 flex items-center gap-2">
                        <span class="w-1.5 h-6 rounded-full bg-blue-500"></span>
                        Edit Profile
                    </h3>

                    <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="name" class="block text-[11px] font-semibold text-gray-500 mb-1.5">Nama Lengkap</label>
                                <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                                    class="w-full h-[44px] px-4 rounded-[14px] border border-gray-200/80 text-[13px] focus:outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-400/15 transition-all bg-white/60 @error('name') border-red-300 @enderror">
                                @error('name')
                                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="email" class="block text-[11px] font-semibold text-gray-500 mb-1.5">Email</label>
                                <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                                    class="w-full h-[44px] px-4 rounded-[14px] border border-gray-200/80 text-[13px] focus:outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-400/15 transition-all bg-white/60 @error('email') border-red-300 @enderror">
                                @error('email')
                                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="whatsapp" class="block text-[11px] font-semibold text-gray-500 mb-1.5">Nomor WhatsApp</label>
                                <input type="tel" name="whatsapp" id="whatsapp" value="{{ old('whatsapp', $user->whatsapp) }}" pattern="[0-9+\-\s]+" required
                                    class="w-full h-[44px] px-4 rounded-[14px] border border-gray-200/80 text-[13px] focus:outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-400/15 transition-all bg-white/60 @error('whatsapp') border-red-300 @enderror">
                                @error('whatsapp')
                                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            @if ($member)
                                <div>
                                    <label for="nim" class="block text-[11px] font-semibold text-gray-500 mb-1.5">NIM</label>
                                    <input type="text" name="nim" id="nim" value="{{ old('nim', $member->nim) }}"
                                        class="w-full h-[44px] px-4 rounded-[14px] border border-gray-200/80 text-[13px] focus:outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-400/15 transition-all bg-white/60 @error('nim') border-red-300 @enderror">
                                    @error('nim')
                                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            @endif
                        </div>

                        @if ($member)
                            <div>
                                <label for="bio" class="block text-[11px] font-semibold text-gray-500 mb-1.5">Bio</label>
                                <textarea name="bio" id="bio" rows="3"
                                    class="w-full px-4 py-3 rounded-[14px] border border-gray-200/80 text-[13px] focus:outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-400/15 transition-all resize-none bg-white/60 @error('bio') border-red-300 @enderror">{{ old('bio', $member->bio) }}</textarea>
                                @error('bio')
                                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div>
                                    <label for="linkedin" class="block text-[11px] font-semibold text-gray-500 mb-1.5">LinkedIn</label>
                                    <input type="url" name="linkedin" id="linkedin" value="{{ old('linkedin', $member->linkedin) }}"
                                        class="w-full h-[44px] px-4 rounded-[14px] border border-gray-200/80 text-[13px] focus:outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-400/15 transition-all bg-white/60"
                                        placeholder="https://linkedin.com/in/...">
                                    @error('linkedin')
                                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="instagram" class="block text-[11px] font-semibold text-gray-500 mb-1.5">Instagram</label>
                                    <input type="url" name="instagram" id="instagram" value="{{ old('instagram', $member->instagram) }}"
                                        class="w-full h-[44px] px-4 rounded-[14px] border border-gray-200/80 text-[13px] focus:outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-400/15 transition-all bg-white/60"
                                        placeholder="https://instagram.com/...">
                                    @error('instagram')
                                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="github" class="block text-[11px] font-semibold text-gray-500 mb-1.5">GitHub</label>
                                    <input type="url" name="github" id="github" value="{{ old('github', $member->github) }}"
                                        class="w-full h-[44px] px-4 rounded-[14px] border border-gray-200/80 text-[13px] focus:outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-400/15 transition-all bg-white/60"
                                        placeholder="https://github.com/...">
                                    @error('github')
                                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div>
                                <label for="photo" class="block text-[11px] font-semibold text-gray-500 mb-1.5">Foto Profile</label>
                                <input type="file" name="photo" id="photo" accept="image/jpeg,image/png,image/jpg,image/webp"
                                    class="w-full h-[44px] px-4 rounded-[14px] border border-gray-200/80 text-[13px] focus:outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-400/15 transition-all bg-white/60">
                                <p class="text-[11px] text-gray-400 mt-1">Maksimal 2MB. Format: JPG, PNG, WEBP</p>
                                @error('photo')
                                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        @endif

                        <div class="flex items-center gap-3 pt-2">
                            <button type="submit" class="relative inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-blue-500 to-blue-600 text-white text-[13px] font-semibold rounded-[18px] shadow-[0_4px_16px_-4px_rgba(59,130,246,0.3)] hover:shadow-[0_8px_24px_-4px_rgba(59,130,246,0.45)] hover:scale-105 transition-all duration-300 overflow-hidden group">
                                <div class="absolute inset-0 bg-white/20 rounded-[18px] opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                <span class="relative flex items-center gap-2">
                                    <i class="fas fa-save text-[10px]"></i>
                                    Simpan Perubahan
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Change Password Form --}}
            <div class="relative bg-white/80 backdrop-blur-xl rounded-[28px] border border-white/60 shadow-[0_8px_32px_rgba(59,130,246,0.06)] overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-blue-400/5 to-purple-400/5 pointer-events-none"></div>
                
                <div class="relative p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-5 flex items-center gap-2">
                        <span class="w-1.5 h-6 rounded-full bg-amber-500"></span>
                        Ubah Password
                    </h3>

                    <form action="{{ route('admin.profile.password') }}" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')

                        <div>
                            <label for="current_password" class="block text-[11px] font-semibold text-gray-500 mb-1.5">Password Saat Ini</label>
                            <input type="password" name="current_password" id="current_password" required
                                class="w-full h-[44px] px-4 rounded-[14px] border border-gray-200/80 text-[13px] focus:outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-400/15 transition-all bg-white/60 @error('current_password') border-red-300 @enderror">
                            @error('current_password')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password" class="block text-[11px] font-semibold text-gray-500 mb-1.5">Password Baru</label>
                            <input type="password" name="password" id="password" required
                                class="w-full h-[44px] px-4 rounded-[14px] border border-gray-200/80 text-[13px] focus:outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-400/15 transition-all bg-white/60 @error('password') border-red-300 @enderror">
                            <p class="text-[11px] text-gray-400 mt-1">Minimal 8 karakter</p>
                            @error('password')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-[11px] font-semibold text-gray-500 mb-1.5">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" required
                                class="w-full h-[44px] px-4 rounded-[14px] border border-gray-200/80 text-[13px] focus:outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-400/15 transition-all bg-white/60">
                        </div>

                        <div class="flex items-center gap-3 pt-2">
                            <button type="submit" class="relative inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-amber-500 to-amber-600 text-white text-[13px] font-semibold rounded-[18px] shadow-[0_4px_16px_-4px_rgba(217,119,6,0.3)] hover:shadow-[0_8px_24px_-4px_rgba(217,119,6,0.45)] hover:scale-105 transition-all duration-300 overflow-hidden group">
                                <div class="absolute inset-0 bg-white/20 rounded-[18px] opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                <span class="relative flex items-center gap-2">
                                    <i class="fas fa-lock text-[10px]"></i>
                                    Ubah Password
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
