{{-- Karya anggota: showcase proyek --}}
<section id="karya" class="scroll-mt-16" style="background: var(--paper);">
    <div class="max-w-[1200px] mx-auto px-5 sm:px-8 py-16 sm:py-24">

        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mb-12">
            <div class="max-w-[680px]">
                <p class="text-[13px] font-medium mb-3" style="color: var(--accent);">Showcase</p>
                <h2 class="text-[clamp(1.7rem,3.8vw,2.4rem)] font-bold leading-[1.22] tracking-[-0.025em]" style="color: var(--ink);">
                    Karya anggota CSA
                </h2>
                <p class="text-[16px] leading-[1.8] mt-5" style="color: var(--ink-soft);">
                    Proyek nyata yang dikerjakan anggota, lengkap dengan demo dan kodenya.
                </p>
            </div>
            <a href="{{ route('projects') }}" class="shrink-0 text-[15px] font-medium hover:underline" style="color: var(--accent);">
                Lihat semua proyek
            </a>
        </div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5">
            @forelse ($projects as $project)
                <article class="group flex flex-col rounded-xl border overflow-hidden transition-all duration-300 hover:-translate-y-1 hover:border-[var(--line-strong)]"
                    style="background: var(--paper-sunken); border-color: var(--line);">

                    <a href="{{ route('projects.show', $project->slug) }}" class="block aspect-[16/10] overflow-hidden" style="background: var(--paper);">
                        @if ($project->thumbnail_url)
                            <img src="{{ $project->thumbnail_url }}" alt="{{ $project->title }}"
                                width="640" height="400" loading="lazy" decoding="async"
                                class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-[1.03]">
                        @else
                            <div class="w-full h-full flex items-center justify-center" style="color: var(--ink-faint);">
                                <i class="fas fa-cube text-2xl"></i>
                            </div>
                        @endif
                    </a>

                    <div class="p-5 flex flex-col flex-1">
                        <a href="{{ route('projects.show', $project->slug) }}">
                            <h3 class="text-[16px] font-semibold leading-[1.45] line-clamp-2" style="color: var(--ink);">{{ $project->title }}</h3>
                        </a>
                        <p class="text-[13px] mt-1.5" style="color: var(--ink-faint);">{{ $project->contributors }}</p>

                        @if (!empty($project->tech_stack))
                            <div class="flex flex-wrap gap-1.5 mt-3">
                                @foreach (array_slice($project->tech_stack, 0, 3) as $tech)
                                    <span class="px-2 py-0.5 rounded-md text-[11px]" style="background: var(--paper); color: var(--ink-soft);">{{ $tech }}</span>
                                @endforeach
                            </div>
                        @endif

                        <div class="flex items-center gap-3 mt-auto pt-4 text-[13px] font-medium">
                            @if ($project->demo_url)
                                <a href="{{ $project->demo_url }}" target="_blank" rel="noopener"
                                    class="inline-flex items-center gap-1.5 hover:underline" style="color: var(--accent);">
                                    <i class="fas fa-arrow-up-right-from-square text-[11px]"></i> Demo
                                </a>
                            @endif
                            @if ($project->repo_url)
                                <a href="{{ $project->repo_url }}" target="_blank" rel="noopener"
                                    class="inline-flex items-center gap-1.5 hover:underline" style="color: var(--ink-faint);">
                                    <i class="fab fa-github text-[13px]"></i> Repo
                                </a>
                            @endif
                        </div>
                    </div>
                </article>
            @empty
                <div class="sm:col-span-2 lg:col-span-3 text-center py-14 rounded-xl border" style="border-color: var(--line);">
                    <p class="text-[15px]" style="color: var(--ink-soft);">Belum ada proyek yang dipublikasikan.</p>
                </div>
            @endforelse
        </div>

    </div>
</section>
