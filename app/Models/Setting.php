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
            'log_auto_purge_enabled' => 'boolean',
            'log_retention_days' => 'integer',
            'log_retention_started_at' => 'datetime',
            'log_next_purge_at' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }
}
