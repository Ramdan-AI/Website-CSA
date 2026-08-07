{{-- resources/views/pages/admin/settings/edit.blade.php --}}
@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    
    <div>
        <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest">Sistem</p>
        <h1 class="text-3xl font-bold text-gray-900 mt-1">Pengaturan</h1>
        <p class="text-sm text-gray-400 mt-1.5 font-medium">Kelola informasi dasar dan kontak organisasi CSA.</p>
    </div>

    <div class="relative bg-white/70 backdrop-blur-xl rounded-[30px] border border-white/50 shadow-[0_2px_16px_-4px_rgba(59,130,246,0.08)] overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-blue-400/5 to-cyan-400/5 pointer-events-none"></div>
        
        <div class="relative p-6">
            <form action="{{ route('admin.settings.update') }}" 
                  method="POST" 
                  enctype="multipart/form-data" 
                  class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div class="lg:col-span-1">
                        <label class="block text-xs font-semibold text-gray-500 mb-2">Logo CSA</label>
                        
                        <div class="relative">
                            <div class="w-full aspect-square rounded-[24px] border-2 border-dashed border-gray-200/80 flex items-center justify-center overflow-hidden bg-gray-50/30 hover:border-blue-300 transition-colors cursor-pointer"
                                 onclick="document.getElementById('logo-input').click()">
                                <img id="logo-preview" 
                                     src="{{ $setting->logo ? asset('storage/' . $setting->logo) : '#' }}" 
                                     class="{{ $setting->logo ? 'w-full h-full object-contain p-8' : 'hidden' }}">
                                <div id="logo-placeholder" class="{{ $setting->logo ? 'hidden' : 'flex flex-col items-center' }}">
                                    <div class="w-12 h-12 rounded-full bg-blue-50 flex items-center justify-center mb-3 shadow-[0_2px_8px_-2px_rgba(59,130,246,0.1)]">
                                        <i class="fas fa-image text-lg text-blue-400"></i>
                                    </div>
                                    <span class="text-xs text-gray-400 font-medium">Upload logo</span>
                                    <span class="text-[10px] text-gray-300 mt-1">PNG, SVG disarankan</span>
                                </div>
                            </div>
                        </div>
                        
                        <input type="file" name="logo" id="logo-input" accept="image/png,image/svg+xml" 
                               class="hidden" onchange="previewLogo(event)">
                        
                        @error('logo')
                            <p class="text-xs text-red-500 mt-2">{{ $message }}</p>
                        @enderror

                        <p class="text-[11px] text-gray-400 mt-2 font-medium">Maksimal 2MB. Format PNG atau SVG.</p>
                    </div>

                    <div class="lg:col-span-2 space-y-5">
                        <div class="bg-gray-50/40 rounded-[24px] p-5">
                            <h3 class="text-[13px] font-semibold text-gray-900 mb-4 flex items-center gap-2">
                                <span class="w-1.5 h-5 rounded-full bg-blue-500"></span>
                                Informasi Dasar
                            </h3>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label for="site_name" class="block text-[11px] font-semibold text-gray-500 mb-1.5">
                                        Nama Situs <span class="text-red-400">*</span>
                                    </label>
                                    <input type="text" name="site_name" id="site_name" 
                                           value="{{ old('site_name', $setting->site_name) }}"
                                           class="w-full h-[44px] px-4 rounded-[14px] border border-gray-200/80 text-[13px] focus:outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-400/15 transition-all bg-white/60"
                                           placeholder="Nama organisasi"
                                           required>
                                    @error('site_name')
                                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="email" class="block text-[11px] font-semibold text-gray-500 mb-1.5">Email</label>
                                    <input type="email" name="email" id="email" 
                                           value="{{ old('email', $setting->email) }}"
                                           class="w-full h-[44px] px-4 rounded-[14px] border border-gray-200/80 text-[13px] focus:outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-400/15 transition-all bg-white/60"
                                           placeholder="email@csa.ac.id">
                                    @error('email')
                                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="phone" class="block text-[11px] font-semibold text-gray-500 mb-1.5">Telepon</label>
                                    <input type="text" name="phone" id="phone" 
                                           value="{{ old('phone', $setting->phone) }}"
                                           class="w-full h-[44px] px-4 rounded-[14px] border border-gray-200/80 text-[13px] focus:outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-400/15 transition-all bg-white/60"
                                           placeholder="08123456789">
                                    @error('phone')
                                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="address" class="block text-[11px] font-semibold text-gray-500 mb-1.5">Alamat</label>
                                    <input type="text" name="address" id="address" 
                                           value="{{ old('address', $setting->address) }}"
                                           class="w-full h-[44px] px-4 rounded-[14px] border border-gray-200/80 text-[13px] focus:outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-400/15 transition-all bg-white/60"
                                           placeholder="Alamat organisasi">
                                    @error('address')
                                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="bg-gray-50/40 rounded-[24px] p-5">
                            <h3 class="text-[13px] font-semibold text-gray-900 mb-4 flex items-center gap-2">
                                <span class="w-1.5 h-5 rounded-full bg-blue-500"></span>
                                Media Sosial
                            </h3>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div>
                                    <label for="instagram" class="block text-[11px] font-semibold text-gray-500 mb-1.5">
                                        <i class="fab fa-instagram text-pink-500 mr-1"></i> Instagram
                                    </label>
                                    <input type="url" name="instagram" id="instagram" 
                                           value="{{ old('instagram', $setting->instagram) }}"
                                           class="w-full h-[44px] px-4 rounded-[14px] border border-gray-200/80 text-[13px] focus:outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-400/15 transition-all bg-white/60"
                                           placeholder="https://instagram.com/csa">
                                    @error('instagram')
                                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="youtube" class="block text-[11px] font-semibold text-gray-500 mb-1.5">
                                        <i class="fab fa-youtube text-red-500 mr-1"></i> YouTube
                                    </label>
                                    <input type="url" name="youtube" id="youtube" 
                                           value="{{ old('youtube', $setting->youtube) }}"
                                           class="w-full h-[44px] px-4 rounded-[14px] border border-gray-200/80 text-[13px] focus:outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-400/15 transition-all bg-white/60"
                                           placeholder="https://youtube.com/@csa">
                                    @error('youtube')
                                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="linkedin" class="block text-[11px] font-semibold text-gray-500 mb-1.5">
                                        <i class="fab fa-linkedin text-blue-600 mr-1"></i> LinkedIn
                                    </label>
                                    <input type="url" name="linkedin" id="linkedin" 
                                           value="{{ old('linkedin', $setting->linkedin) }}"
                                           class="w-full h-[44px] px-4 rounded-[14px] border border-gray-200/80 text-[13px] focus:outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-400/15 transition-all bg-white/60"
                                           placeholder="https://linkedin.com/company/csa">
                                    @error('linkedin')
                                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="bg-gray-50/40 rounded-[24px] p-5">
                            <h3 class="text-[13px] font-semibold text-gray-900 mb-4 flex items-center gap-2">
                                <span class="w-1.5 h-5 rounded-full bg-amber-500"></span>
                                Kontrol Pendaftaran
                            </h3>
                            <label class="flex items-center justify-between cursor-pointer">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Buka Pendaftaran</p>
                                    <p class="text-xs text-gray-400 mt-0.5">Jika diaktifkan, pengguna baru bisa mendaftar akun.</p>
                                </div>
                                <div class="relative">
                                    <input type="hidden" name="registration_open" value="0">
                                    <input type="checkbox" name="registration_open" value="1"
                                        {{ old('registration_open', $setting->registration_open) ? 'checked' : '' }}
                                        class="sr-only peer">
                                    <div class="w-11 h-6 bg-gray-200 rounded-full peer peer-checked:bg-blue-600 after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-full"></div>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button type="submit" class="relative inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-blue-500 to-blue-600 text-white text-[13px] font-semibold rounded-[18px] shadow-[0_4px_16px_-4px_rgba(59,130,246,0.3)] hover:shadow-[0_8px_24px_-4px_rgba(59,130,246,0.45)] hover:scale-105 transition-all duration-300 overflow-hidden group">
                        <div class="absolute inset-0 bg-white/20 rounded-[18px] opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        <span class="relative flex items-center gap-2">
                            <i class="fas fa-save text-[10px]"></i>
                            Simpan Pengaturan
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function previewLogo(event) {
    const file = event.target.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = function(e) {
        document.getElementById('logo-preview').src = e.target.result;
        document.getElementById('logo-preview').classList.remove('hidden');
        document.getElementById('logo-placeholder').classList.add('hidden');
    };
    reader.readAsDataURL(file);
}
</script>
@endpush