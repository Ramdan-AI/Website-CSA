@props([
    'name' => 'photo',
    'label' => 'Foto',
    'current' => null,
    'aspect' => 1,
    'shape' => 'circle',
    'hint' => 'JPG, PNG, WEBP. Maksimal 2MB.',
    'previewSize' => 'w-16 h-16',
    'required' => false,
])

@php
    $fieldId = $name . '-crop';
    $previewClass = $shape === 'circle' ? 'rounded-full' : 'rounded-xl';
    $fallback = $current ?: asset('storage/profile/profile-placeholder.png');
@endphp

<div x-data="photoCropper({ aspect: {{ (float) $aspect }}, initial: @js($fallback) })" x-init="init()">
    @if ($label)
        <label class="block text-[13px] font-medium text-[var(--text-secondary)] mb-2">{{ $label }}</label>
    @endif

    <div class="flex items-center gap-4">
        <div class="{{ $previewSize }} {{ $previewClass }} overflow-hidden bg-[var(--bg-muted)] shrink-0">
            <img :src="preview || '{{ $fallback }}'" alt="Pratinjau {{ $name }}" class="w-full h-full object-cover">
        </div>

        <div class="flex flex-col gap-2 min-w-0">
            <input type="file" x-ref="input" id="{{ $fieldId }}" name="{{ $name }}"
                accept="image/jpeg,image/png,image/webp" class="hidden" @change="onPick($event)" @if ($required) required @endif>
            <button type="button" @click="$refs.input.click()" class="csa-btn csa-btn-ghost !min-h-[40px] !text-[13px] w-fit">
                <x-admin.icon name="upload" :size="15" /> Pilih foto
            </button>
            @if ($hint)
                <p class="text-[11px] text-[var(--text-muted)]">{{ $hint }}</p>
            @endif
        </div>
    </div>

    @error($name)
        <p class="text-[12px] text-red-500 mt-2">{{ $message }}</p>
    @enderror

    <div x-show="open" x-cloak class="fixed inset-0 z-[80] flex items-center justify-center p-4"
        style="background: rgba(0,0,0,.6);">
        <div class="csa-panel w-full max-w-md p-5">
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

@once
    @push('styles')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/cropperjs@1.6.2/dist/cropper.min.css">
    @endpush

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/cropperjs@1.6.2/dist/cropper.min.js"></script>
        <script>
            /**
             * Pemangkas gambar reusable. Pengguna memilih gambar, memangkasnya
             * sesuai rasio, lalu hasilnya dikirim lewat input file asli.
             */
            window.photoCropper = (config = {}) => ({
                open: false,
                preview: null,
                cropper: null,
                originalName: 'photo.jpg',
                aspect: config.aspect ?? 1,

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
                                aspectRatio: this.aspect,
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

                    const size = this.aspect === 1 ? 512 : 1200;
                    const canvas = this.cropper.getCroppedCanvas({
                        width: this.aspect === 1 ? size : undefined,
                        height: this.aspect === 1 ? size : undefined,
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
            });
        </script>
    @endpush
@endonce
