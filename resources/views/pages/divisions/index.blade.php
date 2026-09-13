@extends('layouts.admin')

@section('content')
<div class="space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest">Data Master</p>
            <h1 class="text-3xl font-bold text-gray-900 mt-1">Divisi</h1>
            <p class="text-sm text-gray-400 mt-1.5 font-medium">Kelola cabang departemen atau divisi kegiatan organisasi (Maksimal 5 divisi).</p>
        </div>
        @if($canCreate)
            <a href="{{ route('admin.divisions.create') }}"
               class="inline-flex items-center gap-2 px-5 py-2.5 bg-gray-900 text-white text-sm font-semibold rounded-xl hover:bg-gray-800 transition-colors shadow-sm">
                <i class="fas fa-plus text-xs"></i>
                Tambah Divisi
            </a>
        @else
            <button disabled class="inline-flex items-center gap-2 px-5 py-2.5 bg-gray-200 text-gray-400 text-sm font-semibold rounded-xl cursor-not-allowed">
                <i class="fas fa-lock text-xs"></i>
                Maksimal 5 Divisi Tercapai
            </button>
        @endif
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($divisions as $division)
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 flex flex-col justify-between">
                <div>
                    <div class="flex items-start justify-between gap-4 mb-4">
                        <div class="flex items-center gap-3">
                            @if ($division->image)
                                <img src="{{ asset('storage/' . $division->image) }}" alt="{{ $division->name }}" class="w-12 h-12 rounded-xl object-cover border border-gray-100">
                            @else
                                <div class="w-12 h-12 rounded-xl bg-gray-100 flex items-center justify-center text-gray-400 font-bold">
                                    {{ substr($division->name, 0, 2) }}
                                </div>
                            @endif
                            <div>
                                <h3 class="text-base font-bold text-gray-900">{{ $division->name }}</h3>
                                <span class="text-xs text-gray-400">Ketua: {{ $division->leader_name ?? 'Belum ada' }}</span>
                            </div>
                        </div>

                        <div>
                            @if ($division->is_active)
                                <span class="px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-600 text-xs font-semibold">Aktif</span>
                            @else
                                <span class="px-2.5 py-1 rounded-full bg-gray-100 text-gray-500 text-xs font-semibold">Nonaktif</span>
                            @endif
                        </div>
                    </div>

                    @if($division->description)
                        <p class="text-xs text-gray-500 line-clamp-2 mb-4 leading-relaxed">{{ $division->description }}</p>
                    @endif

                    @if($division->skills)
                        <div class="flex flex-wrap gap-1.5 mb-6">
                            @foreach($division->skills as $skill)
                                <span class="px-2 py-0.5 bg-gray-100 text-gray-600 text-[10px] font-medium rounded-md">{{ $skill }}</span>
                            @endforeach
                        </div>
                    @endif
                </div>

                <div class="pt-4 border-t border-gray-100 flex items-center justify-between text-xs">
                    <a href="{{ route('admin.divisions.members', $division) }}" class="font-semibold text-blue-600 hover:underline">
                        <i class="fas fa-users mr-1"></i> {{ $division->members_count }} Anggota
                    </a>
                    
                    <div class="flex items-center gap-1">
                        <a href="{{ route('admin.divisions.edit', $division) }}" class="w-8 h-8 rounded-lg bg-gray-50 flex items-center justify-center text-gray-600 hover:bg-gray-100" title="Edit">
                            <i class="fas fa-pen text-xs"></i>
                        </a>
                        <button onclick="openDeleteModal('deleteModal', '{{ route('admin.divisions.destroy', $division) }}')" class="w-8 h-8 rounded-lg bg-red-50 flex items-center justify-center text-red-500 hover:bg-red-100" title="Hapus">
                            <i class="fas fa-trash text-xs"></i>
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white rounded-2xl border border-gray-200 p-12 text-center text-gray-400">
                <i class="fas fa-sitemap text-4xl mb-3"></i>
                <p class="text-sm font-semibold">Belum ada divisi tercatat.</p>
            </div>
        @endforelse
    </div>

</div>
@endsection
