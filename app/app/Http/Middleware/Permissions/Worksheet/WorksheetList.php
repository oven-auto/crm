<?php

namespace App\Http\Middleware\Permissions\Worksheet;

use Closure;
use Illuminate\Http\Request;

class WorksheetList
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
        $user = auth()->user();
        if($user->role->permissions->contains('slug','ws_list'))
            return $next($request);
        throw new \Exception('Нет прав для просмотра журнала рабочих листов');
    }
}
