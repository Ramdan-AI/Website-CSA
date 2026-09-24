@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h1 class="text-[26px] sm:text-[30px] font-bold text-[var(--text-primary)] tracking-[-0.03em]">Anggota</h1>
            <p class="text-[13px] text-[var(--text-muted)] mt-1">Kelola data anggota dan struktur keanggotaan CSA.</p>
        </div>
        @can('manage-members')
            <a href="{{ route('admin.members.create') }}" class="csa-btn csa-btn-primary self-start sm:self-auto">
                <x-admin.icon name="plus" :size="16" /> Tambah Anggota
            </a>
        @endcan
    </div>

    <form method="GET" data-ajax-filter class="csa-panel p-3 sm:p-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-[1.6fr_1fr_1fr_1fr_1fr_auto] gap-3">
        <div class="relative">
            <x-admin.icon name="search" :size="17" class="absolute left-4 top-1/2 -translate-y-1/2 text-[var(--text-muted)] pointer-events-none" />
            <input type="search" name="search" value="{{ request('search') }}" placeholder="Cari nama, NIM, email..." class="csa-input !pl-[42px] !rounded-full csa-search-input" aria-label="Cari anggota">
        </div>
        <x-form.select name="division_id" :value="request('division_id')" onchange="this.form.submit()" aria-label="Filter divisi"
            placeholder="Semua Divisi"
            :options="collect($divisions)->mapWithKeys(fn ($d) => [$d->id => $d->name])->all()" />
        <x-form.select name="position_id" :value="request('position_id')" onchange="this.form.submit()" aria-label="Filter jabatan"
            placeholder="Semua Jabatan"
            :options="collect($positions)->mapWithKeys(fn ($p) => [$p->id => $p->name])->all()" />
        <x-form.select name="status" :value="request('status')" onchange="this.form.submit()" aria-label="Filter status"
            placeholder="Semua Status"
            :options="['active' => 'Aktif', 'inactive' => 'Nonaktif']" />
        <x-form.select name="sort" :value="request('sort', 'newest')" onchange="this.form.submit()" aria-label="Urutkan"
            :options="['newest' => 'Terbaru', 'name_asc' => 'Nama A-Z', 'name_desc' => 'Nama Z-A']" />
        <a href="{{ route('admin.members.index') }}" class="csa-btn csa-btn-ghost">Reset</a>
    </form>

    <x-admin.table
        :headers="[
            ['label' => 'NIM'],
            ['label' => 'Anggota'],
            ['label' => 'Kontak'],
            ['label' => 'Divisi'],
            ['label' => 'Jabatan'],
            ['label' => 'Status'],
            ['label' => 'Aksi', 'width' => 'w-24'],
        ]"
        empty-icon="users"
        empty-title="Belum ada anggota"
        empty-description="Tambahkan anggota pertama untuk memulai."
        :create-route="route('admin.members.create')"
        create-label="Tambah Anggota"
        :has-pages="$members->hasPages()"
        :paginator="$members"
    >
        @forelse($members as $member)
            <tr class="csa-table-row">
                <td class="csa-table-td">
                    <span class="text-[12px] font-mono text-[var(--text-secondary)]">{{ $member->nim ?? '—' }}</span>
                </td>
                <td class="csa-table-td">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full overflow-hidden bg-[var(--bg-muted)] shrink-0 {{ $member->photo_url ? 'cursor-pointer' : '' }}"
                             @if($member->photo_url) onclick="openImagePreview('imagePreviewModal', '{{ $member->photo_url }}', '{{ $member->name }}')" @endif>
                            <img src="{{ $member->photo_url ?? asset('storage/profile/profile-placeholder.png') }}" alt="{{ $member->name }}" class="w-full h-full object-cover">
                        </div>
                        <div class="min-w-0">
                            <p class="text-[13px] font-medium text-[var(--text-primary)] truncate">{{ $member->name }}</p>
                            @if($member->email)
                                <p class="text-[11px] text-[var(--text-muted)] truncate">{{ $member->email }}</p>
                            @endif
                        </div>
                    </div>
                </td>
                <td class="csa-table-td">
                    <div class="space-y-1 text-[12px]">
                        @if($member->email)
                            <p class="text-[var(--text-secondary)] truncate">{{ $member->email }}</p>
                        @endif
                        @if($member->phone)
                            <p class="text-[var(--text-muted)]">{{ $member->phone }}</p>
                        @endif
                        @if(!$member->email && !$member->phone)
                            <span class="text-[var(--text-muted)]">-</span>
                        @endif
                    </div>
                </td>
                <td class="csa-table-td">{{ $member->division->name ?? '-' }}</td>
                <td class="csa-table-td">{{ $member->position->name ?? 'Belum ada' }}</td>
                <td class="csa-table-td">
                    <span class="csa-chip {{ $member->is_active ? 'csa-chip-green' : '' }}">{{ $member->is_active ? 'Aktif' : 'Nonaktif' }}</span>
                </td>
                <td class="csa-table-td">
                    <div class="flex items-center gap-1">
                        <a href="{{ route('admin.members.show', $member) }}" class="csa-icon-btn" title="Detail" aria-label="Detail anggota">
                            <x-admin.icon name="eye" :size="16" />
                        </a>
                        <a href="{{ route('admin.members.edit', $member) }}" class="csa-icon-btn" title="Edit" aria-label="Edit anggota">
                            <x-admin.icon name="pen" :size="16" />
                        </a>
                        <button type="button" onclick="openDeleteModal('deleteModal', '{{ route('admin.members.destroy', $member) }}')" class="csa-icon-btn hover:!text-red-500" title="Hapus" aria-label="Hapus anggota">
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
