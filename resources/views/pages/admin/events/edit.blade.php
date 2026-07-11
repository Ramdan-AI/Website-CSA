@extends('layouts.admin')

@section('content')
<div class="space-y-6">

    <div>
        <p class="text-xs font-semibold text-cyan-400 uppercase tracking-widest">Kegiatan</p>
        <h1 class="text-3xl font-bold text-gray-900 mt-1">Edit Acara</h1>
        <p class="text-sm text-gray-400 mt-1.5 font-medium">Perbarui data kegiatan yang sudah terdaftar.</p>
    </div>

    <div class="relative bg-white/70 backdrop-blur-xl rounded-[30px] border border-white/50 shadow-[0_2px_16px_-4px_rgba(6,182,212,0.08)] overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-cyan-400/5 to-blue-400/5 pointer-events-none"></div>

        <div class="relative p-6">
            <form action="{{ route('admin.events.update', $event) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="bg-gray-50/40 rounded-[24px] p-5">
                    <h3 class="text-[13px] font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <span class="w-1.5 h-5 rounded-full bg-cyan-500"></span>
                        Informasi Event
                    </h3>

                    <div class="space-y-4" x-data="{ title: '{{ old('title', $event->title) }}', slug() { return this.title.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)+/g, ''); } }">
                        <div>
                            <label for="title" class="block text-[11px] font-semibold text-gray-500 mb-1.5">
                                Judul Event <span class="text-red-400">*</span>
                            </label>
                            <input type="text" name="title" id="title" x-model="title"
                                   class="w-full h-[44px] px-4 rounded-[14px] border border-gray-200/80 text-[13px] focus:outline-none focus:border-cyan-400 focus:ring-2 focus:ring-cyan-400/15 transition-all bg-white/60"
                                   placeholder="Contoh: Workshop UI/UX Design" required autofocus>
                            <p class="text-[10px] font-mono text-gray-400 flex items-center gap-2 mt-1.5">
                                <span class="px-1.5 py-0.5 rounded bg-gray-100 text-gray-500 border border-gray-100">AUTO_SLUG</span>
                                <span x-text="slug()"></span>
                            </p>
                            @error('title')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="description" class="block text-[11px] font-semibold text-gray-500 mb-1.5">
                                Deskripsi Event <span class="text-red-400">*</span>
                            </label>
                            <textarea name="description" id="description" rows="5"
                                      class="w-full px-4 py-3 rounded-[14px] border border-gray-200/80 text-[13px] focus:outline-none focus:border-cyan-400 focus:ring-2 focus:ring-cyan-400/15 transition-all resize-none bg-white/60"
                                      placeholder="Jelaskan detail event secara lengkap..." required>{{ old('description', $event->description) }}</textarea>
                            @error('description')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="event_date" class="block text-[11px] font-semibold text-gray-500 mb-1.5">
                                    Tanggal Pelaksanaan <span class="text-red-400">*</span>
                                </label>
                                <input type="date" name="event_date" id="event_date"
                                       value="{{ old('event_date', $event->event_date?->format('Y-m-d')) }}"
                                       class="w-full h-[44px] px-4 rounded-[14px] border border-gray-200/80 text-[13px] focus:outline-none focus:border-cyan-400 focus:ring-2 focus:ring-cyan-400/15 transition-all bg-white/60"
                                       required>
                                @error('event_date')
                                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="location" class="block text-[11px] font-semibold text-gray-500 mb-1.5">
                                    Lokasi
                                </label>
                                <input type="text" name="location" id="location"
                                       value="{{ old('location', $event->location) }}"
                                       class="w-full h-[44px] px-4 rounded-[14px] border border-gray-200/80 text-[13px] focus:outline-none focus:border-cyan-400 focus:ring-2 focus:ring-cyan-400/15 transition-all bg-white/60"
                                       placeholder="Contoh: Lab Komputer STMIKMI">
                                @error('location')
                                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="status" class="block text-[11px] font-semibold text-gray-500 mb-1.5">
                                    Status <span class="text-red-400">*</span>
                                </label>
                                <select name="status" id="status" required
                                        class="w-full h-[44px] px-4 rounded-[14px] border border-gray-200/80 text-[13px] focus:outline-none focus:border-cyan-400 focus:ring-2 focus:ring-cyan-400/15 transition-all bg-white/60">
                                    <option value="draft" {{ old('status', $event->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="published" {{ old('status', $event->status) == 'published' ? 'selected' : '' }}>Published</option>
                                    <option value="closed" {{ old('status', $event->status) == 'closed' ? 'selected' : '' }}>Closed</option>
                                </select>
                                @error('status')
                                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="registration_link" class="block text-[11px] font-semibold text-gray-500 mb-1.5">
                                    Link Registrasi Eksternal
                                </label>
                                <input type="url" name="registration_link" id="registration_link"
                                       value="{{ old('registration_link', $event->registration_link) }}"
                                       class="w-full h-[44px] px-4 rounded-[14px] border border-gray-200/80 text-[13px] font-mono focus:outline-none focus:border-cyan-400 focus:ring-2 focus:ring-cyan-400/15 transition-all bg-white/60"
                                       placeholder="https://forms.gle/...">
                                <p class="text-[10px] text-gray-400 mt-1">Kosongkan jika pendaftaran dilakukan di dalam sistem CSA.</p>
                                @error('registration_link')
                                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-[11px] font-semibold text-gray-500 mb-1.5">
                                Banner Event <span class="text-gray-400 font-normal">(opsional)</span>
                            </label>

                            @if($event->banner_url)
                                <div class="mb-3">
                                    <p class="text-[10px] text-gray-400 mb-2">Banner saat ini:</p>
                                    <div class="relative w-full max-h-[150px] rounded-[16px] overflow-hidden border border-gray-200/50">
                                        <img src="{{ $event->banner_url }}" alt="Current Banner" class="w-full object-cover">
                                    </div>
                                </div>
                            @endif

                            <div class="relative">
                                <div class="w-full aspect-[21/9] max-h-[200px] rounded-[24px] border-2 border-dashed border-gray-200/80 flex items-center justify-center overflow-hidden bg-gray-50/30 hover:border-cyan-300 transition-colors cursor-pointer"
                                     onclick="document.getElementById('banner-input').click()">
                                    <img id="banner-preview" src="#" class="hidden w-full h-full object-cover">
                                    <div id="banner-placeholder" class="flex flex-col items-center">
                                        <div class="w-12 h-12 rounded-full bg-cyan-50 flex items-center justify-center mb-3 shadow-[0_2px_8px_-2px_rgba(6,182,212,0.1)]">
                                            <i class="fas fa-camera text-lg text-cyan-400"></i>
                                        </div>
                                        <span class="text-xs text-gray-400 font-medium">
                                            {{ $event->banner_url ? 'Klik untuk ganti banner' : 'Klik untuk upload banner' }}
                                        </span>
                                        <span class="text-[10px] text-gray-300 mt-1">JPG, PNG, WEBP. Maks 2MB.</span>
                                    </div>
                                </div>
                            </div>
                            <input type="file" name="banner" id="banner-input" accept="image/jpeg,image/png,image/webp"
                                   class="hidden" onchange="previewBanner(event)">
                            @error('banner')
                                <p class="text-xs text-red-500 mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button type="submit"
                            class="relative inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-cyan-500 to-cyan-600 text-white text-[13px] font-semibold rounded-[18px] shadow-[0_4px_16px_-4px_rgba(6,182,212,0.3)] hover:shadow-[0_8px_24px_-4px_rgba(6,182,212,0.45)] hover:scale-105 transition-all duration-300 overflow-hidden group">
                        <div class="absolute inset-0 bg-white/20 rounded-[18px] opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        <span class="relative flex items-center gap-2">
                            <i class="fas fa-save text-[10px]"></i>
                            Simpan Perubahan
                        </span>
                    </button>
                    <a href="{{ route('admin.events.index') }}"
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
function previewBanner(event) {
    const file = event.target.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = function(e) {
        document.getElementById('banner-preview').src = e.target.result;
        document.getElementById('banner-preview').classList.remove('hidden');
        document.getElementById('banner-placeholder').classList.add('hidden');
    };
    reader.readAsDataURL(file);
}
</script>
@endpush
