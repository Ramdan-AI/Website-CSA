<?php

namespace App\Http\Controllers\Admin;

use App\Models\Album;
use App\Models\Gallery;
use App\Http\Controllers\Controller;
use App\Http\Requests\GalleryRequest;

class GalleryController extends Controller
{
    public function index()
    {
        $galleries = Gallery::with('album')
            ->latest()
            ->paginate(10);

        return view(
            'admin.galleries.index',
            compact('galleries')
        );
    }

    public function create()
    {
        $albums = Album::orderBy('title')->get();

        return view(
            'admin.galleries.create',
            compact('albums')
        );
    }

    public function store(
        GalleryRequest $request
    ) {
        Gallery::create(
            $request->validated()
        );

        return redirect()
            ->route('admin.galleries.index')
            ->with(
                'success',
                'Gallery berhasil ditambahkan'
            );
    }

    public function edit(
        Gallery $gallery
    ) {
        $albums = Album::orderBy('title')->get();

        return view(
            'admin.galleries.edit',
            compact(
                'gallery',
                'albums'
            )
        );
    }

    public function update(
        GalleryRequest $request,
        Gallery $gallery
    ) {
        $gallery->update(
            $request->validated()
        );

        return redirect()
            ->route('admin.galleries.index')
            ->with(
                'success',
                'Gallery berhasil diupdate'
            );
    }

    public function destroy(
        Gallery $gallery
    ) {
        $gallery->delete();

        return back()->with(
            'success',
            'Gallery berhasil dihapus'
        );
    }

    public function show(
        Gallery $gallery
    ) {
        return view(
            'admin.galleries.show',
            compact('gallery')
        );
    }
}
