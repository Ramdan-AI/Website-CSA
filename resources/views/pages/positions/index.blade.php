{{-- resources/views/pages/admin/positions/index.blade.php --}}
@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    
    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
        <div>
            <p class="text-xs font-semibold text-blue-400 uppercase tracking-widest">Data Master</p>
            <h1 class="text-3xl font-bold text-gray-900 mt-1">Jabatan</h1>
            <p class="text-sm text-gray-400 mt-1.5 font-medium">Kelola jabatan dan atur anggota untuk setiap posisi.</p>
        </div>
        <a href="{{ route('admin.positions.create') }}" 
           class="relative inline-flex items-center gap-2 px-5 py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white text-sm font-semibold rounded-2xl shadow-[0_4px_20px_rgba(59,130,246,0.3)] hover:shadow-[0_4px_25px_rgba(59,130,246,0.45)] hover:scale-105 transition-all duration-300 overflow-hidden group">
            <div class="absolute inset-0 bg-white/20 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
            <span class="relative flex items-center gap-2">
                <i class="fas fa-plus text-xs"></i>
                Tambah Jabatan
            </span>
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
        @forelse($positions as $position)
            @php
                $firstMember = $position->members->first();
                $isUnique = in_array(strtolower($position->name), ['ketua', 'wakil ketua', 'ketua divisi', 'wakil ketua divisi', 'sekretaris', 'bendahara']);
            @endphp
            <div class="relative group/card">
                <div class="absolute -inset-[2px] bg-gradient-to-br from-blue-400/30 via-blue-300/20 to-cyan-300/30 rounded-[34px] blur-lg opacity-0 group-hover/card:opacity-100 transition-all duration-500"></div>
                
                <div class="relative bg-white/70 backdrop-blur-xl rounded-[30px] border border-white/50 shadow-[0_2px_16px_-4px_rgba(59,130,246,0.08)] group-hover/card:shadow-[0_8px_32px_-4px_rgba(59,130,246,0.15)] transition-all duration-500 h-full flex flex-col overflow-hidden">
                    
                    <div class="relative p-5 flex-1 flex flex-col">
                        <div class="flex items-start justify-between gap-3 mb-4">
                            <div class="flex items-center gap-3">
                                @if($isUnique && $firstMember)
                                    @if($firstMember->photo_url)
                                        <div class="w-10 h-10 rounded-full overflow-hidden ring-2 ring-blue-100 shadow-[0_2px_8px_-2px_rgba(59,130,246,0.2)] shrink-0">
                                            <img src="{{ $firstMember->photo_url }}" alt="{{ $firstMember->name }}" class="w-full h-full object-cover">
                                        </div>
                                    @else
                                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white font-bold text-xs shadow-[0_2px_8px_-2px_rgba(59,130,246,0.25)] shrink-0">
                                            {{ strtoupper(substr($firstMember->name, 0, 2)) }}
                                        </div>
                                    @endif
                                @else
                                    <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-blue-50 to-blue-100 flex items-center justify-center shadow-[0_2px_8px_-2px_rgba(59,130,246,0.15)] shrink-0">
                                        <i class="fas fa-briefcase text-blue-500 text-sm"></i>
                                    </div>
                                @endif
                                <div>
                                    <h3 class="text-sm font-bold text-gray-900">{{ $position->name }}</h3>
                                    @if($isUnique && $firstMember)
                                        <p class="text-[10px] text-gray-400 font-medium">{{ $firstMember->name }}</p>
                                    @else
                                        <p class="text-[10px] text-gray-400 font-medium">{{ $position->members_count }} anggota</p>
                                    @endif
                                </div>
                            </div>
                            <div class="flex items-center gap-0.5">
                                <a href="{{ route('admin.positions.edit', $position) }}" 
                                   class="w-8 h-8 rounded-xl flex items-center justify-center text-gray-400 hover:text-blue-600 hover:bg-blue-50 transition-all duration-200">
                                    <i class="fas fa-pen text-[10px]"></i>
                                </a>
                                <button onclick="openDeleteModal('deleteModal', '{{ route('admin.positions.destroy', $position) }}')"
                                        class="w-8 h-8 rounded-xl flex items-center justify-center text-gray-400 hover:text-red-500 hover:bg-red-50 transition-all duration-200">
                                    <i class="fas fa-trash text-[10px]"></i>
                                </button>
                            </div>
                        </div>

                        <p class="text-xs text-gray-500 leading-relaxed line-clamp-2 flex-1">{{ $position->description ?: 'Belum ada deskripsi.' }}</p>

                        <div class="mt-4 flex items-center justify-between pt-3 border-t border-gray-100/60">
                            @if($isUnique)
                                <div class="flex items-center gap-1.5">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $firstMember ? 'bg-emerald-400 shadow-[0_0_6px_rgba(16,185,129,0.4)]' : 'bg-gray-300' }}"></span>
                                    <span class="text-xs font-medium {{ $firstMember ? 'text-emerald-600' : 'text-gray-400' }}">
                                        {{ $firstMember ? 'Terisi' : 'Kosong' }}
                                    </span>
                                </div>
                            @else
                                <div class="flex items-center gap-1.5">
                                    <i class="fas fa-users text-[10px] text-blue-400"></i>
                                    <span class="text-xs font-semibold text-gray-700">{{ $position->members_count }}</span>
                                    <span class="text-xs text-gray-400">anggota</span>
                                </div>
                            @endif
                            <a href="{{ route('admin.positions.members', $position) }}" 
                               class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 hover:bg-blue-500 text-blue-600 hover:text-white rounded-xl text-[11px] font-semibold transition-all duration-300 hover:shadow-[0_4px_12px_-2px_rgba(59,130,246,0.3)]">
                                <span>Atur</span>
                                <i class="fas fa-chevron-right text-[9px]"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full">
                <div class="relative">
                    <div class="absolute -inset-[2px] bg-gradient-to-br from-blue-400/20 via-blue-300/15 to-cyan-300/20 rounded-[34px] blur-md"></div>
                    <div class="relative bg-white/70 backdrop-blur-xl rounded-[30px] border border-white/50 shadow-[0_2px_16px_-4px_rgba(59,130,246,0.08)] overflow-hidden">
                        <div class="relative py-16 text-center">
                            <div class="w-14 h-14 rounded-2xl bg-blue-50 flex items-center justify-center mx-auto mb-4 shadow-[0_2px_8px_-2px_rgba(59,130,246,0.1)]">
                                <i class="fas fa-briefcase text-xl text-blue-400"></i>
                            </div>
                            <p class="text-sm font-bold text-gray-900 mb-1">Belum ada jabatan</p>
                            <p class="text-xs text-gray-400 mb-5">Tambahkan jabatan pertama untuk memulai</p>
                            <a href="{{ route('admin.positions.create') }}" 
                               class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-blue-500 to-blue-600 text-white text-xs font-semibold rounded-xl shadow-[0_4px_12px_-2px_rgba(59,130,246,0.3)] hover:shadow-[0_8px_20px_-4px_rgba(59,130,246,0.4)] hover:scale-105 transition-all duration-300">
                                <i class="fas fa-plus text-[10px]"></i>
                                Tambah Jabatan
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    @if($positions->hasPages())
        <div class="relative bg-white/70 backdrop-blur-xl rounded-[30px] border border-white/50 px-6 py-4 shadow-[0_2px_16px_-4px_rgba(59,130,246,0.08)]">
            {{ $positions->links() }}
        </div>
    @endif
</div>
@endsection