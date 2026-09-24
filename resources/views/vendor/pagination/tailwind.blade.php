@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Navigasi halaman" class="flex flex-col sm:flex-row items-center justify-between gap-4">
        <p class="text-[13px] text-gray-400 order-2 sm:order-1">
            Menampilkan
            <span class="font-semibold text-gray-900">{{ $paginator->firstItem() }}</span>–<span class="font-semibold text-gray-900">{{ $paginator->lastItem() }}</span>
            dari <span class="font-semibold text-gray-900">{{ $paginator->total() }}</span>
        </p>

        <div class="flex items-center gap-1.5 order-1 sm:order-2">
            {{-- Previous --}}
            @if ($paginator->onFirstPage())
                <span aria-disabled="true" class="inline-flex items-center justify-center w-10 h-10 rounded-xl text-gray-300 cursor-not-allowed">
                    <i class="fas fa-chevron-left text-[12px]"></i>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Halaman sebelumnya"
                    class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-[#F4F7FB] text-gray-500 hover:bg-[#E8EEF7] hover:text-gray-900 transition-colors">
                    <i class="fas fa-chevron-left text-[12px]"></i>
                </a>
            @endif

            {{-- Pages --}}
            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="inline-flex items-center justify-center w-10 h-10 text-gray-400 text-[13px]">{{ $element }}</span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span aria-current="page" class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-[#3664AF] text-white text-[13px] font-semibold">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-[#F4F7FB] text-gray-500 text-[13px] font-medium hover:bg-[#E8EEF7] hover:text-gray-900 transition-colors">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Halaman berikutnya"
                    class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-[#F4F7FB] text-gray-500 hover:bg-[#E8EEF7] hover:text-gray-900 transition-colors">
                    <i class="fas fa-chevron-right text-[12px]"></i>
                </a>
            @else
                <span aria-disabled="true" class="inline-flex items-center justify-center w-10 h-10 rounded-xl text-gray-300 cursor-not-allowed">
                    <i class="fas fa-chevron-right text-[12px]"></i>
                </span>
            @endif
        </div>
    </nav>
@endif
