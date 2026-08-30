{{-- resources/views/pages/admin/positions/members.blade.php --}}
@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    
    <div>
        <a href="{{ route('admin.positions.index') }}" class="inline-flex items-center gap-2 text-[13px] text-gray-400 hover:text-blue-600 transition-colors mb-3">
            <i class="fas fa-arrow-left text-[11px]"></i>
            Kembali ke Jabatan
        </a>
        <div class="flex items-center gap-4">
            <div class="relative">
                <div class="absolute -inset-[2px] bg-gradient-to-br from-blue-400/40 to-cyan-400/40 rounded-[24px] blur-md"></div>
                <div class="relative w-[56px] h-[56px] rounded-[20px] bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-white font-bold text-xl shadow-[0_8px_20px_-4px_rgba(59,130,246,0.4)]"
                     style="text-shadow: 0 1px 2px rgba(0,0,0,0.1);">
                    {{ strtoupper(substr($position->name, 0, 2)) }}
                </div>
            </div>
            <div>
                <p class="text-[11px] font-semibold text-blue-400 uppercase tracking-widest">Jabatan</p>
                <h1 class="text-[28px] font-bold text-gray-900 tracking-tight">{{ $position->name }}</h1>
                <p class="text-[13px] text-gray-400 mt-0.5 font-medium">{{ $position->members->count() }} anggota</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-1">
            <div class="relative group/card">
                <div class="absolute -inset-[2px] bg-gradient-to-br from-blue-400/30 via-blue-300/20 to-cyan-300/30 rounded-[36px] blur-xl opacity-0 group-hover/card:opacity-100 transition-all duration-700"></div>
                <div class="relative bg-[#f8faff]/90 backdrop-blur-2xl rounded-[34px] border border-white/50 shadow-[0_1px_3px_rgba(0,0,0,0.02),0_8px_24px_rgba(59,130,246,0.06)] overflow-hidden sticky top-24">
                    <div class="relative p-5">
                        <div class="flex items-center gap-2.5 mb-5">
                            <div class="w-[38px] h-[38px] rounded-[14px] bg-blue-50 flex items-center justify-center text-blue-500 shadow-[0_4px_10px_-2px_rgba(59,130,246,0.2)]">
                                <i class="fas fa-user-plus text-[13px]"></i>
                            </div>
                            <div>
                                <h3 class="text-[15px] font-semibold text-gray-900 tracking-tight">Tambah Anggota</h3>
                                <p class="text-[11px] text-gray-400">ke {{ $position->name }}</p>
                            </div>
                        </div>
                        
                        <form action="{{ route('admin.positions.assign', $position) }}" method="POST" class="space-y-4">
                            @csrf
                            <div>
                                <label for="member_id" class="block text-[12px] font-medium text-gray-500 mb-2">Pilih Anggota</label>
                                <select name="member_id" id="member_id" required
                                        class="w-full h-[46px] px-4 rounded-[16px] border border-gray-200/80 text-[13px] focus:outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-400/20 transition-all bg-white/60">
                                    <option value="">-- Pilih anggota --</option>
                                    @foreach($allMembers as $member)
                                        @php
                                            $isCurrentPosition = $member->position_id == $position->id;
                                            $hasOtherPosition = $member->position_id && $member->position_id != $position->id;
                                        @endphp
                                        <option value="{{ $member->id }}" {{ $isCurrentPosition ? 'selected' : '' }}>
                                            {{ $member->name }}
                                            @if($member->division) — {{ $member->division->name }} @endif
                                            @if($isCurrentPosition) (saat ini) @elseif($hasOtherPosition) ({{ $member->position?->name }}) @else (tersedia) @endif
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" 
                                    class="w-full flex items-center justify-center gap-2 h-11 bg-gradient-to-r from-blue-500 to-blue-600 text-white text-[13px] font-medium rounded-[18px] shadow-[0_8px_20px_-4px_rgba(59,130,246,0.4)] hover:shadow-[0_12px_24px_-4px_rgba(59,130,246,0.5)] hover:scale-[1.02] transition-all duration-300 group/btn">
                                <i class="fas fa-plus text-[11px] group-hover/btn:scale-110 transition-transform duration-300"></i>
                                Tambahkan
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-2">
            <div class="relative group/card">
                <div class="absolute -inset-[2px] bg-gradient-to-br from-blue-400/30 via-blue-300/20 to-cyan-300/30 rounded-[36px] blur-xl opacity-0 group-hover/card:opacity-100 transition-all duration-700"></div>
                <div class="relative bg-[#f8faff]/90 backdrop-blur-2xl rounded-[34px] border border-white/50 shadow-[0_1px_3px_rgba(0,0,0,0.02),0_8px_24px_rgba(59,130,246,0.06)] overflow-hidden">
                    <div class="relative p-5">
                        <div class="flex items-center gap-2.5 mb-5">
                            <div class="w-[38px] h-[38px] rounded-[14px] bg-blue-50 flex items-center justify-center text-blue-500 shadow-[0_4px_10px_-2px_rgba(59,130,246,0.2)]">
                                <i class="fas fa-users text-[13px]"></i>
                            </div>
                            <div>
                                <h3 class="text-[15px] font-semibold text-gray-900 tracking-tight">Anggota {{ $position->name }}</h3>
                                <p class="text-[11px] text-gray-400">{{ $members->total() }} orang</p>
                            </div>
                        </div>
                        
                        @if($members->count() > 0)
                            <div class="space-y-2">
                                @foreach($members as $member)
                                    <div class="flex items-center justify-between p-3 rounded-[18px] bg-white/60 hover:bg-blue-50/60 transition-all duration-200 group/item">
                                        <div class="flex items-center gap-3 min-w-0 flex-1">
                                            <div class="w-[40px] h-[40px] rounded-full overflow-hidden shadow-[0_4px_12px_-2px_rgba(59,130,246,0.3)] ring-[3px] ring-white shrink-0">
                                                <img src="{{ $member->photo_url ?? asset('storage/profile/profile-placeholder.png') }}" alt="{{ $member->name }}" class="w-full h-full object-cover">
                                            </div>
                                            <div class="min-w-0">
                                                <p class="text-[13px] font-semibold text-gray-900 truncate">{{ $member->name }}</p>
                                                <div class="flex items-center gap-2 mt-0.5">
                                                    <p class="text-[11px] text-gray-400 truncate">{{ $member->division?->name ?? 'Tanpa Divisi' }}</p>
                                                    <span class="text-[10px] font-medium text-blue-500 bg-blue-50/80 px-2 py-0.5 rounded-full shrink-0">
                                                        {{ $position->name }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <button type="button"
                                                onclick="openDeleteModal('deleteModal', '{{ route('admin.positions.remove-member', [$position, $member->id]) }}')"
                                                class="w-9 h-9 rounded-xl flex items-center justify-center bg-red-50 text-red-500 hover:bg-red-500 hover:text-white hover:shadow-[0_4px_12px_rgba(239,68,68,0.3)] transition-all duration-200">
                                            <i class="fas fa-user-minus text-xs"></i>
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                            
                            @if($members->hasPages())
                                <div class="mt-5">
                                    {{ $members->links() }}
                                </div>
                            @endif
                        @else
                            <div class="text-center py-14">
                                <div class="relative w-[60px] h-[60px] mx-auto mb-4">
                                    <div class="absolute inset-0 bg-blue-400/20 rounded-[20px] blur-xl"></div>
                                    <div class="relative w-full h-full rounded-[20px] bg-white/80 flex items-center justify-center shadow-[0_8px_20px_-4px_rgba(59,130,246,0.15)]">
                                        <i class="fas fa-user-slash text-[22px] text-blue-300"></i>
                                    </div>
                                </div>
                                <p class="text-[15px] font-semibold text-gray-900 tracking-tight mb-1">Belum ada anggota</p>
                                <p class="text-[13px] text-gray-400">Tambahkan anggota menggunakan form di samping</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection