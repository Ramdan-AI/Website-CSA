<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Member;
use App\Models\EventRegistration;
use App\Models\Attendance;
use App\Http\Requests\EventRegistrationRequest;
use Illuminate\Http\Request;

class EventRegistrationController extends Controller
{
    // View all registrations (admin)
    public function index()
    {
        $registrations = EventRegistration::with(['event', 'member'])
            ->latest()
            ->paginate(12);

        return view('pages.event-registrations.index', compact('registrations'));
    }

    public function create()
    {
        $events = Event::orderBy('title')->get();
        $members = Member::orderBy('name')->get();

        return view('pages.event-registrations.create', compact('events', 'members'));
    }

    public function store(EventRegistrationRequest $request)
    {
        EventRegistration::create([
            'event_id' => $request->event_id,
            'member_id' => $request->member_id,
            'registered_at' => now(),
        ]);

        return redirect()->route('admin.event-registrations.index')->with('success', 'Pendaftaran berhasil dibuat.');
    }

    public function edit(EventRegistration $eventRegistration)
    {
        $events = Event::orderBy('title')->get();
        $members = Member::orderBy('name')->get();

        return view('pages.event-registrations.edit', compact('eventRegistration', 'events', 'members'));
    }

    public function update(EventRegistrationRequest $request, EventRegistration $eventRegistration)
    {
        $eventRegistration->update([
            'event_id' => $request->event_id,
            'member_id' => $request->member_id,
        ]);

        return redirect()->route('admin.event-registrations.index')->with('success', 'Pendaftaran berhasil diperbarui.');
    }

    public function destroy(EventRegistration $eventRegistration)
    {
        $eventRegistration->delete();

        return back()->with('success', 'Pendaftaran berhasil dihapus.');
    }

    // Register for event (self-service)
    public function register(Request $request, Event $event)
    {
        if (!$event->attendance_enabled) {
            return redirect()->back()->with('error', 'Event ini tidak tersedia untuk pendaftaran.');
        }

        if ($event->attendance_closed_at) {
            return redirect()->back()->with('error', 'Pendaftaran untuk event ini telah ditutup.');
        }

        $member = auth()->user()->member;
        if (!$member) {
            return redirect()->back()->with('error', 'Data anggota tidak ditemukan.');
        }

        $existing = Attendance::where('event_id', $event->id)
            ->where('member_id', $member->id)
            ->first();

        if ($existing) {
            return redirect()->back()->with('info', 'Anda sudah terdaftar pada event ini.');
        }

        Attendance::create([
            'event_id' => $event->id,
            'member_id' => $member->id,
            'status' => 'present',
            'check_in_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Pendaftaran berhasil!');
    }
}
