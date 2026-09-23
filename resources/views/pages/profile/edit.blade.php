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
                            <label class="block text-[13px] font-medium text-[var(--text-secondary)] mb-2">Foto Profile</label>

                            {{-- Cropper: pilih gambar, pangkas, lalu unggah. --}}
                            <div x-data="photoCropper()" x-init="init()">
                                <div class="flex items-center gap-4">
                                    <div class="w-16 h-16 rounded-full overflow-hidden bg-[var(--bg-muted)] shrink-0">
                                        <img :src="preview || '{{ $user->avatar ? asset('storage/' . $user->avatar) : ($member?->photo ? asset('storage/' . $member->photo) : asset('storage/profile/profile-placeholder.png')) }}'"
                                            alt="Pratinjau foto" class="w-full h-full object-cover">
                                    </div>

                                    <div class="flex flex-col gap-2">
                                        <input type="file" x-ref="input" name="photo" id="photo"
                                            accept="image/jpeg,image/png,image/webp" class="hidden"
                                            @change="onPick($event)">
                                        <button type="button" @click="$refs.input.click()" class="csa-btn csa-btn-ghost !min-h-[40px] !text-[13px]">
                                            <x-admin.icon name="upload" :size="15" /> Pilih foto
                                        </button>
                                        <p class="text-[11px] text-[var(--text-muted)]">JPG, PNG, WEBP. Maksimal 2MB.</p>
                                    </div>
                                </div>

                                @error('photo')<p class="text-[12px] text-red-500 mt-2">{{ $message }}</p>@enderror

                                {{-- Dialog pemangkasan --}}
                                <div x-show="open" x-cloak class="fixed inset-0 z-[80] flex items-center justify-center p-4"
                                    style="background: rgba(0,0,0,.6);">
                                    <div class="csa-card w-full max-w-md p-5">
                                        <h3 class="text-[14px] font-semibold text-[var(--text-primary)] mb-3">Sesuaikan foto</h3>

                                        <div class="rounded-xl overflow-hidden bg-[var(--bg-muted)]" style="max-height: 360px;">
                                            <img x-ref="cropImage" alt="Gambar untuk dipangkas" class="block w-full">
                                        </div>

                                        <div class="flex items-center gap-2 mt-4">
                                            <button type="button" @click="$refs.zoomRange.value = Math.max(0.2, $refs.zoomRange.value - 0.1); cropper.zoom(-0.1)"
                                                class="csa-icon-btn" title="Perkecil" aria-label="Perkecil">
                                                <x-admin.icon name="minus" :size="16" />
                                            </button>
                                            <input x-ref="zoomRange" type="range" min="0.2" max="3" step="0.05" value="1"
                                                class="flex-1 accent-[var(--accent)]"
                                                @input="cropper.zoomTo(parseFloat($event.target.value))">
                                            <button type="button" @click="$refs.zoomRange.value = Math.min(3, parseFloat($refs.zoomRange.value) + 0.1); cropper.zoom(0.1)"
                                                class="csa-icon-btn" title="Perbesar" aria-label="Perbesar">
                                                <x-admin.icon name="plus" :size="16" />
                                            </button>
                                        </div>

                                        <p class="text-[11px] text-[var(--text-muted)] mt-2">Geser gambar untuk mengatur posisi.</p>

                                        <div class="flex items-center gap-2 mt-5">
                                            <button type="button" @click="apply()" class="csa-btn csa-btn-primary flex-1">Gunakan foto</button>
                                            <button type="button" @click="cancel()" class="csa-btn csa-btn-ghost">Batal</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
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

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/cropperjs@1.6.2/dist/cropper.min.css">
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/cropperjs@1.6.2/dist/cropper.min.js"></script>
    <script>
        /**
         * Pemangkas foto profil. Pengguna memilih gambar, memangkasnya jadi
         * kotak 1:1, lalu hasil pangkasan dikirim lewat input file yang sama.
         */
        function photoCropper() {
            return {
                open: false,
                preview: null,
                cropper: null,
                originalName: 'photo.jpg',

                init() {
                    this._onKey = (event) => {
                        if (event.key === 'Escape' && this.open) this.cancel();
                    };
                    window.addEventListener('keydown', this._onKey);
                },

                onPick(event) {
                    const file = event.target.files?.[0];
                    if (!file) return;

                    this.originalName = file.name || 'photo.jpg';
                    const reader = new FileReader();

                    reader.onload = (e) => {
                        const image = this.$refs.cropImage;
                        image.src = e.target.result;
                        this.open = true;

                        this.$nextTick(() => {
                            if (this.cropper) this.cropper.destroy();

                            this.cropper = new Cropper(image, {
                                aspectRatio: 1,
                                viewMode: 1,
                                dragMode: 'move',
                                autoCropArea: 1,
                                background: false,
                                guides: false,
                                responsive: true,
                                zoomOnWheel: true,
                            });
                        });
                    };

                    reader.readAsDataURL(file);
                },

                apply() {
                    if (!this.cropper) return;

                    const canvas = this.cropper.getCroppedCanvas({
                        width: 512,
                        height: 512,
                        imageSmoothingQuality: 'high',
                    });

                    this.preview = canvas.toDataURL('image/jpeg', 0.9);

                    canvas.toBlob((blob) => {
                        const file = new File([blob], this.originalName.replace(/\.[^.]+$/, '') + '.jpg', {
                            type: 'image/jpeg',
                        });

                        const input = this.$refs.input;
                        const transfer = new DataTransfer();
                        transfer.items.add(file);
                        input.files = transfer.files;

                        // Sinkronkan nama agar validasi server tetap lolos.
                        input.dispatchEvent(new Event('change', { bubbles: false }));

                        this.close();
                    }, 'image/jpeg', 0.9);
                },

                cancel() {
                    this.$refs.input.value = '';
                    this.preview = null;
                    this.close();
                },

                close() {
                    this.open = false;
                    if (this.cropper) {
                        this.cropper.destroy();
                        this.cropper = null;
                    }
                },
            };
        }
    </script>
@endpush
