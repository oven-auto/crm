<?php

namespace App\Http\Middleware\TaskList;

use Closure;
use Illuminate\Http\Request;

class SetManagerMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $param = '')
    {
        if($param == 'executor')
            $request->merge(['executor_ids' => [auth()->user()->id]]);
        if($param == 'manager')
            $request->merge(['manager_id' => [auth()->user()->id]]);

        if(!$request->has('show'))
            $request->merge(['show' => 'opening']);

        if($request->has('show') && $request->show == 'closing' && $request->has('control_date'))
        {
            $request->merge(['date_for_closing' => $request->control_date]);
            $request->request->remove('control_date');
        }

        return $next($request);
    }
}
