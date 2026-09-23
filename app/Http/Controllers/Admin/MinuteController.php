<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\Minute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MinuteController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
        ]);

        $minutes = Minute::query()
            ->with(['creator:id,name'])
            ->withCount('attendees')
            ->when($validated['search'] ?? null, fn ($query, $search) =>
                $query->where(function ($sub) use ($search) {
                    $sub->where('title', 'like', "%{$search}%")
                        ->orWhere('agenda', 'like', "%{$search}%");
                })
            )
            ->orderByDesc('meeting_date')
            ->paginate(15)
            ->withQueryString();

        return view('pages.minutes.index', compact('minutes'));
    }

    public function create()
    {
        return view('pages.minutes.form', [
            'minute' => null,
            'members' => $this->memberOptions(),
            'selectedMembers' => [],
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['created_by'] = Auth::id();

        $minute = Minute::create($data);
        $minute->attendees()->sync($request->input('member_ids', []));

        return redirect()->route('admin.minutes.index')->with('success', 'Notulen berhasil disimpan.');
    }

    public function edit(Minute $minute)
    {
        return view('pages.minutes.form', [
            'minute' => $minute->load('attendees:id'),
            'members' => $this->memberOptions(),
            'selectedMembers' => $minute->attendees->pluck('id')->all(),
        ]);
    }

    public function update(Request $request, Minute $minute)
    {
        $data = $this->validated($request, $minute);
        $minute->update($data);
        $minute->attendees()->sync($request->input('member_ids', []));

        return redirect()->route('admin.minutes.index')->with('success', 'Notulen berhasil diperbarui.');
    }

    public function destroy(Minute $minute)
    {
        if ($minute->attachment) {
            Storage::disk('public')->delete($minute->attachment);
        }

        $minute->delete();

        return redirect()->route('admin.minutes.index')->with('success', 'Notulen berhasil dihapus.');
    }

    private function validated(Request $request, ?Minute $minute = null): array
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:180'],
            'meeting_date' => ['required', 'date'],
            'location' => ['nullable', 'string', 'max:180'],
            'agenda' => ['nullable', 'string', 'max:5000'],
            'decisions' => ['nullable', 'string', 'max:20000'],
            'attachment' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png,webp,doc,docx', 'max:5120'],
            'member_ids' => ['nullable', 'array'],
            'member_ids.*' => ['integer', 'exists:members,id'],
        ]);

        $payload = [
            'title' => $validated['title'],
            'meeting_date' => $validated['meeting_date'],
            'location' => $validated['location'] ?? null,
            'agenda' => $validated['agenda'] ?? null,
            'decisions' => $validated['decisions'] ?? null,
        ];

        if ($request->hasFile('attachment')) {
            if ($minute?->attachment) {
                Storage::disk('public')->delete($minute->attachment);
            }
            $payload['attachment'] = $request->file('attachment')->store('minutes', 'public');
        }

        return $payload;
    }

    private function memberOptions()
    {
        return Member::query()
            ->select(['id', 'name', 'nim', 'division_id'])
            ->with('division:id,name')
            ->orderBy('name')
            ->get();
    }
}
