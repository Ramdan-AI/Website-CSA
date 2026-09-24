<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Album;
use App\Models\Attendance;
use App\Models\Document;
use App\Models\Event;
use App\Models\Gallery;
use App\Models\News;
use App\Models\NewsCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MemberPageController extends Controller
{
    /**
     * Resolve the member profile linked to the authenticated user.
     */
    private function member()
    {
        return Auth::user()->member;
    }

    /**
     * Some pages only make sense for users with a member profile.
     * Non-member staff (e.g. pure admin accounts) get redirected instead of a 500.
     */
    private function requireMember()
    {
        $member = $this->member();

        if (! $member) {
            abort(redirect()->route('admin.dashboard')
                ->with('error', 'Akun Anda belum terhubung dengan data anggota.'));
        }

        return $member;
    }

    public function attendance(Request $request)
    {
        $member = $this->requireMember();

        $statsRow = Attendance::where('member_id', $member->id)
            ->selectRaw('COUNT(*) as total')
            ->selectRaw("SUM(CASE WHEN status = 'present' THEN 1 ELSE 0 END) as present")
            ->selectRaw("SUM(CASE WHEN status = 'permit' THEN 1 ELSE 0 END) as permit")
            ->selectRaw("SUM(CASE WHEN status = 'sick' THEN 1 ELSE 0 END) as sick")
            ->selectRaw("SUM(CASE WHEN status = 'absent' THEN 1 ELSE 0 END) as absent")
            ->first();

        $stats = [
            'present' => (int) ($statsRow->present ?? 0),
            'permit' => (int) ($statsRow->permit ?? 0),
            'sick' => (int) ($statsRow->sick ?? 0),
            'absent' => (int) ($statsRow->absent ?? 0),
        ];

        $validated = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'status' => ['nullable', 'in:present,permit,sick,absent'],
            'sort' => ['nullable', 'in:date_asc,date_desc,title_asc,title_desc'],
        ]);

        $sort = $validated['sort'] ?? 'date_desc';

        $attendances = Attendance::with('event')
            ->where('member_id', $member->id)
            ->when($validated['search'] ?? null, fn ($query, $search) => $query->whereHas('event', fn ($sub) => $sub->where('title', 'like', "%{$search}%"))
            )
            ->when($validated['status'] ?? null, fn ($query, $status) => $query->where('status', $status)
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
            ->when($validated['search'] ?? null, fn ($query, $search) => $query->where(function ($sub) use ($search) {
                $sub->where('title', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%");
            })
            )
            ->when($validated['category_id'] ?? null, fn ($query, $categoryId) => $query->where('category_id', $categoryId)
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
            ->with('user')
            ->when($validated['search'] ?? null, fn ($query, $search) => $query->where('title', 'like', "%{$search}%")
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
        $member = $this->member();

        $events = Event::whereIn('status', ['published', 'closed'])
            ->orderByDesc('event_date')
            ->get([
                'id',
                'title',
                'slug',
                'type',
                'description',
                'location',
                'event_date',
                'banner',
                'status',
                'attendance_enabled',
                'self_attendance_enabled',
                'attendance_code',
                'attendance_closed_at',
            ]);

        $attendanceByEvent = $member
            ? Attendance::where('member_id', $member->id)
                ->whereIn('event_id', $events->pluck('id'))
                ->get()
                ->keyBy('event_id')
            : collect();

        return view('pages.member.calendar.index', compact('events', 'attendanceByEvent'));
    }

    public function division(Request $request)
    {
        $member = $this->requireMember();

        if (! $member->division) {
            return redirect()->route('admin.dashboard')->with('error', 'Anda belum memiliki divisi.');
        }

        $members = $member->division->members()
            ->with(['position'])
            ->when($request->filled('search'), fn ($query) => $query->where(function ($sub) use ($request) {
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

        $heroGalleries = Gallery::with('album')->where('is_hero', true)->latest()->get();
        $recentGalleries = Gallery::with('album')->latest()->take(12)->get();

        return view('pages.member.gallery.index', compact('albums', 'heroGalleries', 'recentGalleries'));
    }

    public function markNotificationsSeen(Request $request)
    {
        session(['notifications_seen_at' => now()->timestamp]);

        return response()->json(['success' => true]);
    }

    // ==================== ATTENDANCE ACTIONS ====================

    public function selfAttendance(Request $request, Event $event)
    {
        $member = $this->requireMember();

        if (! $event->attendance_enabled || $event->attendance_closed_at) {
            return back()->with('error', 'Sesi presensi belum dibuka atau sudah ditutup.');
        }

        if (! $event->self_attendance_enabled) {
            return back()->with('error', 'Presensi mandiri tidak diaktifkan. Silakan scan QR presensi.');
        }

        if ($this->attendanceTodayPassed($event)) {
            return back()->with('error', 'Kegiatan sudah lewat, tidak bisa presensi mandiri.');
        }

        return $this->recordPresence($event, $member, 'present', 'Presensi mandiri berhasil! Anda tercatat HADIR.');
    }

    public function scanAttendance(Request $request, Event $event)
    {
        $request->validate([
            'code' => ['required', 'string', 'size:6'],
        ]);

        $member = $this->requireMember();

        // QR hanya butuh sesi aktif, tidak bergantung preset mandiri.
        if (! $event->attendance_enabled || $event->attendance_code !== $request->code) {
            return back()->with('error', 'Kode QR tidak valid atau tidak sesuai dengan kegiatan ini.');
        }

        if ($event->attendance_closed_at) {
            return back()->with('error', 'Sesi presensi sudah ditutup.');
        }

        if ($this->attendanceTodayPassed($event)) {
            return back()->with('error', 'Kegiatan sudah lewat, tidak bisa presensi via QR.');
        }

        return $this->recordPresence($event, $member, 'present', 'Scan QR berhasil! Anda tercatat HADIR.');
    }

    public function sickAttendance(Request $request, Event $event)
    {
        $member = $this->requireMember();

        if ($error = $this->attendanceWindowGuard($event)) {
            return back()->with('error', $error);
        }

        $validated = $request->validate([
            'note' => ['required', 'string', 'max:255'],
        ], [
            'note.required' => 'Mohon isi alasan sakit Anda.',
        ]);

        return $this->recordPresence($event, $member, 'sick', 'Status dicatat sebagai SAKIT.', $validated['note']);
    }

    public function permitAttendance(Request $request, Event $event)
    {
        $member = $this->requireMember();

        if ($error = $this->attendanceWindowGuard($event)) {
            return back()->with('error', $error);
        }

        $validated = $request->validate([
            'note' => ['required', 'string', 'max:255'],
        ], [
            'note.required' => 'Mohon isi alasan izin Anda.',
        ]);

        return $this->recordPresence($event, $member, 'permit', 'Status dicatat sebagai IZIN.', $validated['note']);
    }

    public function alfaAttendance(Request $request, Event $event)
    {
        $member = $this->requireMember();

        if (! $event->attendance_enabled) {
            return back()->with('error', 'Presensi belum dibuka untuk kegiatan ini.');
        }

        if (! $event->attendance_closed_at) {
            return back()->with('error', 'Sesi presensi belum ditutup, tidak bisa menandai Alfa.');
        }

        return $this->recordPresence($event, $member, 'absent', 'Status dicatat sebagai ALFA.');
    }

    // ==================== HELPERS ====================

    /**
     * Guard untuk aksi dalam sesi presensi (sakit/izin): sesi harus aktif
     * dan belum ditutup. Tidak bergantung pada preset mandiri.
     */
    private function attendanceWindowGuard(Event $event): ?string
    {
        if (! $event->attendance_enabled) {
            return 'Presensi belum dibuka untuk kegiatan ini.';
        }

        if ($event->attendance_closed_at) {
            return 'Sesi presensi sudah ditutup.';
        }

        return null;
    }

    /**
     * An event counts as "passed" only after the end of its scheduled day,
     * not at 00:00 of that same day.
     */
    private function attendanceTodayPassed(Event $event): bool
    {
        return $event->event_date && $event->event_date->isBefore(now()->startOfDay());
    }

    /**
     * Create or update the single attendance row for this member/event pair.
     */
    private function recordPresence(Event $event, $member, string $status, string $message, ?string $note = null)
    {
        $existing = Attendance::where('event_id', $event->id)
            ->where('member_id', $member->id)
            ->first();

        $attributes = [
            'status' => $status,
            'note' => $note,
            'check_in_at' => $status === 'present' ? now() : null,
        ];

        if ($existing) {
            if ($existing->status === $status && $status === 'present') {
                return back()->with('info', 'Anda sudah tercatat hadir pada kegiatan ini.');
            }

            $existing->update($attributes);

            return back()->with('success', $message);
        }

        Attendance::create(array_merge($attributes, [
            'event_id' => $event->id,
            'member_id' => $member->id,
        ]));

        return back()->with('success', $message);
    }
}
