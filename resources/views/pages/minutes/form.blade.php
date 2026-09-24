@extends('layouts.admin')

@php
    $isEdit = $minute !== null;
    $title = $isEdit ? 'Edit Notulen' : 'Tambah Notulen';
    $memberPayload = $members->map(fn ($m) => [
        'id' => $m->id,
        'name' => $m->name,
        'nim' => $m->nim ?? '',
        'division' => $m->division?->name ?? 'Tanpa divisi',
    ])->values();
@endphp

@section('content')
<div class="space-y-4 sm:space-y-5">
    <div>
        <a href="{{ route('admin.minutes.index') }}" class="inline-flex items-center gap-1.5 text-[12px] text-[var(--text-muted)] hover:text-[var(--text-primary)] transition-colors">
            <x-admin.icon name="arrow-left" :size="14" /> Kembali ke Notulen
        </a>
        <h1 class="text-[26px] sm:text-[30px] font-bold text-[var(--text-primary)] tracking-[-0.03em] mt-2">{{ $title }}</h1>
    </div>

    @if ($errors->any())
        <div class="csa-panel p-4 border-l-2 border-red-500 space-y-1.5">
            <p class="text-[13px] font-medium text-red-500">Periksa kembali data berikut:</p>
            <ul class="space-y-1">
                @foreach ($errors->all() as $message)
                    <li class="text-[12px] text-red-500">· {{ $message }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ $isEdit ? route('admin.minutes.update', $minute) : route('admin.minutes.store') }}"
        method="POST" enctype="multipart/form-data" class="space-y-4">
        @csrf
        @if ($isEdit) @method('PUT') @endif

        <div class="csa-panel p-5 sm:p-6 space-y-4">
            <h2 class="text-[14px] font-semibold text-[var(--text-primary)]">Informasi Rapat</h2>

            <x-form.input name="title" id="title" label="Judul Rapat" :value="old('title', $minute->title ?? '')"
                placeholder="Contoh: Rapat Koordinasi Bulanan" required autofocus />

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <x-form.date name="meeting_date" id="meeting_date" label="Tanggal Rapat"
                    :value="old('meeting_date', $isEdit ? $minute->meeting_date->format('Y-m-d') : now()->format('Y-m-d'))" required />
                <x-form.input name="location" id="location" label="Tempat" :value="old('location', $minute->location ?? '')"
                    placeholder="Contoh: Ruang Rapat / Google Meet" />
            </div>

            <x-form.textarea name="agenda" id="agenda" label="Agenda" :value="old('agenda', $minute->agenda ?? '')"
                placeholder="Poin-poin yang dibahas dalam rapat." rows="4" />

            <x-form.textarea name="decisions" id="decisions" label="Keputusan" :value="old('decisions', $minute->decisions ?? '')"
                placeholder="Hasil dan keputusan rapat." rows="6" />
        </div>

        <div class="csa-panel p-5 sm:p-6 space-y-4"
            x-data="{
                query: '',
                members: @js($memberPayload),
                selected: @js(array_map('intval', $selectedMembers)),
                get filtered() {
                    const term = this.query.trim().toLowerCase();
                    if (!term) return this.members;
                    return this.members.filter((m) => (m.name + ' ' + m.nim).toLowerCase().includes(term));
                },
                toggle(id) {
                    this.selected = this.selected.includes(id)
                        ? this.selected.filter((item) => item !== id)
                        : [...this.selected, id];
                }
            }">
            <h2 class="text-[14px] font-semibold text-[var(--text-primary)]">Peserta Rapat</h2>

            <div class="relative">
                <x-admin.icon name="search" :size="16" class="absolute left-3.5 top-1/2 -translate-y-1/2 text-[var(--text-muted)] pointer-events-none" />
                <input type="search" x-model="query" placeholder="Cari nama atau NIM anggota..." class="csa-input !pl-[42px]" aria-label="Cari peserta">
            </div>

            <div class="max-h-72 overflow-y-auto rounded-xl border border-[var(--border-soft)] divide-y divide-[var(--border-soft)]">
                <template x-for="member in filtered" :key="member.id">
                    <label class="flex items-center gap-3 p-3 hover:bg-[var(--bg-hover)] cursor-pointer min-h-[52px]">
                        <input type="checkbox" name="member_ids[]" :value="member.id"
                            class="w-4 h-4 rounded border-[var(--border-strong)] accent-[var(--accent)]"
                            :checked="selected.includes(member.id)"
                            @change="toggle(member.id)">
                        <span class="min-w-0">
                            <span class="block text-[13px] font-medium text-[var(--text-primary)] truncate" x-text="member.name"></span>
                            <span class="block text-[12px] text-[var(--text-muted)]" x-text="(member.nim || 'Tanpa NIM') + ' · ' + member.division"></span>
                        </span>
                    </label>
                </template>

                <p class="p-6 text-center text-[13px] text-[var(--text-muted)]" x-show="filtered.length === 0">
                    Anggota tidak ditemukan.
                </p>
            </div>

            <p class="text-[12px] text-[var(--text-muted)]"><span x-text="selected.length"></span> peserta dipilih.</p>
        </div>

        <div class="csa-panel p-5 sm:p-6 space-y-4">
            <h2 class="text-[14px] font-semibold text-[var(--text-primary)]">Lampiran</h2>
            <input type="file" name="attachment" accept=".pdf,.jpg,.jpeg,.png,.webp,.doc,.docx" class="csa-file">
            <p class="text-[11px] text-[var(--text-muted)]">Opsional. Maksimal 5MB.</p>
            @error('attachment')<p class="text-[12px] text-red-500 mt-1.5">{{ $message }}</p>@enderror

            @if ($isEdit && $minute->attachment_url)
                <a href="{{ $minute->attachment_url }}" target="_blank" rel="noopener" class="inline-flex items-center gap-2 text-[13px] font-medium hover:underline" style="color: var(--accent);">
                    <x-admin.icon name="download" :size="14" /> Lihat lampiran saat ini
                </a>
            @endif
        </div>

        <div class="flex items-center gap-3">
            <button type="submit" class="csa-btn csa-btn-primary">
                <x-admin.icon :name="$isEdit ? 'save' : 'plus'" :size="16" />
                {{ $isEdit ? 'Simpan Perubahan' : 'Tambah Notulen' }}
            </button>
            <a href="{{ route('admin.minutes.index') }}" class="csa-btn csa-btn-ghost">Batal</a>
        </div>
    </form>
</div>
@endsection
