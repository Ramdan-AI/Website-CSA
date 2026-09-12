<?php

namespace App\Http\Controllers\Admin;

use App\Models\Event;
use App\Http\Controllers\Controller;
use App\Http\Requests\EventRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::with('user')
            ->latest()
            ->paginate(10);

        return view('pages.events.index',
            compact('events')
        );
    }

    public function create()
    {
        $event = new Event();
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
