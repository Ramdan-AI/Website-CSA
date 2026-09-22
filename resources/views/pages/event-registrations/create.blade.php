@extends('layouts.admin')

@section('content')
<div class="space-y-4 sm:space-y-5 max-w-2xl">
    <div>
        <a href="{{ route('admin.event-registrations.index') }}" class="inline-flex items-center gap-1.5 text-[12px] text-[var(--text-muted)] hover:text-[var(--text-primary)] transition-colors">
            <x-admin.icon name="arrow-left" :size="14" /> Kembali ke Pendaftaran
        </a>
        <h1 class="text-[26px] sm:text-[30px] font-bold text-[var(--text-primary)] tracking-[-0.03em] mt-2">Tambah Registrasi</h1>
        <p class="text-[13px] text-[var(--text-muted)] mt-1">Tambahkan data registrasi anggota ke dalam kegiatan.</p>
    </div>

    <form action="{{ route('admin.event-registrations.store') }}" method="POST" class="csa-card p-5 sm:p-6 space-y-4">
        @csrf

        <div>
            <label for="event_id" class="block text-[13px] font-medium text-[var(--text-secondary)] mb-1.5">Kegiatan</label>
            <select name="event_id" id="event_id" required class="csa-input">
                <option value="">Pilih kegiatan</option>
                @foreach($events as $event)
                    <option value="{{ $event->id }}" @selected(old('event_id') == $event->id)>{{ $event->title }}</option>
                @endforeach
            </select>
            @error('event_id')<p class="text-[12px] text-red-500 mt-1.5">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="member_id" class="block text-[13px] font-medium text-[var(--text-secondary)] mb-1.5">Anggota</label>
            <select name="member_id" id="member_id" required class="csa-input">
                <option value="">Pilih anggota</option>
                @foreach($members as $member)
                    <option value="{{ $member->id }}" @selected(old('member_id') == $member->id)>{{ $member->name }}</option>
                @endforeach
            </select>
            @error('member_id')<p class="text-[12px] text-red-500 mt-1.5">{{ $message }}</p>@enderror
        </div>

        <div class="flex items-center gap-3 pt-2 border-t border-[var(--border-soft)]">
            <button type="submit" class="csa-btn csa-btn-primary">
                <x-admin.icon name="plus" :size="16" /> Tambah Registrasi
            </button>
            <a href="{{ route('admin.event-registrations.index') }}" class="csa-btn csa-btn-ghost">Batal</a>
        </div>
    </form>
</div>
@endsection
