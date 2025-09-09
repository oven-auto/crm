<?php

namespace App\Http\Middleware\Car;

use App\Models\LogisticState;
use Closure;
use Illuminate\Http\Request;

class CarFilterMiddleware
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
        $logisticStates = LogisticState::whereNotIn('system_name',[
            'sale_date', 'issue_date'
        ])->pluck('system_name')->toArray();
        
        $requestStates = $request->only($logisticStates);
       
        $request->merge(['logistic_dates' => $requestStates]);

        $request->merge(['having' => count($requestStates)]);

        $request->replace($request->except($logisticStates));
        
        return $next($request);
    }
}
