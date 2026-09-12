@props([
    'icon' => 'fa-folder-open',
    'title' => 'Tidak ada data',
    'description' => 'Belum ada data yang ditambahkan.',
    'createRoute' => null,
    'createLabel' => 'Tambah Data',
    'colspan' => 4,
])

<tr>
    <td colspan="{{ $colspan }}" class="py-24 text-center">
        <div class="relative w-16 h-16 mx-auto mb-5">
            <div class="absolute inset-0 bg-blue-400/20 rounded-2xl blur-xl"></div>
            <div
                class="relative w-full h-full rounded-2xl bg-white/80 backdrop-blur-sm border border-white/60 flex items-center justify-center">
                <i class="fas {{ $icon }} text-2xl text-blue-400"></i>
            </div>
        </div>
        <p class="text-sm font-semibold text-gray-900 mb-1">{{ $title }}</p>
        <p class="text-xs text-gray-400 mb-5">{{ $description }}</p>
        @if ($createRoute)
            <a href="{{ $createRoute }}"
                class="relative inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-blue-500 to-blue-600 text-white text-xs font-semibold rounded-xl shadow-[0_4px_16px_rgba(59,130,246,0.3)] hover:shadow-[0_4px_20px_rgba(59,130,246,0.45)] hover:scale-105 transition-all duration-300">
                <i class="fas fa-plus text-[10px]"></i>
                {{ $createLabel }}
            </a>
        @endif
    </td>
</tr>
