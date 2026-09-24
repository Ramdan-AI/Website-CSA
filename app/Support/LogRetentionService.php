<?php

namespace App\Support;

use App\Models\ActivityLog;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;

/**
 * Satu sumber kebenaran untuk retensi log sistem.
 *
 * Dipakai oleh ActivityLogController (setelan) dan oleh command
 * terjadwal (routes/console.php), sehingga logika tidak terduplikasi.
 *
 * Aturan bisnis:
 *  - Saat diaktifkan atau periode diubah, hitungan dimulai dari hari ini.
 *  - Saat dinonaktifkan, jadwal dikosongkan (bukan dijeda); log lama tetap ada.
 *  - Purge manual mengikuti periode yang dipilih; tak bergantung status on/off.
 *  - Penghapusan selalu berbasis created_at < now() - retention_days.
 */
class LogRetentionService
{
    /**
     * Terapkan setelan retensi dari admin.
     *
     * Mengaktifkan berarti menjadwalkan purge berikutnya = sekarang + periode,
     * terhitung mulai hari ini. Menonaktifkan menghapus jadwal sepenuhnya.
     */
    public function updateSettings(bool $enabled, int $days): Setting
    {
        $setting = $this->setting();
        $previous = $setting->only([
            'log_auto_purge_enabled',
            'log_retention_days',
        ]);

        if ($enabled) {
            $setting->forceFill([
                'log_auto_purge_enabled' => true,
                'log_retention_days' => $days,
                'log_retention_started_at' => now(),
                'log_next_purge_at' => now()->addDays($days),
            ])->save();
        } else {
            $setting->forceFill([
                'log_auto_purge_enabled' => false,
                'log_retention_days' => $days,
                'log_retention_started_at' => null,
                'log_next_purge_at' => null,
            ])->save();
        }

        $this->recordAudit(
            description: $enabled
                ? "Mengaktifkan penghapusan log otomatis: {$days} hari"
                : 'Menonaktifkan penghapusan log otomatis',
            from: $previous,
            to: [
                'log_auto_purge_enabled' => $enabled,
                'log_retention_days' => $days,
            ],
        );

        return $setting->refresh();
    }

    /**
     * Hapus log lebih tua dari periode yang diberikan. Mengembalikan jumlah
     * baris terhapus. Aman dipanggil kapan pun, termasuk saat otomatis mati.
     */
    public function purgeOldLogs(int $days): int
    {
        // Hitung batas di awal agar konsisten walau eksekusi berlangsung lama.
        $threshold = now()->subDays($days);

        return ActivityLog::where('created_at', '<', $threshold)->delete();
    }

    /**
     * True bila penghapusan otomatis aktif dan jadwalnya sudah tiba.
     */
    public function isDue(Setting $setting): bool
    {
        return (bool) $setting->log_auto_purge_enabled
            && $setting->log_next_purge_at !== null
            && $setting->log_next_purge_at->lessThanOrEqualTo(now());
    }

    /**
     * Jalankan purge terjadwal. Idempotent: bila otomatis mati atau belum
     * jadwalnya, tidak melakukan apa-apa dan mengembalikan 0.
     */
    public function runScheduledPurge(): int
    {
        $setting = $this->setting();

        if (! $setting->log_auto_purge_enabled) {
            return 0;
        }

        // Jalankan bila jadwal tiba. Bila next_purge_at kosong (data lama),
        // anggap belum waktunya agar tidak menghapus tanpa jadwal jelas.
        if ($setting->log_next_purge_at !== null && ! $this->isDue($setting)) {
            return 0;
        }

        $days = $this->resolvedDays($setting);
        $deleted = $this->purgeOldLogs($days);

        // Hitung ulang jadwal berikutnya dari sekarang (catch-up safe).
        $setting->forceFill([
            'log_next_purge_at' => now()->addDays($days),
        ])->save();

        $this->recordAudit(
            description: "Purge otomatis menghapus {$deleted} catatan lebih tua dari {$days} hari",
            from: null,
            to: null,
        );

        return $deleted;
    }

    /**
     * Sisa hari menuju purge berikutnya, atau null bila tidak dijadwalkan.
     */
    public function remainingDays(Setting $setting): ?int
    {
        if (! $setting->log_auto_purge_enabled || $setting->log_next_purge_at === null) {
            return null;
        }

        // Dibulatkan ke atas: 0 hari berarti "kurang dari sehari lagi".
        return max(0, (int) ceil(now()->diffInSeconds($setting->log_next_purge_at, false) / 86400));
    }

    /**
     * Periode efektif: pakai setelan bila valid, jika tidak jatuh ke 14 hari.
     */
    private function resolvedDays(Setting $setting): int
    {
        $days = (int) $setting->log_retention_days;

        return in_array($days, Setting::LOG_RETENTION_DAYS, true) ? $days : 14;
    }

    /**
     * Ambil (atau buat) baris setelan tunggal, lalu refresh agar nilai
     * default dari database ikut termuat pada instance yang baru dibuat.
     */
    private function setting(): Setting
    {
        $setting = Setting::firstOrCreate(['id' => 1], ['site_name' => 'CSA']);

        return $setting->wasRecentlyCreated ? $setting->refresh() : $setting;
    }

    /**
     * Tulis jejak audit perubahan setelan. Dipanggil eksplisit di sini —
     * bukan lewat trait LogsActivity — agar tidak memicu rekursi tak terbatas,
     * karena ActivityLog sendiri tidak memakai trait tersebut.
     */
    private function recordAudit(string $description, ?array $from, ?array $to): void
    {
        $changes = null;

        if ($from !== null && $to !== null) {
            $changes = [];

            foreach ($to as $field => $newValue) {
                $changes[$field] = [
                    'from' => $this->stringify($from[$field] ?? null),
                    'to' => $this->stringify($newValue),
                ];
            }
        }

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'updated',
            'subject_type' => Setting::class,
            'subject_id' => 1,
            'subject_label' => 'Retensi Log',
            'description' => $description,
            'changes' => $changes,
            'ip_address' => request()->ip(),
            'created_at' => now(),
        ]);
    }

    private function stringify(mixed $value): ?string
    {
        if ($value === null) {
            return null;
        }

        if (is_bool($value)) {
            return $value ? 'ya' : 'tidak';
        }

        return (string) $value;
    }
}
