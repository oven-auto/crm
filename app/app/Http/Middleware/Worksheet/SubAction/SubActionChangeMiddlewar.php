<?php

namespace App\Http\Middleware\Worksheet\SubAction;

use App\Models\SubAction;
use Closure;
use Illuminate\Http\Request;

class SubActionChangeMiddlewar
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

        if(isset($request->subAction) && ($request->subAction instanceof SubAction) && $request->subAction->isWork())
            return $next($request);

        throw new \Exception('Подзадача закрыта, ее изменение невозможно.');

    }
}
