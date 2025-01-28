<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class DetectMaliciousScripts
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
        // Lista de patrones maliciosos
        $maliciousPatterns = [
            '<script>', 'javascript:', 'onerror=', 'onload=', 'alert(', 
            'document.cookie', '<iframe>', '</iframe>'
        ];

        // Decodificar path y query string para analizar contenido
        $path = urldecode($request->path());
        $queryString = urldecode($request->getQueryString());

        // Verificar contenido malicioso en path o query string
        foreach ($maliciousPatterns as $pattern) {
            if (
                str_contains(strtolower($path), $pattern) ||
                ($queryString && str_contains(strtolower($queryString), $pattern))
            ) {
                // Redirige a la vista de contenido malicioso
                return response()->view('malicious-detected', [], 403);
            }
        }

        return $next($request);
    }
}
