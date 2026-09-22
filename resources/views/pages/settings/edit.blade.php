@extends('layouts.admin')

@section('content')
<div class="space-y-4 sm:space-y-5">
    <div>
        <h1 class="text-[26px] sm:text-[30px] font-bold text-[var(--text-primary)] tracking-[-0.03em]">Pengaturan</h1>
        <p class="text-[13px] text-[var(--text-muted)] mt-1">Kelola informasi dasar dan kontak organisasi CSA.</p>
    </div>

    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
            <div class="lg:col-span-1">
                <div class="csa-card p-5">
                    <label class="block text-[13px] font-medium text-[var(--text-secondary)] mb-2">Logo CSA</label>
                    <div class="w-full aspect-square rounded-2xl border border-dashed border-[var(--border-strong)] flex items-center justify-center overflow-hidden bg-[var(--bg-muted)] cursor-pointer hover:border-[var(--accent)] transition-colors"
                        onclick="document.getElementById('logo-input').click()">
                        <img id="logo-preview" src="{{ $setting->logo ? asset('storage/' . $setting->logo) : '#' }}" alt="Pratinjau logo"
                            class="{{ $setting->logo ? 'w-full h-full object-contain p-6' : 'hidden' }}">
                        <div id="logo-placeholder" class="{{ $setting->logo ? 'hidden' : 'flex' }} flex-col items-center text-[var(--text-muted)]">
                            <x-admin.icon name="image" :size="24" />
                            <span class="text-[12px] font-medium mt-2">Upload logo</span>
                            <span class="text-[11px] mt-0.5">PNG, SVG</span>
                        </div>
                    </div>
                    <input type="file" name="logo" id="logo-input" accept="image/png,image/svg+xml" class="hidden" onchange="previewLogo(event)">
                    @error('logo')<p class="text-[12px] text-red-500 mt-2">{{ $message }}</p>@enderror
                    <p class="text-[11px] text-[var(--text-muted)] mt-2">Maksimal 2MB. Format PNG atau SVG.</p>
                </div>
            </div>

            <div class="lg:col-span-2 space-y-4">
                <section class="csa-card p-5">
                    <h3 class="text-[14px] font-semibold text-[var(--text-primary)] mb-4">Informasi Dasar</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <x-form.input name="site_name" id="site_name" label="Nama Situs" :value="old('site_name', $setting->site_name)" placeholder="Nama organisasi" required />
                        <x-form.input name="email" id="email" type="email" label="Email" :value="old('email', $setting->email)" placeholder="email@csa.ac.id" />
                        <x-form.input name="phone" id="phone" label="Telepon" :value="old('phone', $setting->phone)" placeholder="08123456789" />
                        <x-form.input name="address" id="address" label="Alamat" :value="old('address', $setting->address)" placeholder="Alamat organisasi" />
                    </div>
                </section>

                <section class="csa-card p-5">
                    <h3 class="text-[14px] font-semibold text-[var(--text-primary)] mb-4">Media Sosial</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <x-form.input name="instagram" id="instagram" type="url" label="Instagram" :value="old('instagram', $setting->instagram)" placeholder="https://instagram.com/csa" />
                        <x-form.input name="youtube" id="youtube" type="url" label="YouTube" :value="old('youtube', $setting->youtube)" placeholder="https://youtube.com/@csa" />
                        <x-form.input name="linkedin" id="linkedin" type="url" label="LinkedIn" :value="old('linkedin', $setting->linkedin)" placeholder="https://linkedin.com/company/csa" />
                    </div>
                </section>

                <section class="csa-card p-5">
                    <h3 class="text-[14px] font-semibold text-[var(--text-primary)] mb-4">Pendaftaran & Komunitas</h3>

                    <label class="flex items-center justify-between gap-4 cursor-pointer">
                        <span>
                            <span class="block text-[13px] font-medium text-[var(--text-primary)]">Buka Pendaftaran</span>
                            <span class="block text-[12px] text-[var(--text-muted)] mt-0.5">Jika aktif, pengguna baru bisa mendaftar akun.</span>
                        </span>
                        <input type="hidden" name="registration_open" value="0">
                        <input type="checkbox" name="registration_open" value="1" class="peer sr-only" {{ old('registration_open', $setting->registration_open) ? 'checked' : '' }}>
                        <span class="relative w-10 h-6 rounded-full bg-[var(--border-strong)] after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:w-5 after:h-5 after:rounded-full after:bg-white after:transition-all peer-checked:bg-[var(--accent)] peer-checked:after:translate-x-4 shrink-0"></span>
                    </label>

                    <div class="mt-4 pt-4 border-t border-[var(--border-soft)]">
                        <x-form.input name="whatsapp_group_link" id="whatsapp_group_link" type="url" label="Link Grup WhatsApp CSA" :value="old('whatsapp_group_link', $setting->whatsapp_group_link)" placeholder="https://chat.whatsapp.com/..." hint="Dibagikan ke anggota baru setelah berhasil mendaftar." />
                    </div>
                </section>
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="csa-btn csa-btn-primary">
                <x-admin.icon name="save" :size="16" /> Simpan Pengaturan
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    function previewLogo(event) {
        const file = event.target.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = (e) => {
            const preview = document.getElementById('logo-preview');
            preview.src = e.target.result;
            preview.classList.remove('hidden');
            document.getElementById('logo-placeholder').classList.add('hidden');
        };
        reader.readAsDataURL(file);
    }
</script>
@endpush
