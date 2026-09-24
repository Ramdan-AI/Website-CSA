@extends('layouts.admin')

@php
    $isEdit = $announcement !== null;
    $title = $isEdit ? 'Edit Pengumuman' : 'Buat Pengumuman';
    $categoryLabels = ['info' => 'Informasi', 'penting' => 'Penting', 'urgent' => 'Penting Sekali'];
    $categoryChips = ['info' => 'csa-chip-blue', 'penting' => 'csa-chip-amber', 'urgent' => 'csa-chip-red'];
@endphp

@section('content')
<div class="space-y-6" x-data="{
        title: @js(old('title', $announcement->title ?? '')),
        body: @js(old('body', $announcement->body ?? '')),
        category: @js(old('category', $announcement->category ?? 'info')),
        pinned: {{ old('pinned', $announcement->pinned ?? false) ? 'true' : 'false' }},
    }">
    <div>
        <a href="{{ route('admin.announcements.index') }}" class="inline-flex items-center gap-1.5 text-[12px] text-[var(--text-muted)] hover:text-[var(--text-primary)] transition-colors">
            <x-admin.icon name="arrow-left" :size="14" /> Kembali ke Pengumuman
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

    <div class="grid grid-cols-1 lg:grid-cols-[1.5fr_1fr] gap-6 items-start">
        <form action="{{ $isEdit ? route('admin.announcements.update', $announcement) : route('admin.announcements.store') }}"
            method="POST" class="csa-panel p-5 sm:p-6 space-y-5">
            @csrf
            @if ($isEdit) @method('PUT') @endif

            <x-form.input name="title" id="title" label="Judul" :value="old('title', $announcement->title ?? '')"
                placeholder="Contoh: Study club diliburkan" required autofocus x-model="title" />

            <x-form.textarea name="body" id="body" label="Isi Pengumuman" :value="old('body', $announcement->body ?? '')"
                placeholder="Tuliskan detail pengumuman." rows="6" required x-model="body" />

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <x-form.select name="category" id="category" label="Kategori" required
                    :value="old('category', $announcement->category ?? 'info')"
                    :options="$categoryLabels"
                    hint="Kategori memengaruhi warna label di dashboard." x-model="category" />

                <x-form.select name="status" id="status" label="Status" required
                    :value="old('status', $announcement->status ?? 'published')"
                    :options="['published' => 'Terbitkan', 'draft' => 'Simpan sebagai draf']" />
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <x-form.input name="starts_at" id="starts_at" type="date" label="Mulai Tampil"
                    :value="old('starts_at', $isEdit && $announcement->starts_at ? $announcement->starts_at->format('Y-m-d') : '')"
                    hint="Kosongkan bila langsung tampil." />
                <x-form.input name="ends_at" id="ends_at" type="date" label="Akhir Tampil"
                    :value="old('ends_at', $isEdit && $announcement->ends_at ? $announcement->ends_at->format('Y-m-d') : '')"
                    hint="Kosongkan bila tampil terus." />
            </div>

            <label class="flex items-center gap-3 cursor-pointer">
                <input type="hidden" name="pinned" value="0">
                <input type="checkbox" name="pinned" value="1" class="peer sr-only" x-model="pinned"
                    @checked(old('pinned', $announcement->pinned ?? false))>
                <span class="relative w-10 h-6 rounded-full bg-[var(--border-strong)] after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:w-5 after:h-5 after:rounded-full after:bg-white after:transition-all peer-checked:bg-[var(--accent)] peer-checked:after:translate-x-4"></span>
                <span>
                    <span class="block text-[13px] font-medium text-[var(--text-primary)]">Sematkan di paling atas</span>
                    <span class="block text-[12px] text-[var(--text-muted)] mt-0.5">Cocok untuk informasi yang wajib dibaca.</span>
                </span>
            </label>

            <div class="flex items-center gap-3 pt-2 border-t border-[var(--border-soft)]">
                <button type="submit" class="csa-btn csa-btn-primary">
                    <x-admin.icon :name="$isEdit ? 'save' : 'plus'" :size="16" />
                    {{ $isEdit ? 'Simpan Perubahan' : 'Terbitkan' }}
                </button>
                <a href="{{ route('admin.announcements.index') }}" class="csa-btn csa-btn-ghost">Batal</a>
            </div>
        </form>

        {{-- Pratinjau langsung seperti tampil di dashboard anggota --}}
        <aside class="lg:sticky lg:top-20 space-y-3">
            <p class="text-[11px] font-semibold uppercase tracking-[0.08em] text-[var(--text-muted)]">Pratinjau di dashboard</p>
            <article class="csa-panel p-5" :class="pinned && 'border-l-2 border-[var(--accent)]'">
                <div class="flex flex-wrap items-center gap-x-2.5 gap-y-1">
                    <span class="text-[13px] font-semibold text-[var(--text-primary)]">{{ auth()->user()->name }}</span>
                    <span class="text-[12px] text-[var(--text-muted)]">· baru saja</span>
                    <span class="csa-chip ml-auto sm:ml-2"
                        :class="{
                            'csa-chip-blue': category === 'info',
                            'csa-chip-amber': category === 'penting',
                            'csa-chip-red': category === 'urgent',
                        }"
                        x-text="({ info: 'Informasi', penting: 'Penting', urgent: 'Penting Sekali' })[category] || 'Informasi'"></span>
                    <template x-if="pinned">
                        <span class="csa-chip csa-chip-violet">Disematkan</span>
                    </template>
                </div>

                <h2 class="text-[15px] font-semibold text-[var(--text-primary)] mt-2 leading-snug"
                    x-text="title || 'Judul pengumuman'"></h2>
                <p class="text-[13px] text-[var(--text-secondary)] mt-1.5 leading-[1.7] whitespace-pre-line"
                    :class="!body && 'text-[var(--text-muted)] italic'"
                    x-text="body || 'Isi pengumuman akan tampil di sini.'"></p>
            </article>
            <p class="text-[12px] text-[var(--text-muted)]">Pratinjau memperbarui secara langsung saat Anda mengetik.</p>
        </aside>
    </div>
</div>
@endsection
