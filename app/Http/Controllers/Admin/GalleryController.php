<?php

namespace App\Http\Controllers\Admin;

use App\Models\Gallery;
use App\Models\Album;
use App\Http\Controllers\Controller;
use App\Http\Requests\GalleryRequest;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    public function index()
    {
        $galleries = Gallery::with('album')
            ->latest()
            ->paginate(20);

        return view('pages.admin.galleries.index', compact('galleries'));
    }

    public function create()
    {
        $albums = Album::orderBy('title')->get();
        return view('pages.admin.galleries.form', compact('albums'));
    }

    public function store(GalleryRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('galleries', 'public');
        }

        Gallery::create($data);

        return redirect()
            ->route('admin.galleries.index')
            ->with('success', 'Foto berhasil ditambahkan.');
    }

    public function edit(Gallery $gallery)
    {
        $albums = Album::orderBy('title')->get();
        return view('pages.admin.galleries.form', compact('gallery', 'albums'));
    }

    public function update(GalleryRequest $request, Gallery $gallery)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            if ($gallery->image) {
                Storage::disk('public')->delete($gallery->image);
            }
            $data['image'] = $request->file('image')->store('galleries', 'public');
        }

        $gallery->update($data);

        return redirect()
            ->route('admin.galleries.index')
            ->with('success', 'Foto berhasil diperbarui.');
    }

    public function destroy(Gallery $gallery)
    {
        if ($gallery->image) {
            Storage::disk('public')->delete($gallery->image);
        }

        $gallery->delete();

        return back()->with('success', 'Foto berhasil dihapus.');
    }
}
