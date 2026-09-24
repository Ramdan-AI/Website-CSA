<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\NewsCategoryRequest;
use App\Models\NewsCategory;

class NewsCategoryController extends Controller
{
    /**
     * Kategori berita dikelola lewat modal di halaman Berita.
     * Controller ini hanya menangani aksi tulis.
     */
    public function store(NewsCategoryRequest $request)
    {
        NewsCategory::create($request->validated());

        return redirect()
            ->route('admin.news.index')
            ->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function update(NewsCategoryRequest $request, NewsCategory $newsCategory)
    {
        $newsCategory->update($request->validated());

        return redirect()
            ->route('admin.news.index')
            ->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(NewsCategory $newsCategory)
    {
        $newsCategory->delete();

        return redirect()
            ->route('admin.news.index')
            ->with('success', 'Kategori berhasil dihapus.');
    }
}
