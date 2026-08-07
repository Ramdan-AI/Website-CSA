<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Auth;
use App\Models\News;
use App\Models\NewsCategory;
use App\Http\Controllers\Controller;
use App\Http\Requests\NewsRequest;

class NewsController extends Controller
{
    public function index()
    {
        $news = News::with([
            'category',
            'author'
        ])
            ->latest()
            ->paginate(10);

        return view('pages.news.index',
            compact('news')
        );
    }

    public function create()
    {
        $categories = NewsCategory::orderBy('name')->get();

        return view('pages.news.create',
            compact('categories')
        );
    }

    public function store(
        NewsRequest $request
    ) {
        News::create([

            'category_id' => $request->category_id,

            'user_id' => Auth::id(),

            'title' => $request->title,

            'content' => $request->content,

            'status' => $request->status,

            'published_at' => $request->published_at

        ]);

        return redirect()
            ->route('admin.news.index')
            ->with(
                'success',
                'Berita berhasil ditambahkan'
            );
    }

    public function edit(
        News $news
    ) {
        $categories = NewsCategory::orderBy('name')->get();

        return view('pages.news.edit',
            compact(
                'news',
                'categories'
            )
        );
    }

    public function update(
        NewsRequest $request,
        News $news
    ) {
        $news->update([

            'category_id' => $request->category_id,

            'title' => $request->title,

            'content' => $request->content,

            'status' => $request->status,

            'published_at' => $request->published_at

        ]);

        return redirect()
            ->route('admin.news.index')
            ->with(
                'success',
                'Berita berhasil diupdate'
            );
    }

    public function destroy(
        News $news
    ) {
        $news->delete();

        return back()->with(
            'success',
            'Berita berhasil dihapus'
        );
    }
}
