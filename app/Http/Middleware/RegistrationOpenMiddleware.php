<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class RegistrationOpenMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $setting = Cache::remember('setting.registration_open', 60, fn () => Setting::first()?->registration_open ?? false);

        if (!$setting) {
            return redirect()->route('home')
                ->with('error', 'Pendaftaran sedang ditutup. Silakan cek kembali nanti.')
                ->with('registration_closed', true);
        }

        return $next($request);
    }
}
