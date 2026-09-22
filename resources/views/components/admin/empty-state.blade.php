@props([
    'icon' => 'folder-open',
    'title' => 'Tidak ada data',
    'description' => 'Belum ada data yang ditambahkan.',
    'createRoute' => null,
    'createLabel' => 'Tambah Data',
    'colspan' => 4,
])

<tr>
    <td colspan="{{ $colspan }}" class="py-16 text-center">
        <div class="w-12 h-12 mx-auto mb-4 rounded-xl bg-[var(--bg-muted)] flex items-center justify-center text-[var(--text-muted)]">
            <x-admin.icon :name="$icon" :size="22" />
        </div>
        <p class="text-[13px] font-medium text-[var(--text-primary)]">{{ $title }}</p>
        <p class="text-[12px] text-[var(--text-muted)] mt-1">{{ $description }}</p>
        @if ($createRoute)
            <a href="{{ $createRoute }}" class="csa-btn csa-btn-primary mt-4 inline-flex">
                <x-admin.icon name="plus" :size="15" />
                {{ $createLabel }}
            </a>
        @endif
    </td>
</tr>
