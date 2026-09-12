@extends('layouts.admin')

@section('content')
    <div class="space-y-8">
        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
            <div>
                <p class="text-xs font-semibold text-blue-500 uppercase tracking-wider">Sistem</p>
                <h1 class="text-2xl font-bold text-gray-900 mt-1">Role &amp; Permission</h1>
                <p class="text-sm text-gray-500 mt-1">Kelola peran dan hak akses pengguna dalam sistem.</p>
            </div>
            <a href="{{ route('admin.roles.create') }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 text-white text-sm font-semibold rounded-xl hover:bg-blue-700 transition-colors shadow-sm">
                <i class="fas fa-plus text-xs"></i>
                Tambah Role
            </a>
        </div>

        {{-- Grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach ($roles as $role)
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm hover:shadow-md transition-shadow">
                    <div class="p-5">
                        {{-- Header --}}
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-10 h-10 rounded-xl bg-gray-100 flex items-center justify-center">
                                <i class="fas fa-shield-alt text-sm text-gray-600"></i>
                            </div>
                            <div class="min-w-0 flex-1">
                                <h3 class="text-sm font-bold text-gray-900 truncate">{{ $role->name }}</h3>
                                <p class="text-xs text-gray-400 font-mono">{{ $role->slug }}</p>
                            </div>
                            <div class="flex items-center gap-1">
                                <a href="{{ route('admin.roles.edit', $role) }}"
                                    class="w-8 h-8 rounded-lg hover:bg-gray-100 flex items-center justify-center text-gray-400 hover:text-gray-600 transition-colors">
                                    <i class="fas fa-pen text-xs"></i>
                                </a>
                                @if ($role->users_count === 0)
                                    <button type="button"
                                        onclick="openDeleteModal('deleteModal', '{{ route('admin.roles.destroy', $role) }}')"
                                        class="w-8 h-8 rounded-lg hover:bg-red-50 flex items-center justify-center text-gray-400 hover:text-red-500 transition-colors">
                                        <i class="fas fa-trash text-xs"></i>
                                    </button>
                                @endif
                            </div>
                        </div>

                        {{-- Stats --}}
                        <div class="flex items-center gap-4 text-xs">
                            <span class="text-gray-500">
                                <i class="fas fa-key mr-1 text-gray-400"></i>
                                {{ $role->permissions_count }} permission
                            </span>
                            <span class="text-gray-500">
                                <i class="fas fa-users mr-1 text-gray-400"></i>
                                {{ $role->users_count }} pengguna
                            </span>
                        </div>

                        {{-- Description --}}
                        @if ($role->description)
                            <p class="text-xs text-gray-400 mt-3 leading-relaxed">{{ $role->description }}</p>
                        @endif

                        {{-- Action --}}
                        <div class="mt-4 pt-3 border-t border-gray-100">
                            <a href="{{ route('admin.roles.permissions', $role) }}"
                                class="inline-flex items-center gap-1.5 text-xs font-semibold text-blue-600 hover:text-blue-700 transition-colors">
                                <i class="fas fa-key"></i>
                                Atur Permission
                                <i class="fas fa-chevron-right text-[10px]"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
