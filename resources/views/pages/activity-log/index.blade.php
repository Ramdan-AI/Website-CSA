@extends('layouts.admin')

@section('content')
<div class="space-y-4 sm:space-y-5">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h1 class="text-[26px] sm:text-[30px] font-bold text-[var(--text-primary)] tracking-[-0.03em]">Log Sistem</h1>
            <p class="text-[13px] text-[var(--text-muted)] mt-1">Riwayat aktivitas pengguna pada data penting.</p>
        </div>
        <form method="POST" action="{{ route('admin.activity-log.purge') }}" onsubmit="return confirm('Bersihkan catatan lebih tua dari {{ $setting->log_retention_days }} hari?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="csa-btn csa-btn-ghost">
                <x-admin.icon name="trash-2" :size="15" /> Bersihkan catatan lama
            </button>
        </form>
    </div>

    @if (session('success'))
        <div class="csa-panel p-3.5 text-[13px] text-emerald-600 dark:text-emerald-400">{{ session('success') }}</div>
    @endif

    {{-- Setelan retensi: penghapusan otomatis on/off + periode. --}}
    <div class="csa-panel p-5 sm:p-6" x-data="{ auto: {{ $setting->log_auto_purge_enabled ? 'true' : 'false' }} }">
        <div class="flex items-start justify-between gap-4">
            <div>
                <h2 class="text-[14px] font-semibold text-[var(--text-primary)]">Penghapusan Otomatis</h2>
                <p class="text-[12px] text-[var(--text-muted)] mt-1 max-w-xl">
                    Saat aktif, catatan aktivitas yang lebih tua dari periode terpilih dibersihkan otomatis.
                    Hitungan dimulai sejak kamu mengaktifkannya.
                </p>
            </div>
            <span class="csa-chip {{ $setting->log_auto_purge_enabled ? 'csa-chip-green' : '' }} shrink-0">
                {{ $setting->log_auto_purge_enabled ? 'Aktif' : 'Nonaktif' }}
            </span>
        </div>

        <form method="POST" action="{{ route('admin.activity-log.settings') }}" class="mt-5">
            @csrf
            @method('PUT')

            <label class="flex items-center justify-between gap-4 cursor-pointer">
                <span>
                    <span class="block text-[14px] font-medium text-[var(--text-primary)]" x-text="auto ? 'Penghapusan otomatis aktif' : 'Penghapusan otomatis nonaktif'"></span>
                    <span class="block text-[12px] text-[var(--text-muted)] mt-0.5">Ubah status lalu simpan untuk menerapkan.</span>
                </span>
                <input type="hidden" name="log_auto_purge_enabled" value="0">
                <input type="checkbox" name="log_auto_purge_enabled" value="1" class="peer sr-only"
                    x-model="auto"
                    {{ old('log_auto_purge_enabled', $setting->log_auto_purge_enabled) ? 'checked' : '' }}>
                <span class="relative w-11 h-6 rounded-full bg-[var(--border-strong)] after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:w-5 after:h-5 after:rounded-full after:bg-white after:transition-all peer-checked:bg-[var(--accent)] peer-checked:after:translate-x-5 shrink-0"></span>
            </label>

            <div class="mt-5 grid grid-cols-1 sm:grid-cols-2 gap-4 items-end">
                <div>
                    <label for="log_retention_days" class="block text-[13px] font-medium text-[var(--text-secondary)] mb-2">Periode retensi</label>
                    <select name="log_retention_days" id="log_retention_days" class="csa-input"
                        :disabled="!auto" :class="!auto ? 'opacity-50 cursor-not-allowed' : ''">
                        @foreach ($retentionOptions as $days)
                            <option value="{{ $days }}" @selected(old('log_retention_days', $setting->log_retention_days) == $days)>
                                Per {{ $days }} hari
                            </option>
                        @endforeach
                    </select>
                    @error('log_retention_days')<p class="text-[12px] text-red-500 mt-2">{{ $message }}</p>@enderror
                </div>

                <div class="text-[12px] text-[var(--text-muted)]">
                    @if ($setting->log_auto_purge_enabled && $setting->log_next_purge_at)
                        <p class="text-[var(--text-secondary)]">
                            Jadwal berikutnya: <span class="font-medium">{{ $setting->log_next_purge_at->translatedFormat('d M Y H:i') }}</span>
                        </p>
                        <p class="mt-0.5">Sekitar {{ $remainingDays }} hari lagi.</p>
                    @else
                        <p>Penghapusan otomatis nonaktif. Log lama tetap tersimpan sampai kamu membersihkannya manual.</p>
                    @endif
                </div>
            </div>

            <div class="flex justify-end mt-5 pt-4 border-t border-[var(--border-soft)]">
                <button type="submit" class="csa-btn csa-btn-primary">
                    <x-admin.icon name="save" :size="16" /> Simpan Setelan
                </button>
            </div>
        </form>
    </div>

    <form method="GET" data-ajax-filter class="csa-panel p-3 sm:p-4 grid grid-cols-1 sm:grid-cols-[1fr_auto_auto_auto] gap-3">
        <div class="relative">
            <x-admin.icon name="search" :size="17" class="absolute left-4 top-1/2 -translate-y-1/2 text-[var(--text-muted)] pointer-events-none" />
            <input type="search" name="search" value="{{ request('search') }}" placeholder="Cari deskripsi atau nama entitas..." class="csa-input !pl-[42px] !rounded-full csa-search-input" aria-label="Cari log">
        </div>
        <x-form.select name="action" :value="$action" onchange="this.form.submit()" class="sm:w-40" aria-label="Jenis aksi"
            placeholder="Semua aksi"
            :options="['created' => 'Penambahan', 'updated' => 'Perubahan', 'deleted' => 'Penghapusan']" />
        <x-form.select name="module" :value="$module" onchange="this.form.submit()" class="sm:w-44" aria-label="Modul"
            placeholder="Semua modul"
            :options="collect($modules)->mapWithKeys(fn ($m) => [$m => $m])->all()" />
        <button type="submit" class="csa-btn csa-btn-ghost">Cari</button>
    </form>

    @if ($logs->isEmpty())
        <div class="csa-panel p-12 text-center">
            <x-admin.icon name="file-text" :size="32" class="mx-auto text-[var(--text-muted)]" />
            <p class="text-[13px] font-medium text-[var(--text-secondary)] mt-3">Belum ada catatan aktivitas</p>
            <p class="text-[12px] text-[var(--text-muted)] mt-1">Catatan muncul saat pengguna menambah, mengubah, atau menghapus data.</p>
        </div>
    @else
        <div class="csa-panel overflow-hidden">
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
