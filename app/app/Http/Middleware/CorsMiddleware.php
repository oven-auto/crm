<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CorsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */

    //  protected $domains = [
    //     'http://192.168.1.130',
    //     'http://192.168.1.130:8080',
    //     'http://192.168.1.130:8082',
    //     'http://localhost',
    //     'http://localhost:8080',
    //     'http://localhost:8082',
    //     'http://192.168.1.98:8280'
    // ];

    public function handle(Request $request, Closure $next)
    {
        return $next($request);
        // $domains = config('cors.trusted');

        // $origin = $request->headers->get('origin');
        
        // //return $next($request);

        // if($origin && in_array($origin, $domains, true)) {
        //     return $next($request)
        //         ->header('Access-Control-Allow-Origin', $origin)
        //         ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE','PATCH')
        //         ->header('Access-Control-Allow-Credentials', 'true')
        //         ->header(
        //             'Access-Control-Allow-Headers',
        //             'Authorization, Origin, X-Requested-With, Accept, X-PINGOTHER, Content-Type'
        //         );
        // }

        // return response()->json([
        //     'message' => 'Ваш домен или устройство не поддерживается сервером',
        //     'error' => 'Доступ отклонен',
        //     'success' => 0,
        //     'domains' => $domains,
        //     'origin' => $origin
        // ],403);

    }
}
