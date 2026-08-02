<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminWahanaMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->role === 'admin_wahana') {
            return $next($request);
        }

        return redirect('/')->with('error', 'Akses ditolak! Hanya Admin Wahana yang bisa masuk.');
    }
}
