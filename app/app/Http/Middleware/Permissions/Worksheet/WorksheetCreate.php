<?php

namespace App\Http\Middleware\Permissions\Worksheet;

use Closure;
use Illuminate\Http\Request;

class WorksheetCreate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $trafic = \App\Models\Trafic::find($request->trafic_id);
        $user = auth()->user();
        $userPermissions = $user->role->permissions;

        if($userPermissions->contains('slug','worksheet_create_any'))
            return $next($request);

        if($user->role->permissions->contains('slug','ws_create_where_i_manager'))
            if($user->id == $trafic->manager_id)
                return $next($request);

        if(\App\Models\Trafic::checkTrafic('all', $trafic, 'ws_create_where_i_appeals'))
            return $next($request);

        throw new \Exception('У вас нет права создавать рабочий лист из этого трафика');
    }
}
