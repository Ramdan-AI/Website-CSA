@props(['chart', 'advisors'])

<div {{ $attributes->merge(['class' => 'flex flex-col items-center']) }}>
    @foreach ($advisors as $advisor)
        <x-public.org.box :box="$advisor" class="max-w-[300px]" />
        <x-public.org.arrow :head="! $loop->last" />
    @endforeach

    <div class="grid w-full grid-cols-1 gap-y-3 sm:grid-cols-2 lg:grid-cols-4 lg:gap-y-0">
        @foreach ($chart->leadership() as $box)
            <div class="relative flex flex-col items-center px-2 {{ $loop->first ? 'lg:col-start-2' : '' }}">
                <span class="absolute top-0 hidden h-px bg-[var(--line-strong)] lg:block {{ $loop->first ? 'left-1/2 right-0' : 'left-0 right-1/2' }}" aria-hidden="true"></span>
                <div class="hidden lg:block">
                    <x-public.org.arrow />
                </div>
                <x-public.org.box :box="$box" class="flex-1" />
            </div>
        @endforeach
    </div>

    <div class="hidden w-full grid-cols-4 lg:grid" aria-hidden="true">
        <span class="col-start-2 h-6 w-px justify-self-center bg-[var(--line-strong)]"></span>
        <span class="h-6 w-px justify-self-center bg-[var(--line-strong)]"></span>
    </div>
    <x-public.org.arrow class="lg:hidden" />

    <div class="relative flex w-full flex-col items-center">
        <span class="absolute inset-y-0 left-1/2 hidden w-px bg-[var(--line-strong)] lg:block" aria-hidden="true"></span>
        <x-public.org.branch :columns="$chart->officers()" />
        <x-public.org.arrow class="lg:hidden" />
        <div class="w-full lg:mt-10">
            <x-public.org.branch :columns="$chart->divisions()" />
        </div>
    </div>

    @foreach ($chart->creativeTeam() as $box)
        <x-public.org.arrow />
        <x-public.org.box :box="$box" class="max-w-[300px]" />
    @endforeach

    <x-public.org.arrow />
    <a href="{{ route('anggota') }}" class="w-full max-w-[300px] rounded-xl border-2 border-[var(--accent)] bg-[var(--accent-soft)] px-3 py-4 text-center text-[12px] font-bold uppercase tracking-[0.08em] text-[var(--accent-deep)] hover:underline">
        Seluruh Anggota CSA
    </a>
</div>
