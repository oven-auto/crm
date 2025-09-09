<?php

namespace App\Http\Middleware\Permissions\Worksheet\Action;

use App\Models\Worksheet;
use Closure;
use Illuminate\Http\Request;
use App\Models\Task;

class ActionCompleteMiddleware
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
        if (!$request->has('task_id') || !$request->has('worksheet_id'))
            throw new \Exception('Недостаточно данных для проверки правила.');

        $task = Task::findOrFail($request->task_id);

        if ($task->worksheet_label != 'check')
            return $next($request);

        $userPermissions = auth()->user()->role->permissions;

        if ($userPermissions->contains('slug', 'ws_complete_any'))
            return $next($request);

        $worksheet = Worksheet::findOrFail($request->worksheet_id);
        if (\App\Models\Trafic::checkTrafic('all', $worksheet->trafic, 'ws_complete_section'))
            return $next($request);

        throw new \Exception('Нет прав для завершения работы с клиентом.');
    }
}
