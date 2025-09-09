<?php

namespace App\Http\Middleware\Permissions\Worksheet;

use Closure;
use Illuminate\Http\Request;

class WorksheetBasePerm
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
        $message = '';
        $trafic = \App\Models\Trafic::find($request->trafic_id);
        if(!$trafic->id)
            $message = 'Для создания рабочего листа необходим трафик';
        if(!$trafic->manager_id)
            $message = 'Для создания рабочего листа необходим ответственный';
        if($trafic->trafic_status_id != 2)
            $message = 'Для создания рабочего листа необходим статус "НАЗНАЧЕНО"';

        if($message)
            throw new \Exception($message);
        return $next($request);
    }
}
