<?php

namespace App\Http\Middleware;

use App\Models\Setting;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RegistrationOpenMiddleware
{
    /**
     * Izinkan akses bila pendaftaran dibuka (toggle manual atau rentang tanggal).
     * Nilai dihitung langsung, bukan dari cache, karena bergantung waktu.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $setting = Setting::firstOrCreate(['id' => 1], ['site_name' => 'CSA']);

        if (! $setting->isRegistrationOpen()) {
            return redirect()->route('home')
                ->with('error', 'Pendaftaran sedang ditutup. Silakan cek kembali nanti.')
                ->with('registration_closed', true);
        }

        return $next($request);
    }
}
