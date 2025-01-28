<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureEmailIsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            if ($request->user()->verification_code !== null) {
                // Si la solicitud no espera JSON (es una solicitud normal), redirigir a la vista de verificación
                if (! $request->expectsJson()) {
                    return redirect()->route('verifyCode')->with('warning', 'Please verify your email address.');
                }

                // Si la solicitud espera JSON (posiblemente una solicitud AJAX), devolver un error en JSON
                return response()->json([
                    'message' => 'Please verify your email address.',
                    'redirect' => route('verifyCode'),
                ], 403);  // Código de estado 403 - Forbidden
            }
        }

        // Si no tiene un código pendiente, continuar con la solicitud
        return $next($request);
    }
}
