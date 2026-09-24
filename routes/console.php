<?php

use App\Support\LogRetentionService;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/*
 * Retensi log: periode dan status on/off kini dikendalikan dari halaman
 * Log Sistem (setelan tersimpan di tabel settings). Command ini hanya
 * menjalankan purge bila otomatis aktif dan jadwalnya tiba; interval riil
 * ditentukan setelan, bukan frekuensi scheduler.
 */
Artisan::command('activity-log:purge', function () {
    $deleted = app(LogRetentionService::class)->runScheduledPurge();

    if ($deleted === 0) {
        $this->info('Tidak ada log yang dibersihkan (otomatis nonaktif atau belum waktunya).');

        return;
    }

    $this->info("{$deleted} catatan aktivitas lama dihapus.");
})->purpose('Bersihkan log aktivitas sesuai setelan retensi');

// Dijalankan tiap hari; keputusan hapus ada di dalam command (idempotent).
Schedule::command('activity-log:purge')->daily()->withoutOverlapping();
