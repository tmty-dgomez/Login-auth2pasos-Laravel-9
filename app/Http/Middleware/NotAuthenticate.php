<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotAuthenticate
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Si el usuario ya está autenticado, lo redirige al dashboard
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        // Si no está autenticado, permite continuar con la solicitud
        return $next($request);
    }
}
