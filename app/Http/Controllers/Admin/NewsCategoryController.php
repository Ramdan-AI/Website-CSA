<?php

namespace App\Http\Controllers\Admin;

use App\Models\NewsCategory;
use App\Http\Controllers\Controller;
use App\Http\Requests\NewsCategoryRequest;

class NewsCategoryController extends Controller
{
    public function index()
    {
        $categories = NewsCategory::latest()
            ->paginate(10);

        return view('pages.news-categories.index',
            compact('categories')
        );
    }

    public function create()
    {
        return view('pages.news-categories.create'
        );
    }

    public function store(
        NewsCategoryRequest $request
    ) {
        NewsCategory::create(
            $request->validated()
        );

        return redirect()
            ->route(
                'admin.news-categories.index'
            )
            ->with(
                'success',
                'Kategori berhasil ditambahkan'
            );
    }

    public function edit(
        NewsCategory $newsCategory
    ) {
        return view('pages.news-categories.edit',
            compact('newsCategory')
        );
    }

    public function update(
        NewsCategoryRequest $request,
        NewsCategory $newsCategory
    ) {
        $newsCategory->update(
            $request->validated()
        );

        return redirect()
            ->route(
                'admin.news-categories.index'
            )
            ->with(
                'success',
                'Kategori berhasil diupdate'
            );
    }

    public function destroy(
        NewsCategory $newsCategory
    ) {
        $newsCategory->delete();

        return back()->with(
            'success',
            'Kategori berhasil dihapus'
        );
    }
}
