{{-- Berita terbaru --}}
<section id="kegiatan" class="bg-white scroll-mt-16">
    <div class="max-w-[1200px] mx-auto px-5 sm:px-8 py-16 sm:py-20">
        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mb-10">
            <div class="max-w-[680px]">
                <h2 class="text-[clamp(1.6rem,3.6vw,2.25rem)] font-bold text-gray-900 leading-[1.25]">
                    Berita terbaru
                </h2>
                <p class="text-[16px] text-gray-600 leading-[1.75] mt-4">
                    Kegiatan dan kabar terbaru dari CSA.
                </p>
            </div>
            <a href="{{ route('berita') }}" class="shrink-0 text-[15px] font-medium text-[#1a73e8] hover:underline">
                Lihat semua berita
            </a>
        </div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($news as $item)
                <a href="{{ route('berita.show', $item->slug ?? $item->id) }}"
                    class="group flex flex-col rounded-xl border border-gray-200 overflow-hidden hover:border-gray-300 transition-colors">
                    <div class="aspect-[16/10] bg-gray-100 overflow-hidden">
                        @if($item->thumbnail)
                            <img src="{{ asset('storage/' . $item->thumbnail) }}" alt="{{ $item->title }}"
                                width="640" height="400" class="w-full h-full object-cover" loading="lazy" decoding="async">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-300">
                                <i class="fas fa-newspaper text-3xl"></i>
                            </div>
                        @endif
                    </div>
                    <div class="p-5 flex flex-col flex-1">
                        @if($item->category)
                            <span class="text-[13px] font-medium text-[#1a73e8]">{{ $item->category->name }}</span>
                        @endif
                        <h3 class="text-[17px] font-semibold text-gray-900 mt-2 leading-[1.4] line-clamp-2">{{ $item->title }}</h3>
                        <p class="text-[14px] text-gray-600 leading-[1.7] mt-2 line-clamp-2">{{ Str::limit(strip_tags($item->content), 100) }}</p>
                        <p class="text-[13px] text-gray-500 mt-auto pt-4">
                            {{ $item->published_at?->translatedFormat('d M Y') ?? $item->created_at->translatedFormat('d M Y') }}
                        </p>
                    </div>
                </a>
            @empty
                <div class="sm:col-span-2 lg:col-span-3 text-center py-14">
                    <p class="text-[15px] text-gray-500">Belum ada berita yang dipublikasikan.</p>
                </div>
            @endforelse
        </div>
    </div>
</section>
