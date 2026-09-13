<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Member;
use App\Models\Attendance;
use Illuminate\Http\Request;

class EventRegistrationController extends Controller
{
    // View all events (for members)
    public function index()
    {
        $events = Event::where('status', 'published')
            ->orderBy('event_date', 'desc')
            ->paginate(12);

        return view('pages.events.index', compact('events'));
    }

    // View single event details
    public function show(Event $event)
    {
        $event->load(['attendances.member', 'registrations.member']);
        return view('pages.events.show', compact('event'));
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
