<?php

namespace App\Http\Middleware\Permissions\Audit;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;

class AuditCRUDMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $userPermissions = auth()->user()->role->permissions;

        if($userPermissions->contains('slug', 'audit_crud'))
            return $next($request);

        $route = Route::getCurrentRoute();

        $ref = new \ReflectionMethod($route, 'getControllerMethod');
        
        $ref->setAccessible(true);
        
        $controllerMethod = $ref->invoke($route);

        if(in_array($controllerMethod, ['index','show']))
            return $next($request);

        throw new \Exception('Отсутствуют права для создания или изменения аудита.');
    }
}
