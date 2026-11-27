@props(['columns'])

<div class="grid w-full grid-cols-1 gap-y-6 sm:grid-cols-2 lg:grid-cols-4 lg:gap-y-0">
    @foreach ($columns as $boxes)
        <div class="relative flex flex-col items-center px-2">
            <span class="absolute top-0 hidden h-px bg-[var(--line-strong)] lg:block {{ $loop->first ? 'left-1/2' : 'left-0' }} {{ $loop->last ? 'right-1/2' : 'right-0' }}" aria-hidden="true"></span>
            <div class="hidden lg:block">
                <x-public.org.arrow />
            </div>

            @foreach ($boxes as $box)
                @unless ($loop->first)
                    <x-public.org.arrow />
                @endunless
                <x-public.org.box :box="$box" />
            @endforeach
        </div>
    @endforeach
</div>
