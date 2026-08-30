@extends('layouts.admin')

@section('content')
<div class="space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
        <div>
            <p class="text-xs font-semibold text-cyan-400 uppercase tracking-widest">Kegiatan</p>
            <h1 class="text-3xl font-bold text-gray-900 mt-1">Pendaftaran</h1>
            <p class="text-sm text-gray-400 mt-1.5 font-medium">Kelola seluruh data registrasi member terhadap event.</p>
        </div>
        <a href="{{ route('admin.event-registrations.create') }}"
           class="relative inline-flex items-center gap-2 px-5 py-3 bg-gradient-to-r from-cyan-500 to-cyan-600 text-white text-sm font-semibold rounded-2xl shadow-[0_4px_20px_rgba(6,182,212,0.3)] hover:shadow-[0_4px_25px_rgba(6,182,212,0.45)] hover:scale-105 transition-all duration-300 overflow-hidden group">
            <div class="absolute inset-0 bg-white/20 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
            <span class="relative flex items-center gap-2">
                <i class="fas fa-plus text-xs"></i>
                Tambah Registrasi
            </span>
        </a>
    </div>

    <x-admin.table :headers="[
        ['label' => 'Event', 'width' => ''],
        ['label' => 'Member', 'width' => ''],
        ['label' => 'Terdaftar Pada', 'width' => ''],
        ['label' => 'Aksi', 'width' => 'w-24'],
    ]" :hasPages="$registrations->hasPages()" :paginator="$registrations">
        @forelse($registrations as $registration)
            <tr class="hover:bg-cyan-50/30 transition-colors duration-200">
                <td class="py-4 px-6">
                    <p class="text-sm font-bold text-gray-900">{{ $registration->event->title }}</p>
                </td>
                <td class="py-4 px-6">
                    <div class="flex items-center gap-2">
                        <div class="w-7 h-7 rounded-full overflow-hidden flex items-center justify-center ring-2 ring-white shrink-0 shadow-sm">
                            <img src="{{ $registration->member->photo_url ?? asset('storage/profile/profile-placeholder.png') }}" alt="{{ $registration->member->name }}" class="w-full h-full object-cover">
                        </div>
                        <span class="text-xs text-gray-600">{{ $registration->member->name }}</span>
                    </div>
                </td>
                <td class="py-4 px-6">
                    <span class="text-xs text-gray-600">{{ $registration->registered_at }}</span>
                </td>
                <td class="py-4 px-6">
                    <div class="flex items-center gap-1">
                        <a href="{{ route('admin.event-registrations.edit', $registration) }}"
                           class="w-8 h-8 rounded-lg flex items-center justify-center bg-cyan-50 text-cyan-600 hover:bg-cyan-500 hover:text-white transition-all duration-200 hover:shadow-[0_4px_12px_rgba(6,182,212,0.3)]"
                           title="Edit">
                            <i class="fas fa-pen text-[10px]"></i>
                        </a>
                        <button onclick="openDeleteModal('deleteModal', '{{ route('admin.event-registrations.destroy', $registration) }}')"
                                class="w-8 h-8 rounded-lg flex items-center justify-center bg-red-50 text-red-500 hover:bg-red-500 hover:text-white transition-all duration-200 hover:shadow-[0_4px_12px_rgba(239,68,68,0.3)]"
                                title="Hapus">
                            <i class="fas fa-trash text-[10px]"></i>
                        </button>
                    </div>
                </td>
            </tr>
        @empty
            <x-admin.empty-state icon="fa-clipboard-list" title="Belum ada pendaftaran"
                description="Belum ada registrasi event yang tercatat." :createRoute="route('admin.event-registrations.create')" createLabel="Tambah Registrasi"
                :colspan="4" />
        @endforelse
    </x-admin.table>
</div>
@endsection
