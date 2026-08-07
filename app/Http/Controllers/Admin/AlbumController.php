<?php

namespace App\Http\Controllers\Admin;

use App\Models\Album;
use App\Http\Controllers\Controller;
use App\Http\Requests\AlbumRequest;
use Illuminate\Support\Facades\Storage;

class AlbumController extends Controller
{
    public function index()
    {
        $albums = Album::withCount('galleries')
            ->latest()
            ->paginate(10);

        return view('pages.albums.index',
            compact('albums')
        );
    }

    public function create()
    {
        return view('pages.albums.form'
        );
    }

    public function store(AlbumRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $request->file('cover_image')->store('albums', 'public');
        }

        Album::create($data);

        return redirect()
            ->route('admin.albums.index')
            ->with('success', 'Album berhasil ditambahkan.');
    }

    public function edit(
        Album $album
    ) {
        return view('pages.albums.form',
            compact('album')
        );
    }

    public function update(AlbumRequest $request, Album $album)
    {
        $data = $request->validated();

        if ($request->hasFile('cover_image')) {
            // Hapus foto lama
            if ($album->cover_image) {
                Storage::disk('public')->delete($album->cover_image);
            }
            $data['cover_image'] = $request->file('cover_image')->store('albums', 'public');
        }

        $album->update($data);

        return redirect()
            ->route('admin.albums.index')
            ->with('success', 'Album berhasil diperbarui.');
    }

    public function destroy(
        Album $album
    ) {
        // Hapus cover album jika ada
        if ($album->cover_image) {
            Storage::disk('public')->delete($album->cover_image);
        }

        // Hapus semua file galeri terkait
        foreach ($album->galleries as $gallery) {
            if ($gallery->image) {
                Storage::disk('public')->delete($gallery->image);
            }
        }

        // Hapus galeri dari database (cascade delete)
        $album->galleries()->delete();

        $album->delete();

        return back()->with(
            'success',
            'Album beserta seluruh galeri berhasil dihapus'
        );
    }
}
