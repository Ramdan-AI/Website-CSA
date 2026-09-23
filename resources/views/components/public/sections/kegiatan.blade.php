{{-- Berita: satu feature + empat kartu --}}
<section id="kegiatan" class="scroll-mt-16" style="background: var(--paper-sunken); border-top: 1px solid var(--line);">
    <div class="max-w-[1200px] mx-auto px-5 sm:px-8 py-16 sm:py-24">

        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mb-12">
            <div class="max-w-[680px]">
                <p class="text-[13px] font-medium mb-3" style="color: var(--accent);">Kegiatan</p>
                <h2 class="text-[clamp(1.7rem,3.8vw,2.4rem)] font-bold leading-[1.22] tracking-[-0.025em]" style="color: var(--ink);">
                    Berita terbaru
                </h2>
            </div>
            <a href="{{ route('berita') }}" class="shrink-0 text-[15px] font-medium hover:underline" style="color: var(--accent);">
                Lihat semua berita
            </a>
        </div>

        @php $featured = $news->first(); $rest = $news->skip(1); @endphp

        @if ($featured)
            {{-- Feature --}}
            <a href="{{ route('berita.show', $featured->slug ?? $featured->id) }}"
                class="group grid lg:grid-cols-2 rounded-xl border overflow-hidden transition-colors hover:border-[var(--line-strong)]"
                style="background: var(--paper); border-color: var(--line);">
                <div class="aspect-[16/10] lg:aspect-auto lg:min-h-[320px] overflow-hidden" style="background: var(--paper-sunken);">
                    @if($featured->thumbnail)
                        <img src="{{ asset('storage/' . $featured->thumbnail) }}" alt="{{ $featured->title }}"
                            width="960" height="600" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-[1.02]" loading="lazy" decoding="async">
                    @else
                        <div class="w-full h-full flex items-center justify-center" style="color: var(--ink-faint);">
                            <i class="fas fa-newspaper text-4xl"></i>
                        </div>
                    @endif
                </div>
                <div class="p-6 sm:p-10 flex flex-col justify-center">
                    @if($featured->category)
                        <span class="text-[13px] font-medium" style="color: var(--accent);">{{ $featured->category->name }}</span>
                    @endif
                    <h3 class="text-[24px] sm:text-[30px] font-bold mt-3 leading-[1.25] tracking-[-0.025em]" style="color: var(--ink);">
                        {{ $featured->title }}
                    </h3>
                    <p class="text-[15px] sm:text-[16px] leading-[1.8] mt-4 line-clamp-3" style="color: var(--ink-soft);">
                        {{ Str::limit(strip_tags($featured->content), 180) }}
                    </p>
                    <div class="flex items-center gap-4 mt-6 text-[13px]" style="color: var(--ink-faint);">
                        <span>{{ $featured->author?->name ?? 'CSA' }}</span>
                        <span>·</span>
                        <span>{{ $featured->published_at?->translatedFormat('d M Y') ?? $featured->created_at->translatedFormat('d M Y') }}</span>
                    </div>
                </div>
            </a>

            {{-- Empat kartu --}}
            @if ($rest->isNotEmpty())
                <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-5 mt-5">
                    @foreach ($rest as $item)
                        <a href="{{ route('berita.show', $item->slug ?? $item->id) }}"
                            class="group flex flex-col rounded-xl border overflow-hidden transition-all duration-300 hover:-translate-y-1 hover:border-[var(--line-strong)]"
                            style="background: var(--paper); border-color: var(--line);">
                            <div class="aspect-[16/10] overflow-hidden" style="background: var(--paper-sunken);">
                                @if($item->thumbnail)
                                    <img src="{{ asset('storage/' . $item->thumbnail) }}" alt="{{ $item->title }}"
                                        width="480" height="300" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-[1.03]" loading="lazy" decoding="async">
                                @else
                                    <div class="w-full h-full flex items-center justify-center" style="color: var(--ink-faint);">
                                        <i class="fas fa-newspaper text-2xl"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="p-5 flex flex-col flex-1">
                                @if($item->category)
                                    <span class="text-[12px] font-medium" style="color: var(--accent);">{{ $item->category->name }}</span>
                                @endif
                                <h3 class="text-[15px] font-semibold mt-2 leading-[1.45] line-clamp-3" style="color: var(--ink);">
                                    {{ $item->title }}
                                </h3>
                                <p class="text-[13px] mt-auto pt-4" style="color: var(--ink-faint);">
                                    {{ $item->published_at?->translatedFormat('d M Y') ?? $item->created_at->translatedFormat('d M Y') }}
                                </p>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        @else
            <div class="text-center py-14 rounded-xl border" style="background: var(--paper); border-color: var(--line);">
                <p class="text-[15px]" style="color: var(--ink-soft);">Belum ada berita yang dipublikasikan.</p>
            </div>
        @endif

    </div>
</section>
