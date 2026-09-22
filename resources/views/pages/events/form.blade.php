@extends('layouts.admin')

@section('content')
<div class="space-y-4 sm:space-y-5 max-w-3xl">
    <div>
        <a href="{{ route('admin.events.index') }}" class="inline-flex items-center gap-1.5 text-[12px] text-[var(--text-muted)] hover:text-[var(--text-primary)] transition-colors">
            <x-admin.icon name="arrow-left" :size="14" /> Kembali ke Acara
        </a>
        <h1 class="text-[26px] sm:text-[30px] font-bold text-[var(--text-primary)] tracking-[-0.03em] mt-2">Tambah Acara</h1>
        <p class="text-[13px] text-[var(--text-muted)] mt-1">Registrasikan event atau kegiatan baru ke dalam sistem.</p>
    </div>

    <form action="{{ route('admin.events.store') }}" method="POST" enctype="multipart/form-data" class="csa-card p-5 sm:p-6 space-y-4">
        @csrf

        <x-form.input name="title" id="title" label="Judul Event" :value="old('title')" placeholder="Contoh: Workshop UI/UX Design" required autofocus />
        <x-form.textarea name="description" id="description" label="Deskripsi Event" :value="old('description')" placeholder="Jelaskan detail event secara lengkap..." rows="5" required />

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <x-form.input name="event_date" id="event_date" type="date" label="Tanggal Pelaksanaan" :value="old('event_date')" required />
            <x-form.input name="location" id="location" label="Lokasi" :value="old('location')" placeholder="Contoh: Lab Komputer" />
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label for="status" class="block text-[13px] font-medium text-[var(--text-secondary)] mb-1.5">Status</label>
                <select name="status" id="status" required class="csa-input">
                    <option value="draft" @selected(old('status') === 'draft')>Draft</option>
                    <option value="published" @selected(old('status') === 'published')>Published</option>
                    <option value="closed" @selected(old('status') === 'closed')>Closed</option>
                </select>
                @error('status')<p class="text-[12px] text-red-500 mt-1.5">{{ $message }}</p>@enderror
            </div>
            <x-form.input name="registration_link" id="registration_link" type="url" label="Link Registrasi Eksternal" :value="old('registration_link')" placeholder="https://forms.gle/..." />
        </div>

        <div>
            <label class="block text-[13px] font-medium text-[var(--text-secondary)] mb-1.5">Banner Event <span class="text-[var(--text-muted)] font-normal">· Opsional</span></label>
            <label for="banner-input" class="block w-full aspect-[21/9] max-h-[200px] rounded-2xl border border-dashed border-[var(--border-strong)] overflow-hidden bg-[var(--bg-muted)] hover:border-[var(--accent)] transition-colors cursor-pointer">
                <img id="banner-preview" src="#" class="hidden w-full h-full object-cover" alt="Pratinjau banner">
                <span id="banner-placeholder" class="flex flex-col items-center justify-center h-full text-[var(--text-muted)]">
                    <x-admin.icon name="camera" :size="24" />
                    <span class="text-[12px] font-medium mt-2">Klik untuk upload banner</span>
                    <span class="text-[11px] mt-0.5">JPG, PNG, WEBP. Maks 2MB.</span>
                </span>
                <input type="file" name="banner" id="banner-input" accept="image/jpeg,image/png,image/webp" class="sr-only" onchange="previewBanner(event)">
            </label>
        </div>

        <div class="flex items-center gap-3 pt-2 border-t border-[var(--border-soft)]">
            <button type="submit" class="csa-btn csa-btn-primary">
                <x-admin.icon name="plus" :size="16" /> Tambah Acara
            </button>
            <a href="{{ route('admin.events.index') }}" class="csa-btn csa-btn-ghost">Batal</a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    function previewBanner(event) {
        const file = event.target.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = (e) => {
            const preview = document.getElementById('banner-preview');
            preview.src = e.target.result;
            preview.classList.remove('hidden');
            document.getElementById('banner-placeholder').classList.add('hidden');
        };
        reader.readAsDataURL(file);
    }
</script>
@endpush
