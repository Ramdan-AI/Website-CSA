@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-4">
        <div>
            <a href="{{ route('admin.divisions.index') }}" class="inline-flex items-center gap-2 text-sm text-gray-400 hover:text-gray-700 mb-3">
                <i class="fas fa-arrow-left text-xs"></i>
                Kembali ke Divisi
            </a>
            <div class="flex items-center gap-4">
                @if ($division->image)
                    <img src="{{ asset('storage/' . $division->image) }}" alt="{{ $division->name }}" class="w-14 h-14 rounded-2xl object-cover border border-gray-200 bg-white">
                @else
                    <div class="w-14 h-14 rounded-2xl bg-gray-100 flex items-center justify-center text-gray-500 font-bold text-lg">
                        {{ strtoupper(substr($division->name, 0, 2)) }}
                    </div>
                @endif
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest">Divisi</p>
                    <h1 class="text-3xl font-bold text-gray-900">{{ $division->name }}</h1>
                    <p class="text-sm text-gray-400 mt-1">{{ $members->total() }} anggota</p>
                </div>
            </div>
        </div>
        <button type="button" onclick="document.getElementById('addMembersPanel').classList.toggle('hidden')"
            class="inline-flex items-center gap-2 px-5 py-2.5 bg-gray-900 text-white text-sm font-semibold rounded-xl hover:bg-gray-800 transition-colors">
            <i class="fas fa-user-plus text-xs"></i>
            Tambah Anggota
        </button>
    </div>

    <div id="addMembersPanel" class="hidden bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
        <div class="p-5 border-b border-gray-100 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
            <div>
                <h2 class="text-base font-bold text-gray-900">Tambah Anggota ke {{ $division->name }}</h2>
                <p class="text-xs text-gray-400 mt-1">Pilih beberapa anggota yang belum memiliki divisi, lalu simpan sekaligus.</p>
            </div>
            <form method="GET" action="{{ route('admin.divisions.members', $division) }}" class="flex gap-2">
                <input type="text" name="candidate_search" value="{{ request('candidate_search') }}" placeholder="Cari kandidat..." class="h-10 px-3 rounded-lg border border-gray-200 text-sm bg-gray-50 focus:outline-none focus:border-gray-400">
                <button class="h-10 px-3 rounded-lg bg-gray-100 text-gray-700 text-xs font-semibold hover:bg-gray-200">Cari</button>
            </form>
        </div>
        <form method="POST" action="{{ route('admin.divisions.add-members', $division) }}">
            @csrf
            <div class="max-h-96 overflow-y-auto divide-y divide-gray-100">
                @forelse($availableMembers as $candidate)
                    <label class="flex items-center gap-3 p-4 hover:bg-gray-50 cursor-pointer">
                        <input type="checkbox" name="member_ids[]" value="{{ $candidate->id }}" class="rounded border-gray-300 text-gray-900 focus:ring-gray-900">
                        <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center text-xs font-bold text-gray-600 shrink-0">
                            {{ substr($candidate->name, 0, 1) }}
                        </div>
                        <div class="min-w-0">
                            <p class="text-sm font-semibold text-gray-900 truncate">{{ $candidate->name }}</p>
                            <p class="text-xs text-gray-400">{{ $candidate->nim ?: '-' }} · {{ $candidate->position?->name ?: 'Tanpa Jabatan' }}</p>
                        </div>
                    </label>
                @empty
                    <div class="p-8 text-center text-sm text-gray-400">Tidak ada kandidat anggota tersedia.</div>
                @endforelse
            </div>
            <div class="p-5 border-t border-gray-100 flex justify-end gap-2">
                <button type="button" onclick="document.getElementById('addMembersPanel').classList.add('hidden')" class="px-4 py-2 rounded-lg bg-gray-100 text-gray-600 text-xs font-semibold hover:bg-gray-200">Batal</button>
                <button type="submit" class="px-4 py-2 rounded-lg bg-gray-900 text-white text-xs font-semibold hover:bg-gray-800">Tambahkan Terpilih</button>
            </div>
        </form>
    </div>

    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
        <div class="p-5 border-b border-gray-100 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
            <div>
                <h2 class="text-base font-bold text-gray-900">Anggota Saat Ini</h2>
                <p class="text-xs text-gray-400 mt-1">Data anggota yang tergabung di divisi ini.</p>
            </div>
            <form method="GET" action="{{ route('admin.divisions.members', $division) }}" class="flex gap-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama/NIM/email..." class="h-10 px-3 rounded-lg border border-gray-200 text-sm bg-gray-50 focus:outline-none focus:border-gray-400">
                <button class="h-10 px-3 rounded-lg bg-gray-100 text-gray-700 text-xs font-semibold hover:bg-gray-200">Cari</button>
                @if(request()->hasAny(['search', 'candidate_search']))
                    <a href="{{ route('admin.divisions.members', $division) }}" class="h-10 px-3 rounded-lg bg-gray-50 text-gray-500 text-xs font-semibold flex items-center hover:bg-gray-100">Reset</a>
                @endif
            </form>
        </div>

        <div class="divide-y divide-gray-100">
            @forelse ($members as $member)
                <div class="p-4 flex flex-col md:flex-row md:items-center md:justify-between gap-3 hover:bg-gray-50">
                    <div class="flex items-center gap-3 min-w-0">
                        <img src="{{ $member->photo_url ?? asset('storage/profile/profile-placeholder.png') }}" alt="{{ $member->name }}" class="w-10 h-10 rounded-full object-cover bg-gray-100 border border-gray-100 shrink-0">
                        <div class="min-w-0">
                            <p class="text-sm font-semibold text-gray-900 truncate">{{ $member->name }}</p>
                            <p class="text-xs text-gray-400">{{ $member->nim ?: '-' }} · {{ $member->position?->name ?? 'Tanpa Jabatan' }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-1">
                        <a href="{{ route('admin.members.edit', $member) }}" class="w-8 h-8 rounded-lg flex items-center justify-center bg-gray-50 text-gray-600 hover:bg-gray-100" title="Edit">
                            <i class="fas fa-pen text-[10px]"></i>
                        </a>
                        <button type="button" onclick="openDeleteModal('deleteModal', '{{ route('admin.divisions.remove-member', [$division, $member]) }}')" class="w-8 h-8 rounded-lg flex items-center justify-center bg-red-50 text-red-500 hover:bg-red-100" title="Keluarkan">
                            <i class="fas fa-user-minus text-[10px]"></i>
                        </button>
                    </div>
                </div>
            @empty
                <div class="p-10 text-center text-sm text-gray-400">Belum ada anggota pada divisi ini.</div>
            @endforelse
        </div>

        @if ($members->hasPages())
            <div class="p-5 border-t border-gray-100">
                {{ $members->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
