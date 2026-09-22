<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\NewsCategory;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
            'kategori' => ['nullable', 'string', 'max:100'],
        ]);

        // Only the columns needed for a listing card — never pull the full
        // article body for a grid, which is what made the page heavy.
        $listColumns = ['id', 'category_id', 'user_id', 'title', 'slug', 'thumbnail', 'images', 'published_at', 'created_at'];

        $news = News::query()
            ->select($listColumns)
            ->with([
                'category:id,name,slug',
                'author:id,name',
            ])
            ->where('status', 'published')
            ->when($validated['q'] ?? null, fn ($query, $search) =>
                $query->where(function ($sub) use ($search) {
                    $sub->where('title', 'like', "%{$search}%")
                        ->orWhere('content', 'like', "%{$search}%");
                })
            )
            ->when($validated['kategori'] ?? null, fn ($query, $slug) =>
                $query->whereHas('category', fn ($sub) => $sub->where('slug', $slug))
            )
            ->orderByDesc('published_at')
            ->paginate(10)
            ->withQueryString();

        $categories = NewsCategory::query()
            ->select(['id', 'name', 'slug'])
            ->withCount([
                'news as published_news_count' => fn ($query) => $query->where('status', 'published'),
            ])
            ->orderBy('name')
            ->get();

        return view('pages.public.berita', [
            'news' => $news,
            'categories' => $categories,
            'q' => $validated['q'] ?? '',
            'categorySlug' => $validated['kategori'] ?? '',
        ]);
    }

    public function show(string $slug)
    {
        $article = News::with(['category', 'author'])
            ->where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        $related = News::with('category')
            ->where('status', 'published')
            ->where('id', '!=', $article->id)
            ->when($article->category_id, fn ($query) => $query->where('category_id', $article->category_id))
            ->orderByDesc('published_at')
            ->take(3)
            ->get();

        return view('pages.public.berita-show', compact('article', 'related'));
    }
}
