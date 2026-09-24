@extends('layouts.admin')

@php
    $isEdit = $letter !== null;
    $title = $isEdit ? 'Edit Surat' : 'Catat Surat';
@endphp

@section('content')
<div class="space-y-4 sm:space-y-5 max-w-3xl">
    <div>
        <a href="{{ route('admin.letters.index') }}" class="inline-flex items-center gap-1.5 text-[12px] text-[var(--text-muted)] hover:text-[var(--text-primary)] transition-colors">
            <x-admin.icon name="arrow-left" :size="14" /> Kembali ke Surat & Arsip
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

    <form action="{{ $isEdit ? route('admin.letters.update', $letter) : route('admin.letters.store') }}"
        method="POST" enctype="multipart/form-data" class="csa-panel p-5 sm:p-6 space-y-4">
        @csrf
        @if ($isEdit) @method('PUT') @endif

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <x-form.select name="type" id="type" label="Jenis Surat" required
                :value="old('type', $letter->type ?? 'incoming')"
                :options="['incoming' => 'Surat Masuk', 'outgoing' => 'Surat Keluar']" />

            <x-form.date name="letter_date" id="letter_date" label="Tanggal Surat"
                :value="old('letter_date', $isEdit ? $letter->letter_date->format('Y-m-d') : now()->format('Y-m-d'))" required />
        </div>

        <x-form.input name="reference_number" id="reference_number" label="Nomor Surat"
            :value="old('reference_number', $letter->reference_number ?? '')"
            placeholder="Kosongkan untuk penomoran otomatis"
            hint="Format otomatis: 001/SK/CSA/IX/2026" />

        <x-form.input name="subject" id="subject" label="Perihal" :value="old('subject', $letter->subject ?? '')"
            placeholder="Contoh: Permohonan Izin Kegiatan" required />

        <x-form.input name="correspondent" id="correspondent" label="Pengirim / Tujuan"
            :value="old('correspondent', $letter->correspondent ?? '')"
            placeholder="Contoh: BEM STMIK Mardira Indonesia" />

        <x-form.textarea name="description" id="description" label="Keterangan" :value="old('description', $letter->description ?? '')"
            placeholder="Ringkasan isi surat atau catatan tambahan." rows="4" />

        <div>
            <label class="block text-[13px] font-medium text-[var(--text-secondary)] mb-1.5">Lampiran</label>
            <input type="file" name="attachment" accept=".pdf,.jpg,.jpeg,.png,.webp,.doc,.docx" class="csa-file">
            <p class="text-[11px] text-[var(--text-muted)] mt-1.5">PDF, gambar, atau dokumen. Maksimal 5MB.</p>
            @error('attachment')<p class="text-[12px] text-red-500 mt-1.5">{{ $message }}</p>@enderror

            @if ($isEdit && $letter->attachment_url)
                <a href="{{ $letter->attachment_url }}" target="_blank" rel="noopener" class="inline-flex items-center gap-2 mt-2.5 text-[13px] font-medium hover:underline" style="color: var(--accent);">
                    <x-admin.icon name="download" :size="14" /> Lihat lampiran saat ini
                </a>
            @endif
        </div>

        <div class="flex items-center gap-3 pt-2 border-t border-[var(--border-soft)]">
            <button type="submit" class="csa-btn csa-btn-primary">
                <x-admin.icon :name="$isEdit ? 'save' : 'plus'" :size="16" />
                {{ $isEdit ? 'Simpan Perubahan' : 'Catat Surat' }}
            </button>
            <a href="{{ route('admin.letters.index') }}" class="csa-btn csa-btn-ghost">Batal</a>
        </div>
    </form>
</div>
@endsection
