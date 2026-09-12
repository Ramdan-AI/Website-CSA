<?php

namespace App\Http\Controllers\Admin;

use App\Models\Event;
use App\Models\Member;
use App\Models\EventRegistration;
use App\Http\Controllers\Controller;
use App\Http\Requests\EventRegistrationRequest;

class EventRegistrationController extends Controller
{
    public function index()
    {
        $registrations = EventRegistration::with([
            'event',
            'member'
        ])
            ->latest()
            ->paginate(10);

        return view('pages.event-registrations.index',
            compact('registrations')
        );
    }

    public function create()
    {
        $events = Event::orderBy('title')->get();

        $members = Member::orderBy('name')->get();

        return view('pages.event-registrations.create',
            compact(
                'events',
                'members'
            )
        );
    }

    public function store(
        EventRegistrationRequest $request
    ) {
        EventRegistration::create([

            'event_id' => $request->event_id,

            'member_id' => $request->member_id,

            'registered_at' => now()

        ]);

        return redirect()
            ->route(
                'admin.event-registrations.index'
            )
            ->with(
                'success',
                'Registrasi berhasil dibuat'
            );
    }

    public function edit(
        EventRegistration $eventRegistration
    ) {
        $events = Event::orderBy('title')->get();

        $members = Member::orderBy('name')->get();

        return view('pages.event-registrations.edit',
            compact(
                'eventRegistration',
                'events',
                'members'
            )
        );
    }

    public function update(
        EventRegistrationRequest $request,
        EventRegistration $eventRegistration
    ) {
        $eventRegistration->update([

            'event_id' => $request->event_id,

            'member_id' => $request->member_id

        ]);

        return redirect()
            ->route(
                'admin.event-registrations.index'
            )
            ->with(
                'success',
                'Registrasi berhasil diupdate'
            );
    }

    public function destroy(
        EventRegistration $eventRegistration
    ) {
        $eventRegistration->delete();

        return back()->with(
            'success',
            'Registrasi berhasil dihapus'
        );
    }
}
