<?php

namespace App\Http\Controllers\Admin;

use App\Models\Event;
use App\Http\Controllers\Controller;
use App\Http\Requests\EventRequest;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::with('user')
            ->latest()
            ->paginate(10);

        return view(
            'admin.events.index',
            compact('events')
        );
    }

    public function create()
    {
        return view(
            'admin.events.create'
        );
    }

    public function store(
        EventRequest $request
    ) {
        Event::create([

            'user_id' => Auth::id(),

            'title' => $request->title,

            'slug' => $request->slug,

            'description' => $request->description,

            'location' => $request->location,

            'event_date' => $request->event_date,

            'banner' => $request->banner,

            'registration_link' => $request->registration_link,

            'status' => $request->status

        ]);

        return redirect()
            ->route('admin.events.index')
            ->with(
                'success',
                'Event berhasil ditambahkan'
            );
    }

    public function edit(
        Event $event
    ) {
        return view(
            'admin.events.edit',
            compact('event')
        );
    }

    public function update(
        EventRequest $request,
        Event $event
    ) {
        $event->update([

            'title' => $request->title,

            'slug' => $request->slug,

            'description' => $request->description,

            'location' => $request->location,

            'event_date' => $request->event_date,

            'banner' => $request->banner,

            'registration_link' => $request->registration_link,

            'status' => $request->status

        ]);

        return redirect()
            ->route('admin.events.index')
            ->with(
                'success',
                'Event berhasil diupdate'
            );
    }

    public function destroy(
        Event $event
    ) {
        $event->delete();

        return back()->with(
            'success',
            'Event berhasil dihapus'
        );
    }
}
