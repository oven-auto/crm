<?php

namespace App\Http\Middleware\Permissions\Worksheet\SubAction;

use Closure;
use Illuminate\Http\Request;

class SubActionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $action)
    {
        $user = auth()->user();
        $userPermissions = $user->role->permissions;

        $executorPerm = 'ws_action_executor';
        $superPerm = 'ws_action_any';

        switch($action) {
            case 'show' :
                return $next($request);
                break;

            case 'create' :
                if($request->has('worksheet_id'))
                {
                    $worksheet = \App\Models\Worksheet::findOrFail($request->worksheet_id);

                    if($userPermissions->contains('slug', $superPerm))
                        return $next($request);

                    if($userPermissions->contains('slug', $executorPerm) && $worksheet->executors->contains('id', $user->id))
                        return $next($request);
                }
                else
                    throw new \Exception('Не указан рабочий лист связанной задачи');
                break;

            case 'update' :
                if($request->subAction)
                {
                    $worksheet = \App\Models\Worksheet::findOrFail($request->subAction->worksheet_id);
                    
                    if($userPermissions->contains('slug', $superPerm))
                        return $next($request);

                    if($userPermissions->contains('slug', $executorPerm) && $worksheet->executors->contains('id', $user->id))
                        return $next($request);
                }
                else
                    throw new \Exception('Не указан идентификатор связанной задачи');
                break;
        }

        throw new \Exception('Доступ ограничен! Вы не можете отслеживать и редактировать работу с этим клиентом. Запросите расширение прав у участников Рабочего листа.');
    }
}
