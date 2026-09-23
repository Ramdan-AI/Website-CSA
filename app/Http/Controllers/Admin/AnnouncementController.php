<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnnouncementController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'category' => ['nullable', 'in:info,penting,urgent'],
        ]);

        $announcements = Announcement::query()
            ->with('creator:id,name')
            ->when($validated['search'] ?? null, fn ($query, $search) =>
                $query->where(function ($sub) use ($search) {
                    $sub->where('title', 'like', "%{$search}%")
                        ->orWhere('body', 'like', "%{$search}%");
                })
            )
            ->when($validated['category'] ?? null, fn ($query, $category) => $query->where('category', $category))
            ->orderByDesc('pinned')
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('pages.announcements.index', [
            'announcements' => $announcements,
            'category' => $validated['category'] ?? '',
        ]);
    }

    public function create()
    {
        return view('pages.announcements.form', ['announcement' => null]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['created_by'] = Auth::id();

        Announcement::create($data);

        return redirect()->route('admin.announcements.index')->with('success', 'Pengumuman diterbitkan.');
    }

    public function edit(Announcement $announcement)
    {
        return view('pages.announcements.form', compact('announcement'));
    }

    public function update(Request $request, Announcement $announcement)
    {
        $announcement->update($this->validated($request));

        return redirect()->route('admin.announcements.index')->with('success', 'Pengumuman diperbarui.');
    }

    public function destroy(Announcement $announcement)
    {
        $announcement->delete();

        return redirect()->route('admin.announcements.index')->with('success', 'Pengumuman dihapus.');
    }

    private function validated(Request $request): array
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:180'],
            'body' => ['required', 'string', 'max:5000'],
            'category' => ['required', 'in:info,penting,urgent'],
            'status' => ['required', 'in:draft,published'],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
            'pinned' => ['nullable', 'boolean'],
        ]);

        return [
            'title' => $validated['title'],
            'body' => $validated['body'],
            'category' => $validated['category'],
            'status' => $validated['status'],
            'starts_at' => $validated['starts_at'] ?? null,
            'ends_at' => $validated['ends_at'] ?? null,
            'pinned' => $request->boolean('pinned'),
        ];
    }
}
