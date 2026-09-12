@extends('layouts.admin')

@section('content')
    @php
        $isEdit = isset($attendance) && $attendance->exists;
        $title = $isEdit ? 'Edit Absensi' : 'Tambah Absensi';
        $subtitle = $isEdit 
            ? 'Perbarui data absensi member yang telah terdaftar.' 
            : 'Tambahkan data absensi member ke dalam event.';
    @endphp

    <div class="space-y-6">
        <div>
            <p class="text-xs font-semibold text-blue-400 uppercase tracking-widest">Keanggotaan</p>
            <h1 class="text-3xl font-bold text-gray-900 mt-1">{{ $title }}</h1>
            <p class="text-sm text-gray-400 mt-1.5 font-medium">{{ $subtitle }}</p>
        </div>

        <div
            class="relative bg-white/70 backdrop-blur-xl rounded-[30px] border border-white/50 shadow-[0_2px_16px_-4px_rgba(59,130,246,0.08)] overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-blue-400/5 to-cyan-400/5 pointer-events-none"></div>

            <div class="relative p-6">
                <form
                    action="{{ $isEdit ? route('admin.attendances.update', $attendance) : route('admin.attendances.store') }}"
                    method="POST" class="space-y-6">
                    @csrf
                    @if ($isEdit)
                        @method('PUT')
                    @endif

                    <div class="bg-gray-50/40 rounded-[24px] p-5">
                        <h3 class="text-[13px] font-semibold text-gray-900 mb-4 flex items-center gap-2">
                            <span class="w-1.5 h-5 rounded-full bg-blue-500"></span>
                            Data Absensi
                        </h3>

                        <div class="space-y-4">
                            {{-- Event --}}
                            <div>
                                <label for="event_id" class="block text-[11px] font-semibold text-gray-500 mb-1.5">
                                    Event <span class="text-red-400">*</span>
                                </label>
                                <select name="event_id" id="event_id" required
                                    class="w-full h-[44px] px-4 rounded-[14px] border border-gray-200/80 text-[13px] focus:outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-400/15 transition-all bg-white/60">
                                    <option value="">Pilih Event</option>
                                    @foreach($events as $event)
                                        <option value="{{ $event->id }}"
                                            {{ old('event_id', $attendance->event_id ?? '') == $event->id ? 'selected' : '' }}>
                                            {{ $event->title }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('event_id')
                                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Member --}}
                            <div>
                                <label for="member_id" class="block text-[11px] font-semibold text-gray-500 mb-1.5">
                                    Member <span class="text-red-400">*</span>
                                </label>
                                <select name="member_id" id="member_id" required
                                    class="w-full h-[44px] px-4 rounded-[14px] border border-gray-200/80 text-[13px] focus:outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-400/15 transition-all bg-white/60">
                                    <option value="">Pilih Member</option>
                                    @foreach($members as $member)
                                        <option value="{{ $member->id }}"
                                            {{ old('member_id', $attendance->member_id ?? '') == $member->id ? 'selected' : '' }}>
                                            {{ $member->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('member_id')
                                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Status --}}
                            <div>
                                <label for="status" class="block text-[11px] font-semibold text-gray-500 mb-1.5">
                                    Status <span class="text-red-400">*</span>
                                </label>
                                <select name="status" id="status" required
                                    class="w-full h-[44px] px-4 rounded-[14px] border border-gray-200/80 text-[13px] focus:outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-400/15 transition-all bg-white/60">
                                    <option value="present"
                                        {{ old('status', $attendance->status ?? '') == 'present' ? 'selected' : '' }}>
                                        Hadir</option>
                                    <option value="absent"
                                        {{ old('status', $attendance->status ?? '') == 'absent' ? 'selected' : '' }}>
                                        Absen</option>
                                </select>
                                @error('status')
                                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Tombol Submit --}}
                    <div class="flex items-center gap-3 pt-2">
                        <button type="submit"
                            class="relative inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-blue-500 to-blue-600 text-white text-[13px] font-semibold rounded-[18px] shadow-[0_4px_16px_-4px_rgba(59,130,246,0.3)] hover:shadow-[0_8px_24px_-4px_rgba(59,130,246,0.45)] hover:scale-105 transition-all duration-300 overflow-hidden group">
                            <div
                                class="absolute inset-0 bg-white/20 rounded-[18px] opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            </div>
                            <span class="relative flex items-center gap-2">
                                <i class="fas fa-{{ $isEdit ? 'save' : 'plus' }} text-[10px]"></i>
                                {{ $isEdit ? 'Simpan Perubahan' : 'Tambah Absensi' }}
                            </span>
                        </button>
                        <a href="{{ route('admin.attendances.index') }}"
                            class="px-5 py-2.5 bg-gray-100 text-gray-700 text-[13px] font-semibold rounded-[18px] hover:bg-gray-200 transition-colors">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection