<?php

namespace App\Http\Controllers\Admin;

use App\Models\Album;
use App\Http\Controllers\Controller;
use App\Http\Requests\AlbumRequest;

class AlbumController extends Controller
{
    public function index()
    {
        $albums = Album::latest()
            ->paginate(10);

        return view(
            'admin.albums.index',
            compact('albums')
        );
    }

    public function create()
    {
        return view(
            'admin.albums.create'
        );
    }

    public function store(
        AlbumRequest $request
    ) {
        Album::create(
            $request->validated()
        );

        return redirect()
            ->route('admin.albums.index')
            ->with(
                'success',
                'Album berhasil ditambahkan'
            );
    }

    public function edit(
        Album $album
    ) {
        return view(
            'admin.albums.edit',
            compact('album')
        );
    }

    public function update(
        AlbumRequest $request,
        Album $album
    ) {
        $album->update(
            $request->validated()
        );

        return redirect()
            ->route('admin.albums.index')
            ->with(
                'success',
                'Album berhasil diupdate'
            );
    }

    public function destroy(
        Album $album
    ) {
        $album->delete();

        return back()->with(
            'success',
            'Album berhasil dihapus'
        );
    }
}
