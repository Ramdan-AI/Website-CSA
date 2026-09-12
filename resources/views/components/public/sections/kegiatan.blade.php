{{-- KEGIATAN --}}
<section id="kegiatan" class="relative py-20 md:py-32 lg:py-40 bg-[#fafafa] scroll-mt-24 overflow-hidden">
    <div class="absolute top-24 right-[-120px] w-[340px] h-[340px] rounded-full bg-[radial-gradient(circle,rgba(54,100,175,0.05)_0%,transparent_70%)] pointer-events-none hidden lg:block" data-parallax="-110"></div>
    <div class="max-w-[1200px] mx-auto px-5 sm:px-8">
        <div class="mb-10 sm:mb-16" data-parallax="-10">
            <p class="text-[11px] sm:text-xs font-semibold text-[#3664AF] tracking-[0.15em] uppercase mb-4 sm:mb-6" data-reveal="down">Kegiatan</p>
            <h2 class="text-[clamp(1.75rem,5.5vw,3rem)] font-bold text-gray-900 leading-[1.15] lg:leading-[1.1] tracking-[-0.03em] max-w-[600px]" data-reveal="curtain" data-reveal-delay="0.15">
                Berita dan aktivitas terkini
            </h2>
        </div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5 sm:gap-8" data-reveal-stagger data-parallax="12">
            @forelse($news as $item)
                <div class="rounded-[20px] sm:rounded-[24px] bg-white border border-gray-100 overflow-hidden hover:shadow-[0_8px_32px_-12px_rgba(0,0,0,0.08)] transition-shadow duration-300">
                    <div class="h-44 sm:h-48 bg-gray-50 flex items-center justify-center overflow-hidden">
                        @if($item->thumbnail)
                            <img src="{{ asset('storage/' . $item->thumbnail) }}" alt="{{ $item->title }}" class="w-full h-full object-cover" loading="lazy" decoding="async">
                        @else
                            <i class="fas fa-newspaper text-3xl sm:text-4xl text-gray-300"></i>
                        @endif
                    </div>
                    <div class="p-5 sm:p-6">
                        @if($item->category)
                            <span class="text-[10px] font-semibold text-[#3664AF] uppercase tracking-wider">{{ $item->category->name }}</span>
                        @endif
                        <h3 class="text-[14px] sm:text-[15px] font-bold text-gray-900 mt-1.5 mb-2 leading-snug">{{ $item->title }}</h3>
                        <p class="text-xs text-gray-400 leading-relaxed">{{ Str::limit(strip_tags($item->content), 120) }}</p>
                    </div>
                </div>
            @empty
                <div class="sm:col-span-2 lg:col-span-3 text-center py-16">
                    <p class="text-sm text-gray-400">Belum ada berita yang dipublikasikan.</p>
                </div>
            @endforelse
        </div>
    </div>
</section>
