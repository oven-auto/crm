<?php

namespace App\Http\Middleware\Notice;

use App\Classes\Notice\Notice;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class NoticeMessageMiddleware
{
    public function handle(Request $request, Closure $next): JsonResponse
    {
      $route = Route::getCurrentRoute();
      
      $subject = $route->getController()->subject ?? 'Запись';

      $genus = $route->getController()->genus ?? '';
     
      $ref = new \ReflectionMethod($route, 'getControllerMethod');
      
      $ref->setAccessible(true);
      
      $controllerMethod = $ref->invoke($route);

      $response = $next($request);

      if(!($response instanceOf \Illuminate\Http\JsonResponse))
            return $response;
      
      if($response)
      {
        $content = $response->getData();
        
        $success = ($content->success ?? 0) == 1;
        
        if($success)
        {
          $content->message = Notice::make(subject: $subject, action: $controllerMethod, genus:$genus);
          
          $response->setData($content);
        }
      }
      return $response;
    }
}
