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
     * True bila pendaftaran sedang dibuka: toggle manual menyala, ATAU
     * waktu sekarang berada di dalam rentang tanggal pendaftaran.
     */
    public function isRegistrationOpen(): bool
    {
        if ($this->registration_open) {
            return true;
        }

        $now = now();

        $afterOpen = $this->registration_opens_at === null
            || $this->registration_opens_at->lessThanOrEqualTo($now);

        $beforeClose = $this->registration_closes_at === null
            || $this->registration_closes_at->greaterThanOrEqualTo($now);

        // Kedua kolom harus terisi agar rentang tanggal berlaku.
        $hasWindow = $this->registration_opens_at !== null || $this->registration_closes_at !== null;

        return $hasWindow && $afterOpen && $beforeClose;
    }
}
