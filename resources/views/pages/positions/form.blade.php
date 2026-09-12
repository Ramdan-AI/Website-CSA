{{-- resources/views/pages/admin/positions/form.blade.php --}}
@extends('layouts.admin')

@section('content')
    @php
        $isEdit = isset($position);
        $title = $isEdit ? 'Edit Jabatan' : 'Tambah Jabatan';
    @endphp

    <div class="space-y-6">

        <div>
            <p class="text-xs font-semibold text-blue-400 uppercase tracking-widest">Data Master</p>
            <h1 class="text-3xl font-bold text-gray-900 mt-1">{{ $title }}</h1>
            <p class="text-sm text-gray-400 mt-1.5 font-medium">
                {{ $isEdit ? 'Perbarui informasi jabatan.' : 'Tambahkan jabatan baru ke dalam sistem.' }}
            </p>
        </div>

        <div class="relative bg-white/70 backdrop-blur-xl rounded-[30px] border border-white/50 shadow-[0_2px_16px_-4px_rgba(59,130,246,0.08)] overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-blue-400/5 to-cyan-400/5 pointer-events-none"></div>

            <div class="relative p-6">
                <form action="{{ $isEdit ? route('admin.positions.update', $position) : route('admin.positions.store') }}"
                    method="POST" class="space-y-6">
                    @csrf
                    @if ($isEdit)
                        @method('PUT')
                    @endif

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="name" class="block text-xs font-semibold text-gray-500 mb-2">
                                Nama Jabatan <span class="text-red-400">*</span>
                            </label>
                            <input type="text" name="name" id="name" value="{{ old('name', $position->name ?? '') }}"
                                class="w-full h-[46px] px-4 rounded-[16px] border border-gray-200/80 text-[13px] focus:outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-400/20 transition-all bg-white/60"
                                placeholder="Contoh: Sekretaris" required autofocus>
                            @error('name')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="max_members" class="block text-xs font-semibold text-gray-500 mb-2">
                                Maksimal Anggota <span class="text-red-400">*</span>
                            </label>
                            <input type="number" name="max_members" id="max_members" 
                                value="{{ old('max_members', $position->max_members ?? 1) }}"
                                min="1"
                                class="w-full h-[46px] px-4 rounded-[16px] border border-gray-200/80 text-[13px] focus:outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-400/20 transition-all bg-white/60"
                                required>
                            @error('max_members')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div id="max-info" class="bg-gray-50/60 rounded-[20px] p-4">
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-xl bg-white flex items-center justify-center shrink-0 shadow-[0_2px_6px_-2px_rgba(0,0,0,0.06)]">
                                <i id="max-icon" class="fas fa-lock text-blue-500 text-xs"></i>
                            </div>
                            <div>
                                <p id="max-title" class="text-[13px] font-semibold text-gray-900">Jabatan Tunggal</p>
                                <p id="max-desc" class="text-[11px] text-gray-400 mt-0.5 leading-relaxed">
                                    Hanya 1 orang yang dapat menduduki jabatan ini.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label for="description" class="block text-xs font-semibold text-gray-500 mb-2">
                            Deskripsi <span class="text-gray-400 font-normal">(opsional)</span>
                        </label>
                        <textarea name="description" id="description" rows="3"
                            class="w-full px-4 py-3 rounded-[16px] border border-gray-200/80 text-[13px] focus:outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-400/20 transition-all resize-none bg-white/60"
                            placeholder="Jelaskan tugas dan tanggung jawab jabatan ini...">{{ old('description', $position->description ?? '') }}</textarea>
                        @error('description')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center gap-3 pt-2">
                        <button type="submit"
                            class="relative inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-blue-500 to-blue-600 text-white text-[13px] font-semibold rounded-[18px] shadow-[0_4px_16px_-4px_rgba(59,130,246,0.3)] hover:shadow-[0_8px_24px_-4px_rgba(59,130,246,0.45)] hover:scale-105 transition-all duration-300 overflow-hidden group">
                            <div class="absolute inset-0 bg-white/20 rounded-[18px] opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            <span class="relative flex items-center gap-2">
                                <i class="fas fa-{{ $isEdit ? 'save' : 'plus' }} text-[10px]"></i>
                                {{ $isEdit ? 'Simpan Perubahan' : 'Tambah Jabatan' }}
                            </span>
                        </button>
                        <a href="{{ route('admin.positions.index') }}"
                            class="px-5 py-2.5 bg-gray-100 text-gray-700 text-[13px] font-semibold rounded-[18px] hover:bg-gray-200 transition-colors">
                            Batal
                        </a>

                        @if ($isEdit)
                            <button type="button"
                                onclick="openDeleteModal('deleteModal', '{{ route('admin.positions.destroy', $position) }}')"
                                class="px-5 py-2.5 bg-red-50 text-red-500 text-[13px] font-semibold rounded-[18px] hover:bg-red-100 transition-colors ml-auto">
                                <i class="fas fa-trash text-[10px] mr-1.5"></i>
                                Hapus
                            </button>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
const maxInput = document.getElementById('max_members');
const maxIcon = document.getElementById('max-icon');
const maxTitle = document.getElementById('max-title');
const maxDesc = document.getElementById('max-desc');

function updateMaxUI() {
    const value = parseInt(maxInput.value) || 1;
    
    if (value === 1) {
        maxIcon.className = 'fas fa-lock text-blue-500 text-xs';
        maxTitle.textContent = 'Jabatan Tunggal';
        maxDesc.textContent = 'Hanya 1 orang yang dapat menduduki jabatan ini.';
    } else {
        maxIcon.className = 'fas fa-users text-emerald-500 text-xs';
        maxTitle.textContent = 'Jabatan Kolektif';
        maxDesc.textContent = 'Maksimal ' + value + ' orang yang dapat menduduki jabatan ini secara bersamaan.';
    }
}

maxInput.addEventListener('input', updateMaxUI);
updateMaxUI();
</script>
@endpush