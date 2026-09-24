<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Setting;
use App\Support\LogRetentionService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ActivityLogController extends Controller
{
    public function __construct(private readonly LogRetentionService $retention) {}

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
            ->when($validated['search'] ?? null, fn ($query, $search) => $query->where(function ($sub) use ($search) {
                $sub->where('description', 'like', "%{$search}%")
                    ->orWhere('subject_label', 'like', "%{$search}%");
            })
            )
            ->when($validated['action'] ?? null, fn ($query, $action) => $query->where('action', $action))
            ->when($validated['module'] ?? null, fn ($query, $module) => $query->where('subject_type', 'like', '%'.$module)
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

        $setting = Setting::firstOrCreate(['id' => 1], ['site_name' => 'CSA']);
        if ($setting->wasRecentlyCreated) {
            $setting->refresh();
        }

        return view('pages.activity-log.index', [
            'logs' => $logs,
            'modules' => $modules,
            'action' => $validated['action'] ?? '',
            'module' => $validated['module'] ?? '',
            'setting' => $setting,
            'retentionOptions' => Setting::LOG_RETENTION_DAYS,
            'remainingDays' => $this->retention->remainingDays($setting),
        ]);
    }

    /**
     * Simpan setelan penghapusan log otomatis.
     */
    public function updateSettings(Request $request)
    {
        $validated = $request->validate([
            'log_auto_purge_enabled' => ['required', 'boolean'],
            'log_retention_days' => ['required', 'integer', Rule::in(Setting::LOG_RETENTION_DAYS)],
        ]);

        $this->retention->updateSettings(
            enabled: $request->boolean('log_auto_purge_enabled'),
            days: (int) $validated['log_retention_days'],
        );

        $message = $request->boolean('log_auto_purge_enabled')
            ? "Penghapusan otomatis aktif: catatan lebih tua dari {$validated['log_retention_days']} hari akan dibersihkan."
            : 'Penghapusan otomatis dinonaktifkan. Log lama tetap tersimpan.';

        return back()->with('success', $message);
    }

    public function destroy(ActivityLog $activityLog)
    {
        $activityLog->delete();

        return back()->with('success', 'Satu catatan aktivitas dihapus.');
    }

    /**
     * Bersihkan log lebih tua dari periode retensi yang sedang dipilih.
     * Manual: tetap berjalan walaupun penghapusan otomatis nonaktif.
     */
    public function purge()
    {
        $setting = Setting::firstOrCreate(['id' => 1], ['site_name' => 'CSA']);
        $days = in_array((int) $setting->log_retention_days, Setting::LOG_RETENTION_DAYS, true)
            ? (int) $setting->log_retention_days
            : 14;

        $deleted = $this->retention->purgeOldLogs($days);

        return back()->with('success', "{$deleted} catatan lebih tua dari {$days} hari dibersihkan.");
    }
}
