<?php

namespace App\Http\Middleware\System;

use App\Classes\System\TimeControl;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetMacrotimeMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        TimeControl::remember('control');

        return $next($request);
    }
}
