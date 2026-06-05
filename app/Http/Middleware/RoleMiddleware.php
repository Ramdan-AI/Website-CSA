<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Role;
use App\Models\User;

class RoleMiddleware
{
    /**
     * @param string ...$roles
     */
    
    public function handle(
        Request $request,
        Closure $next,
        ...$roles
    ): Response {

        $user = Auth::user();

        if (
            !$user ||
            !$user->role
        ) {
            abort(403);
        }

        if (
            in_array(
                $user->role->slug,
                $roles
            )
        ) {
            return $next($request);
        }

        abort(403);
    }
}
