<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Kemana user diarahkan kalau belum login.
     */
    protected function redirectTo($request): ?string
    {
        if (! $request->expectsJson()) {
            // arahkan ke route login yang sudah kamu buat
            return route('login');
        }

        return null;
    }
}
