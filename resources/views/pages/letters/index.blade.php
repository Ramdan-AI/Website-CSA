@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h1 class="text-[26px] sm:text-[30px] font-bold text-[var(--text-primary)] tracking-[-0.03em]">Surat & Arsip</h1>
            <p class="text-[13px] text-[var(--text-muted)] mt-1">Catat surat masuk dan keluar organisasi.</p>
        </div>
        <a href="{{ route('admin.letters.create') }}" class="csa-btn csa-btn-primary self-start sm:self-auto">
            <x-admin.icon name="plus" :size="16" /> Catat Surat
        </a>
    </div>

    @if (session('success'))
        <div class="csa-panel p-3.5 text-[13px] text-emerald-600 dark:text-emerald-400">{{ session('success') }}</div>
    @endif

    <div class="grid grid-cols-2 gap-3">
        <div class="csa-panel p-4">
            <p class="csa-stat-label">Surat Masuk</p>
            <p class="csa-stat-number mt-2">{{ number_format($counts['incoming']) }}</p>
        </div>
        <div class="csa-panel p-4">
            <p class="csa-stat-label">Surat Keluar</p>
            <p class="csa-stat-number mt-2">{{ number_format($counts['outgoing']) }}</p>
        </div>
    </div>

    <div class="flex flex-col gap-3 lg:flex-row lg:items-center">
        <x-admin.search-bar name="search" :value="request('search')" placeholder="Cari nomor, perihal, atau pengirim..." class="lg:max-w-md flex-1" />

        <div class="inline-flex items-center gap-1 p-1 rounded-full bg-[var(--bg-muted)] lg:ml-auto w-fit">
            @foreach (['' => 'Semua', 'incoming' => 'Masuk', 'outgoing' => 'Keluar'] as $value => $label)
                <a href="{{ route('admin.letters.index', array_filter(['type' => $value, 'search' => request('search')])) }}" data-ajax-link
                    class="px-3.5 py-1.5 rounded-full text-[12px] font-medium transition-colors {{ $type === $value ? 'bg-[var(--bg-card)] text-[var(--text-primary)] shadow-sm' : 'text-[var(--text-muted)] hover:text-[var(--text-primary)]' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>
    </div>

    <x-admin.table :headers="[
        ['label' => 'Nomor'],
        ['label' => 'Perihal'],
        ['label' => 'Jenis'],
        ['label' => 'Tanggal'],
        ['label' => 'Aksi', 'width' => 'w-24'],
    ]"
        empty-icon="file-text"
        empty-title="Belum ada surat tercatat"
        empty-description="Catat surat masuk atau keluar pertama."
        :create-route="route('admin.letters.create')"
        create-label="Catat Surat"
        :has-pages="$letters->hasPages()"
        :paginator="$letters">
        @forelse ($letters as $letter)
            <tr class="csa-table-row">
                <td class="csa-table-td font-mono text-[12px] whitespace-nowrap">{{ $letter->reference_number }}</td>
                <td class="csa-table-td">
                    <p class="text-[13px] font-medium text-[var(--text-primary)]">{{ $letter->subject }}</p>
                    @if ($letter->correspondent)
                        <p class="text-[12px] text-[var(--text-muted)] mt-0.5">{{ $letter->correspondent }}</p>
                    @endif
                </td>
                <td class="csa-table-td">
                    <span class="csa-chip {{ $letter->type === 'incoming' ? 'csa-chip-blue' : 'csa-chip-green' }}">{{ $letter->type_label }}</span>
                </td>
                <td class="csa-table-td whitespace-nowrap">{{ $letter->letter_date->translatedFormat('d M Y') }}</td>
                <td class="csa-table-td">
                    <div class="flex items-center gap-1">
                        @if ($letter->attachment_url)
                            <a href="{{ $letter->attachment_url }}" target="_blank" rel="noopener" class="csa-icon-btn" title="Lampiran" aria-label="Lampiran">
                                <x-admin.icon name="download" :size="16" />
                            </a>
                        @endif
                        <a href="{{ route('admin.letters.edit', $letter) }}" class="csa-icon-btn" title="Edit" aria-label="Edit surat">
                            <x-admin.icon name="pen" :size="16" />
                        </a>
                        <button type="button" onclick="openDeleteModal('deleteModal', '{{ route('admin.letters.destroy', $letter) }}')" class="csa-icon-btn hover:!text-red-500" title="Hapus" aria-label="Hapus surat">
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
