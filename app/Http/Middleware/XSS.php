<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class XSS
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $import = $request->all();
        array_walk_recursive($import, function (&$input) {
            $input = strip_tags($input);
            $input = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
        });
        $request->merge($import);
        return $next($request);
    }
}
