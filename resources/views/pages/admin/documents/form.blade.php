@extends('layouts.admin')

@section('content')
    @php
        $isEdit = isset($document) && $document->exists;
        $title = $isEdit ? 'Edit Dokumen' : 'Tambah Dokumen';
        $subtitle = $isEdit 
            ? 'Perbarui informasi dokumen yang telah tersimpan.' 
            : 'Unggah dan registrasikan dokumen baru ke dalam sistem.';
        $hasFile = $isEdit && !empty($document->file);
    @endphp

    <div class="space-y-6">
        <div>
            <p class="text-xs font-semibold text-teal-400 uppercase tracking-widest">Publikasi</p>
            <h1 class="text-3xl font-bold text-gray-900 mt-1">{{ $title }}</h1>
            <p class="text-sm text-gray-400 mt-1.5 font-medium">{{ $subtitle }}</p>
        </div>

        <div
            class="relative bg-white/70 backdrop-blur-xl rounded-[30px] border border-white/50 shadow-[0_2px_16px_-4px_rgba(20,184,166,0.08)] overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-teal-400/5 to-cyan-400/5 pointer-events-none"></div>

            <div class="relative p-6">
                <form
                    action="{{ $isEdit ? route('admin.documents.update', $document) : route('admin.documents.store') }}"
                    method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @if ($isEdit)
                        @method('PUT')
                    @endif

                    <div class="bg-gray-50/40 rounded-[24px] p-5">
                        <h3 class="text-[13px] font-semibold text-gray-900 mb-4 flex items-center gap-2">
                            <span class="w-1.5 h-5 rounded-full bg-teal-500"></span>
                            Informasi Dokumen
                        </h3>

                        <div class="space-y-4">
                            {{-- Judul Dokumen --}}
                            <div>
                                <label for="title" class="block text-[11px] font-semibold text-gray-500 mb-1.5">
                                    Judul Dokumen <span class="text-red-400">*</span>
                                </label>
                                <input type="text" name="title" id="title"
                                    value="{{ old('title', $document->title ?? '') }}"
                                    class="w-full h-[44px] px-4 rounded-[14px] border border-gray-200/80 text-[13px] focus:outline-none focus:border-teal-400 focus:ring-2 focus:ring-teal-400/15 transition-all bg-white/60"
                                    placeholder="Contoh: SOP Divisi Programming" required autofocus>
                                @error('title')
                                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Upload File --}}
                            <div>
                                <label class="block text-[11px] font-semibold text-gray-500 mb-1.5">
                                    File Dokumen
                                    @if (!$isEdit)
                                        <span class="text-red-400">*</span>
                                    @else
                                        <span class="text-gray-400 font-normal">(biarkan kosong jika tidak ingin mengganti)</span>
                                    @endif
                                </label>

                                @if ($hasFile)
                                    <div class="mb-3 flex items-center gap-3 p-3 bg-white/60 rounded-[14px] border border-gray-200/50">
                                        <div class="w-10 h-10 rounded-xl bg-teal-100 flex items-center justify-center text-teal-600 shrink-0">
                                            <i class="fas fa-file-pdf text-sm"></i>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-[13px] font-semibold text-gray-900 truncate">
                                                {{ basename($document->file) }}
                                            </p>
                                            <p class="text-[10px] text-gray-400">
                                                {{ strtoupper(pathinfo($document->file, PATHINFO_EXTENSION)) }} • 
                                                {{ isset($document->file_size) ? number_format($document->file_size / 1024, 1) . ' KB' : 'Tersimpan' }}
                                            </p>
                                        </div>
                                        <a href="{{ asset('storage/' . $document->file) }}" target="_blank"
                                            class="w-8 h-8 rounded-lg bg-teal-50 flex items-center justify-center text-teal-500 hover:bg-teal-100 transition-colors"
                                            title="Lihat file">
                                            <i class="fas fa-external-link-alt text-xs"></i>
                                        </a>
                                    </div>
                                @endif

                                <div class="relative">
                                    <div class="w-full h-[120px] rounded-[14px] border-2 border-dashed border-gray-200/80 flex items-center justify-center bg-gray-50/30 hover:border-teal-300 transition-colors cursor-pointer"
                                        onclick="document.getElementById('file-input').click()">
                                        <div id="file-placeholder" class="flex flex-col items-center {{ $hasFile ? 'hidden' : '' }}">
                                            <div class="w-12 h-12 rounded-full bg-teal-50 flex items-center justify-center mb-3 shadow-[0_2px_8px_-2px_rgba(20,184,166,0.1)]">
                                                <i class="fas fa-cloud-upload-alt text-lg text-teal-400"></i>
                                            </div>
                                            <span class="text-xs text-gray-400 font-medium">Klik untuk upload file</span>
                                            <span class="text-[10px] text-gray-300 mt-1">PDF, DOC, DOCX, XLS, XLSX. Maks 10MB.</span>
                                        </div>
                                        <div id="file-selected" class="hidden flex-col items-center">
                                            <div class="w-12 h-12 rounded-full bg-teal-100 flex items-center justify-center mb-3">
                                                <i class="fas fa-check-circle text-xl text-teal-500"></i>
                                            </div>
                                            <span class="text-xs text-teal-600 font-semibold" id="file-name-display"></span>
                                            <span class="text-[10px] text-gray-400 mt-1" id="file-size-display"></span>
                                        </div>
                                    </div>
                                </div>
                                <input type="file" name="file" id="file-input"
                                    accept=".pdf,.doc,.docx,.xls,.xlsx"
                                    class="hidden" 
                                    onchange="previewFile(event)"
                                    {{ $isEdit ? '' : 'required' }}>
                                @error('file')
                                    <p class="text-xs text-red-500 mt-2">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Deskripsi --}}
                            <div>
                                <label for="description" class="block text-[11px] font-semibold text-gray-500 mb-1.5">
                                    Deskripsi <span class="text-gray-400 font-normal">(opsional)</span>
                                </label>
                                <textarea name="description" id="description" rows="3"
                                    class="w-full px-4 py-3 rounded-[14px] border border-gray-200/80 text-[13px] focus:outline-none focus:border-teal-400 focus:ring-2 focus:ring-teal-400/15 transition-all resize-none bg-white/60"
                                    placeholder="Tuliskan deskripsi singkat dokumen...">{{ old('description', $document->description ?? '') }}</textarea>
                                @error('description')
                                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Tombol Submit --}}
                    <div class="flex items-center gap-3 pt-2">
                        <button type="submit"
                            class="relative inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-teal-500 to-teal-600 text-white text-[13px] font-semibold rounded-[18px] shadow-[0_4px_16px_-4px_rgba(20,184,166,0.3)] hover:shadow-[0_8px_24px_-4px_rgba(20,184,166,0.45)] hover:scale-105 transition-all duration-300 overflow-hidden group">
                            <div
                                class="absolute inset-0 bg-white/20 rounded-[18px] opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            </div>
                            <span class="relative flex items-center gap-2">
                                <i class="fas fa-{{ $isEdit ? 'save' : 'plus' }} text-[10px]"></i>
                                {{ $isEdit ? 'Simpan Perubahan' : 'Tambah Dokumen' }}
                            </span>
                        </button>
                        <a href="{{ route('admin.documents.index') }}"
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
        function previewFile(event) {
            const file = event.target.files[0];
            if (!file) return;

            const placeholder = document.getElementById('file-placeholder');
            const selected = document.getElementById('file-selected');
            const nameDisplay = document.getElementById('file-name-display');
            const sizeDisplay = document.getElementById('file-size-display');

            placeholder.classList.add('hidden');
            selected.classList.remove('hidden');
            nameDisplay.textContent = file.name;
            sizeDisplay.textContent = (file.size / 1024).toFixed(1) + ' KB';
        }
    </script>
@endpush