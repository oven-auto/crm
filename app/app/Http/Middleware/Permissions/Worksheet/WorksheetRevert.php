<?php

namespace App\Http\Middleware\Permissions\Worksheet;

use Closure;
use Illuminate\Http\Request;
use App\Models\Worksheet;

class WorksheetRevert
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

        $userPermissions = $user->role->permissions;

        $worksheet =$request->worksheet;

        if($userPermissions->contains('slug', 'ws_revert_any'))
            return $next($request);

        if(\App\Models\Trafic::checkTrafic('all', $worksheet->trafic, 'ws_revert_appeal'))
            return $next($request);

        throw new \Exception('Нет прав для того, что бы вернуть этот рабочий лист в работу');
    }
}
