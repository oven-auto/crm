<?php

namespace App\Http\Middleware\Permissions\Worksheet\Modules;

use Closure;
use Illuminate\Http\Request;

class RedemptionsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $action, $permission = '')
    {
        $user = auth()->user();
        $userPermissions = $user->role->permissions;

        $executorPerm = 'ws_action_executor';
        $superPerm = 'ws_action_any';

        $message = 'Доступ ограничен! Вы не можете отслеживать и редактировать работу с этим клиентом. Запросите расширение прав у участников Рабочего листа.';

        switch($action) {
            //Просмотр журнала
            case 'list':
                $canAppraisal = $userPermissions->contains('slug', $permission);
                if($canAppraisal)
                    return $next($request);
                $message = 'У Вас нет прав для просмотра журнала';
                break;

            //Отправлять оценку в CME
            case 'appraisal':
                $canAppraisal = $userPermissions->contains('slug', $permission);
                if($canAppraisal)
                    return $next($request);
                $message = 'У Вас нет прав для отправки оценки в Auto.ru';
                break;

            //Упускать оценку
            case 'delete':
                //РЛ оценки
                $worksheet = \App\Models\Worksheet::findOrFail($request->redemption->worksheet_id);
                //Смотрим есть ли право на упущение
                $canDelete = $userPermissions->contains('slug', $permission);
                //Проверяем есть ли пользователь в списке участников РЛ
                $isExecutor = $worksheet->executors->contains('id', $user->id);
                if($canDelete /*&& $isExecutor*/)
                    return $next($request);
                $message = 'У Вас нет прав для того, что бы упустить оценку.';
                break;

            //Возвращать оценку
            case 'revert':
                //РЛ оценки
                $worksheet = \App\Models\Worksheet::findOrFail($request->redemption->worksheet_id);
                //Смотрим есть ли право на возврат
                $canRevert = $userPermissions->contains('slug', $permission);
                //Проверяем есть ли пользователь в списке участников РЛ
                $isExecutor = $worksheet->executors->contains('id', $user->id);
                if($canRevert /*&& $isExecutor*/)
                    return $next($request);
                $message = 'У Вас нет прав для того, что бы вернуть оценку в работу.';
                break;

            //Открыть на просмотр
            //поидее ни каких прав нет, тк если есть право открыть РЛ, то и оценку откроешь. Оценка без РЛ не открывается.
            case 'show' :
                return $next($request);
                break;

            //Заявлять оценку
            case 'create' :
                if(isset($request->worksheet))
                {
                    $worksheet = $request->worksheet;

                    if($userPermissions->contains('slug', $superPerm))
                        return $next($request);

                    if($userPermissions->contains('slug', $executorPerm) && $worksheet->executors->contains('id', $user->id))
                        return $next($request);
                }
                else
                    $message = 'Не указан рабочий лист оценки';
                break;

            //Изменять оценку
            case 'update' :
                if(isset($request->redemption))
                {
                    $worksheet = \App\Models\Worksheet::findOrFail($request->redemption->worksheet_id);

                    if($userPermissions->contains('slug', $superPerm))
                        return $next($request);

                    if($userPermissions->contains('slug', $executorPerm) && $worksheet->executors->contains('id', $user->id))
                        return $next($request);
                }
                else
                    $message = 'Не указан идентификатор связанной задачи';
                break;
        }

        throw new \Exception($message);

    }
}
