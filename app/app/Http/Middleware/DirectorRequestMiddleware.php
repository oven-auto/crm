<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class DirectorRequestMiddleware
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
        //if(!$request->has('appeal_ids'))
        //    $request->merge( ['appeal_ids' => [0]]);
            //$request->merge( ['appeal_ids' => auth()->user()->appeals->pluck('id')->toArray()] );

        //if(!$request->has('structure_ids'))
        //    $request->merge(['structure_ids' => [0]]);
            //$request->merge(['structure_ids' => auth()->user()->structures->pluck('company_structure_id')->toArray()]);

        //if(!$request->has('company_ids'))
        //    $request->merge(['company_ids' => [0]]);
            //$request->merge(['company_ids' => auth()->user()->companies->pluck('id')->unique()->toArray()]);

        // if(!$request->has('manager_id'))
        //     $request->merge(['manager_id' => auth()->user()->colleagues()->pluck('id')->toArray()]);

        return $next($request);
    }
}
