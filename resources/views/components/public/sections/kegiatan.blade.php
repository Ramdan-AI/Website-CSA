{{-- Berita: satu feature besar + grid kartu editorial --}}
@php
    $featured = $news->first();
    $rest = $news->skip(1)->take(4);
    $fmt = fn ($n) => $n->published_at?->translatedFormat('d M Y') ?? $n->created_at->translatedFormat('d M Y');
@endphp

<section id="kegiatan" class="scroll-mt-16" style="background: var(--paper); border-top: 1px solid var(--line);">
    <div class="max-w-[1200px] mx-auto px-5 sm:px-8 py-16 sm:py-24">

        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-5 mb-12">
            <div class="max-w-[680px]" data-reveal="up">
                <p class="text-[13px] font-medium mb-3 inline-flex items-center gap-2" style="color: var(--accent);">
                    <span class="w-6 h-px" style="background: var(--accent);"></span> Publikasi
                </p>
                <h2 class="text-[clamp(1.7rem,3.8vw,2.4rem)] font-bold leading-[1.22] tracking-[-0.025em]" style="color: var(--ink);">
                    Berita terbaru
                </h2>
                <p class="text-[16px] leading-[1.8] mt-4" style="color: var(--ink-soft);">
                    Kabar kegiatan, program kerja, dan pencapaian anggota CSA.
                </p>
            </div>
            <a href="{{ route('berita') }}"
                class="shrink-0 inline-flex items-center gap-2 text-[15px] font-medium hover:gap-3 transition-all" style="color: var(--accent);">
                Lihat semua berita
                <i class="fas fa-arrow-right text-[12px]"></i>
            </a>
        </div>

        @if ($featured)
            <div class="grid lg:grid-cols-12 gap-5">
                {{-- Feature --}}
                <a href="{{ route('berita.show', $featured->slug) }}" data-reveal="up"
                    class="lg:col-span-7 group relative flex flex-col rounded-2xl border overflow-hidden transition-colors hover:border-[var(--line-strong)]"
                    style="background: var(--paper); border-color: var(--line);">
                    <div class="aspect-[16/10] overflow-hidden" style="background: var(--paper-sunken);">
                        @if ($featured->thumbnail_url)
                            <img src="{{ $featured->thumbnail_url }}" alt="{{ $featured->title }}"
                                width="960" height="600" fetchpriority="high" decoding="async"
                                class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-[1.03]">
                        @else
                            <div class="w-full h-full flex items-center justify-center" style="color: var(--ink-faint);">
                                <i class="fas fa-newspaper text-4xl"></i>
                            </div>
                        @endif
                    </div>
                    <div class="p-6 sm:p-8 flex flex-col flex-1">
                        @if ($featured->category)
                            <span class="text-[13px] font-medium" style="color: var(--accent);">{{ $featured->category->name }}</span>
                        @endif
                        <h3 class="text-[22px] sm:text-[26px] font-bold mt-2.5 leading-[1.28] tracking-[-0.02em] group-hover:text-[var(--accent)] transition-colors" style="color: var(--ink);">
                            {{ $featured->title }}
                        </h3>
                        <p class="text-[15px] leading-[1.8] mt-3 line-clamp-2" style="color: var(--ink-soft);">
                            {{ $featured->excerpt ?? Str::limit(strip_tags($featured->content), 160) }}
                        </p>
                        <div class="flex items-center gap-3 mt-auto pt-5 text-[13px]" style="color: var(--ink-faint);">
                            <span class="font-medium" style="color: var(--ink-soft);">{{ $featured->author?->name ?? 'CSA' }}</span>
                            <span class="w-1 h-1 rounded-full" style="background: var(--line-strong);"></span>
                            <span>{{ $fmt($featured) }}</span>
                        </div>
                    </div>
                </a>

                {{-- Empat kartu --}}
                @if ($rest->isNotEmpty())
                    <div class="lg:col-span-5 flex flex-col gap-5" data-reveal-stagger>
                        @foreach ($rest as $item)
                            <a href="{{ route('berita.show', $item->slug) }}"
                                class="group flex gap-4 rounded-2xl border p-3 transition-colors hover:border-[var(--line-strong)]"
                                style="background: var(--paper); border-color: var(--line);">
                                <div class="w-24 h-24 sm:w-28 sm:h-28 shrink-0 rounded-xl overflow-hidden" style="background: var(--paper-sunken);">
                                    @if ($item->thumbnail_url)
                                        <img src="{{ $item->thumbnail_url }}" alt="{{ $item->title }}"
                                            width="240" height="240" loading="lazy" decoding="async"
                                            class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-[1.05]">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center" style="color: var(--ink-faint);">
                                            <i class="fas fa-newspaper text-xl"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="min-w-0 flex flex-col justify-center py-1">
                                    @if ($item->category)
                                        <span class="text-[12px] font-medium" style="color: var(--accent);">{{ $item->category->name }}</span>
                                    @endif
                                    <h3 class="text-[15px] font-semibold mt-1.5 leading-[1.4] line-clamp-2 group-hover:text-[var(--accent)] transition-colors" style="color: var(--ink);">
                                        {{ $item->title }}
                                    </h3>
                                    <p class="text-[12px] mt-2" style="color: var(--ink-faint);">{{ $fmt($item) }}</p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        @else
            <div class="text-center py-16 rounded-2xl border" style="background: var(--paper-sunken); border-color: var(--line);">
                <i class="fas fa-newspaper text-3xl" style="color: var(--line-strong);"></i>
                <p class="text-[15px] mt-4" style="color: var(--ink-soft);">Belum ada berita yang dipublikasikan.</p>
            </div>
        @endif

    </div>
</section>
