<?php

namespace App\Http\Middleware\Permissions\Trafic;

use Closure;
use Illuminate\Http\Request;
use App\Models\Trafic;

class DeleteTraficMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $permission_author = '', $permission_appeals = '', $permission_all = '')
    {   
        $userPermission = auth()->user()->role->permissions;

        $trafic = $request->trafic;
        
        if(is_numeric($request->trafic))
            $trafic = Trafic::withoutTrashed()->findOrFail($request->trafic);

        if ($userPermission->contains('slug', $permission_all))
            return $next($request);

        if (Trafic::checkTrafic('all', $trafic, $permission_appeals))
            return $next($request);

        if ($userPermission->contains('slug', $permission_author) && $trafic->author_id == auth()->user()->id)
            return $next($request);

        throw new \Exception('Доступ ограничен! Вы не можете удалять работу с этим клиентом.');
    }
}
