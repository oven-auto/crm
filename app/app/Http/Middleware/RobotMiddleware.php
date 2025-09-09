<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RobotMiddleware
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
        $headers = getallheaders();

        if (!isset($headers['Authorization']))
            throw new \Exception('Упс. Ошибочка. Нет заголовка "Authorization: Bearer TOKEN"');

        $headerToken = explode(' ', $headers['Authorization']);

        if (!isset($headerToken[1]))
            throw new \Exception('Упс. Ошибочка. Заголовок должен быть в формате "Authorization: Bearer TOKEN"');

        $headerToken = $headerToken[1];

        $token = \Laravel\Sanctum\PersonalAccessToken::findToken($headerToken);

        if ($token) {
            $user = $token->tokenable;
            \Auth::login($user);
            //if ($user->role->permissions->contains('slug', 'robot'))
            return $next($request);
            // else
            //     throw new \Exception('Это робот-маршрут, что бы по нему пройти, надо иметь права робота.');
        } else {
            throw new \Exception('Вы не авторизованы или Ваша сессия устарела');
        }

        throw new \Exception('Вы не авторизованы, сходите авторизуйтесь');
    }
}
