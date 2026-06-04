<?php

namespace App\Http\Controllers\Admin;

use App\Models\Document;
use App\Http\Controllers\Controller;
use App\Http\Requests\DocumentRequest;
use Illuminate\Support\Facades\Auth;

class DocumentController extends Controller
{
    public function index()
    {
        $documents = Document::with('user')
            ->latest()
            ->paginate(10);

        return view(
            'admin.documents.index',
            compact('documents')
        );
    }

    public function create()
    {
        return view(
            'admin.documents.create'
        );
    }

    public function store(
        DocumentRequest $request
    ) {
        Document::create([

            'user_id' => Auth::id(),

            'title' => $request->title,

            'file' => $request->file,

            'description' => $request->description

        ]);

        return redirect()
            ->route('admin.documents.index')
            ->with(
                'success',
                'Dokumen berhasil ditambahkan'
            );
    }

    public function edit(
        Document $document
    ) {
        return view(
            'admin.documents.edit',
            compact('document')
        );
    }

    public function update(
        DocumentRequest $request,
        Document $document
    ) {
        $document->update([

            'title' => $request->title,

            'file' => $request->file,

            'description' => $request->description

        ]);

        return redirect()
            ->route('admin.documents.index')
            ->with(
                'success',
                'Dokumen berhasil diupdate'
            );
    }

    public function destroy(
        Document $document
    ) {
        $document->delete();

        return back()->with(
            'success',
            'Dokumen berhasil dihapus'
        );
    }

    public function show(
        Document $document
    ) {
        abort(404);
    }
}
