<?php

namespace App\Http\Middleware\Permissions\Worksheet;

use App\Models\Worksheet;
use App\Models\WorksheetAction;
use Closure;
use Illuminate\Http\Request;

class WorksheetActionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $executorPerm = '', $superPerm = '')
    {
        $user = auth()->user();
        $userPermissions = $user->role->permissions;

        //echo($request->worksheetId);
        //return $next($request);

        if($request->has('action_id'))
            $worksheet = WorksheetAction::findOrFail($request->action_id)->worksheet;
        elseif($request->has('worksheet_id'))
            $worksheet = Worksheet::findOrFail($request->worksheet_id);
        elseif(isset($request->worksheetId))
            $worksheet = Worksheet::findOrFail($request->worksheetId);
        else
            throw new \Exception('Проблема: недостаточно данных для выполнения запроса');

        if($userPermissions->contains('slug', $superPerm))
            return $next($request);

        if($userPermissions->contains('slug', $executorPerm) && $worksheet->executors->contains('id', $user->id))
            return $next($request);

        throw new \Exception('Доступ ограничен! Вы не можете отслеживать и редактировать работу с этим клиентом. Запросите расширение прав у участников Рабочего листа.');
    }
}
