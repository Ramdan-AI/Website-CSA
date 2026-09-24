<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\DocumentRequest;
use App\Models\Document;
use App\Models\DocumentDownload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DocumentController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
        ]);

        $documents = Document::with('user')
            ->when($validated['search'] ?? null, fn ($query, $search) => $query->where(function ($sub) use ($search) {
                $sub->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            })
            )
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('pages.documents.index', [
            'documents' => $documents,
        ]);
    }

    public function create()
    {
        $document = new Document;

        return view('pages.documents.form', compact('document'));
    }

    public function store(DocumentRequest $request)
    {
        $validated = $request->validated();

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = Str::slug($validated['title']).'-'.time().'.'.$file->getClientOriginalExtension();
            $validated['file'] = $file->storeAs('documents', $filename, 'public');
        }

        $validated['user_id'] = Auth::id();

        Document::create($validated);

        return redirect()
            ->route('admin.documents.index')
            ->with('success', 'Dokumen berhasil ditambahkan.');
    }

    public function edit(Document $document)
    {
        return view('pages.documents.form', compact('document'));
    }

    public function update(DocumentRequest $request, Document $document)
    {
        $validated = $request->validated();

        if ($request->hasFile('file')) {
            // Hapus file lama
            if ($document->file) {
                Storage::disk('public')->delete($document->file);
            }
            // Format nama file baru
            $file = $request->file('file');
            $filename = Str::slug($validated['title']).'-'.time().'.'.$file->getClientOriginalExtension();
            $validated['file'] = $file->storeAs('documents', $filename, 'public');
        } else {
            // If title changed, use Storage facade to move the file
            if ($document->title !== $validated['title'] && $document->file) {
                $extension = pathinfo($document->file, PATHINFO_EXTENSION);
                $newFilename = 'documents/'.Str::slug($validated['title']).'-'.$document->created_at->timestamp.'.'.$extension;

                if (Storage::disk('public')->exists($document->file)) {
                    Storage::disk('public')->move($document->file, $newFilename);
                    $validated['file'] = $newFilename;
                }
            } else {
                unset($validated['file']);
            }
        }

        $document->update($validated);

        return redirect()
            ->route('admin.documents.index')
            ->with('success', 'Dokumen berhasil diperbarui.');
    }

    public function download(Document $document)
    {
        if (! Storage::disk('public')->exists($document->file)) {
            return back()->with('error', 'File tidak ditemukan.');
        }

        // Catat download
        DocumentDownload::create([
            'document_id' => $document->id,
            'user_id' => Auth::id(),
            'downloaded_at' => now(),
        ]);

        // Download dengan nama yang rapi: Judul Dokumen.extension
        $downloadName = Str::slug($document->title).'.'.pathinfo($document->file, PATHINFO_EXTENSION);

        return Storage::disk('public')->download($document->file, $downloadName);
    }

    public function destroy(Document $document)
    {
        if ($document->file) {
            Storage::disk('public')->delete($document->file);
        }

        $document->delete();

        return back()->with('success', 'Dokumen berhasil dihapus.');
    }
}
