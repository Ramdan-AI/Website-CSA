{{-- resources/views/pages/public/anggota.blade.php --}}
@extends('layouts.landing')

@php
    $baseUrl = route('anggota');
    $filtered = $q !== '' || $divisionSlug !== '';
@endphp

@section('content')

{{-- Hero --}}
<section class="bg-[var(--paper)] border-b border-[var(--line)]">
    <div class="max-w-[1200px] mx-auto px-5 sm:px-8 py-14 sm:py-20">
        <h1 class="text-[clamp(1.9rem,4.4vw,2.75rem)] font-bold text-[var(--ink)] leading-[1.2]">
            Anggota CSA
        </h1>
        <p class="text-[16px] sm:text-[17px] text-[var(--ink-soft)] leading-[1.7] mt-4 max-w-[560px]">
            Daftar anggota aktif Creative Student Association di STMIK Mardira Indonesia.
        </p>

        <dl class="grid grid-cols-2 sm:grid-cols-3 gap-6 sm:gap-4 mt-10 max-w-[640px]">
            <div>
                <dt class="text-[13px] text-[var(--ink-faint)]">Anggota aktif</dt>
                <dd class="text-[28px] font-bold text-[var(--ink)] mt-1">{{ $totalMembers }}</dd>
            </div>
            <div>
                <dt class="text-[13px] text-[var(--ink-faint)]">Divisi</dt>
                <dd class="text-[28px] font-bold text-[var(--ink)] mt-1">{{ $divisions->count() }}</dd>
            </div>
            <div>
                <dt class="text-[13px] text-[var(--ink-faint)]">Berdiri sejak</dt>
                <dd class="text-[28px] font-bold text-[var(--ink)] mt-1">2017</dd>
            </div>
        </dl>
    </div>
</section>

{{-- Direktori --}}
<section class="bg-[var(--paper-sunken)] py-14 sm:py-16">
    <div class="max-w-[1200px] mx-auto px-5 sm:px-8">
        <div class="grid lg:grid-cols-[260px_1fr] gap-10 lg:gap-14 items-start">

            {{-- Filter --}}
            <aside class="lg:sticky lg:top-24"
                x-data="{
                    q: {{ Illuminate\Support\Js::from($q) }},
                    divisi: {{ Illuminate\Support\Js::from($divisionSlug) }},
                    go() {
                        const params = new URLSearchParams();
                        if (this.q) params.set('q', this.q);
                        if (this.divisi) params.set('divisi', this.divisi);
                        const qs = params.toString();
                        window.location = @js($baseUrl) + (qs ? '?' + qs : '');
                    }
                }"
                x-init="
                    $watch('divisi', () => go());
                    let qTimer = null;
                    $watch('q', (val) => { clearTimeout(qTimer); qTimer = setTimeout(() => go(), 500); });
                ">
                <h2 class="text-[15px] font-semibold text-[var(--ink)] mb-4">Filter</h2>

                <div class="relative mb-6">
                    <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-[var(--ink-faint)] text-[14px] pointer-events-none"></i>
                    <input type="text" x-model="q" placeholder="Cari nama"
                        class="w-full h-12 pl-11 pr-4 rounded-lg bg-[var(--paper)] text-[15px] text-[var(--ink)] placeholder:text-[var(--ink-faint)] border border-[var(--line-strong)] focus:border-[var(--accent)] focus:outline-none transition-colors">
                </div>

                <nav class="flex flex-col gap-1">
                    <button type="button" @click="divisi = ''"
                        class="text-left px-4 py-2.5 rounded-lg text-[15px] transition-colors"
                        :class="divisi === '' ? 'bg-[var(--accent-soft)] text-[var(--accent)] font-medium' : 'text-[var(--ink)] hover:bg-[var(--paper-sunken)]'">
                        Semua divisi
                    </button>
                    @foreach ($divisions as $division)
                        <button type="button" @click="divisi = {{ Illuminate\Support\Js::from($division->slug) }}"
                            class="text-left px-4 py-2.5 rounded-lg text-[15px] transition-colors"
                            :class="divisi === {{ Illuminate\Support\Js::from($division->slug) }} ? 'bg-[var(--accent-soft)] text-[var(--accent)] font-medium' : 'text-[var(--ink)] hover:bg-[var(--paper-sunken)]'">
                            {{ $division->name }}
                        </button>
                    @endforeach
                </nav>

                @if ($filtered)
                    <a href="{{ $baseUrl }}" class="inline-flex items-center gap-2 mt-6 text-[14px] font-medium text-[var(--accent)] hover:underline">
                        Reset filter
                    </a>
                @endif
            </aside>

            {{-- Daftar --}}
            <div>
                <div class="flex items-center justify-between gap-4 mb-6">
                    <p class="text-[14px] text-[var(--ink-soft)]">
                        <span class="font-semibold text-[var(--ink)]">{{ $members->total() }}</span>
                        @if ($filtered) hasil filter @else anggota aktif @endif
                    </p>
                    @if ($filtered)
                        <a href="{{ $baseUrl }}" class="text-[14px] font-medium text-[var(--accent)] hover:underline lg:hidden">Reset</a>
                    @endif
                </div>

                @if ($members->isEmpty())
                    <div class="rounded-xl bg-[var(--paper)] border border-[var(--line)] py-20 text-center">
                        <p class="text-[16px] font-medium text-[var(--ink-soft)]">Tidak ada anggota yang cocok</p>
                        <p class="text-[14px] text-[var(--ink-faint)] mt-1">Coba ubah kata kunci atau filter divisi.</p>
                        <a href="{{ $baseUrl }}" class="inline-flex items-center gap-2 mt-5 text-[14px] font-medium text-[var(--accent)] hover:underline">
                            Reset filter
                        </a>
                    </div>
                @else
                    <div class="grid sm:grid-cols-2 xl:grid-cols-3 gap-4">
                        @foreach ($members as $member)
                            <article class="bg-[var(--paper)] rounded-xl border border-[var(--line)] p-5">
                                <div class="flex items-center gap-4">
                                    <span class="w-12 h-12 rounded-full overflow-hidden bg-[var(--paper-sunken)] shrink-0">
                                        <img src="{{ $member->photo_url ?: asset('storage/profile/profile-placeholder.png') }}"
                                            alt="{{ $member->name }}" width="48" height="48" class="w-full h-full object-cover" loading="lazy" decoding="async">
                                    </span>
                                    <div class="min-w-0">
                                        <h3 class="text-[15px] font-semibold text-[var(--ink)] truncate">{{ $member->name }}</h3>
                                        <p class="text-[13px] text-[var(--ink-faint)] truncate mt-0.5">
                                            {{ $member->position?->name ?? 'Anggota' }}
                                            @if ($member->division?->name) · {{ $member->division->name }} @endif
                                        </p>
                                    </div>
                                </div>
                                <div class="mt-4 pt-4 border-t border-[var(--line)]">
                                    @include('components.public.partials.member-socials', ['member' => $member])
                                </div>
                            </article>
                        @endforeach
                    </div>

                    @if ($members->hasPages())
                        <div class="mt-10">{{ $members->links() }}</div>
                    @endif
                @endif
            </div>

        </div>
    </div>
</section>

@endsection
