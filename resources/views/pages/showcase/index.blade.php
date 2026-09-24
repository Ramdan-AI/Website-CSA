@extends('layouts.admin')

@section('content')
<div class="space-y-4 sm:space-y-5">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h1 class="text-[26px] sm:text-[30px] font-bold text-[var(--text-primary)] tracking-[-0.03em]">Showcase Proyek</h1>
            <p class="text-[13px] text-[var(--text-muted)] mt-1">Karya anggota CSA yang tampil di halaman publik.</p>
        </div>
        <a href="{{ route('admin.showcase.create') }}" class="csa-btn csa-btn-primary">
            <x-admin.icon name="plus" :size="16" /> Tambah Proyek
        </a>
    </div>

    @if (session('success'))
        <div class="csa-panel p-3.5 text-[13px] text-emerald-600 dark:text-emerald-400">{{ session('success') }}</div>
    @endif

    <form method="GET" class="csa-panel p-3 sm:p-4 grid grid-cols-1 sm:grid-cols-[1fr_auto_auto] gap-3">
        <div class="relative">
            <x-admin.icon name="search" :size="16" class="absolute left-3.5 top-1/2 -translate-y-1/2 text-[var(--text-muted)] pointer-events-none" />
            <input type="search" name="search" value="{{ request('search') }}" placeholder="Cari proyek..." class="csa-input pl-10" aria-label="Cari proyek">
        </div>

        @if ($canManageAll)
            <x-form.select name="scope" :value="$scope" onchange="this.form.submit()" class="sm:w-44" aria-label="Cakupan"
                :options="['all' => 'Semua proyek', 'mine' => 'Proyek saya']" />
        @endif

        <button type="submit" class="csa-btn csa-btn-ghost">Cari</button>
    </form>

    @if ($projects->isEmpty())
        <div class="csa-panel p-12 text-center">
            <x-admin.icon name="folder-open" :size="32" class="mx-auto text-[var(--text-muted)]" />
            <p class="text-[13px] font-medium text-[var(--text-secondary)] mt-3">Belum ada proyek</p>
            <p class="text-[12px] text-[var(--text-muted)] mt-1">Tambahkan proyek pertama untuk ditampilkan.</p>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach ($projects as $project)
                <article class="csa-panel overflow-hidden flex flex-col">
                    <div class="aspect-[16/10] bg-[var(--bg-muted)] overflow-hidden">
                        @if ($project->thumbnail_url)
                            <img src="{{ $project->thumbnail_url }}" alt="{{ $project->title }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-[var(--text-muted)]">
                                <x-admin.icon name="image" :size="26" />
                            </div>
                        @endif
                    </div>

                    <div class="p-4 flex flex-col flex-1">
                        <div class="flex items-center gap-2">
                            <span class="csa-chip {{ $project->status === 'published' ? 'csa-chip-green' : 'csa-chip-amber' }}">
                                {{ $project->status === 'published' ? 'Tayang' : 'Draf' }}
                            </span>
                            @if ($project->featured)
                                <span class="csa-chip csa-chip-blue">Unggulan</span>
                            @endif
                        </div>

                        <h2 class="text-[14px] font-semibold text-[var(--text-primary)] mt-2.5 line-clamp-2">{{ $project->title }}</h2>
                        <p class="text-[12px] text-[var(--text-muted)] mt-1">{{ $project->contributors }}</p>

                        @if (!empty($project->tech_stack))
                            <div class="flex flex-wrap gap-1.5 mt-3">
                                @foreach (array_slice($project->tech_stack, 0, 4) as $tech)
                                    <span class="px-2 py-0.5 rounded-md bg-[var(--bg-muted)] text-[11px] text-[var(--text-secondary)]">{{ $tech }}</span>
                                @endforeach
                            </div>
                        @endif

                        <div class="mt-auto pt-4 flex items-center justify-between">
                            <div class="flex items-center gap-1">
                                @if ($project->demo_url)
                                    <a href="{{ $project->demo_url }}" target="_blank" rel="noopener" class="csa-icon-btn" title="Lihat demo" aria-label="Lihat demo">
                                        <x-admin.icon name="external-link" :size="16" />
                                    </a>
                                @endif
                                @if ($project->repo_url)
                                    <a href="{{ $project->repo_url }}" target="_blank" rel="noopener" class="csa-icon-btn" title="Repo" aria-label="Repo">
                                        <x-admin.icon name="code" :size="16" />
                                    </a>
                                @endif
                            </div>

                            <div class="flex items-center gap-1">
                                <a href="{{ route('admin.showcase.preview', $project) }}" target="_blank" rel="noopener"
                                    class="csa-icon-btn" title="Pratinjau publik" aria-label="Pratinjau publik">
                                    <x-admin.icon name="eye" :size="16" />
                                </a>
                                <a href="{{ route('admin.showcase.edit', $project) }}" class="csa-icon-btn" title="Edit" aria-label="Edit proyek">
                                    <x-admin.icon name="pen" :size="16" />
                                </a>
                                <button type="button" onclick="openDeleteModal('deleteModal', '{{ route('admin.showcase.destroy', $project) }}')"
                                    class="csa-icon-btn hover:!text-red-500" title="Hapus" aria-label="Hapus proyek">
                                    <x-admin.icon name="trash-2" :size="16" />
                                </button>
                            </div>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>

        @if ($projects->hasPages())
            <div>{{ $projects->links() }}</div>
        @endif
    @endif
</div>
@endsection
