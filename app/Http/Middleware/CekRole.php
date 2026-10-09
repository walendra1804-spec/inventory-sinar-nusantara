<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CekRole
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        //jika role user yang login tidak sesuai, tolak
        if (Auth::user()->role != $role) {
            abort(403, 'Anda tidak punya akses ke halaman ini');
        }

        return $next($request);
    }
}
