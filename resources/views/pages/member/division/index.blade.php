@extends('layouts.admin')

@section('content')
<div class="space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
        <div>
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest">Keanggotaan</p>
            <h1 class="text-3xl font-bold text-gray-900 mt-1">Anggota Divisi {{ $division->name }}</h1>
            <p class="text-sm text-gray-400 mt-1.5 font-medium">Daftar anggota yang tergabung dalam divisi Anda.</p>
        </div>
        <a href="{{ route('admin.events.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-900 text-white text-xs font-semibold rounded-xl hover:bg-gray-800 transition-colors">
            <i class="fas fa-calendar mr-1"></i> Kegiatan Divisi
        </a>
    </div>

    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
        <div class="p-5 border-b border-gray-100 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
            <div>
                <h2 class="text-base font-bold text-gray-900">Daftar Anggota ({{ $members->total() }})</h2>
                <p class="text-xs text-gray-400 mt-1">Klik nama untuk melihat profil</p>
            </div>
            <form method="GET" class="flex gap-2">
                <input type="search" name="search" value="{{ request('search') }}" placeholder="Cari nama/NIM..." class="h-10 px-3 rounded-xl border border-gray-200 text-sm bg-gray-50 focus:bg-white focus:outline-none focus:border-gray-400">
                <button class="h-10 px-3 rounded-lg bg-gray-100 text-gray-700 text-xs font-semibold hover:bg-gray-200">Cari</button>
            </form>
        </div>

        <div class="divide-y divide-gray-100">
            @forelse($members as $member)
                <a href="{{ route('admin.members.show', $member) }}" class="block p-4 flex items-center gap-4 hover:bg-gray-50 transition-colors">
                    <div class="relative shrink-0">
                        <div class="w-12 h-12 rounded-full overflow-hidden border border-gray-100 bg-gray-100 flex items-center justify-center">
                            @if($member->photo)
                                <img src="{{ asset('storage/' . $member->photo) }}" alt="{{ $member->name }}" class="w-full h-full object-cover">
                            @else
                                <span class="text-2xl font-bold text-gray-400">{{ substr($member->name, 0, 1) }}</span>
                            @endif
                        </div>
                        @if($member->is_active)
                            <span class="absolute -bottom-1 -right-1 w-3 h-3 rounded-full bg-emerald-500 border-2 border-white"></span>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-gray-900 truncate">{{ $member->name }}</p>
                        <p class="text-xs text-gray-400 truncate">{{ $member->nim ?? 'Belum ada NIM' }}</p>
                    </div>
                    <div class="flex items-center gap-2 text-right shrink-0">
                        <span class="px-2 py-1 rounded-lg text-xs font-semibold bg-teal-50 text-teal-700">{{ $member->position?->name ?? 'Anggota' }}</span>
                        <i class="fas fa-chevron-right text-gray-300 text-xs"></i>
                    </div>
                </a>
            @empty
                <div class="p-10 text-center text-sm text-gray-400">
                    <i class="fas fa-users text-3xl mb-2"></i>
                    <p>Belum ada anggota di divisi ini.</p>
                </div>
            @endforelse
        </div>

        @if($members->hasPages())
            <div class="p-5 border-t border-gray-100">
                {{ $members->links() }}
            </div>
        @endif
    </div>

</div>
@endsection