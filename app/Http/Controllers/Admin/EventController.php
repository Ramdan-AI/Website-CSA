<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\EventRequest;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'type' => ['nullable', 'in:acara,study_club'],
        ]);

        $events = Event::with('user')
            ->when($validated['type'] ?? null, fn ($query, $type) => $query->where('type', $type))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('pages.events.index', [
            'events' => $events,
            'type' => $validated['type'] ?? '',
        ]);
    }

    public function show(Event $event)
    {
        $event->load('attendances.member');

        return view('pages.events.show', compact('event'));
    }

    public function create()
    {
        $event = new Event;

        return view('pages.events.form', compact('event'));
    }

    public function store(EventRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::id();
        $data['slug'] = Str::slug($request->title);

        if ($request->hasFile('banner')) {
            $data['banner'] = $request->file('banner')->store('events', 'public');
        }

        Event::create($data);

        return redirect()
            ->route('admin.events.index')
            ->with(
                'success',
                'Event berhasil ditambahkan'
            );
    }

    public function edit(Event $event)
    {
        return view('pages.events.form', compact('event'));
    }

    public function update(EventRequest $request, Event $event)
    {
        $data = $request->validated();
        $data['slug'] = Str::slug($request->title);

        if ($request->hasFile('banner')) {
            if ($event->banner) {
                Storage::disk('public')->delete($event->banner);
            }
            $data['banner'] = $request->file('banner')->store('events', 'public');
        }

        $event->update($data);

        return redirect()
            ->route('admin.events.index')
            ->with(
                'success',
                'Event berhasil diupdate'
            );
    }

    public function destroy(Event $event)
    {
        if ($event->banner) {
            Storage::disk('public')->delete($event->banner);
        }
        $event->delete();

        return back()->with(
            'success',
            'Event berhasil dihapus'
        );
    }
}
