@extends('layouts.admin')

@section('content')
<div class="space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
        <div>
            <p class="text-xs font-semibold text-blue-400 uppercase tracking-widest">Keanggotaan</p>
            <h1 class="text-3xl font-bold text-gray-900 mt-1">Kehadiran</h1>
            <p class="text-sm text-gray-400 mt-1.5 font-medium">Kelola seluruh data absensi member pada event.</p>
        </div>
        <a href="{{ route('admin.attendances.create') }}"
           class="relative inline-flex items-center gap-2 px-5 py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white text-sm font-semibold rounded-2xl shadow-[0_4px_20px_rgba(59,130,246,0.3)] hover:shadow-[0_4px_25px_rgba(59,130,246,0.45)] hover:scale-105 transition-all duration-300 overflow-hidden group">
            <div class="absolute inset-0 bg-white/20 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
            <span class="relative flex items-center gap-2">
                <i class="fas fa-plus text-xs"></i>
                Tambah Absensi
            </span>
        </a>
    </div>

    <x-admin.table :headers="[
        ['label' => 'Event', 'width' => ''],
        ['label' => 'Member', 'width' => ''],
        ['label' => 'Status', 'width' => ''],
        ['label' => 'Check In', 'width' => ''],
        ['label' => 'Aksi', 'width' => 'w-24'],
    ]" :hasPages="$attendances->hasPages()" :paginator="$attendances">
        @forelse($attendances as $attendance)
            <tr class="hover:bg-blue-50/30 transition-colors duration-200">
                <td class="py-4 px-6">
                    <p class="text-sm font-bold text-gray-900">{{ $attendance->event->title }}</p>
                </td>
                <td class="py-4 px-6">
                    <div class="flex items-center gap-2">
                        <div class="w-7 h-7 rounded-full overflow-hidden flex items-center justify-center ring-2 ring-white shrink-0 shadow-sm">
                            <img src="{{ $attendance->member->photo_url ?? asset('storage/profile/profile-placeholder.png') }}" alt="{{ $attendance->member->name }}" class="w-full h-full object-cover">
                        </div>
                        <span class="text-xs text-gray-600">{{ $attendance->member->name }}</span>
                    </div>
                </td>
                <td class="py-4 px-6">
                    @if($attendance->status === 'present')
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-emerald-50 text-[11px] font-semibold text-emerald-600 border border-emerald-100/50">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 shadow-[0_0_6px_rgba(16,185,129,0.4)]"></span>
                            Hadir
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-red-50 text-[11px] font-semibold text-red-500 border border-red-100/50">
                            <span class="w-1.5 h-1.5 rounded-full bg-red-400"></span>
                            Absen
                        </span>
                    @endif
                </td>
                <td class="py-4 px-6">
                    <span class="text-xs font-mono text-gray-500">{{ $attendance->check_in_at ?? '-' }}</span>
                </td>
                <td class="py-4 px-6">
                    <div class="flex items-center gap-1">
                        <a href="{{ route('admin.attendances.edit', $attendance) }}"
                           class="w-8 h-8 rounded-lg flex items-center justify-center bg-blue-50 text-blue-600 hover:bg-blue-500 hover:text-white transition-all duration-200 hover:shadow-[0_4px_12px_rgba(59,130,246,0.3)]"
                           title="Edit">
                            <i class="fas fa-pen text-[10px]"></i>
                        </a>
                        <button onclick="openDeleteModal('deleteModal', '{{ route('admin.attendances.destroy', $attendance) }}')"
                                class="w-8 h-8 rounded-lg flex items-center justify-center bg-red-50 text-red-500 hover:bg-red-500 hover:text-white transition-all duration-200 hover:shadow-[0_4px_12px_rgba(239,68,68,0.3)]"
                                title="Hapus">
                            <i class="fas fa-trash text-[10px]"></i>
                        </button>
                    </div>
                </td>
            </tr>
        @empty
            <x-admin.empty-state icon="fa-user-check" title="Belum ada absensi"
                description="Belum ada data absensi yang tercatat." :createRoute="route('admin.attendances.create')" createLabel="Tambah Absensi"
                :colspan="5" />
        @endforelse
    </x-admin.table>
</div>
@endsection
