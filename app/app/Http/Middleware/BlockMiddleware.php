<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class BlockMiddleware
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
        if(env('ADMIN_WORK') != 1)
            return $next($request);

        if($request->ip() == '192.168.1.98')
            return $next($request);

        abort(403, 'Работает админ.');
    }
}
