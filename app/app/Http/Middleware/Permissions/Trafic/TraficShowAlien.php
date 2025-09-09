<?php

namespace App\Http\Middleware\Permissions\Trafic;

use Closure;
use Illuminate\Http\Request;
Use App\Models\Trafic;

class TraficShowAlien
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $permission, $permission2 = '', $permission3 = '', $permission4 = '')
    {
        //Все права пользователя из роли
        $userPermissions = auth()->user()->role->permissions;

        $trafic = $request->trafic;
        
        if(is_numeric($request->trafic))
            $trafic = \App\Models\Trafic::withTrashed()->findOrFail($request->trafic);

        //Если трафик ожидающий
        if(\App\Models\Trafic::checkTrafic('waiting', $trafic, $permission3))
            return $next($request);
        //dump(\App\Models\Trafic::checkTrafic('waiting', $trafic, $permission3));

        if(\App\Models\Trafic::checkTrafic('all', $trafic, $permission4))
            return $next($request);
        //dump(\App\Models\Trafic::checkTrafic('all', $trafic, $permission4));

        //Проверка что есть права либо что ответсвенный за трафик тот же кто залогенен
        $boolRes = (
            $userPermissions->contains('slug', $permission) ||
            ($userPermissions->contains('slug', $permission2) && $trafic->manager_id == '') ||
            $trafic->manager_id == auth()->user()->id ||
            $trafic->author_id == auth()->user()->id
        );

        if($boolRes)
            return $next($request);

        //$trafic = Trafic::findOrFail($request->trafic);
        if($trafic->isTraficFromEnabledWorksheet())
            return $next($request);

        //Получение текущего права, что бы вернуть исключение
        //$permission = \App\Models\Permission::where('slug', $permission)->first();
        //throw new \Exception('Отсутствуют права "'.$permission->name.'"');
        throw new \Exception('Доступ ограничен! Вы не можете отслеживать работу с этим клиентом.');
    }
}
