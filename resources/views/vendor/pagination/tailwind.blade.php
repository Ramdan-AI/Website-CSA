@if ($paginator->hasPages())
    @php
        $cell = 'inline-flex items-center justify-center w-10 h-10 rounded-xl text-[13px] transition-colors';
        $idle = 'bg-[#F4F7FB] text-slate-600 font-medium hover:bg-[#E8EEF7] hover:text-slate-900 dark:bg-white/5 dark:text-slate-300 dark:hover:bg-white/10 dark:hover:text-white';
        $current = 'bg-[#3664AF] text-white font-semibold dark:bg-[#8ab4f8] dark:text-[#0a0a0b]';
        $disabled = 'text-slate-300 cursor-not-allowed dark:text-slate-600';
        $muted = 'text-slate-600 dark:text-slate-400';
        $strong = 'font-semibold text-slate-900 dark:text-white';
    @endphp

    <nav role="navigation" aria-label="Navigasi halaman" class="flex flex-col sm:flex-row items-center justify-between gap-4">
        <p class="text-[13px] {{ $muted }} order-2 sm:order-1">
            Menampilkan
            <span class="{{ $strong }}">{{ $paginator->firstItem() }}</span>–<span class="{{ $strong }}">{{ $paginator->lastItem() }}</span>
            dari <span class="{{ $strong }}">{{ $paginator->total() }}</span>
        </p>

        <div class="flex items-center gap-1.5 order-1 sm:order-2">
            @if ($paginator->onFirstPage())
                <span aria-disabled="true" class="{{ $cell }} {{ $disabled }}">
                    <x-ui.chevron direction="left" />
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Halaman sebelumnya" class="{{ $cell }} {{ $idle }}">
                    <x-ui.chevron direction="left" />
                </a>
            @endif

            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="{{ $cell }} {{ $muted }}">{{ $element }}</span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span aria-current="page" class="{{ $cell }} {{ $current }}">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="{{ $cell }} {{ $idle }}">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Halaman berikutnya" class="{{ $cell }} {{ $idle }}">
                    <x-ui.chevron direction="right" />
                </a>
            @else
                <span aria-disabled="true" class="{{ $cell }} {{ $disabled }}">
                    <x-ui.chevron direction="right" />
                </span>
            @endif
        </div>
    </nav>
@endif
