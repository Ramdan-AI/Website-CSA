@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h1 class="text-[26px] sm:text-[30px] font-bold text-[var(--text-primary)] tracking-[-0.03em]">Pengumuman</h1>
            <p class="text-[13px] text-[var(--text-muted)] mt-1">Informasi yang tampil di dashboard seluruh anggota.</p>
        </div>
        <a href="{{ route('admin.announcements.create') }}" class="csa-btn csa-btn-primary self-start sm:self-auto">
            <x-admin.icon name="plus" :size="16" /> Buat Pengumuman
        </a>
    </div>

    @if (session('success'))
        <div class="csa-panel p-3.5 text-[13px] text-emerald-600 dark:text-emerald-400">{{ session('success') }}</div>
    @endif

    <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
        <x-admin.search-bar name="search" :value="request('search')" placeholder="Cari judul atau isi pengumuman..." class="flex-1" />
        <form method="GET" class="sm:w-48">
            @if (request('search'))
                <input type="hidden" name="search" value="{{ request('search') }}">
            @endif
            <x-form.select name="category" :value="$category" onchange="this.form.submit()"
                placeholder="Semua kategori"
                :options="['info' => 'Informasi', 'penting' => 'Penting', 'urgent' => 'Penting Sekali']" />
        </form>
    </div>

    @if ($announcements->isEmpty())
        <div class="csa-panel p-16 text-center">
            <div class="w-14 h-14 rounded-2xl bg-[var(--bg-muted)] flex items-center justify-center mx-auto text-[var(--text-muted)]">
                <x-admin.icon name="megaphone" :size="26" />
            </div>
            <p class="text-[15px] font-semibold text-[var(--text-secondary)] mt-4">Belum ada pengumuman</p>
            <p class="text-[13px] text-[var(--text-muted)] mt-1">Buat pengumuman pertama untuk ditampilkan di dashboard anggota.</p>
            <a href="{{ route('admin.announcements.create') }}" class="csa-btn csa-btn-primary mt-5 inline-flex">
                <x-admin.icon name="plus" :size="16" /> Buat Pengumuman
            </a>
        </div>
    @else
        <div class="space-y-3">
            @foreach ($announcements as $announcement)
                <x-admin.announcement-card :announcement="$announcement" :actions="true" />
            @endforeach
        </div>

        @if ($announcements->hasPages())
            <div>{{ $announcements->links() }}</div>
        @endif
    @endif
</div>
@endsection
