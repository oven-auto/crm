<?php

namespace App\Http\Middleware\For1c;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if($request->header('Auth') == env('1C_TOKKEN'))
            return $next($request);
        throw new \Exception('Ошибочка аутентификации.');
    }
}
