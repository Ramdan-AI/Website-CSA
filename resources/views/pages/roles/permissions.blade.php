@extends('layouts.admin')

@section('content')
<div class="space-y-4 sm:space-y-5">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <a href="{{ route('admin.roles.index') }}" class="inline-flex items-center gap-1.5 text-[12px] text-[var(--text-muted)] hover:text-[var(--text-primary)] transition-colors">
                <x-admin.icon name="arrow-left" :size="14" /> Kembali ke Role
            </a>
            <h1 class="text-[26px] sm:text-[30px] font-bold text-[var(--text-primary)] tracking-[-0.03em] mt-2">Permission: {{ $role->name }}</h1>
            <p class="text-[13px] text-[var(--text-muted)] mt-1">Kelola hak akses untuk role ini.</p>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.roles.permissions.update', $role) }}">
        @csrf
        @method('PUT')

        <div class="csa-panel overflow-hidden">
            <div class="p-5 space-y-6">
                @foreach ($permissions as $group => $groupPermissions)
                    <div>
                        <h4 class="text-[11px] font-semibold text-[var(--text-muted)] uppercase tracking-wider mb-3">
                            {{ match($group) {
                                'manage' => 'Management',
                                'view' => 'View Only',
                                default => ucfirst($group)
                            } }}
                        </h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2.5">
                            @foreach ($groupPermissions as $permission)
                                @php $checked = in_array($permission->id, $rolePermissionIds); @endphp
                                <label class="flex items-center gap-3 p-3 rounded-xl border transition-colors cursor-pointer {{ $checked ? 'bg-[var(--accent-subtle)] border-[var(--accent)]' : 'border-[var(--border-soft)] hover:border-[var(--border-strong)]' }}">
                                    <input type="checkbox" name="permissions[]" value="{{ $permission->id }}" @checked($checked) class="w-4 h-4 rounded border-[var(--border-strong)] accent-[var(--accent)] shrink-0">
                                    <span class="min-w-0 flex-1">
                                        <span class="block text-[13px] font-medium text-[var(--text-primary)]">{{ $permission->name }}</span>
                                        <span class="block text-[11px] text-[var(--text-muted)] font-mono mt-0.5 truncate">{{ $permission->slug }}</span>
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="px-5 py-4 border-t border-[var(--border-soft)] flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <p class="text-[12px] text-[var(--text-muted)]">
                    <span id="checkedCount">{{ count($rolePermissionIds) }}</span> dari {{ $permissions->flatten()->count() }} permission dipilih
                </p>
                <button type="submit" class="csa-btn csa-btn-primary">
                    <x-admin.icon name="save" :size="16" /> Simpan Permission
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    document.querySelectorAll('input[name="permissions[]"]').forEach((checkbox) => {
        checkbox.addEventListener('change', () => {
            const count = document.querySelectorAll('input[name="permissions[]"]:checked').length;
            document.getElementById('checkedCount').textContent = count;
        });
    });
</script>
@endpush
