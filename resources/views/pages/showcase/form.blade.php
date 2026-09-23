@extends('layouts.admin')

@php
    $isEdit = $project !== null;
    $title = $isEdit ? 'Edit Proyek' : 'Tambah Proyek';
    $hasThumb = $isEdit && $project->thumbnail;
    $gallery = $isEdit ? collect($project->images ?? [])->filter()->values() : collect();
    $selectedMembers = $isEdit ? $project->members->pluck('id')->all() : [];
@endphp

@section('content')
<div class="space-y-4 sm:space-y-5">
    <div>
        <a href="{{ route('admin.showcase.index') }}" class="inline-flex items-center gap-1.5 text-[12px] text-[var(--text-muted)] hover:text-[var(--text-primary)] transition-colors">
            <x-admin.icon name="arrow-left" :size="14" /> Kembali ke Showcase
        </a>
        <h1 class="text-[26px] sm:text-[30px] font-bold text-[var(--text-primary)] tracking-[-0.03em] mt-2">{{ $title }}</h1>
        <p class="text-[13px] text-[var(--text-muted)] mt-1">
            {{ $isEdit ? 'Perbarui informasi proyek.' : 'Tambahkan proyek agar tampil di halaman publik.' }}
        </p>
    </div>

    @if ($errors->any())
        <div class="csa-card p-4 border-l-2 border-red-500 space-y-1.5">
            <p class="text-[13px] font-medium text-red-500">Periksa kembali data berikut:</p>
            <ul class="space-y-1">
                @foreach ($errors->all() as $message)
                    <li class="text-[12px] text-red-500">· {{ $message }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ $isEdit ? route('admin.showcase.update', $project) : route('admin.showcase.store') }}"
        method="POST" enctype="multipart/form-data" class="space-y-4">
        @csrf
        @if ($isEdit) @method('PUT') @endif

        <div class="csa-card p-5 sm:p-6 space-y-4">
            <h2 class="text-[14px] font-semibold text-[var(--text-primary)]">Informasi Proyek</h2>

            <x-form.input name="title" id="title" label="Judul Proyek" :value="old('title', $project->title ?? '')"
                placeholder="Contoh: Sistem Informasi Akademik" required autofocus />

            <x-form.input name="summary" id="summary" label="Ringkasan" :value="old('summary', $project->summary ?? '')"
                placeholder="Satu kalimat tentang proyek ini" hint="Maksimal 300 karakter, tampil di kartu proyek." />

            <x-form.textarea name="description" id="description" label="Deskripsi" :value="old('description', $project->description ?? '')"
                placeholder="Jelaskan latar belakang, fitur, dan teknologi yang dipakai." rows="7" />

            <x-form.input name="tech_stack" id="tech_stack" label="Teknologi"
                :value="old('tech_stack', $isEdit && !empty($project->tech_stack) ? implode(', ', $project->tech_stack) : '')"
                placeholder="Laravel, Vue, MySQL" hint="Pisahkan dengan koma." />
        </div>

        <div class="csa-card p-5 sm:p-6 space-y-4">
            <h2 class="text-[14px] font-semibold text-[var(--text-primary)]">Tautan</h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <x-form.input name="demo_url" id="demo_url" type="url" label="Link Demo"
                    :value="old('demo_url', $project->demo_url ?? '')" placeholder="https://proyek.vercel.app" />
                <x-form.input name="repo_url" id="repo_url" type="url" label="Link Repository"
                    :value="old('repo_url', $project->repo_url ?? '')" placeholder="https://github.com/..." />
            </div>
        </div>

        <div class="csa-card p-5 sm:p-6 space-y-4">
            <h2 class="text-[14px] font-semibold text-[var(--text-primary)]">Gambar</h2>

            <div>
                <label class="block text-[13px] font-medium text-[var(--text-secondary)] mb-1.5">Thumbnail</label>
                <div class="flex items-center gap-4">
                    <div class="w-28 h-20 rounded-xl overflow-hidden bg-[var(--bg-muted)] shrink-0">
                        <img id="thumb-preview" src="{{ $hasThumb ? $project->thumbnail_url : '#' }}"
                            alt="Pratinjau" class="{{ $hasThumb ? 'w-full h-full object-cover' : 'hidden' }}">
                    </div>
                    <div>
                        <input type="file" name="thumbnail" id="thumbnail" accept="image/jpeg,image/png,image/webp"
                            class="hidden" onchange="previewThumb(event)">
                        <button type="button" onclick="document.getElementById('thumbnail').click()" class="csa-btn csa-btn-ghost !min-h-[40px] !text-[13px]">
                            <x-admin.icon name="upload" :size="15" /> Pilih gambar
                        </button>
                        <p class="text-[11px] text-[var(--text-muted)] mt-1.5">JPG, PNG, WEBP. Maks 2MB.</p>
                    </div>
                </div>
                @error('thumbnail')<p class="text-[12px] text-red-500 mt-1.5">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-[13px] font-medium text-[var(--text-secondary)] mb-1.5">Galeri Tambahan</label>
                <input type="file" name="images[]" accept="image/jpeg,image/png,image/webp" multiple class="csa-file">
                <p class="text-[11px] text-[var(--text-muted)] mt-1.5">Bisa pilih beberapa gambar sekaligus.</p>
                @error('images.*')<p class="text-[12px] text-red-500 mt-1.5">{{ $message }}</p>@enderror
            </div>

            @if ($gallery->isNotEmpty())
                <div>
                    <p class="text-[12px] text-[var(--text-muted)] mb-2">Gambar tersimpan ({{ $gallery->count() }})</p>
                    <div class="grid grid-cols-3 sm:grid-cols-5 gap-2">
                        @foreach ($gallery as $image)
                            <div class="aspect-square rounded-lg overflow-hidden bg-[var(--bg-muted)]">
                                <img src="{{ asset('storage/' . $image) }}" alt="Gambar proyek" class="w-full h-full object-cover">
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        @php
            $memberPayload = $members->map(fn ($m) => [
                'id' => $m->id,
                'name' => $m->name,
                'nim' => $m->nim ?? '',
                'division' => $m->division?->name ?? 'Tanpa divisi',
            ])->values();
        @endphp

        <div class="csa-card p-5 sm:p-6 space-y-4"
            x-data="{
                query: '',
                members: @js($memberPayload),
                selected: @js(array_map('intval', $selectedMembers)),
                get filtered() {
                    const term = this.query.trim().toLowerCase();
                    if (!term) return this.members;
                    return this.members.filter((m) =>
                        (m.name + ' ' + m.nim).toLowerCase().includes(term)
                    );
                },
                toggle(id) {
                    this.selected = this.selected.includes(id)
                        ? this.selected.filter((item) => item !== id)
                        : [...this.selected, id];
                }
            }">
            <h2 class="text-[14px] font-semibold text-[var(--text-primary)]">Anggota Tim</h2>
            <p class="text-[12px] text-[var(--text-muted)] -mt-2">Pilih anggota yang ikut mengerjakan proyek ini. Kosongkan bila dikerjakan sendiri.</p>

            <div class="relative">
                <x-admin.icon name="search" :size="16" class="absolute left-3.5 top-1/2 -translate-y-1/2 text-[var(--text-muted)] pointer-events-none" />
                <input type="search" x-model="query" placeholder="Cari nama atau NIM anggota..."
                    class="csa-input pl-10" aria-label="Cari anggota">
            </div>

            <div class="max-h-72 overflow-y-auto rounded-xl border border-[var(--border-soft)] divide-y divide-[var(--border-soft)]">
                <template x-for="member in filtered" :key="member.id">
                    <label class="flex items-center gap-3 p-3 hover:bg-[var(--bg-hover)] cursor-pointer min-h-[52px]">
                        <input type="checkbox" name="member_ids[]" :value="member.id"
                            class="w-4 h-4 rounded border-[var(--border-strong)] accent-[var(--accent)]"
                            :checked="selected.includes(member.id)"
                            @change="toggle(member.id)">
                        <span class="min-w-0">
                            <span class="block text-[13px] font-medium text-[var(--text-primary)] truncate" x-text="member.name"></span>
                            <span class="block text-[12px] text-[var(--text-muted)]" x-text="(member.nim || 'Tanpa NIM') + ' · ' + member.division"></span>
                        </span>
                    </label>
                </template>

                <p class="p-6 text-center text-[13px] text-[var(--text-muted)]" x-show="filtered.length === 0">
                    Anggota tidak ditemukan.
                </p>
            </div>

            <p class="text-[12px] text-[var(--text-muted)]">
                <span x-text="selected.length"></span> anggota dipilih.
            </p>
        </div>

        <div class="csa-card p-5 sm:p-6 space-y-4">
            <h2 class="text-[14px] font-semibold text-[var(--text-primary)]">Publikasi</h2>

            <div>
                <label for="status" class="block text-[13px] font-medium text-[var(--text-secondary)] mb-1.5">Status</label>
                <select name="status" id="status" class="csa-input">
                    <option value="published" @selected(old('status', $project->status ?? 'published') === 'published')>Tayang di publik</option>
                    <option value="draft" @selected(old('status', $project->status ?? '') === 'draft')>Simpan sebagai draf</option>
                </select>
            </div>

            <label class="flex items-center gap-3 cursor-pointer">
                <input type="hidden" name="featured" value="0">
                <input type="checkbox" name="featured" value="1" class="peer sr-only"
                    @checked(old('featured', $project->featured ?? false))>
                <span class="relative w-10 h-6 rounded-full bg-[var(--border-strong)] after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:w-5 after:h-5 after:rounded-full after:bg-white after:transition-all peer-checked:bg-[var(--accent)] peer-checked:after:translate-x-4"></span>
                <span>
                    <span class="block text-[13px] font-medium text-[var(--text-primary)]">Tandai sebagai unggulan</span>
                    <span class="block text-[12px] text-[var(--text-muted)] mt-0.5">Proyek unggulan tampil lebih besar di landing page.</span>
                </span>
            </label>
        </div>

        <div class="flex items-center gap-3 flex-wrap">
            <button type="submit" class="csa-btn csa-btn-primary">
                <x-admin.icon :name="$isEdit ? 'save' : 'plus'" :size="16" />
                {{ $isEdit ? 'Simpan Perubahan' : 'Tambah Proyek' }}
            </button>
            <a href="{{ route('admin.showcase.index') }}" class="csa-btn csa-btn-ghost">Batal</a>

            @if ($isEdit)
                <a href="{{ route('admin.showcase.preview', $project) }}" target="_blank" rel="noopener" class="csa-btn csa-btn-ghost sm:ml-auto">
                    <x-admin.icon name="eye" :size="15" /> Pratinjau publik
                </a>
            @endif
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    function previewThumb(event) {
        const file = event.target.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = (e) => {
            const preview = document.getElementById('thumb-preview');
            preview.src = e.target.result;
            preview.classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    }
</script>
@endpush
