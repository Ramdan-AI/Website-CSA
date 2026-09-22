@extends('layouts.admin')

@section('content')
<div class="space-y-4 sm:space-y-5">

    <div>
        <h1 class="text-[26px] sm:text-[30px] font-bold text-[var(--text-primary)] tracking-[-0.03em]">Galeri & Album</h1>
        <p class="text-[13px] text-[var(--text-muted)] mt-1">Jelajahi momen kegiatan UKM CSA.</p>
    </div>

    @if ($albums->isNotEmpty())
        <section class="space-y-3">
            <h2 class="text-[14px] font-semibold text-[var(--text-primary)]">Album</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4">
                @foreach ($albums as $album)
                    <article class="csa-card overflow-hidden">
                        @if ($album->cover_image)
                            <div class="aspect-[4/3] overflow-hidden">
                                <img src="{{ asset('storage/' . $album->cover_image) }}" alt="{{ $album->title }}" class="w-full h-full object-cover" loading="lazy">
                            </div>
                        @else
                            <div class="aspect-[4/3] bg-[var(--bg-muted)] flex items-center justify-center text-[var(--text-muted)]">
                                <x-admin.icon name="images" :size="30" />
                            </div>
                        @endif
                        <div class="p-4">
                            <h3 class="text-[14px] font-semibold text-[var(--text-primary)] truncate">{{ $album->title }}</h3>
                            <p class="text-[12px] text-[var(--text-muted)] mt-0.5">{{ $album->galleries_count ?? 0 }} foto</p>
                        </div>
                    </article>
                @endforeach
            </div>
        </section>
    @endif

    @php
        $photoSections = [
            ['Foto Unggulan', $heroGalleries],
            ['Foto Terbaru', $recentGalleries],
        ];
    @endphp

    @foreach ($photoSections as [$title, $collection])
        @if ($collection->isNotEmpty())
            <section class="space-y-3">
                <h2 class="text-[14px] font-semibold text-[var(--text-primary)]">{{ $title }}</h2>
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-3">
                    @foreach ($collection as $gallery)
                        <a href="{{ asset('storage/' . $gallery->image) }}" target="_blank" rel="noopener"
                            class="csa-card csa-card-hover overflow-hidden aspect-square block">
                            <img src="{{ asset('storage/' . $gallery->image) }}" alt="{{ $gallery->caption ?? 'Foto kegiatan' }}"
                                class="w-full h-full object-cover" loading="lazy">
                        </a>
                    @endforeach
                </div>
            </section>
        @endif
    @endforeach

    @if ($albums->isEmpty() && $heroGalleries->isEmpty() && $recentGalleries->isEmpty())
        <div class="csa-card p-12 text-center">
            <x-admin.icon name="images" :size="32" class="mx-auto text-[var(--text-muted)]" />
            <p class="text-[13px] font-medium text-[var(--text-secondary)] mt-3">Belum ada foto atau album</p>
            <p class="text-[12px] text-[var(--text-muted)] mt-1">Album dan foto akan muncul setelah ditambahkan.</p>
        </div>
    @endif
</div>
@endsection
