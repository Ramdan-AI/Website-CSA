<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Event;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DivisionReportController extends Controller
{
    /**
     * Rekap kehadiran anggota dalam divisi pengguna.
     *
     * Semua query di-scope ke divisi pemanggil, sehingga ketua/wakil divisi
     * hanya bisa melihat data divisinya sendiri.
     */
    public function index(Request $request)
    {
        $member = $this->currentMember();
        $division = $member->division;

        $validated = $request->validate([
            'event_id' => ['nullable', 'integer', 'exists:events,id'],
        ]);

        // Kegiatan untuk filter, dibatasi pada kegiatan yang relevan.
        $events = Event::query()
            ->select(['id', 'title', 'event_date'])
            ->orderByDesc('event_date')
            ->take(30)
            ->get();

        $eventId = $validated['event_id'] ?? null;

        // Hanya anggota divisi ini.
        $members = Member::query()
            ->select(['id', 'name', 'nim', 'division_id'])
            ->where('division_id', $division->id)
            ->orderBy('name')
            ->get();

        $memberIds = $members->pluck('id');

        // Rekap per anggota: total kehadiran dan jumlah hadir.
        $summary = Attendance::query()
            ->selectRaw('member_id')
            ->selectRaw('COUNT(*) as total')
            ->selectRaw("SUM(CASE WHEN status = 'present' THEN 1 ELSE 0 END) as present")
            ->selectRaw("SUM(CASE WHEN status = 'permit' THEN 1 ELSE 0 END) as permit")
            ->selectRaw("SUM(CASE WHEN status = 'sick' THEN 1 ELSE 0 END) as sick")
            ->selectRaw("SUM(CASE WHEN status = 'absent' THEN 1 ELSE 0 END) as absent")
            ->whereIn('member_id', $memberIds)
            ->when($eventId, fn ($query) => $query->where('event_id', $eventId))
            ->groupBy('member_id')
            ->get()
            ->keyBy('member_id');

        $rows = $members->map(function (Member $m) use ($summary) {
            $stat = $summary->get($m->id);
            $total = (int) ($stat->total ?? 0);
            $present = (int) ($stat->present ?? 0);

            return [
                'member' => $m,
                'total' => $total,
                'present' => $present,
                'permit' => (int) ($stat->permit ?? 0),
                'sick' => (int) ($stat->sick ?? 0),
                'absent' => (int) ($stat->absent ?? 0),
                'rate' => $total > 0 ? round(($present / $total) * 100, 1) : 0,
            ];
        });

        $divisionAverage = $rows->count() > 0
            ? round($rows->avg('rate'), 1)
            : 0;

        return view('pages.division-report.index', [
            'division' => $division,
            'rows' => $rows,
            'events' => $events,
            'eventId' => $eventId,
            'divisionAverage' => $divisionAverage,
        ]);
    }

    /**
     * Ekspor rekap divisi sebagai CSV.
     */
    public function export(Request $request)
    {
        $member = $this->currentMember();
        $division = $member->division;
        $eventId = $request->integer('event_id') ?: null;

        $members = Member::query()
            ->select(['id', 'name', 'nim'])
            ->where('division_id', $division->id)
            ->orderBy('name')
            ->get();

        $summary = Attendance::query()
            ->selectRaw('member_id')
            ->selectRaw('COUNT(*) as total')
            ->selectRaw("SUM(CASE WHEN status = 'present' THEN 1 ELSE 0 END) as present")
            ->whereIn('member_id', $members->pluck('id'))
            ->when($eventId, fn ($query) => $query->where('event_id', $eventId))
            ->groupBy('member_id')
            ->get()
            ->keyBy('member_id');

        $filename = 'rekap-kehadiran-' . $division->slug . '.csv';

        return response()->streamDownload(function () use ($members, $summary) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Nama', 'NIM', 'Total Tercatat', 'Hadir', 'Persentase']);

            foreach ($members as $m) {
                $stat = $summary->get($m->id);
                $total = (int) ($stat->total ?? 0);
                $present = (int) ($stat->present ?? 0);

                fputcsv($handle, [
                    $m->name,
                    $m->nim ?? '-',
                    $total,
                    $present,
                    $total > 0 ? round(($present / $total) * 100, 1) . '%' : '0%',
                ]);
            }

            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv']);
    }

    private function currentMember(): Member
    {
        $member = Auth::user()->member;

        abort_if(!$member || !$member->division_id, 403, 'Anda belum terhubung ke divisi mana pun.');

        return $member;
    }
}
