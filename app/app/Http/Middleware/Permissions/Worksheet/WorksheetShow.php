<?php

namespace App\Http\Middleware\Permissions\Worksheet;

use App\Models\Permission;
use Closure;
use Illuminate\Http\Request;
use App\Models\Worksheet;

class WorksheetShow
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

        $userPermissions = collect($user->role->permissions->whereIn('slug',[
            'ws_show_author', 'ws_show_executor', 'ws_show_appeals', 'ws_show_any'
        ])->all());

        $worksheet = Worksheet::with(['executors', 'trafic'])->findOrFail($request->worksheet);

        $arr = [];

        //where i`m author
        if($worksheet->author_id == $user->id && $userPermissions->contains('slug', 'ws_show_author'))
            return $next($request);
        else
            $arr[] = 'ws_show_author';

        //where i`m executor
        if($worksheet->reporters->contains('id', $user->id) || $worksheet->executors->contains('id', $user->id) && $userPermissions->contains('slug', 'ws_show_executor'))
            return $next($request);
        else
            $arr[] = 'ws_show_executor';

        //where any ws
        if($userPermissions->contains('slug', 'ws_show_any'))
            return $next($request);
        else
            $arr[] = 'ws_show_any';

        //where my appeals
        if(\App\Models\Trafic::checkTrafic('all', $worksheet->trafic, 'ws_show_appeals'))
            return $next($request);
        else
            $arr[] = 'ws_show_appeals';

        $message = [];

        foreach($arr as $key => $item)
            if($userPermissions->contains('slug', $item))
                unset($arr[$key]);

        //$neededPermission = Permission::whereIn('slug', $arr)->pluck('name')->toArray();

        //throw new \Exception("Недостаточно прав для просмотра рабочего листа\n\r".implode("\n\r", $neededPermission));

        throw new \Exception('Доступ ограничен! Вы не можете отслеживать работу с этим клиентом. Запросите расширение прав у участников Рабочего листа.');
    }
}
