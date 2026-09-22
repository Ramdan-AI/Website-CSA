@extends('layouts.admin')

@section('content')
<div class="space-y-4 sm:space-y-5">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h1 class="text-[26px] sm:text-[30px] font-bold text-[var(--text-primary)] tracking-[-0.03em]">Pendaftaran</h1>
            <p class="text-[13px] text-[var(--text-muted)] mt-1">Kelola seluruh data registrasi anggota terhadap kegiatan.</p>
        </div>
        <a href="{{ route('admin.event-registrations.create') }}" class="csa-btn csa-btn-primary">
            <x-admin.icon name="plus" :size="16" /> Tambah Registrasi
        </a>
    </div>

    <x-admin.table :headers="[
        ['label' => 'Kegiatan'],
        ['label' => 'Anggota'],
        ['label' => 'Terdaftar Pada'],
        ['label' => 'Aksi', 'width' => 'w-24'],
    ]"
        empty-icon="clipboard-check"
        empty-title="Belum ada pendaftaran"
        empty-description="Belum ada registrasi kegiatan yang tercatat."
        :create-route="route('admin.event-registrations.create')"
        create-label="Tambah Registrasi"
        :has-pages="$registrations->hasPages()"
        :paginator="$registrations">
        @forelse($registrations as $registration)
            <tr class="csa-table-row">
                <td class="csa-table-td font-medium text-[var(--text-primary)]">{{ $registration->event->title }}</td>
                <td class="csa-table-td">
                    <div class="flex items-center gap-2">
                        <img src="{{ $registration->member->photo_url ?? asset('storage/profile/profile-placeholder.png') }}" alt="{{ $registration->member->name }}" class="w-7 h-7 rounded-full object-cover shrink-0">
                        <span class="text-[12px] text-[var(--text-secondary)]">{{ $registration->member->name }}</span>
                    </div>
                </td>
                <td class="csa-table-td whitespace-nowrap">{{ $registration->registered_at }}</td>
                <td class="csa-table-td">
                    <div class="flex items-center gap-1">
                        <a href="{{ route('admin.event-registrations.edit', $registration) }}" class="csa-icon-btn" title="Edit" aria-label="Edit registrasi">
                            <x-admin.icon name="pen" :size="16" />
                        </a>
                        <button type="button" onclick="openDeleteModal('deleteModal', '{{ route('admin.event-registrations.destroy', $registration) }}')" class="csa-icon-btn hover:!text-red-500" title="Hapus" aria-label="Hapus registrasi">
                            <x-admin.icon name="trash-2" :size="16" />
                        </button>
                    </div>
                </td>
            </tr>
        @empty
        @endforelse
    </x-admin.table>
</div>
@endsection
