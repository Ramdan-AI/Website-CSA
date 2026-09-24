@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h1 class="text-[26px] sm:text-[30px] font-bold text-[var(--text-primary)] tracking-[-0.03em]">Role & Permission</h1>
            <p class="text-[13px] text-[var(--text-muted)] mt-1">Kelola peran dan hak akses pengguna dalam sistem.</p>
        </div>
        @can('manage-roles')
            <a href="{{ route('admin.roles.create') }}" class="csa-btn csa-btn-primary self-start sm:self-auto">
                <x-admin.icon name="plus" :size="16" /> Tambah Role
            </a>
        @endcan
    </div>

    @if (session('success'))
        <div class="csa-panel p-3.5 text-[13px] text-emerald-600 dark:text-emerald-400">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="csa-panel p-3.5 text-[13px] text-red-500">{{ session('error') }}</div>
    @endif

    @if ($roles->isEmpty())
        <div class="csa-panel p-16 text-center">
            <div class="w-14 h-14 rounded-2xl bg-[var(--bg-muted)] flex items-center justify-center mx-auto text-[var(--text-muted)]">
                <x-admin.icon name="shield" :size="26" />
            </div>
            <p class="text-[15px] font-semibold text-[var(--text-secondary)] mt-4">Belum ada role</p>
        </div>
    @else
        <div class="csa-panel overflow-hidden">
            <div class="divide-y divide-[var(--border-soft)]">
                @foreach ($roles as $role)
                    <div class="flex items-center gap-4 p-4 hover:bg-[var(--bg-hover)] transition-colors">
                        <span class="w-10 h-10 rounded-full bg-[var(--bg-muted)] text-[var(--text-secondary)] flex items-center justify-center shrink-0">
                            <x-admin.icon name="shield" :size="18" />
                        </span>

                        <div class="min-w-0 flex-1">
                            <div class="flex items-center gap-2">
                                <h3 class="text-[14px] font-semibold text-[var(--text-primary)] truncate">{{ $role->name }}</h3>
                                <span class="csa-chip shrink-0 font-mono">{{ $role->slug }}</span>
                            </div>
                            @if ($role->description)
                                <p class="text-[12px] text-[var(--text-muted)] truncate mt-0.5">{{ $role->description }}</p>
                            @endif
                        </div>

                        <div class="hidden sm:flex items-center gap-5 shrink-0 text-[12px] text-[var(--text-secondary)]">
                            <span class="inline-flex items-center gap-1.5"><x-admin.icon name="key-round" :size="14" class="text-[var(--text-muted)]" /> {{ $role->permissions_count }} permission</span>
                            <span class="inline-flex items-center gap-1.5"><x-admin.icon name="users" :size="14" class="text-[var(--text-muted)]" /> {{ $role->users_count }} pengguna</span>
                        </div>

                        <div class="flex items-center gap-1 shrink-0">
                            <a href="{{ route('admin.roles.permissions', $role) }}" class="csa-icon-btn" title="Atur permission" aria-label="Atur permission">
                                <x-admin.icon name="key-round" :size="16" />
                            </a>
                            @can('manage-roles')
                                <a href="{{ route('admin.roles.edit', $role) }}" class="csa-icon-btn" title="Edit" aria-label="Edit role">
                                    <x-admin.icon name="pen" :size="16" />
                                </a>
                                @if ($role->users_count === 0)
                                    <button type="button" onclick="openDeleteModal('deleteModal', '{{ route('admin.roles.destroy', $role) }}')" class="csa-icon-btn hover:!text-red-500" title="Hapus" aria-label="Hapus role">
                                        <x-admin.icon name="trash-2" :size="16" />
                                    </button>
                                @endif
                            @endcan
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection
