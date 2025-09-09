<?php

namespace App\Http\Middleware\Permissions\Trafic;

use Closure;
use Illuminate\Http\Request;

class CanShowDraft
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $permission)
    {
        $trafic = $request->trafic;
        
        if(is_numeric($request->trafic))
            $trafic = \App\Models\Trafic::where('id', $request->trafic)->withTrashed()->first();
        
        if($trafic->isDraft())
        {
            $userPermissions = auth()->user()->role->permissions;
            if($trafic->author_id == auth()->user()->id || $userPermissions->contains('slug', $permission))
                return $next($request);
        }
        else
            return $next($request);

        throw new \Exception('Доступ ограничен! Вы не можете отслеживать работу с этим клиентом.');
    }
}
