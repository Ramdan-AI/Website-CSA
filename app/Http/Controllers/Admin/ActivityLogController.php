<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    /**
     * Riwayat aktivitas pengguna, khusus pemegang view-activity-log.
     */
    public function index(Request $request)
    {
        $validated = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'action' => ['nullable', 'in:created,updated,deleted'],
            'module' => ['nullable', 'string', 'max:60'],
        ]);

        $logs = ActivityLog::query()
            ->with('user:id,name,avatar')
            ->when($validated['search'] ?? null, fn ($query, $search) =>
                $query->where(function ($sub) use ($search) {
                    $sub->where('description', 'like', "%{$search}%")
                        ->orWhere('subject_label', 'like', "%{$search}%");
                })
            )
            ->when($validated['action'] ?? null, fn ($query, $action) => $query->where('action', $action))
            ->when($validated['module'] ?? null, fn ($query, $module) =>
                $query->where('subject_type', 'like', '%' . $module)
            )
            ->latestFirst()
            ->paginate(25)
            ->withQueryString();

        // Daftar modul yang pernah tercatat, untuk filter.
        $modules = ActivityLog::query()
            ->select('subject_type')
            ->distinct()
            ->pluck('subject_type')
            ->map(fn ($type) => class_basename($type))
            ->sort()
            ->values();

        return view('pages.activity-log.index', [
            'logs' => $logs,
            'modules' => $modules,
            'action' => $validated['action'] ?? '',
            'module' => $validated['module'] ?? '',
        ]);
    }

    public function destroy(ActivityLog $activityLog)
    {
        $activityLog->delete();

        return back()->with('success', 'Satu catatan aktivitas dihapus.');
    }

    /**
     * Bersihkan log lebih tua dari 14 hari. Dijalankan manual dari halaman.
     */
    public function purge()
    {
        $deleted = ActivityLog::where('created_at', '<', now()->subDays(14))->delete();

        return back()->with('success', $deleted . ' catatan lama dibersihkan.');
    }
}
