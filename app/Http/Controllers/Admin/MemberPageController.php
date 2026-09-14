<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Album;
use App\Models\Attendance;
use App\Models\Document;
use App\Models\Event;
use App\Models\Gallery;
use App\Models\Member;
use App\Models\News;
use App\Models\NewsCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MemberPageController extends Controller
{
    private function member()
    {
        return Auth::user()->member;
    }

    public function attendance(Request $request)
    {
        $member = $this->member();

        $stats = Attendance::where('member_id', $member->id)
            ->selectRaw("COUNT(*) as total")
            ->selectRaw("SUM(CASE WHEN status = 'present' THEN 1 ELSE 0 END) as present")
            ->selectRaw("SUM(CASE WHEN status = 'permit' THEN 1 ELSE 0 END) as permit")
            ->selectRaw("SUM(CASE WHEN status = 'sick' THEN 1 ELSE 0 END) as sick")
            ->selectRaw("SUM(CASE WHEN status = 'absent' THEN 1 ELSE 0 END) as absent")
            ->first();

        $validated = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'status' => ['nullable', 'in:present,permit,sick,absent'],
            'sort' => ['nullable', 'in:date_asc,date_desc,title_asc,title_desc'],
        ]);

        $sort = $validated['sort'] ?? 'date_desc';

        $attendances = Attendance::with('event')
            ->where('member_id', $member->id)
            ->when($validated['search'] ?? null, fn ($query, $search) =>
                $query->whereHas('event', fn ($sub) => $sub->where('title', 'like', "%{$search}%"))
            )
            ->when($validated['status'] ?? null, fn ($query, $status) =>
                $query->where('status', $status)
            )
            ->when($sort === 'date_asc', fn ($query) => $query->orderBy(
                Event::select('event_date')->whereColumn('events.id', 'attendances.event_id')
            ))
            ->when($sort === 'date_desc', fn ($query) => $query->orderByDesc(
                Event::select('event_date')->whereColumn('events.id', 'attendances.event_id')
            ))
            ->when($sort === 'title_asc', fn ($query) => $query->orderBy(
                Event::select('title')->whereColumn('events.id', 'attendances.event_id')
            ))
            ->when($sort === 'title_desc', fn ($query) => $query->orderByDesc(
                Event::select('title')->whereColumn('events.id', 'attendances.event_id')
            ))
            ->paginate(15)
            ->withQueryString();

        return view('pages.member.attendance.index', compact('attendances', 'stats'));
    }

    public function news(Request $request)
    {
        $validated = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'category_id' => ['nullable', 'integer', 'exists:news_categories,id'],
            'sort' => ['nullable', 'in:published_asc,published_desc,title_asc,title_desc'],
        ]);

        $sort = $validated['sort'] ?? 'published_desc';

        $news = News::with(['category', 'author'])
            ->where('status', 'published')
            ->when($validated['search'] ?? null, fn ($query, $search) =>
                $query->where(function ($sub) use ($search) {
                    $sub->where('title', 'like', "%{$search}%")
                        ->orWhere('content', 'like', "%{$search}%");
                })
            )
            ->when($validated['category_id'] ?? null, fn ($query, $categoryId) =>
                $query->where('category_id', $categoryId)
            )
            ->when($sort === 'published_asc', fn ($query) => $query->orderBy('published_at'))
            ->when($sort === 'published_desc', fn ($query) => $query->orderByDesc('published_at'))
            ->when($sort === 'title_asc', fn ($query) => $query->orderBy('title'))
            ->when($sort === 'title_desc', fn ($query) => $query->orderByDesc('title'))
            ->paginate(12)
            ->withQueryString();

        $categories = NewsCategory::orderBy('name')->get();

        return view('pages.member.news.index', compact('news', 'categories'));
    }

    public function documents(Request $request)
    {
        $validated = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'sort' => ['nullable', 'in:newest,oldest,title_asc,title_desc'],
        ]);

        $sort = $validated['sort'] ?? 'newest';

        $documents = Document::query()
            ->when($validated['search'] ?? null, fn ($query, $search) =>
                $query->where('title', 'like', "%{$search}%")
            )
            ->when($sort === 'newest', fn ($query) => $query->latest())
            ->when($sort === 'oldest', fn ($query) => $query->oldest())
            ->when($sort === 'title_asc', fn ($query) => $query->orderBy('title'))
            ->when($sort === 'title_desc', fn ($query) => $query->orderByDesc('title'))
            ->paginate(15)
            ->withQueryString();

        return view('pages.member.documents.index', compact('documents'));
    }

    public function calendar()
    {
        $events = Event::whereIn('status', ['published', 'closed'])
            ->get(['id', 'title', 'description', 'location', 'event_date', 'status', 'attendance_enabled', 'attendance_code']);

        return view('pages.member.calendar.index', compact('events'));
    }

    public function division(Request $request)
    {
        $member = $this->member();

        if (!$member?->division) {
            return redirect()->route('admin.dashboard')->with('error', 'Anda belum memiliki divisi.');
        }

        $members = $member->division->members()
            ->with(['position'])
            ->when($request->filled('search'), fn ($query) =>
                $query->where(function ($sub) use ($request) {
                    $sub->where('name', 'like', "%{$request->search}%")
                        ->orWhere('nim', 'like', "%{$request->search}%");
                })
            )
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        $division = $member->division;

        return view('pages.member.division.index', compact('division', 'members'));
    }

    public function gallery()
    {
        $albums = Album::withCount('galleries')->latest()->get();

        $heroGalleries = Gallery::where('is_hero', true)->latest()->get();
        $recentGalleries = Gallery::latest()->take(12)->get();

        return view('pages.member.gallery.index', compact('albums', 'heroGalleries', 'recentGalleries'));
    }

public function settings()
    {
        $user = Auth::user();
        $member = $this->member();

        return view('pages.member.settings.index', compact('user', 'member'));
    }

    public function markNotificationsSeen(Request $request)
    {
        session(['notifications_seen_at' => now()->timestamp]);
        return response()->json(['success' => true]);
    }

    // ==================== ATTENDANCE ACTIONS ====================

    public function selfAttendance(Request $request, Event $event)
    {
        $member = $this->member();

        if (!$event->attendance_enabled) {
            return back()->with('error', 'Presensi mandiri belum dibuka untuk kegiatan ini.');
        }

        if ($event->attendance_closed_at) {
            return back()->with('error', 'Sesi presensi sudah ditutup.');
        }

        if ($event->event_date->isPast()) {
            return back()->with('error', 'Kegiatan sudah lewat, tidak bisa presensi mandiri.');
        }

        $existing = Attendance::where('event_id', $event->id)
            ->where('member_id', $member->id)
            ->first();

        if ($existing) {
            return back()->with('info', 'Anda sudah tercatat hadir pada kegiatan ini.');
        }

        Attendance::create([
            'event_id' => $event->id,
            'member_id' => $member->id,
            'status' => 'present',
            'check_in_at' => now(),
        ]);

        return back()->with('success', 'Presensi mandiri berhasil! Anda tercatat HADIR.');
    }

    public function scanAttendance(Request $request, Event $event)
    {
        $request->validate([
            'code' => ['required', 'string', 'size:6'],
        ]);

        $member = $this->member();

        if (!$event->attendance_enabled || $event->attendance_code !== $request->code) {
            return back()->with('error', 'Kode QR tidak valid atau tidak sesuai dengan kegiatan ini.');
        }

        if ($event->attendance_closed_at) {
            return back()->with('error', 'Sesi presensi sudah ditutup.');
        }

        if ($event->event_date->isPast()) {
            return back()->with('error', 'Kegiatan sudah lewat, tidak bisa presensi via QR.');
        }

        $existing = Attendance::where('event_id', $event->id)
            ->where('member_id', $member->id)
            ->first();

        if ($existing) {
            return back()->with('info', 'Anda sudah tercatat hadir pada kegiatan ini.');
        }

        Attendance::create([
            'event_id' => $event->id,
            'member_id' => $member->id,
            'status' => 'present',
            'check_in_at' => now(),
        ]);

        return back()->with('success', 'Scan QR berhasil! Anda tercatat HADIR.');
    }

    public function sickAttendance(Request $request, Event $event)
    {
        $member = $this->member();

        if (!$event->attendance_enabled) {
            return back()->with('error', 'Presensi belum dibuka untuk kegiatan ini.');
        }

        if ($event->attendance_closed_at) {
            return back()->with('error', 'Sesi presensi sudah ditutup.');
        }

        $existing = Attendance::where('event_id', $event->id)
            ->where('member_id', $member->id)
            ->first();

        if ($existing) {
            $existing->update([
                'status' => 'sick',
                'check_in_at' => null,
            ]);
            return back()->with('success', 'Status diubah menjadi SAKIT.');
        }

        Attendance::create([
            'event_id' => $event->id,
            'member_id' => $member->id,
            'status' => 'sick',
            'check_in_at' => null,
        ]);

        return back()->with('success', 'Status dicatat sebagai SAKIT.');
    }

    public function permitAttendance(Request $request, Event $event)
    {
        $member = $this->member();

        if (!$event->attendance_enabled) {
            return back()->with('error', 'Presensi belum dibuka untuk kegiatan ini.');
        }

        if ($event->attendance_closed_at) {
            return back()->with('error', 'Sesi presensi sudah ditutup.');
        }

        $existing = Attendance::where('event_id', $event->id)
            ->where('member_id', $member->id)
            ->first();

        if ($existing) {
            $existing->update([
                'status' => 'permit',
                'check_in_at' => null,
            ]);
            return back()->with('success', 'Status diubah menjadi IZIN.');
        }

        Attendance::create([
            'event_id' => $event->id,
            'member_id' => $member->id,
            'status' => 'permit',
            'check_in_at' => null,
        ]);

        return back()->with('success', 'Status dicatat sebagai IZIN.');
    }

    public function alfaAttendance(Request $request, Event $event)
    {
        $member = $this->member();

        if (!$event->attendance_enabled) {
            return back()->with('error', 'Presensi belum dibuka untuk kegiatan ini.');
        }

        if (!$event->attendance_closed_at) {
            return back()->with('error', 'Sesi presensi belum ditutup, tidak bisa menandai Alfa.');
        }

        $existing = Attendance::where('event_id', $event->id)
            ->where('member_id', $member->id)
            ->first();

        if ($existing) {
            $existing->update([
                'status' => 'absent',
                'check_in_at' => null,
            ]);
            return back()->with('success', 'Status diubah menjadi ALFA.');
        }

        Attendance::create([
            'event_id' => $event->id,
            'member_id' => $member->id,
            'status' => 'absent',
            'check_in_at' => null,
        ]);

        return back()->with('success', 'Status dicatat sebagai ALFA.');
    }
}