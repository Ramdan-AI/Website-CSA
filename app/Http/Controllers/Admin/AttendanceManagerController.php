<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Event;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AttendanceManagerController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'date_from' => ['nullable', 'date'],
            'date_to' => ['nullable', 'date', 'after_or_equal:date_from'],
            'sort' => ['nullable', 'in:date_asc,date_desc,title_asc,title_desc'],
        ]);

        $sort = $validated['sort'] ?? 'date_desc';

        $events = Event::query()
            ->withCount([
                'attendances',
                'attendances as present_count' => fn ($q) => $q->where('status', 'present'),
                'attendances as permit_count' => fn ($q) => $q->where('status', 'permit'),
                'attendances as sick_count' => fn ($q) => $q->where('status', 'sick'),
                'attendances as absent_count' => fn ($q) => $q->where('status', 'absent'),
            ])
            ->when($validated['search'] ?? null, fn ($query, $search) => $query->where('title', 'like', "%{$search}%")
            )
            ->when($validated['date_from'] ?? null, fn ($query, $date) => $query->whereDate('event_date', '>=', $date)
            )
            ->when($validated['date_to'] ?? null, fn ($query, $date) => $query->whereDate('event_date', '<=', $date)
            )
            ->when($sort === 'date_asc', fn ($query) => $query->orderBy('event_date'))
            ->when($sort === 'date_desc', fn ($query) => $query->orderByDesc('event_date'))
            ->when($sort === 'title_asc', fn ($query) => $query->orderBy('title'))
            ->when($sort === 'title_desc', fn ($query) => $query->orderByDesc('title'))
            ->paginate(15)
            ->withQueryString();

        return view('pages.attendances.index', compact('events'));
    }

    public function showEvent(Event $event)
    {
        $event->loadCount([
            'attendances',
            'attendances as present_count' => fn ($q) => $q->where('status', 'present'),
            'attendances as permit_count' => fn ($q) => $q->where('status', 'permit'),
            'attendances as sick_count' => fn ($q) => $q->where('status', 'sick'),
            'attendances as absent_count' => fn ($q) => $q->where('status', 'absent'),
        ]);

        $members = Member::with(['division', 'position'])
            ->active()
            ->orderBy('name')
            ->get();

        $attendances = Attendance::where('event_id', $event->id)
            ->get()
            ->keyBy('member_id');

        return view('pages.attendances.event', compact('event', 'members', 'attendances'));
    }

    public function toggleAttendance(Request $request, Event $event)
    {
        Gate::authorize('manage-attendances');

        try {
            $enabled = ! $event->self_attendance_enabled;
            $event->update(['self_attendance_enabled' => $enabled]);

            return redirect()->back()->with('success', $enabled
                ? 'Presensi mandiri diaktifkan.'
                : 'Presensi mandiri dinonaktifkan. Anggota harus scan QR.');
        } catch (\Exception $e) {
            Log::error('Toggle self attendance failed', ['event_id' => $event->id, 'error' => $e->getMessage()]);

            return redirect()->back()->with('error', 'Gagal mengubah status presensi mandiri.');
        }
    }

    public function openAttendance(Request $request, Event $event)
    {
        Gate::authorize('manage-attendances');

        try {
            $event->update([
                'attendance_enabled' => true,
                'attendance_code' => $event->attendance_code ?: strtoupper(Str::random(6)),
                'attendance_opened_at' => now(),
                'attendance_closed_at' => null,
            ]);

            return redirect()->back()->with('success', 'Sesi presensi dibuka.');
        } catch (\Exception $e) {
            Log::error('Open attendance failed', ['event_id' => $event->id, 'error' => $e->getMessage()]);

            return redirect()->back()->with('error', 'Gagal membuka sesi presensi.');
        }
    }

    public function closeAttendance(Request $request, Event $event)
    {
        Gate::authorize('manage-attendances');

        try {
            $event->update([
                'attendance_closed_at' => now(),
                'self_attendance_enabled' => false,
            ]);

            // Setelah sesi ditutup, anggota yang belum hadir/izin/sakit otomatis Alfa.
            $recorded = Attendance::where('event_id', $event->id)->pluck('member_id');

            $absent = Member::whereNotIn('id', $recorded)
                ->where('is_active', true)
                ->pluck('id');

            $now = now();
            $rows = $absent->map(fn ($memberId) => [
                'event_id' => $event->id,
                'member_id' => $memberId,
                'status' => 'absent',
                'note' => null,
                'check_in_at' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ])->all();

            if ($rows !== []) {
                Attendance::insert($rows);
            }

            return redirect()->back()->with('success', 'Sesi presensi ditutup. '.count($rows).' anggota ditandai Alfa.');
        } catch (\Exception $e) {
            Log::error('Close attendance failed', ['event_id' => $event->id, 'error' => $e->getMessage()]);

            return redirect()->back()->with('error', 'Gagal menutup sesi presensi.');
        }
    }

    public function generateQrCode(Request $request, Event $event)
    {
        Gate::authorize('manage-attendances');

        try {
            if (! $event->attendance_enabled) {
                $event->update([
                    'attendance_enabled' => true,
                    'attendance_code' => strtoupper(Str::random(6)),
                ]);
            } elseif (! $event->attendance_code) {
                $event->update(['attendance_code' => strtoupper(Str::random(6))]);
            }

            $url = route('admin.attendance.scan', ['event' => $event->id, 'code' => $event->attendance_code]);
            $event->update(['qr_code' => $url]);

            return redirect()->route('admin.attendances.qr', [
                'roleSlug' => auth()->user()->role->slug,
                'event' => $event->id,
            ]);
        } catch (\Exception $e) {
            Log::error('Generate QR failed', ['event_id' => $event->id, 'error' => $e->getMessage()]);

            return redirect()->back()->with('error', 'Gagal membuat QR presensi.');
        }
    }

    public function showQr(Event $event)
    {
        Gate::authorize('manage-attendances');

        if (! $event->attendance_enabled || ! $event->attendance_code) {
            $event->update([
                'attendance_enabled' => true,
                'attendance_code' => strtoupper(Str::random(6)),
            ]);
        }

        return view('pages.attendances.qr', compact('event'));
    }

    public function bulkManual(Request $request, Event $event)
    {
        Gate::authorize('manage-attendances');

        $validated = $request->validate([
            'attendances' => ['required', 'array'],
            'attendances.*.member_id' => ['required', 'exists:members,id'],
            'attendances.*.status' => ['required', 'in:present,permit,sick,absent'],
        ]);

        try {
            DB::transaction(function () use ($validated, $event) {
                foreach ($validated['attendances'] as $row) {
                    Attendance::updateOrCreate(
                        [
                            'event_id' => $event->id,
                            'member_id' => $row['member_id'],
                        ],
                        [
                            'status' => $row['status'],
                            'check_in_at' => $row['status'] === 'present' ? now() : null,
                        ]
                    );
                }
            });

            return redirect()->back()->with('success', 'Data presensi berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('Bulk attendance failed', ['event_id' => $event->id, 'error' => $e->getMessage()]);

            return redirect()->back()->with('error', 'Gagal memperbarui data presensi.');
        }
    }

    public function scanAttendance(Request $request, Event $event, $code)
    {
        if (! $event->attendance_enabled || $event->attendance_code !== $code) {
            return response()->json(['error' => 'Kode presensi tidak valid'], 403);
        }

        if ($event->attendance_closed_at) {
            return response()->json(['error' => 'Sesi presensi sudah ditutup'], 403);
        }

        $member = auth()->user()->member;
        if (! $member) {
            return response()->json(['error' => 'Data anggota tidak ditemukan'], 404);
        }

        try {
            $existing = Attendance::where('event_id', $event->id)
                ->where('member_id', $member->id)
                ->first();

            if ($existing) {
                return response()->json(['message' => 'Anda sudah tercatat hadir', 'attendance' => $existing]);
            }

            $attendance = Attendance::create([
                'event_id' => $event->id,
                'member_id' => $member->id,
                'status' => 'present',
                'check_in_at' => now(),
            ]);

            Log::info('Scan attendance recorded', ['event_id' => $event->id, 'member_id' => $member->id]);

            return response()->json([
                'message' => 'Kehadiran berhasil dicatat',
                'attendance' => $attendance,
            ]);
        } catch (\Exception $e) {
            Log::error('Scan attendance failed', ['event_id' => $event->id, 'error' => $e->getMessage()]);

            return response()->json(['error' => 'Gagal mencatat kehadiran'], 500);
        }
    }

    public function selfAttendance(Request $request, Event $event)
    {
        if (! $event->attendance_enabled) {
            return redirect()->back()->with('error', 'Presensi belum diaktifkan untuk kegiatan ini.');
        }

        if ($event->attendance_closed_at) {
            return redirect()->back()->with('error', 'Sesi presensi sudah ditutup.');
        }

        $member = auth()->user()->member;
        if (! $member) {
            return redirect()->back()->with('error', 'Data anggota tidak ditemukan.');
        }

        try {
            $existing = Attendance::where('event_id', $event->id)
                ->where('member_id', $member->id)
                ->first();

            if ($existing) {
                return redirect()->back()->with('info', 'Anda sudah tercatat hadir pada kegiatan ini.');
            }

            Attendance::create([
                'event_id' => $event->id,
                'member_id' => $member->id,
                'status' => 'present',
                'check_in_at' => now(),
            ]);

            Log::info('Self attendance recorded', ['event_id' => $event->id, 'member_id' => $member->id]);

            return redirect()->back()->with('success', 'Kehadiran berhasil dicatat.');
        } catch (\Exception $e) {
            Log::error('Self attendance failed', ['event_id' => $event->id, 'error' => $e->getMessage()]);

            return redirect()->back()->with('error', 'Gagal mencatat kehadiran. Silakan coba lagi.');
        }
    }

    public function manualAttendance(Request $request, Event $event)
    {
        Gate::authorize('manage-attendances');

        $request->validate([
            'member_id' => 'required|exists:members,id',
            'status' => 'required|in:present,permit,sick,absent',
        ]);

        try {
            $existing = Attendance::where('event_id', $event->id)
                ->where('member_id', $request->member_id)
                ->first();

            if ($existing) {
                $existing->update([
                    'status' => $request->status,
                    'check_in_at' => $request->status === 'present' ? now() : null,
                ]);
            } else {
                Attendance::create([
                    'event_id' => $event->id,
                    'member_id' => $request->member_id,
                    'status' => $request->status,
                    'check_in_at' => $request->status === 'present' ? now() : null,
                ]);
            }

            return redirect()->back()->with('success', 'Data kehadiran berhasil disimpan.');
        } catch (\Exception $e) {
            Log::error('Manual attendance failed', ['event_id' => $event->id, 'error' => $e->getMessage()]);

            return redirect()->back()->with('error', 'Gagal menyimpan data kehadiran.');
        }
    }

    public function exportAttendance(Request $request, Event $event)
    {
        Gate::authorize('manage-attendances');

        $attendances = Attendance::with(['member', 'member.division'])
            ->where('event_id', $event->id)
            ->get();

        $filename = "presensi_{$event->slug}_".date('Y-m-d').'.csv';
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($attendances, $event) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($file, [
                'Kegiatan',
                'Nama',
                'NIM',
                'Divisi',
                'Status',
                'Waktu Check-in',
                'Dicatat Pada',
            ]);

            foreach ($attendances as $attendance) {
                fputcsv($file, [
                    $event->title,
                    $attendance->member->name ?? '-',
                    $attendance->member->nim ?? '-',
                    $attendance->member->division->name ?? '-',
                    ['present' => 'Hadir', 'permit' => 'Izin', 'sick' => 'Sakit', 'absent' => 'Alfa'][$attendance->status] ?? $attendance->status,
                    $attendance->check_in_at ? $attendance->check_in_at->format('d/m/Y H:i:s') : '-',
                    $attendance->created_at->format('d/m/Y H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function getStats(Request $request, Event $event)
    {
        $stats = Attendance::where('event_id', $event->id)
            ->selectRaw('COUNT(*) as total')
            ->selectRaw("SUM(CASE WHEN status = 'present' THEN 1 ELSE 0 END) as present")
            ->selectRaw("SUM(CASE WHEN status = 'permit' THEN 1 ELSE 0 END) as permit")
            ->selectRaw("SUM(CASE WHEN status = 'sick' THEN 1 ELSE 0 END) as sick")
            ->selectRaw("SUM(CASE WHEN status = 'absent' THEN 1 ELSE 0 END) as absent")
            ->first();

        return response()->json([
            'total' => $stats->total ?? 0,
            'present' => $stats->present ?? 0,
            'permit' => $stats->permit ?? 0,
            'sick' => $stats->sick ?? 0,
            'absent' => $stats->absent ?? 0,
            'attendance_rate' => $stats->total > 0 ? round(($stats->present / $stats->total) * 100, 1) : 0,
        ]);
    }
}
