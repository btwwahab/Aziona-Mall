<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class Authenticate
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        dd(123);
        if (!Auth::check()) {
            return redirect()->route('login-signup')->with('error', 'You must be logged in to continue.');
        }

        return $next($request);
    }
}
