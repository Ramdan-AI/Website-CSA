@extends('layouts.admin')

@section('content')
<div class="space-y-6" x-data="{
        selected: @js(array_map('strval', $rolePermissionIds)),
        total: {{ $permissions->flatten()->count() }},
        get count() { return this.selected.length; },
        has(id) { return this.selected.includes(String(id)); },
        toggle(id) {
            id = String(id);
            const i = this.selected.indexOf(id);
            if (i === -1) this.selected.push(id); else this.selected.splice(i, 1);
        },
        allOf(ids) { return ids.every((id) => this.selected.includes(String(id))); },
        setGroup(ids, on) {
            ids.map(String).forEach((id) => {
                const i = this.selected.indexOf(id);
                if (on && i === -1) this.selected.push(id);
                if (!on && i !== -1) this.selected.splice(i, 1);
            });
        },
    }">
    <div>
        <a href="{{ route('admin.roles.index') }}" class="inline-flex items-center gap-1.5 text-[12px] text-[var(--text-muted)] hover:text-[var(--text-primary)] transition-colors">
            <x-admin.icon name="arrow-left" :size="14" /> Kembali ke Role
        </a>
        <div class="flex items-center gap-3 mt-3">
            <span class="w-11 h-11 rounded-full bg-[var(--bg-muted)] text-[var(--text-secondary)] flex items-center justify-center shrink-0">
                <x-admin.icon name="shield" :size="20" />
            </span>
            <div>
                <h1 class="text-[22px] sm:text-[26px] font-bold text-[var(--text-primary)] tracking-[-0.03em]">{{ $role->name }}</h1>
                <p class="text-[13px] text-[var(--text-muted)] mt-0.5">Atur hak akses untuk role ini.</p>
            </div>
        </div>
    </div>

    @if (session('success'))
        <div class="csa-panel p-3.5 text-[13px] text-emerald-600 dark:text-emerald-400">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('admin.roles.permissions.update', $role) }}" class="grid grid-cols-1 lg:grid-cols-[1fr_280px] gap-5 items-start">
        @csrf
        @method('PUT')

        <div class="space-y-4">
            @foreach ($permissions as $group => $groupPermissions)
                @php $ids = $groupPermissions->pluck('id')->all(); @endphp
                <section class="csa-panel overflow-hidden">
                    <header class="px-5 py-3.5 border-b border-[var(--border-soft)] flex items-center justify-between gap-3">
                        <div>
                            <h2 class="text-[14px] font-semibold text-[var(--text-primary)] capitalize">{{ $group }}</h2>
                            <p class="text-[12px] text-[var(--text-muted)] mt-0.5">{{ $groupPermissions->count() }} permission</p>
                        </div>
                        <button type="button" class="text-[12px] font-medium text-[var(--accent-bright)] hover:underline"
                            x-text="allOf(@js($ids)) ? 'Kosongkan' : 'Pilih semua'"
                            @click="setGroup(@js($ids), !allOf(@js($ids)))"></button>
                    </header>

                    <div class="divide-y divide-[var(--border-soft)]">
                        @foreach ($groupPermissions as $permission)
                            <label class="flex items-center gap-3.5 px-5 py-3 cursor-pointer hover:bg-[var(--bg-hover)] transition-colors">
                                <input type="checkbox" name="permissions[]" value="{{ $permission->id }}" class="sr-only peer"
                                    :checked="has({{ $permission->id }})" @change="toggle({{ $permission->id }})">
                                <span class="relative w-9 h-5 rounded-full bg-[var(--border-strong)] transition-colors peer-checked:bg-[var(--accent)] after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:w-4 after:h-4 after:rounded-full after:bg-white after:transition-all peer-checked:after:translate-x-4 shrink-0"></span>
                                <span class="min-w-0 flex-1">
                                    <span class="block text-[13px] font-medium text-[var(--text-primary)]">{{ $permission->name }}</span>
                                    <span class="block text-[11px] text-[var(--text-muted)] font-mono mt-0.5 truncate">{{ $permission->slug }}</span>
                                </span>
                            </label>
                        @endforeach
                    </div>
                </section>
            @endforeach
        </div>

        {{-- Ringkasan sticky --}}
        <aside class="csa-panel p-5 lg:sticky lg:top-20">
            <p class="text-[11px] font-semibold uppercase tracking-[0.08em] text-[var(--text-muted)]">Ringkasan</p>
            <p class="text-[26px] font-bold text-[var(--text-primary)] mt-2 tabular-nums">
                <span x-text="count"></span><span class="text-[16px] text-[var(--text-muted)] font-medium">/{{ $permissions->flatten()->count() }}</span>
            </p>
            <p class="text-[12px] text-[var(--text-muted)] mt-1">permission dipilih</p>

            <button type="submit" class="csa-btn csa-btn-primary w-full mt-5">
                <x-admin.icon name="save" :size="16" /> Simpan Permission
            </button>
            <a href="{{ route('admin.roles.index') }}" class="csa-btn csa-btn-ghost w-full mt-2">Batal</a>
        </aside>
    </form>
</div>
@endsection
