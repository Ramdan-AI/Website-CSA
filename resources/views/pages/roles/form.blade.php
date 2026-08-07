@extends('layouts.admin')

@section('content')
    @php
        $isEdit = isset($role) && $role->exists;
        $title = $isEdit ? 'Edit Role' : 'Tambah Role';
    @endphp

    <div class="space-y-8">
        <div>
            <p class="text-xs font-semibold text-blue-500 uppercase tracking-wider">Sistem</p>
            <h1 class="text-2xl font-bold text-gray-900 mt-1">{{ $title }}</h1>
            <p class="text-sm text-gray-500 mt-1">
                {{ $isEdit ? 'Perbarui informasi role.' : 'Tambahkan role baru ke dalam sistem.' }}
            </p>
        </div>

        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm">
            <form action="{{ $isEdit ? route('admin.roles.update', $role) : route('admin.roles.store') }}"
                method="POST" class="p-6 space-y-6">
                @csrf
                @if ($isEdit)
                    @method('PUT')
                @endif

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="name" class="block text-xs font-semibold text-gray-500 mb-2">
                            Nama Role <span class="text-red-400">*</span>
                        </label>
                        <input type="text" name="name" id="name"
                            value="{{ old('name', $role->name ?? '') }}"
                            class="w-full h-[46px] px-4 rounded-xl border border-gray-200 text-sm focus:outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100 transition-all"
                            placeholder="Contoh: Ketua Divisi" required autofocus>
                        @error('name')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="slug" class="block text-xs font-semibold text-gray-500 mb-2">
                            Slug <span class="text-red-400">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-xs text-gray-400 font-mono">/</span>
                            <input type="text" name="slug" id="slug"
                                value="{{ old('slug', $role->slug ?? '') }}"
                                class="w-full h-[46px] pl-8 pr-4 rounded-xl border border-gray-200 text-sm focus:outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100 transition-all font-mono"
                                placeholder="ketua-divisi" required>
                        </div>
                        @error('slug')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="description" class="block text-xs font-semibold text-gray-500 mb-2">
                        Deskripsi <span class="text-gray-400 font-normal">(opsional)</span>
                    </label>
                    <textarea name="description" id="description" rows="3"
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100 transition-all resize-none"
                        placeholder="Jelaskan peran ini...">{{ old('description', $role->description ?? '') }}</textarea>
                    @error('description')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button type="submit"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 text-white text-sm font-semibold rounded-xl hover:bg-blue-700 transition-colors shadow-sm">
                        <i class="fas fa-{{ $isEdit ? 'save' : 'plus' }} text-xs"></i>
                        {{ $isEdit ? 'Simpan Perubahan' : 'Tambah Role' }}
                    </button>
                    <a href="{{ route('admin.roles.index') }}"
                        class="px-5 py-2.5 bg-gray-100 text-gray-700 text-sm font-semibold rounded-xl hover:bg-gray-200 transition-colors">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    const nameInput = document.getElementById('name');
    const slugInput = document.getElementById('slug');

    nameInput.addEventListener('input', function() {
        if (slugInput.dataset.modified !== 'true') {
            slugInput.value = this.value
                .toLowerCase()
                .replace(/[^a-z0-9]+/g, '-')
                .replace(/^-+|-+$/g, '');
        }
    });

    slugInput.addEventListener('input', function() {
        this.dataset.modified = this.value !== '' ? 'true' : 'false';
    });
</script>
@endpush
