<?php

namespace App\Http\Controllers\Admin;

use App\Models\Event;
use App\Models\Member;
use App\Models\Attendance;
use App\Http\Controllers\Controller;
use App\Http\Requests\AttendanceRequest;

class AttendanceController extends Controller
{
    public function index()
    {
        $attendances = Attendance::with([
            'event',
            'member'
        ])
            ->latest()
            ->paginate(10);

        return view(
            'admin.attendances.index',
            compact('attendances')
        );
    }

    public function create()
    {
        $events = Event::orderBy('title')->get();

        $members = Member::orderBy('name')->get();

        return view(
            'admin.attendances.create',
            compact(
                'events',
                'members'
            )
        );
    }

    public function store(
        AttendanceRequest $request
    ) {
        Attendance::create([

            'event_id' => $request->event_id,

            'member_id' => $request->member_id,

            'status' => $request->status,

            'check_in_at' => now()

        ]);

        return redirect()
            ->route('admin.attendances.index')
            ->with(
                'success',
                'Absensi berhasil ditambahkan'
            );
    }

    public function edit(
        Attendance $attendance
    ) {
        $events = Event::orderBy('title')->get();

        $members = Member::orderBy('name')->get();

        return view(
            'admin.attendances.edit',
            compact(
                'attendance',
                'events',
                'members'
            )
        );
    }

    public function update(
        AttendanceRequest $request,
        Attendance $attendance
    ) {
        $attendance->update([

            'event_id' => $request->event_id,

            'member_id' => $request->member_id,

            'status' => $request->status

        ]);

        return redirect()
            ->route('admin.attendances.index')
            ->with(
                'success',
                'Absensi berhasil diupdate'
            );
    }

    public function destroy(
        Attendance $attendance
    ) {
        $attendance->delete();

        return back()->with(
            'success',
            'Absensi berhasil dihapus'
        );
    }

    public function show(
        Attendance $attendance
    ) {
        abort(404);
    }
}
