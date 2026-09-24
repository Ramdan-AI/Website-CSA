<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\NewsRequest;
use App\Models\News;
use App\Models\NewsCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'category_id' => ['nullable', 'integer', 'exists:news_categories,id'],
            'status' => ['nullable', 'in:draft,published'],
            'sort' => ['nullable', 'in:newest,oldest,title_asc,title_desc,published_desc'],
        ]);

        $sort = $validated['sort'] ?? 'newest';

        $news = News::with(['category', 'author'])
            ->when($validated['search'] ?? null, fn ($query, $search) => $query->where(function ($sub) use ($search) {
                $sub->where('title', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%");
            })
            )
            ->when($validated['category_id'] ?? null, fn ($query, $categoryId) => $query->where('category_id', $categoryId)
            )
            ->when($validated['status'] ?? null, fn ($query, $status) => $query->where('status', $status)
            )
            ->when($sort === 'oldest', fn ($query) => $query->oldest())
            ->when($sort === 'title_asc', fn ($query) => $query->orderBy('title'))
            ->when($sort === 'title_desc', fn ($query) => $query->orderByDesc('title'))
            ->when($sort === 'published_desc', fn ($query) => $query->orderByDesc('published_at'))
            ->when($sort === 'newest', fn ($query) => $query->latest())
            ->paginate(12)
            ->withQueryString();

        $categories = NewsCategory::orderBy('name')->get();

        return view('pages.news.index', compact('news', 'categories'));
    }

    public function show(News $news)
    {
        $news->load(['category', 'author']);

        $relatedNews = News::where('category_id', $news->category_id)
            ->where('id', '!=', $news->id)
            ->where('status', 'published')
            ->latest()
            ->take(4)
            ->get();

        return view('pages.news.show', compact('news', 'relatedNews'));
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
        $images = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $images[] = $image->store('news', 'public');
            }
        }

        $thumbnail = $request->hasFile('thumbnail')
            ? $request->file('thumbnail')->store('news', 'public')
            : ($images[0] ?? null);

        News::create([
            'category_id' => $request->category_id,
            'user_id' => Auth::id(),
            'title' => $request->title,
            'content' => $request->content,
            'thumbnail' => $thumbnail,
            'images' => $images,
            'status' => $request->status,
            'published_at' => $request->published_at,
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
        $images = $news->images ?? [];

        $removedImages = $request->input('removed_images', []);
        if ($removedImages !== []) {
            foreach ($removedImages as $path) {
                if (in_array($path, $images, true)) {
                    Storage::disk('public')->delete($path);
                }
            }
            $images = array_values(array_diff($images, $removedImages));
        }

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $images[] = $image->store('news', 'public');
            }
        }

        $thumbnail = $news->thumbnail;

        if ($request->hasFile('thumbnail')) {
            if ($news->thumbnail) {
                Storage::disk('public')->delete($news->thumbnail);
            }
            $thumbnail = $request->file('thumbnail')->store('news', 'public');
        } elseif (empty($images) === false && ! $news->thumbnail) {
            $thumbnail = $images[0];
        }

        $news->update([
            'category_id' => $request->category_id,
            'title' => $request->title,
            'content' => $request->content,
            'thumbnail' => $thumbnail,
            'images' => $images,
            'status' => $request->status,
            'published_at' => $request->published_at,
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
        foreach ($news->images ?? [] as $path) {
            Storage::disk('public')->delete($path);
        }

        if ($news->thumbnail) {
            Storage::disk('public')->delete($news->thumbnail);
        }

        $news->delete();

        return back()->with(
            'success',
            'Berita berhasil dihapus'
        );
    }
}
