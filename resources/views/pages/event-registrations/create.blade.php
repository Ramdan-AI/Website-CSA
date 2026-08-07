@extends('layouts.admin')

@section('content')
<div class="space-y-6">

    <div>
        <p class="text-xs font-semibold text-cyan-400 uppercase tracking-widest">Kegiatan</p>
        <h1 class="text-3xl font-bold text-gray-900 mt-1">Tambah Registrasi</h1>
        <p class="text-sm text-gray-400 mt-1.5 font-medium">Tambahkan data registrasi member ke dalam event.</p>
    </div>

    <div class="relative bg-white/70 backdrop-blur-xl rounded-[30px] border border-white/50 shadow-[0_2px_16px_-4px_rgba(6,182,212,0.08)] overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-cyan-400/5 to-blue-400/5 pointer-events-none"></div>

        <div class="relative p-6">
            <form action="{{ route('admin.event-registrations.store') }}" method="POST" class="space-y-6">
                @csrf

                <div class="bg-gray-50/40 rounded-[24px] p-5">
                    <h3 class="text-[13px] font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <span class="w-1.5 h-5 rounded-full bg-cyan-500"></span>
                        Data Registrasi
                    </h3>

                    <div class="space-y-4">
                        <div>
                            <label for="event_id" class="block text-[11px] font-semibold text-gray-500 mb-1.5">
                                Event <span class="text-red-400">*</span>
                            </label>
                            <select name="event_id" id="event_id" required
                                    class="w-full h-[44px] px-4 rounded-[14px] border border-gray-200/80 text-[13px] focus:outline-none focus:border-cyan-400 focus:ring-2 focus:ring-cyan-400/15 transition-all bg-white/60">
                                <option value="">Pilih Event</option>
                                @foreach($events as $event)
                                    <option value="{{ $event->id }}" {{ old('event_id') == $event->id ? 'selected' : '' }}>
                                        {{ $event->title }}
                                    </option>
                                @endforeach
                            </select>
                            @error('event_id')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="member_id" class="block text-[11px] font-semibold text-gray-500 mb-1.5">
                                Member <span class="text-red-400">*</span>
                            </label>
                            <select name="member_id" id="member_id" required
                                    class="w-full h-[44px] px-4 rounded-[14px] border border-gray-200/80 text-[13px] focus:outline-none focus:border-cyan-400 focus:ring-2 focus:ring-cyan-400/15 transition-all bg-white/60">
                                <option value="">Pilih Member</option>
                                @foreach($members as $member)
                                    <option value="{{ $member->id }}" {{ old('member_id') == $member->id ? 'selected' : '' }}>
                                        {{ $member->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('member_id')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button type="submit"
                            class="relative inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-cyan-500 to-cyan-600 text-white text-[13px] font-semibold rounded-[18px] shadow-[0_4px_16px_-4px_rgba(6,182,212,0.3)] hover:shadow-[0_8px_24px_-4px_rgba(6,182,212,0.45)] hover:scale-105 transition-all duration-300 overflow-hidden group">
                        <div class="absolute inset-0 bg-white/20 rounded-[18px] opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        <span class="relative flex items-center gap-2">
                            <i class="fas fa-plus text-[10px]"></i>
                            Tambah Registrasi
                        </span>
                    </button>
                    <a href="{{ route('admin.event-registrations.index') }}"
                       class="px-5 py-2.5 bg-gray-100 text-gray-700 text-[13px] font-semibold rounded-[18px] hover:bg-gray-200 transition-colors">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
