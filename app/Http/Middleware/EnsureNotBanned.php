<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureNotBanned
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if ($user && $user->isBanned()) {
            auth()->logout();
            return redirect()->route('login')
                ->withErrors(['email' => 'Your account has been banned.']);
        }

        return $next($request);
    }
}
