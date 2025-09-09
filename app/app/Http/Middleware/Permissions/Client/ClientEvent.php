<?php

namespace App\Http\Middleware\Permissions\Client;

use Closure;
use Illuminate\Http\Request;

class ClientEvent
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $action)
    {
        $user = auth()->user();
        $userPermissons = $user->role->permissions;

        if(isset($request->event))
            $eventStatus = \App\Models\ClientEventStatus::findOrFail($request->event);

        switch ($action) {

            case 'show':
                if(!isset($eventStatus))
                    throw new \Exception('Нет такого события');
                if($userPermissons->contains('slug', 'cevent_show_alien'))
                    return $next($request);

                $isOwner = $eventStatus->event->author_id == $user->id
                    || $eventStatus->executors->contains('id', $user->id)
                    || $eventStatus->reporters->contains('id', $user->id);

                if($userPermissons->contains('slug', 'cevent_show') && $isOwner )
                    return $next($request);

                // elseif(!$userPermissons->contains('slug', 'cevent_show') && $isOwner )
                //     throw new \Exception('Отсутствуют права: '.\App\Models\Permission::where('slug','cevent_show')->first()->name);
                // throw new \Exception('Отсутствуют права: '.\App\Models\Permission::where('slug','cevent_show_alien')->first()->name);
                throw new \Exception('Доступ ограничен! Вы не можете отслеживать работу с этим клиентом. Запросите расширение прав у участников коммуникации.');

            case 'store':
                if($userPermissons->contains('slug', 'cevent_add'))
                    return $next($request);
                throw new \Exception('Отсутствуют права на создание коммуникации');

            case 'update':
                if(!isset($eventStatus))
                    throw new \Exception('Нет такого события');
                if($userPermissons->contains('slug', 'cevent_edit_alien'))
                    return $next($request);
                $isOwner = $eventStatus->event->author_id == $user->id || $eventStatus->executors->contains('id', $user->id);
                if($userPermissons->contains('slug', 'cevent_edit') && $isOwner )
                    return $next($request);
                throw new \Exception('Доступ ограничен! Вы не можете отслеживать работу с этим клиентом. Запросите расширение прав у участников коммуникации.');
                // elseif(!$userPermissons->contains('slug', 'cevent_edit') && $isOwner )
                //     throw new \Exception('Отсутствуют права: '.\App\Models\Permission::where('slug','cevent_edit')->first()->name);
                // throw new \Exception('Отсутствуют права: '.\App\Models\Permission::where('slug','cevent_edit_alien')->first()->name);

            case 'index':
                if($userPermissons->contains('slug', 'cevent_list'))
                    return $next($request);
                else
                    throw new \Exception('Отсутствуют права на просмотр журнала коммуникации');

            default:
                break;
        }
    }
}
