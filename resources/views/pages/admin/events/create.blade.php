@extends('layouts.admin')

@section('content')

<div class="mb-8">
    <div class="flex items-center gap-2 mb-2">
        <a href="{{ route('admin.events.index') }}" class="text-xs font-mono text-zinc-500 hover:text-zinc-300 uppercase tracking-widest transition-colors">EVENTS</a>
        <span class="text-zinc-700">/</span>
        <span class="text-xs font-mono text-zinc-300 uppercase tracking-widest">CREATE</span>
    </div>
    <h1 class="text-2xl font-semibold tracking-tight text-zinc-100">Tambah Event Baru</h1>
    <p class="text-sm text-zinc-500 mt-1">Registrasikan event atau kegiatan baru ke dalam sistem.</p>
</div>

<div class="w-full max-w-3xl bg-zinc-950 border border-zinc-800 rounded-lg shadow-sm">
    <form action="{{ route('admin.events.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
        @csrf

        {{-- Alpine used just for realtime slug preview --}}
        <div x-data="{ title: '{{ old('title') }}', slug() { return this.title.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)+/g, ''); } }">
            <div class="space-y-1.5">
                <label for="title" class="text-xs font-semibold text-zinc-400 tracking-wide uppercase">Judul Event <span class="text-red-500">*</span></label>
                <input type="text" id="title" name="title" x-model="title" required
                       class="w-full h-10 px-3 bg-zinc-900 border border-zinc-700 hover:border-zinc-600 focus:border-blue-500 rounded text-sm text-zinc-100 placeholder-zinc-600 focus:outline-none focus:ring-1 focus:ring-blue-500 transition-colors"
                       placeholder="Contoh: Workshop UI/UX Design">
                
                {{-- Auto-slug preview --}}
                <p class="text-[11px] font-mono text-zinc-500 flex items-center gap-2 mt-1">
                    <span class="px-1.5 py-0.5 rounded-sm bg-zinc-800 text-zinc-400 border border-zinc-700">AUTO_SLUG</span>
                    <span x-text="slug() || 'akan-di-generate-otomatis'"></span>
                </p>
                @error('title') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="space-y-1.5">
            <label for="description" class="text-xs font-semibold text-zinc-400 tracking-wide uppercase">Deskripsi Event <span class="text-red-500">*</span></label>
            <textarea id="description" name="description" rows="5" required
                      class="w-full p-3 bg-zinc-900 border border-zinc-700 hover:border-zinc-600 focus:border-blue-500 rounded text-sm text-zinc-100 placeholder-zinc-600 focus:outline-none focus:ring-1 focus:ring-blue-500 transition-colors"
                      placeholder="Jelaskan detail event secara lengkap...">{{ old('description') }}</textarea>
            @error('description') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-1.5">
                <label for="event_date" class="text-xs font-semibold text-zinc-400 tracking-wide uppercase">Tanggal Pelaksanaan <span class="text-red-500">*</span></label>
                <input type="date" id="event_date" name="event_date" value="{{ old('event_date') }}" required
                       class="w-full h-10 px-3 bg-zinc-900 border border-zinc-700 hover:border-zinc-600 focus:border-blue-500 rounded text-sm text-zinc-100 focus:outline-none focus:ring-1 focus:ring-blue-500 transition-colors">
                @error('event_date') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="space-y-1.5">
                <label for="location" class="text-xs font-semibold text-zinc-400 tracking-wide uppercase">Lokasi</label>
                <input type="text" id="location" name="location" value="{{ old('location') }}"
                       class="w-full h-10 px-3 bg-zinc-900 border border-zinc-700 hover:border-zinc-600 focus:border-blue-500 rounded text-sm text-zinc-100 placeholder-zinc-600 focus:outline-none focus:ring-1 focus:ring-blue-500 transition-colors"
                       placeholder="Contoh: Lab Komputer STMIKMI">
                @error('location') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-1.5">
                <label for="status" class="text-xs font-semibold text-zinc-400 tracking-wide uppercase">Status <span class="text-red-500">*</span></label>
                <select id="status" name="status" required
                        class="w-full h-10 px-3 bg-zinc-900 border border-zinc-700 hover:border-zinc-600 focus:border-blue-500 rounded text-sm text-zinc-100 focus:outline-none focus:ring-1 focus:ring-blue-500 transition-colors">
                    <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Published</option>
                    <option value="closed" {{ old('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                </select>
                @error('status') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="space-y-1.5">
                <label for="registration_link" class="text-xs font-semibold text-zinc-400 tracking-wide uppercase">Link Registrasi Eksternal</label>
                <input type="url" id="registration_link" name="registration_link" value="{{ old('registration_link') }}"
                       class="w-full h-10 px-3 bg-zinc-900 border border-zinc-700 hover:border-zinc-600 focus:border-blue-500 rounded text-sm text-zinc-100 placeholder-zinc-600 font-mono focus:outline-none focus:ring-1 focus:ring-blue-500 transition-colors"
                       placeholder="https://forms.gle/...">
                <p class="text-[10px] text-zinc-500 mt-1">Kosongkan jika pendaftaran dilakukan di dalam sistem CSA.</p>
                @error('registration_link') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="border-t border-zinc-800 pt-6 space-y-1.5">
            <label for="banner" class="text-xs font-semibold text-zinc-400 tracking-wide uppercase">Banner Event (Opsional)</label>
            <input type="file" id="banner" name="banner" accept="image/jpeg,image/png,image/webp"
                   class="block w-full text-sm text-zinc-400
                          file:mr-4 file:py-2 file:px-4
                          file:rounded-sm file:border-0
                          file:text-xs file:font-semibold
                          file:bg-zinc-800 file:text-zinc-300
                          hover:file:bg-zinc-700 transition-colors">
            <p class="text-[10px] text-zinc-500 mt-1 font-mono">Format: JPG, PNG, WEBP. Maks 2MB.</p>
            @error('banner') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="flex items-center gap-3 pt-4 border-t border-zinc-800 mt-6">
            <button type="submit" class="h-10 px-5 inline-flex items-center justify-center gap-2 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-500 rounded transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-zinc-950">
                <i class="fas fa-save text-xs"></i> Simpan Event
            </button>
            <a href="{{ route('admin.events.index') }}" class="h-10 px-5 inline-flex items-center justify-center text-sm font-medium text-zinc-300 bg-zinc-900 border border-zinc-700 hover:bg-zinc-800 hover:text-zinc-100 rounded transition-colors focus:outline-none focus:ring-2 focus:ring-zinc-500">
                Batal
            </a>
        </div>
    </form>
</div>

@endsection