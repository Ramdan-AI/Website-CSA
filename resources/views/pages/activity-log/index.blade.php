@extends('layouts.admin')

@section('content')
<div class="space-y-4 sm:space-y-5">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h1 class="text-[26px] sm:text-[30px] font-bold text-[var(--text-primary)] tracking-[-0.03em]">Log Sistem</h1>
            <p class="text-[13px] text-[var(--text-muted)] mt-1">Riwayat aktivitas pengguna pada data penting.</p>
        </div>
        <form method="POST" action="{{ route('admin.activity-log.purge') }}" onsubmit="return confirm('Hapus catatan lebih tua dari 14 hari?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="csa-btn csa-btn-ghost">
                <x-admin.icon name="trash-2" :size="15" /> Bersihkan catatan lama
            </button>
        </form>
    </div>

    @if (session('success'))
        <div class="csa-card p-3.5 text-[13px] text-emerald-600 dark:text-emerald-400">{{ session('success') }}</div>
    @endif

    <form method="GET" class="csa-card p-3 sm:p-4 grid grid-cols-1 sm:grid-cols-[1fr_auto_auto_auto] gap-3">
        <div class="relative">
            <x-admin.icon name="search" :size="16" class="absolute left-3.5 top-1/2 -translate-y-1/2 text-[var(--text-muted)] pointer-events-none" />
            <input type="search" name="search" value="{{ request('search') }}" placeholder="Cari deskripsi atau nama entitas..." class="csa-input pl-10" aria-label="Cari log">
        </div>
        <select name="action" onchange="this.form.submit()" class="csa-input sm:w-40" aria-label="Jenis aksi">
            <option value="">Semua aksi</option>
            <option value="created" @selected($action === 'created')>Penambahan</option>
            <option value="updated" @selected($action === 'updated')>Perubahan</option>
            <option value="deleted" @selected($action === 'deleted')>Penghapusan</option>
        </select>
        <select name="module" onchange="this.form.submit()" class="csa-input sm:w-44" aria-label="Modul">
            <option value="">Semua modul</option>
            @foreach ($modules as $moduleName)
                <option value="{{ $moduleName }}" @selected($moduleName === $module)>{{ $moduleName }}</option>
            @endforeach
        </select>
        <button type="submit" class="csa-btn csa-btn-ghost">Cari</button>
    </form>

    @if ($logs->isEmpty())
        <div class="csa-card p-12 text-center">
            <x-admin.icon name="file-text" :size="32" class="mx-auto text-[var(--text-muted)]" />
            <p class="text-[13px] font-medium text-[var(--text-secondary)] mt-3">Belum ada catatan aktivitas</p>
            <p class="text-[12px] text-[var(--text-muted)] mt-1">Catatan muncul saat pengguna menambah, mengubah, atau menghapus data.</p>
        </div>
    @else
        <div class="csa-card overflow-hidden">
            <div class="divide-y divide-[var(--border-soft)]">
                @foreach ($logs as $log)
                    <div class="flex items-start gap-4 p-4" x-data="{ open: false }">
                        <img src="{{ $log->user?->avatar ? asset('storage/' . $log->user->avatar) : asset('storage/profile/profile-placeholder.png') }}"
                            alt="{{ $log->user?->name ?? 'Pengguna' }}"
                            class="w-9 h-9 rounded-full object-cover bg-[var(--bg-muted)] shrink-0">

                        <div class="min-w-0 flex-1">
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="text-[13px] font-medium text-[var(--text-primary)]">{{ $log->user?->name ?? 'Sistem' }}</span>
                                <span class="csa-chip {{ $log->action_classes }}">{{ $log->action_label }}</span>
                                <span class="csa-chip">{{ $log->subject_module }}</span>
                            </div>

                            <p class="text-[13px] text-[var(--text-secondary)] mt-1.5">{{ $log->description }}</p>

                            <div class="flex flex-wrap items-center gap-x-4 gap-y-1 mt-1.5 text-[12px] text-[var(--text-muted)]">
                                <span>{{ $log->created_at?->translatedFormat('d M Y H:i') }}</span>
                                @if ($log->ip_address)
                                    <span class="font-mono">{{ $log->ip_address }}</span>
                                @endif
                                @if (!empty($log->changes))
                                    <button type="button" @click="open = !open" class="font-medium hover:underline" style="color: var(--accent);">Detail perubahan</button>
                                @endif
                            </div>

                            @if (!empty($log->changes))
                                <div x-show="open" x-cloak class="mt-3 rounded-lg border border-[var(--border-soft)] overflow-hidden">
                                    <table class="w-full text-[12px]">
                                        <thead>
                                            <tr class="bg-[var(--bg-muted)]">
                                                <th class="text-left px-3 py-2 font-medium text-[var(--text-muted)]">Field</th>
                                                <th class="text-left px-3 py-2 font-medium text-[var(--text-muted)]">Sebelum</th>
                                                <th class="text-left px-3 py-2 font-medium text-[var(--text-muted)]">Sesudah</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-[var(--border-soft)]">
                                            @foreach ($log->changes as $field => $change)
                                                <tr>
                                                    <td class="px-3 py-2 font-mono text-[var(--text-secondary)]">{{ $field }}</td>
                                                    <td class="px-3 py-2 text-[var(--text-muted)] break-all">{{ $change['from'] ?? '(kosong)' }}</td>
                                                    <td class="px-3 py-2 text-[var(--text-primary)] break-all">{{ $change['to'] ?? '(kosong)' }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>

                        <form method="POST" action="{{ route('admin.activity-log.destroy', $log) }}" class="shrink-0">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="csa-icon-btn hover:!text-red-500" title="Hapus catatan" aria-label="Hapus catatan">
                                <x-admin.icon name="trash-2" :size="15" />
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>
        </div>

        @if ($logs->hasPages())
            <div>{{ $logs->links() }}</div>
        @endif
    @endif
</div>
@endsection
