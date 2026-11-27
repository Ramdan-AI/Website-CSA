@props(['eyebrow', 'title', 'members', 'caption'])

@php
    $buttonClass = 'w-10 h-10 rounded-full bg-[var(--paper)] border border-[var(--line)] flex items-center justify-center text-[var(--ink-faint)] hover:text-[var(--accent)] hover:border-[var(--line-strong)] transition-colors';
    $placeholder = asset('images/profile-placeholder.svg');
@endphp

<div class="mt-20">
    <div class="flex items-end justify-between gap-5 mb-6">
        <div>
            <p class="text-[13px] text-[var(--ink-faint)] mb-1">{{ $eyebrow }}</p>
            <h3 class="text-[20px] font-semibold text-[var(--ink)]">{{ $title }}</h3>
        </div>
        @if ($members->count() > 2)
            <div class="flex items-center gap-2 shrink-0">
                <button type="button" data-slider-prev aria-label="Geser ke kiri" class="{{ $buttonClass }}">
                    <i class="fas fa-chevron-left text-[12px]"></i>
                </button>
                <button type="button" data-slider-next aria-label="Geser ke kanan" class="{{ $buttonClass }}">
                    <i class="fas fa-chevron-right text-[12px]"></i>
                </button>
            </div>
        @endif
    </div>

    <div data-slider>
        <div class="flex gap-3 sm:gap-4 overflow-x-auto no-scrollbar pb-2" data-slider-track>
            @foreach ($members as $member)
                <article class="group shrink-0 w-[164px] sm:w-[220px] bg-[var(--paper)] rounded-xl border border-[var(--line)] p-4 sm:p-5 text-center hover:border-[var(--line-strong)] transition-all duration-300">
                    <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-full overflow-hidden bg-[var(--accent-soft)] mx-auto">
                        <img src="{{ $member->photo_url ?: $placeholder }}" alt="{{ $member->name }}" width="80" height="80" class="w-full h-full object-cover" loading="lazy" onerror="this.onerror=null;this.src='{{ $placeholder }}';">
                    </div>
                    <h4 class="text-[13px] sm:text-[14px] font-bold text-[var(--ink)] mt-3 sm:mt-3.5 leading-snug">{{ $member->name }}</h4>
                    <p class="text-[11px] text-[var(--ink-faint)] mt-0.5 truncate">{{ $caption($member) }}</p>
                    <div class="flex justify-center mt-3 sm:mt-4 pt-3 sm:pt-4 border-t border-[var(--line)]">
                        @include('components.public.partials.member-socials', ['member' => $member])
                    </div>
                </article>
            @endforeach
        </div>
    </div>

    {{ $slot }}
</div>
