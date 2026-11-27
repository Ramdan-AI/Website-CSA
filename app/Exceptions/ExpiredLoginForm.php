<?php

namespace App\Exceptions;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ExpiredLoginForm
{
    public function __invoke(HttpException $exception, Request $request): ?RedirectResponse
    {
        if ($exception->getStatusCode() !== 419 || ! $request->isMethod('post') || ! $request->is('login')) {
            return null;
        }

        if ($user = $request->user()) {
            return redirect()->route('admin.dashboard', ['roleSlug' => $user->role?->slug ?? 'anggota']);
        }

        return redirect()->route('login')
            ->withInput($request->only('email'))
            ->withErrors(['email' => 'Sesi formulir sudah habis. Silakan masuk lagi.']);
    }
}
