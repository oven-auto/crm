<?php

namespace App\Http\Middleware\System;

use App\Classes\System\TimeControl;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EndMacrotimeMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        
        if(!($response instanceOf \Illuminate\Http\JsonResponse))
            return $response;
       
        $content = $response->getData();

        TimeControl::remember('control');

        $content->time = TimeControl::get('control').'s';

        $content->memory = round(memory_get_usage()/1048576,1).'mb';

        $response->setData($content);

        return $response;
    }
}
