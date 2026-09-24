@props([
    'categories' => collect(),
])

<div x-show="categoryModal" x-cloak class="fixed inset-0 z-[90] flex items-start justify-center p-4 sm:p-6 overflow-y-auto"
    style="background: rgba(0,0,0,.45); backdrop-filter: blur(4px);" @click.self="categoryModal = false"
    @keydown.escape.window="categoryModal = false">
    <div x-show="categoryModal" x-transition.opacity.duration.150ms
        class="w-full max-w-lg mt-[6vh] rounded-2xl border border-[var(--border-strong)] bg-[var(--bg-elevated)] shadow-2xl overflow-hidden"
        x-data="{ editing: null, name: '', description: '' }"
        @edit-category.window="editing = $event.detail.id; name = $event.detail.name; description = $event.detail.description"
        @category-saved.window="editing = null; name = ''; description = ''">
        <div class="flex items-center justify-between px-5 py-4 border-b border-[var(--border-soft)]">
            <h3 class="text-[15px] font-semibold text-[var(--text-primary)]">Kelola Kategori Berita</h3>
            <button type="button" @click="categoryModal = false; editing = null; name = ''; description = ''"
                class="csa-icon-btn" aria-label="Tutup">
                <x-admin.icon name="x" :size="18" />
            </button>
        </div>

        <div class="p-5 space-y-5 max-h-[70vh] overflow-y-auto">
            {{-- Form tambah / edit kategori --}}
            <form method="POST" x-bind:action="editing
                    ? '{{ url(request()->route('roleSlug') . '/news-categories') }}/' + editing
                    : '{{ route('admin.news-categories.store') }}'"
                class="csa-panel p-4 space-y-3">
                @csrf
                <template x-if="editing">
                    <input type="hidden" name="_method" value="PUT">
                </template>

                <x-form.input name="name" id="category_name" label="Nama Kategori" x-model="name"
                    placeholder="Contoh: Prestasi" required />
                <x-form.textarea name="description" id="category_description" label="Deskripsi" x-model="description" rows="2"
                    placeholder="Keterangan singkat kategori (opsional)" />

                <div class="flex items-center gap-2">
                    <button type="submit" class="csa-btn csa-btn-primary !min-h-[40px] !text-[13px]"
                        x-text="editing ? 'Simpan Perubahan' : 'Tambah Kategori'"></button>
                    <button type="button" x-show="editing" @click="editing = null; name = ''; description = ''"
                        class="csa-btn csa-btn-ghost !min-h-[40px] !text-[13px]">Batal</button>
                </div>
            </form>

            {{-- Daftar kategori --}}
            <div>
                <p class="text-[11px] font-semibold uppercase tracking-[0.08em] text-[var(--text-muted)] mb-2">Daftar Kategori</p>

                @forelse ($categories as $category)
                    <div class="flex items-center gap-3 py-2.5 border-b border-[var(--border-soft)] last:border-0">
                        <div class="min-w-0 flex-1">
                            <p class="text-[13px] font-medium text-[var(--text-primary)] truncate">{{ $category->name }}</p>
                            <p class="text-[12px] text-[var(--text-muted)]">{{ $category->news_count ?? $category->news()->count() }} berita</p>
                        </div>
                        <button type="button" class="csa-icon-btn" title="Edit"
                            @click="$dispatch('edit-category', { id: {{ $category->id }}, name: @js($category->name), description: @js($category->description) })">
                            <x-admin.icon name="pen" :size="15" />
                        </button>
                        <form method="POST" action="{{ route('admin.news-categories.destroy', $category) }}"
                            onsubmit="return confirm('Hapus kategori ini? Berita di dalamnya akan kehilangan kategori.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="csa-icon-btn hover:!text-red-500" title="Hapus">
                                <x-admin.icon name="trash-2" :size="15" />
                            </button>
                        </form>
                    </div>
                @empty
                    <p class="text-[13px] text-[var(--text-muted)] py-4 text-center">Belum ada kategori.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
