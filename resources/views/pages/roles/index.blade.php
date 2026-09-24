@extends('layouts.admin')

@section('content')
<div class="space-y-4 sm:space-y-5">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h1 class="text-[26px] sm:text-[30px] font-bold text-[var(--text-primary)] tracking-[-0.03em]">Role & Permission</h1>
            <p class="text-[13px] text-[var(--text-muted)] mt-1">Kelola peran dan hak akses pengguna dalam sistem.</p>
        </div>
        <a href="{{ route('admin.roles.create') }}" class="csa-btn csa-btn-primary">
            <x-admin.icon name="plus" :size="16" /> Tambah Role
        </a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse ($roles as $role)
            <article class="csa-panel p-5 flex flex-col">
                <div class="flex items-start justify-between gap-3">
                    <div class="flex items-center gap-3 min-w-0">
                        <span class="w-10 h-10 rounded-xl bg-[var(--bg-muted)] text-[var(--text-secondary)] flex items-center justify-center shrink-0">
                            <x-admin.icon name="shield" :size="18" />
                        </span>
                        <div class="min-w-0">
                            <h3 class="text-[14px] font-semibold text-[var(--text-primary)] truncate">{{ $role->name }}</h3>
                            <p class="text-[12px] text-[var(--text-muted)] font-mono truncate">{{ $role->slug }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-0.5 shrink-0">
                        <a href="{{ route('admin.roles.edit', $role) }}" class="csa-icon-btn" title="Edit" aria-label="Edit role">
                            <x-admin.icon name="pen" :size="16" />
                        </a>
                        @if ($role->users_count === 0)
                            <button type="button" onclick="openDeleteModal('deleteModal', '{{ route('admin.roles.destroy', $role) }}')" class="csa-icon-btn hover:!text-red-500" title="Hapus" aria-label="Hapus role">
                                <x-admin.icon name="trash-2" :size="16" />
                            </button>
                        @endif
                    </div>
                </div>

                @if ($role->description)
                    <p class="text-[12px] text-[var(--text-muted)] mt-3 leading-relaxed line-clamp-2">{{ $role->description }}</p>
                @endif

                <div class="mt-4 pt-3.5 border-t border-[var(--border-soft)] flex items-center justify-between text-[12px]">
                    <span class="text-[var(--text-secondary)] inline-flex items-center gap-1.5">
                        <x-admin.icon name="key-round" :size="14" /> {{ $role->permissions_count }} permission
                    </span>
                    <span class="text-[var(--text-secondary)] inline-flex items-center gap-1.5">
                        <x-admin.icon name="users" :size="14" /> {{ $role->users_count }} pengguna
                    </span>
                </div>

                <a href="{{ route('admin.roles.permissions', $role) }}" class="csa-btn csa-btn-ghost w-full mt-3 !min-h-[38px] !text-[12px]">
                    Atur Permission <x-admin.icon name="chevron-right" :size="14" />
                </a>
            </article>
        @empty
            <div class="col-span-full csa-panel p-12 text-center">
                <x-admin.icon name="shield" :size="32" class="mx-auto text-[var(--text-muted)]" />
                <p class="text-[13px] font-medium text-[var(--text-secondary)] mt-3">Belum ada role</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
