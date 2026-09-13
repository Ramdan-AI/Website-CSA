@extends('layouts.admin')

@section('content')
<div class="space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
        <div>
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest">Keanggotaan</p>
            <h1 class="text-3xl font-bold text-gray-900 mt-1">Anggota</h1>
            <p class="text-sm text-gray-400 mt-1.5 font-medium">Kelola data anggota dan struktur keanggotaan CSA.</p>
        </div>
        <a href="{{ route('admin.members.create') }}"
           class="inline-flex items-center gap-2 px-5 py-2.5 bg-gray-900 text-white text-sm font-semibold rounded-xl hover:bg-gray-800 transition-colors shadow-sm">
            <i class="fas fa-plus text-xs"></i>
            Tambah Anggota
        </a>
    </div>

    {{-- Search & Filters --}}
    <form method="GET" class="bg-white rounded-2xl p-5 border border-gray-200 shadow-sm">
        <div class="flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1">
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Cari nama, NIM, email..."
                    class="w-full h-11 px-4 pl-11 rounded-xl border border-gray-200 text-sm focus:outline-none focus:border-gray-400 focus:ring-1 focus:ring-gray-200 bg-gray-50 focus:bg-white transition-all">
                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
            </div>

            <select name="division_id" class="h-11 px-3 rounded-xl border border-gray-200 text-sm text-gray-600 focus:outline-none focus:border-gray-400 bg-gray-50 focus:bg-white">
                <option value="">Semua Divisi</option>
                @foreach($divisions as $division)
                    <option value="{{ $division->id }}" {{ request('division_id') == $division->id ? 'selected' : '' }}>
                        {{ $division->name }}
                    </option>
                @endforeach
            </select>

            <select name="position_id" class="h-11 px-3 rounded-xl border border-gray-200 text-sm text-gray-600 focus:outline-none focus:border-gray-400 bg-gray-50 focus:bg-white">
                <option value="">Semua Jabatan</option>
                @foreach($positions as $position)
                    <option value="{{ $position->id }}" {{ request('position_id') == $position->id ? 'selected' : '' }}>
                        {{ $position->name }}
                    </option>
                @endforeach
            </select>

            <select name="status" class="h-11 px-3 rounded-xl border border-gray-200 text-sm text-gray-600 focus:outline-none focus:border-gray-400 bg-gray-50 focus:bg-white">
                <option value="">Semua Status</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
            </select>

            <select name="sort" class="h-11 px-3 rounded-xl border border-gray-200 text-sm text-gray-600 focus:outline-none focus:border-gray-400 bg-gray-50 focus:bg-white">
                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Terbaru</option>
                <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Nama A-Z</option>
                <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Nama Z-A</option>
                <option value="nim_asc" {{ request('sort') == 'nim_asc' ? 'selected' : '' }}>NIM Naik</option>
                <option value="nim_desc" {{ request('sort') == 'nim_desc' ? 'selected' : '' }}>NIM Turun</option>
                <option value="division_asc" {{ request('sort') == 'division_asc' ? 'selected' : '' }}>Divisi A-Z</option>
                <option value="position_asc" {{ request('sort') == 'position_asc' ? 'selected' : '' }}>Jabatan A-Z</option>
            </select>

            <a href="{{ route('admin.members.index') }}" class="h-11 px-4 rounded-xl bg-gray-100 text-gray-600 text-sm font-medium hover:bg-gray-200 transition-colors flex items-center justify-center">
                Reset
            </a>
        </div>
    </form>

    <x-admin.table
        :headers="[
            ['label' => 'Anggota', 'width' => ''],
            ['label' => 'Kontak', 'width' => ''],
            ['label' => 'Divisi', 'width' => ''],
            ['label' => 'Jabatan', 'width' => ''],
            ['label' => 'Status', 'width' => ''],
            ['label' => 'Aksi', 'width' => 'w-24'],
        ]"
        :hasPages="$members->hasPages()"
        :paginator="$members"
    >
        @forelse($members as $member)
            <tr class="hover:bg-gray-50 transition-colors duration-200">
                {{-- Kolom Anggota --}}
                <td class="py-4 px-6">
                    <div class="flex items-center gap-3">
                        <div class="relative shrink-0 {{ $member->photo_url ? 'cursor-pointer' : '' }}"
                             @if($member->photo_url)
                             onclick="openImagePreview('imagePreviewModal', '{{ $member->photo_url }}', '{{ $member->name }}')"
                             @endif>
                            <div class="w-11 h-11 rounded-full overflow-hidden bg-gray-100 flex items-center justify-center text-gray-600 font-bold text-sm ring-2 ring-white shadow-sm">
                                @if($member->photo_url)
                                    <img src="{{ $member->photo_url }}" alt="{{ $member->name }}" class="w-full h-full object-cover">
                                @else
                                    <img src="{{ asset('storage/profile/profile-placeholder.png') }}" alt="{{ $member->name }}" class="w-full h-full object-cover">
                                @endif
                            </div>
                        </div>
                        <div class="min-w-0">
                            <p class="text-sm font-bold text-gray-900 truncate">{{ $member->name }}</p>
                            @if($member->nim)
                                <p class="text-[10px] text-gray-400 font-mono tracking-wide">{{ $member->nim }}</p>
                            @endif
                        </div>
                    </div>
                </td>

                {{-- Kolom Kontak --}}
                <td class="py-4 px-6">
                    <div class="space-y-1">
                        @if($member->email)
                            <div class="flex items-center gap-1.5 text-xs">
                                <i class="fas fa-envelope text-gray-400 w-3.5 text-[10px]"></i>
                                <span class="text-gray-600 truncate">{{ $member->email }}</span>
                            </div>
                        @endif
                        @if($member->phone)
                            <div class="flex items-center gap-1.5 text-xs">
                                <i class="fas fa-phone text-gray-400 w-3.5 text-[10px]"></i>
                                <span class="text-gray-600">{{ $member->phone }}</span>
                            </div>
                        @endif
                        @if(!$member->email && !$member->phone)
                            <span class="text-xs text-gray-300">-</span>
                        @endif
                    </div>
                </td>

                {{-- Kolom Divisi --}}
                <td class="py-4 px-6">
                    @if($member->division)
                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-gray-50 text-xs font-semibold text-gray-700 border border-gray-100">
                            {{ $member->division->name }}
                        </span>
                    @else
                        <span class="text-xs text-gray-300">-</span>
                    @endif
                </td>

                {{-- Kolom Jabatan --}}
                <td class="py-4 px-6">
                    @if($member->position)
                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-gray-50 text-xs font-semibold text-gray-700 border border-gray-100">
                            {{ $member->position->name }}
                        </span>
                    @else
                        <span class="text-xs text-gray-300">Belum ada</span>
                    @endif
                </td>

                {{-- Kolom Status --}}
                <td class="py-4 px-6">
                    <div class="flex items-center gap-2">
                        @if($member->is_active)
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-gray-50 text-xs font-semibold text-gray-700 border border-gray-100">
                                <span class="w-1.5 h-1.5 rounded-full bg-gray-600"></span>
                                Aktif
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-gray-50 text-xs font-semibold text-gray-500 border border-gray-100">
                                <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span>
                                Nonaktif
                            </span>
                        @endif
                        @if($member->user_id)
                            <span class="text-[10px] text-gray-400" title="Terhubung dengan akun">
                                <i class="fas fa-link"></i>
                            </span>
                        @endif
                    </div>
                </td>

                {{-- Kolom Aksi --}}
                <td class="py-4 px-6">
                    <div class="flex items-center gap-1">
                        <a href="{{ route('admin.members.edit', $member) }}"
                           class="w-8 h-8 rounded-lg flex items-center justify-center bg-gray-50 text-gray-600 hover:bg-gray-100 transition-colors"
                           title="Edit">
                            <i class="fas fa-pen text-[10px]"></i>
                        </a>
                        <button onclick="openDeleteModal('deleteModal', '{{ route('admin.members.destroy', $member) }}')"
                                class="w-8 h-8 rounded-lg flex items-center justify-center bg-red-50 text-red-500 hover:bg-red-50 hover:text-red-600 transition-colors"
                                title="Hapus">
                            <i class="fas fa-trash text-[10px]"></i>
                        </button>
                    </div>
                </td>
            </tr>
        @empty
            <x-admin.empty-state
                icon="fa-users"
                title="Belum ada anggota"
                description="Tambahkan anggota pertama untuk memulai"
                :createRoute="route('admin.members.create')"
                createLabel="Tambah Anggota Pertama"
                :colspan="6"
            />
        @endforelse
    </x-admin.table>
</div>
@endsection
