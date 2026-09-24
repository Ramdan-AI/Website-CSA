<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    /**
     * Pilihan periode retensi log (hari) yang boleh dipilih admin.
     * Satu sumber kebenaran untuk validasi, service, dan UI.
     */
    public const LOG_RETENTION_DAYS = [7, 14, 30, 60, 90];

    protected $fillable = [
        'site_name',
        'logo',
        'email',
        'phone',
        'address',
        'instagram',
        'youtube',
        'linkedin',
        'about',
        'registration_open',
        'registration_use_schedule',
        'registration_opens_at',
        'registration_closes_at',
        'whatsapp_group_link',
        'log_auto_purge_enabled',
        'log_retention_days',
        'log_retention_started_at',
        'log_next_purge_at',
    ];

    protected function casts(): array
    {
        return [
            'registration_open' => 'boolean',
            'registration_use_schedule' => 'boolean',
            'registration_opens_at' => 'datetime',
            'registration_closes_at' => 'datetime',
            'log_auto_purge_enabled' => 'boolean',
            'log_retention_days' => 'integer',
            'log_retention_started_at' => 'datetime',
            'log_next_purge_at' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * True bila pendaftaran sedang dibuka. Mode bersifat eksklusif:
     *  - Mode otomatis (use_schedule = true): terbuka bila sekarang berada di
     *    dalam rentang tanggal. Terbuka & tertutup otomatis mengikuti waktu.
     *  - Mode manual (use_schedule = false): mengikuti sakelar registration_open.
     */
    public function isRegistrationOpen(): bool
    {
        if ($this->registration_use_schedule) {
            // Mode otomatis: butuh minimal salah satu tanggal sebagai acuan.
            if ($this->registration_opens_at === null && $this->registration_closes_at === null) {
                return false;
            }

            $now = now();

            $afterOpen = $this->registration_opens_at === null
                || $this->registration_opens_at->lessThanOrEqualTo($now);

            $beforeClose = $this->registration_closes_at === null
                || $this->registration_closes_at->greaterThanOrEqualTo($now);

            return $afterOpen && $beforeClose;
        }

        return (bool) $this->registration_open;
    }

    /**
     * Ambil baris setelan tunggal (dibuat bila belum ada).
     */
    public static function current(): self
    {
        return static::firstOrCreate(['id' => 1], ['site_name' => 'CSA']);
    }

    /**
     * Status pendaftaran saat ini untuk halaman publik. Tidak di-cache karena
     * bergantung waktu (mode otomatis mengikuti rentang tanggal).
     */
    public static function registrationOpen(): bool
    {
        return static::current()->isRegistrationOpen();
    }
}
