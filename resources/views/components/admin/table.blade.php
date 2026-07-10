@props([
    'headers' => [],
    'emptyIcon' => 'fa-folder-open',
    'emptyTitle' => 'Tidak ada data',
    'emptyDescription' => 'Belum ada data yang ditambahkan.',
    'createRoute' => null,
    'createLabel' => 'Tambah Data',
    'hasPages' => false,
    'paginator' => null,
])

<div class="relative bg-white/80 backdrop-blur-xl rounded-[32px] border border-white/60 shadow-[0_8px_32px_rgba(59,130,246,0.08)] overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-br from-blue-400/5 to-purple-400/5 pointer-events-none"></div>
    
    <div class="relative overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b border-gray-100/50">
                    @foreach($headers as $header)
                        <th class="text-left py-5 px-6 text-[11px] font-bold text-gray-400 uppercase tracking-widest {{ $header['width'] ?? '' }}">
                            {{ $header['label'] }}
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100/50">
                {{ $slot }}
            </tbody>
        </table>
    </div>
    
    @if($hasPages && $paginator)
        <div class="relative px-6 py-4 border-t border-gray-100/50">
            {{ $paginator->links() }}
        </div>
    @endif
</div>