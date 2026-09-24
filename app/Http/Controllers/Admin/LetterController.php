<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Letter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class LetterController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'type' => ['nullable', 'in:incoming,outgoing'],
        ]);

        $letters = Letter::query()
            ->with('creator:id,name')
            ->when($validated['search'] ?? null, fn ($query, $search) =>
                $query->where(function ($sub) use ($search) {
                    $sub->where('subject', 'like', "%{$search}%")
                        ->orWhere('reference_number', 'like', "%{$search}%")
                        ->orWhere('correspondent', 'like', "%{$search}%");
                })
            )
            ->when($validated['type'] ?? null, fn ($query, $type) => $query->where('type', $type))
            ->orderByDesc('letter_date')
            ->paginate(15)
            ->withQueryString();

        $counts = [
            'incoming' => Letter::incoming()->count(),
            'outgoing' => Letter::outgoing()->count(),
        ];

        return view('pages.letters.index', [
            'letters' => $letters,
            'counts' => $counts,
            'type' => $validated['type'] ?? '',
        ]);
    }

    public function create()
    {
        return view('pages.letters.form', ['letter' => null]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);

        $data['created_by'] = Auth::id();
        $data['reference_number'] = $data['reference_number']
            ?: Letter::nextReferenceNumber($data['type']);

        Letter::create($data);

        return redirect()->route('admin.letters.index')->with('success', 'Surat berhasil dicatat.');
    }

    public function edit(Letter $letter)
    {
        return view('pages.letters.form', compact('letter'));
    }

    public function update(Request $request, Letter $letter)
    {
        $data = $this->validated($request, $letter);
        $letter->update($data);

        return redirect()->route('admin.letters.index')->with('success', 'Surat berhasil diperbarui.');
    }

    public function destroy(Letter $letter)
    {
        if ($letter->attachment) {
            Storage::disk('public')->delete($letter->attachment);
        }

        $letter->delete();

        return redirect()->route('admin.letters.index')->with('success', 'Surat berhasil dihapus.');
    }

    private function validated(Request $request, ?Letter $letter = null): array
    {
        $validated = $request->validate([
            'reference_number' => ['nullable', 'string', 'max:60', 'unique:letters,reference_number,' . ($letter->id ?? 'NULL')],
            'type' => ['required', 'in:incoming,outgoing'],
            'subject' => ['required', 'string', 'max:180'],
            'correspondent' => ['nullable', 'string', 'max:180'],
            'letter_date' => ['required', 'date'],
            'description' => ['nullable', 'string', 'max:5000'],
            'attachment' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png,webp,doc,docx', 'max:5120'],
        ]);

        $payload = [
            'reference_number' => $validated['reference_number'] ?? null,
            'type' => $validated['type'],
            'subject' => $validated['subject'],
            'correspondent' => $validated['correspondent'] ?? null,
            'letter_date' => $validated['letter_date'],
            'description' => $validated['description'] ?? null,
        ];

        if ($request->hasFile('attachment')) {
            if ($letter?->attachment) {
                Storage::disk('public')->delete($letter->attachment);
            }
            $payload['attachment'] = $request->file('attachment')->store('letters', 'public');
        }

        return $payload;
    }
}
