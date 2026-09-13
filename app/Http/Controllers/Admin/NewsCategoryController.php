<?php

namespace App\Http\Controllers\Admin;

use App\Models\NewsCategory;
use App\Http\Controllers\Controller;
use App\Http\Requests\NewsCategoryRequest;

class NewsCategoryController extends Controller
{
    public function index()
    {
        $categories = NewsCategory::withCount([
            'news',
            'news as published_news_count' => fn ($query) => $query->where('status', 'published'),
            'news as draft_news_count' => fn ($query) => $query->where('status', 'draft'),
        ])
            ->with(['news' => fn ($query) => $query->latest()->take(3)])
            ->latest()
            ->get();

        return view('pages.news-categories.index',
            compact('categories')
        );
    }

    public function show(NewsCategory $newsCategory)
    {
        $news = $newsCategory->news()
            ->with(['category', 'author'])
            ->latest()
            ->paginate(12);

        return view('pages.news-categories.show',
            compact('newsCategory', 'news')
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
