@extends('layouts.admin')

@section('content')
    <div class="space-y-8">
        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
            <div>
                <p class="text-xs font-semibold text-blue-500 uppercase tracking-wider">Sistem</p>
                <h1 class="text-2xl font-bold text-gray-900 mt-1">Permission: {{ $role->name }}</h1>
                <p class="text-sm text-gray-500 mt-1">
                    Kelola hak akses untuk role <strong class="text-gray-700">{{ $role->name }}</strong>.
                </p>
            </div>
            <a href="{{ route('admin.roles.index') }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 bg-white border border-gray-200 text-gray-700 text-sm font-semibold rounded-xl hover:bg-gray-50 transition-colors shadow-sm">
                <i class="fas fa-arrow-left text-xs"></i>
                Kembali
            </a>
        </div>

        {{-- Form --}}
        <form method="POST" action="{{ route('admin.roles.permissions.update', $role) }}">
            @csrf
            @method('PUT')

            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="p-6">
                    @foreach ($permissions as $group => $groupPermissions)
                        <div class="mb-6 last:mb-0">
                            <h4 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">
                                {{ match($group) {
                                    'manage' => 'Management',
                                    'view' => 'View Only',
                                    default => ucfirst($group)
                                } }}
                            </h4>
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                                @foreach ($groupPermissions as $permission)
                                    @php
                                        $checked = in_array($permission->id, $rolePermissionIds);
                                    @endphp
                                    <label
                                        class="flex items-center gap-3 p-3 rounded-xl border transition-colors cursor-pointer
                                        {{ $checked ? 'bg-blue-50 border-blue-200' : 'bg-white border-gray-100 hover:border-gray-200' }}">
                                        <input type="checkbox" name="permissions[]" value="{{ $permission->id }}"
                                            {{ $checked ? 'checked' : '' }}
                                            class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        <div class="min-w-0 flex-1">
                                            <p class="text-sm font-medium text-gray-900">{{ $permission->name }}</p>
                                            <p class="text-xs text-gray-400 font-mono mt-0.5">{{ $permission->slug }}</p>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-between">
                    <div class="text-xs text-gray-400">
                        <span id="checkedCount">{{ count($rolePermissionIds) }}</span> dari {{ $permissions->flatten()->count() }} permission dipilih
                    </div>
                    <button type="submit"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 text-white text-sm font-semibold rounded-xl hover:bg-blue-700 transition-colors shadow-sm">
                        <i class="fas fa-save text-xs"></i>
                        Simpan Permission
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
<script>
    document.querySelectorAll('input[name="permissions[]"]').forEach(cb => {
        cb.addEventListener('change', function() {
            const count = document.querySelectorAll('input[name="permissions[]"]:checked').length;
            document.getElementById('checkedCount').textContent = count;
        });
    });
</script>
@endpush
