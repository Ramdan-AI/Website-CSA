<?php

use App\Models\ActivityLog;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('activity-log:purge', function () {
    $deleted = ActivityLog::where('created_at', '<', now()->subDays(14))->delete();

    $this->info("{$deleted} catatan aktivitas lebih tua dari 14 hari dihapus.");
})->purpose('Bersihkan log aktivitas lama');

// Retensi log: catatan lebih tua dari 14 hari dibuang otomatis.
Schedule::command('activity-log:purge')->daily();
