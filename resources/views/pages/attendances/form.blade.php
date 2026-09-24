@extends('layouts.admin')

@section('content')
<div class="space-y-6 max-w-2xl">
    <div>
        <a href="{{ route('admin.attendances.index') }}" class="inline-flex items-center gap-1.5 text-[12px] text-[var(--text-muted)] hover:text-[var(--text-primary)] transition-colors">
            <x-admin.icon name="arrow-left" :size="14" /> Kembali
        </a>
        <h1 class="text-[26px] sm:text-[30px] font-bold text-[var(--text-primary)] tracking-[-0.03em] mt-2">Tambah Absensi</h1>
        <p class="text-[13px] text-[var(--text-muted)] mt-1">Tambahkan data absensi anggota ke dalam kegiatan.</p>
    </div>

    <form action="{{ route('admin.attendances.store') }}" method="POST" class="csa-panel p-5 sm:p-6 space-y-4">
        @csrf
        <x-form.select name="event_id" id="event_id" label="Kegiatan" required
            :value="old('event_id')" placeholder="Pilih kegiatan"
            :options="collect($events)->mapWithKeys(fn ($e) => [$e->id => $e->title])->all()" />
        <x-form.select name="member_id" id="member_id" label="Anggota" required
            :value="old('member_id')" placeholder="Pilih anggota"
            :options="collect($members)->mapWithKeys(fn ($m) => [$m->id => $m->name])->all()" />
        <x-form.select name="status" id="status" label="Status" required
            :value="old('status', 'present')"
            :options="['present' => 'Hadir', 'permit' => 'Izin', 'sick' => 'Sakit', 'absent' => 'Alfa']" />

        <div class="flex items-center gap-3 pt-2 border-t border-[var(--border-soft)]">
            <button type="submit" class="csa-btn csa-btn-primary">
                <x-admin.icon name="plus" :size="16" /> Tambah Absensi
            </button>
            <a href="{{ route('admin.attendances.index') }}" class="csa-btn csa-btn-ghost">Batal</a>
        </div>
    </form>
</div>
@endsection
