{{-- resources/views/pages/admin/members/index.blade.php --}}
@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    
    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
        <div>
            <p class="text-xs font-semibold text-blue-400 uppercase tracking-widest">Keanggotaan</p>
            <h1 class="text-3xl font-bold text-gray-900 mt-1">Anggota</h1>
            <p class="text-sm text-gray-400 mt-1.5 font-medium">Kelola data anggota dan struktur keanggotaan CSA.</p>
        </div>
        <a href="{{ route('admin.members.create') }}" 
           class="relative inline-flex items-center gap-2 px-5 py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white text-sm font-semibold rounded-2xl shadow-[0_4px_20px_rgba(59,130,246,0.3)] hover:shadow-[0_4px_25px_rgba(59,130,246,0.45)] hover:scale-105 transition-all duration-300 overflow-hidden group">
            <div class="absolute inset-0 bg-white/20 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
            <span class="relative flex items-center gap-2">
                <i class="fas fa-plus text-xs"></i>
                Tambah Anggota
            </span>
        </a>
    </div>

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
            <tr class="hover:bg-blue-50/30 transition-colors duration-200">
                {{-- Kolom Anggota --}}
                <td class="py-4 px-6">
                    <div class="flex items-center gap-3">
                        {{-- Avatar bulat --}}
                        <div class="relative group/avatar shrink-0 {{ $member->photo_url ? 'cursor-pointer' : '' }}" 
                             @if($member->photo_url)
                             onclick="openImagePreview('imagePreviewModal', '{{ $member->photo_url }}', '{{ $member->name }}')"
                             @endif>
                            <div class="w-11 h-11 rounded-full overflow-hidden bg-gradient-to-br from-blue-400 to-purple-500 flex items-center justify-center text-white font-bold text-sm shadow-[0_4px_12px_rgba(59,130,246,0.25)] group-hover/avatar:shadow-[0_4px_20px_rgba(59,130,246,0.4)] transition-all duration-300 ring-2 ring-white/80">
                                @if($member->photo_url)
                                    <img src="{{ $member->photo_url }}" alt="{{ $member->name }}" class="w-full h-full object-cover group-hover/avatar:scale-110 transition-transform duration-300">
                                @else
                                    <span class="group-hover/avatar:scale-110 transition-transform duration-300">{{ strtoupper(substr($member->name, 0, 2)) }}</span>
                                @endif
                            </div>
                            @if($member->photo_url)
                                <div class="absolute inset-0 rounded-full bg-black/0 group-hover/avatar:bg-black/10 transition-colors duration-300 flex items-center justify-center">
                                    <i class="fas fa-search text-white text-xs opacity-0 group-hover/avatar:opacity-100 transition-opacity duration-300 drop-shadow-lg"></i>
                                </div>
                            @endif
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
                                <i class="fas fa-envelope text-blue-400/60 w-3.5 text-[10px]"></i>
                                <span class="text-gray-600 truncate">{{ $member->email }}</span>
                            </div>
                        @endif
                        @if($member->phone)
                            <div class="flex items-center gap-1.5 text-xs">
                                <i class="fas fa-phone text-purple-400/60 w-3.5 text-[10px]"></i>
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
                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-blue-50 text-xs font-semibold text-blue-700 border border-blue-100/50">
                            {{ $member->division->name }}
                        </span>
                    @else
                        <span class="text-xs text-gray-300">-</span>
                    @endif
                </td>

                {{-- Kolom Jabatan --}}
                <td class="py-4 px-6">
                    @if($member->position)
                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-purple-50 text-xs font-semibold text-purple-700 border border-purple-100/50">
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
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-emerald-50 text-[11px] font-semibold text-emerald-600 border border-emerald-100/50">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 shadow-[0_0_6px_rgba(16,185,129,0.4)] animate-pulse"></span>
                                Aktif
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-gray-100 text-[11px] font-semibold text-gray-400 border border-gray-200/50">
                                <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span>
                                Nonaktif
                            </span>
                        @endif
                        @if($member->user_id)
                            <span class="text-[10px] text-blue-400" title="Terhubung dengan akun">
                                <i class="fas fa-link"></i>
                            </span>
                        @endif
                    </div>
                </td>

                {{-- Kolom Aksi --}}
                <td class="py-4 px-6">
                    <div class="flex items-center gap-1">
                        <a href="{{ route('admin.members.edit', $member) }}" 
                           class="w-8 h-8 rounded-lg flex items-center justify-center bg-blue-50 text-blue-600 hover:bg-blue-500 hover:text-white transition-all duration-200 hover:shadow-[0_4px_12px_rgba(59,130,246,0.3)]"
                           title="Edit">
                            <i class="fas fa-pen text-[10px]"></i>
                        </a>
                        <button onclick="openDeleteModal('deleteModal', '{{ route('admin.members.destroy', $member) }}')"
                                class="w-8 h-8 rounded-lg flex items-center justify-center bg-red-50 text-red-500 hover:bg-red-500 hover:text-white transition-all duration-200 hover:shadow-[0_4px_12px_rgba(239,68,68,0.3)]"
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