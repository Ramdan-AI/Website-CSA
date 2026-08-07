<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response;

class RoleMatchMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (!$user || !$user->role) {
            abort(403);
        }

        if (!$user->is_active) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('login')->with('error', 'Akun Anda telah dinonaktifkan. Hubungi administrator.');
        }

        if ($request->route('roleSlug') !== $user->role->slug) {
            abort(403);
        }

        URL::defaults(['roleSlug' => $user->role->slug]);

        return $next($request);
    }
}
