@extends('layouts.admin')

@section('content')
<div class="space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
        <div>
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest">Pengaturan</p>
            <h1 class="text-3xl font-bold text-gray-900 mt-1">Pengaturan Akun</h1>
            <p class="text-sm text-gray-400 mt-1.5 font-medium">Kelola pengaturan akun dan preferensi pribadi Anda.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Profile Card --}}
        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
            <div class="p-6 text-center">
                <div class="w-20 h-20 rounded-full overflow-hidden mx-auto border-2 border-gray-100 bg-gray-100 mb-4">
                    @if($member && $member->photo)
                        <img src="{{ asset('storage/' . $member->photo) }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                    @else
                        <img src="{{ asset('storage/profile/profile-placeholder.png') }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                    @endif
                </div>
                <h2 class="text-lg font-bold text-gray-900">{{ $user->name }}</h2>
                <p class="text-xs text-gray-400 mt-1">{{ $user->email }}</p>
                <p class="text-xs text-gray-400 mt-1">{{ $user->role->name ?? 'Anggota' }}</p>
            </div>
        </div>

        {{-- Settings Form --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-base font-bold text-gray-900">Ubah Profil</h3>
                </div>
                <div class="p-6">
                    <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="name" class="block text-sm font-semibold text-gray-900 mb-1">Nama Lengkap</label>
                                <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                                    class="w-full h-12 px-4 rounded-xl border border-gray-200 text-sm focus:outline-none focus:border-gray-400 bg-gray-50 focus:bg-white transition-all">
                            </div>
                            <div>
                                <label for="email" class="block text-sm font-semibold text-gray-900 mb-1">Email</label>
                                <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                                    class="w-full h-12 px-4 rounded-xl border border-gray-200 text-sm focus:outline-none focus:border-gray-400 bg-gray-50 focus:bg-white transition-all">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="whatsapp" class="block text-sm font-semibold text-gray-900 mb-1">Nomor WhatsApp</label>
                                <input type="tel" name="whatsapp" id="whatsapp" value="{{ old('whatsapp', $user->whatsapp) }}"
                                    class="w-full h-12 px-4 rounded-xl border border-gray-200 text-sm focus:outline-none focus:border-gray-400 bg-gray-50 focus:bg-white transition-all">
                            </div>
                            <div>
                                <label for="nim" class="block text-sm font-semibold text-gray-900 mb-1">NIM</label>
                                <input type="text" name="nim" id="nim" value="{{ old('nim', $member?->nim) }}"
                                    class="w-full h-12 px-4 rounded-xl border border-gray-200 text-sm focus:outline-none focus:border-gray-400 bg-gray-50 focus:bg-white transition-all">
                            </div>
                        </div>

                        <div>
                            <label for="bio" class="block text-sm font-semibold text-gray-900 mb-1">Bio</label>
                            <textarea name="bio" id="bio" rows="3"
                                class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none focus:border-gray-400 bg-gray-50 focus:bg-white transition-all resize-none">{{ old('bio', $member?->bio) }}</textarea>
                        </div>

                        <div>
                            <label for="photo" class="block text-sm font-semibold text-gray-900 mb-1">Foto Profil</label>
                            <input type="file" name="photo" id="photo" accept="image/*"
                                class="w-full h-12 px-4 py-2 rounded-xl border border-gray-200 text-sm focus:outline-none focus:border-gray-400 bg-gray-50 focus:bg-white transition-all">
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="px-6 py-2.5 bg-gray-900 text-white text-sm font-semibold rounded-xl hover:bg-gray-800 transition-colors">
                                Simpan Profil
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-base font-bold text-gray-900">Ubah Password</h3>
                </div>
                <div class="p-6">
                    <form action="{{ route('admin.profile.password') }}" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')

                        <div>
                            <label for="current_password" class="block text-sm font-semibold text-gray-900 mb-1">Password Saat Ini</label>
                            <input type="password" name="current_password" id="current_password" required
                                class="w-full h-12 px-4 rounded-xl border border-gray-200 text-sm focus:outline-none focus:border-gray-400 bg-gray-50 focus:bg-white transition-all">
                        </div>

                        <div>
                            <label for="new_password" class="block text-sm font-semibold text-gray-900 mb-1">Password Baru</label>
                            <input type="password" name="password" id="new_password" required
                                class="w-full h-12 px-4 rounded-xl border border-gray-200 text-sm focus:outline-none focus:border-gray-400 bg-gray-50 focus:bg-white transition-all">
                            <p class="text-xs text-gray-400 mt-1">Minimal 8 karakter</p>
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-sm font-semibold text-gray-900 mb-1">Konfirmasi Password Baru</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" required
                                class="w-full h-12 px-4 rounded-xl border border-gray-200 text-sm focus:outline-none focus:border-gray-400 bg-gray-50 focus:bg-white transition-all">
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="px-6 py-2.5 bg-gray-900 text-white text-sm font-semibold rounded-xl hover:bg-gray-800 transition-colors">
                                Ubah Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-base font-bold text-gray-900">Akses & Hak</h3>
                </div>
                <div class="p-6 space-y-3">
                    <div class="flex items-center justify-between py-3 border-b border-gray-100">
                        <div>
                            <p class="text-sm font-semibold text-gray-900">Role</p>
                            <p class="text-xs text-gray-400">Hak akses Anda saat ini</p>
                        </div>
                        <span class="px-3 py-1 rounded-lg text-xs font-semibold bg-blue-50 text-blue-700">{{ $user->role->name ?? 'Anggota' }}</span>
                    </div>
                    <div class="flex items-center justify-between py-3 border-b border-gray-100">
                        <div>
                            <p class="text-sm font-semibold text-gray-900">Divisi</p>
                            <p class="text-xs text-gray-400">Divisi Anda tergabung</p>
                        </div>
                        <span class="text-sm font-semibold text-gray-900">{{ $member?->division?->name ?? 'Tidak ada' }}</span>
                    </div>
                    <div class="flex items-center justify-between py-3">
                        <div>
                            <p class="text-sm font-semibold text-gray-900">Jabatan</p>
                            <p class="text-xs text-gray-400">Jabatan Anda di divisi</p>
                        </div>
                        <span class="text-sm font-semibold text-gray-900">{{ $member?->position?->name ?? 'Anggota' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection