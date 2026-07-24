<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\DocumentDownload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DocumentController extends Controller
{
    public function index()
    {
        $documents = Document::with('user')
            ->latest()
            ->paginate(12);

        return view('pages.admin.documents.index', compact('documents'));
    }

    public function create()
    {
        $document = new Document();
        return view('pages.admin.documents.form', compact('document'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'file' => 'required|file|mimes:pdf,doc,docx,xls,xlsx|max:10240',
            'description' => 'nullable|string',
        ]);

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = Str::slug($validated['title']) . '-' . time() . '.' . $file->getClientOriginalExtension();
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
        return view('pages.admin.documents.form', compact('document'));
    }

    public function update(Request $request, Document $document)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx|max:10240',
            'description' => 'nullable|string',
        ]);

        if ($request->hasFile('file')) {
            // Hapus file lama
            if ($document->file) {
                Storage::disk('public')->delete($document->file);
            }
            // Format nama file baru
            $file = $request->file('file');
            $filename = Str::slug($validated['title']) . '-' . time() . '.' . $file->getClientOriginalExtension();
            $validated['file'] = $file->storeAs('documents', $filename, 'public');
        } else {
            // Jika judul berubah, rename file juga
            if ($document->title !== $validated['title'] && $document->file) {
                $oldPath = storage_path('app/public/' . $document->file);
                $extension = pathinfo($document->file, PATHINFO_EXTENSION);
                $newFilename = 'documents/' . Str::slug($validated['title']) . '-' . $document->created_at->timestamp . '.' . $extension;
                $newPath = storage_path('app/public/' . $newFilename);

                if (file_exists($oldPath)) {
                    rename($oldPath, $newPath);
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
        $filePath = storage_path('app/public/' . $document->file);

        if (!file_exists($filePath)) {
            return back()->with('error', 'File tidak ditemukan.');
        }

        // Catat download
        DocumentDownload::create([
            'document_id' => $document->id,
            'user_id' => Auth::id(),
            'downloaded_at' => now(),
        ]);

        // Download dengan nama yang rapi: Judul Dokumen.extension
        $downloadName = Str::slug($document->title) . '.' . pathinfo($document->file, PATHINFO_EXTENSION);

        return response()->download($filePath, $downloadName);
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
