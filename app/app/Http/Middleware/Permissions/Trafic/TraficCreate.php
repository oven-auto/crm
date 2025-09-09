<?php

namespace App\Http\Middleware\Permissions\Trafic;

use Closure;
use Illuminate\Http\Request;

class TraficCreate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $permission)
    {
        $userPermissions = auth()->user()->role->permissions;

        foreach($userPermissions as $item)
            if($item->slug == $permission)
                 return $next($request);

        //Получение текущего права, что бы вернуть исключение
        //$permission = \App\Models\Permission::where('slug', $permission)->first();
        //throw new \Exception('Отсутствуют права "'.$permission->name.'"');
        throw new \Exception('Доступ ограничен! Вы не можете создавать трафик для работы с клиентом.');
    }
}
