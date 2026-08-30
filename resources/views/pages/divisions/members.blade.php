{{-- resources/views/pages/admin/divisions/members.blade.php --}}
@extends('layouts.admin')

@section('content')
    <div class="space-y-6">

        <div>
            <a href="{{ route('admin.divisions.index') }}"
                class="inline-flex items-center gap-2 text-sm text-gray-400 hover:text-blue-600 transition-colors mb-3">
                <i class="fas fa-arrow-left text-xs"></i>
                Kembali ke Divisi
            </a>
            <div class="flex items-center gap-4">
                <div class="relative">
                    <div class="absolute -inset-1 bg-gradient-to-br from-blue-400/40 to-purple-400/40 rounded-2xl blur-md">
                    </div>
                    <div class="relative w-14 h-14 rounded-2xl overflow-hidden shadow-[0_4px_12px_rgba(59,130,246,0.3)]">
                        @if ($division->image)
                            <img src="{{ asset('storage/' . $division->image) }}" alt="{{ $division->name }}"
                                class="w-full h-full object-cover">
                        @else
                            <div
                                class="w-full h-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-white font-bold text-xl">
                                {{ strtoupper(substr($division->name, 0, 2)) }}
                            </div>
                        @endif
                    </div>
                </div>
                <div>
                    <p class="text-xs font-semibold text-blue-400 uppercase tracking-widest">Divisi</p>
                    <h1 class="text-3xl font-bold text-gray-900">{{ $division->name }}</h1>
                    <p class="text-sm text-gray-400 mt-1">{{ $division->description ?: 'Tidak ada deskripsi.' }} ·
                        {{ $members->total() }} anggota</p>
                </div>
            </div>
        </div>

        <div class="relative group/card">
            <div
                class="absolute -inset-1 bg-gradient-to-br from-blue-400/20 to-purple-400/20 rounded-[32px] blur-md opacity-0 group-hover/card:opacity-100 transition-opacity duration-500">
            </div>
            <div
                class="relative bg-white/80 backdrop-blur-xl rounded-[28px] border border-white/60 shadow-[0_8px_32px_rgba(59,130,246,0.05)] overflow-hidden">
                <div
                    class="absolute inset-0 bg-gradient-to-br from-blue-400/5 to-purple-400/5 rounded-[28px] pointer-events-none">
                </div>
                <div class="relative p-6">
                    <div class="flex items-center gap-2 mb-5">
                        <div class="w-8 h-8 rounded-lg bg-blue-100 flex items-center justify-center text-blue-600">
                            <i class="fas fa-users text-xs"></i>
                        </div>
                        <h3 class="text-sm font-bold text-gray-900">Daftar Anggota ({{ $members->total() }})</h3>
                    </div>

                    @if ($members->count() > 0)
                        <div class="space-y-2">
                            @foreach ($members as $member)
                                <div
                                    class="flex items-center justify-between p-3 rounded-xl bg-gray-50/80 hover:bg-blue-50/80 transition-colors">
                                    <div class="flex items-center gap-3">
                                        <div class="relative">
                                            <div
                                                class="w-10 h-10 rounded-xl overflow-hidden shadow-[0_4px_8px_rgba(59,130,246,0.2)] shrink-0">
                                                <img src="{{ $member->photo_url ?? asset('storage/profile/profile-placeholder.png') }}" alt="{{ $member->name }}" class="w-full h-full object-cover">
                                            </div>
                                            <div
                                                class="absolute -bottom-0.5 -right-0.5 w-3.5 h-3.5 rounded-full bg-emerald-400 border-2 border-white shadow-[0_0_6px_rgba(16,185,129,0.5)]">
                                            </div>
                                        </div>
                                        <div>
                                            <p class="text-sm font-semibold text-gray-900">{{ $member->name }}</p>
                                            <div class="flex items-center gap-2 text-[11px] text-gray-400">
                                                <span>{{ $member->position?->name ?? 'Tanpa Jabatan' }}</span>
                                                @if ($member->nim)
                                                    <span>·</span>
                                                    <span>{{ $member->nim }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-1">
                                        <a href="{{ route('admin.members.edit', $member) }}"
                                            class="w-8 h-8 rounded-lg flex items-center justify-center bg-blue-50 text-blue-600 hover:bg-blue-500 hover:text-white hover:shadow-[0_4px_8px_rgba(59,130,246,0.2)] transition-all duration-200">
                                            <i class="fas fa-pen text-[10px]"></i>
                                        </a>
                                        <button type="button"
                                            onclick="openDeleteModal('deleteModal', '{{ route('admin.divisions.remove-member', [$division, $member->id]) }}')"
                                            class="w-8 h-8 rounded-lg flex items-center justify-center bg-red-50 text-red-500 hover:bg-red-500 hover:text-white hover:shadow-[0_4px_8px_rgba(239,68,68,0.2)] transition-all duration-200">
                                            <i class="fas fa-user-minus text-[10px]"></i>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        @if ($members->hasPages())
                            <div class="mt-4">
                                {{ $members->links() }}
                            </div>
                        @endif
                    @else
                        <div class="text-center py-12">
                            <div class="relative w-14 h-14 mx-auto mb-4">
                                <div class="absolute inset-0 bg-blue-400/20 rounded-xl blur-lg"></div>
                                <div class="relative w-full h-full rounded-xl bg-white/80 flex items-center justify-center">
                                    <i class="fas fa-user-slash text-xl text-blue-300"></i>
                                </div>
                            </div>
                            <p class="text-sm font-semibold text-gray-900 mb-1">Belum ada anggota</p>
                            <p class="text-xs text-gray-400">Anggota belum ditambahkan ke divisi ini</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
