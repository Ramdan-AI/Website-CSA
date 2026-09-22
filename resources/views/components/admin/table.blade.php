@props([
    'headers' => [],
    'emptyIcon' => 'folder-open',
    'emptyTitle' => 'Tidak ada data',
    'emptyDescription' => 'Belum ada data yang ditambahkan.',
    'createRoute' => null,
    'createLabel' => 'Tambah Data',
    'hasPages' => false,
    'paginator' => null,
])

<div {{ $attributes->merge(['class' => 'csa-card overflow-hidden']) }}>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr>
                    @foreach($headers as $header)
                        <th class="csa-table-th {{ $header['width'] ?? '' }}">
                            {{ $header['label'] }}
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @if (trim($slot) === '')
                    <x-admin.empty-state
                        :icon="$emptyIcon"
                        :title="$emptyTitle"
                        :description="$emptyDescription"
                        :create-route="$createRoute"
                        :create-label="$createLabel"
                        :colspan="max(count($headers), 1)" />
                @else
                    {{ $slot }}
                @endif
            </tbody>
        </table>
    </div>

    @if($hasPages && $paginator)
        <div class="px-4 py-3 border-t border-[var(--border-soft)]">
            {{ $paginator->links() }}
        </div>
    @endif
</div>
