<?php

namespace App\Classes\Socket;

use App\Classes\Socket\Auth\WSAuth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

Class Router
{
    private static function getNameSpace()
    {
        $reflection = new \ReflectionClass(self::class);
        return $reflection->getNamespaceName();
    }



    public function __construct(array $data, \Ratchet\ConnectionInterface $conn)
    {  
        request()->merge($data['request'] ?? []);
        
        $auth = WSAuth::getInstance();
        $auth->setAuth($data['auth']);
        $auth->setConn($conn);

        $path = explode('.',$data['action']);

        $className = ucfirst($path[0] ?? '');

        $methodName = ucfirst($path[1] ?? '');

        $className = self::getNameSpace().'\\Action\\'.$className.'Action';

        if(class_exists($className))
        {
            $class = new $className(); 

            if(method_exists($class, $methodName))
                $class->$methodName(request());

            elseif(method_exists($class, '__invoke'))
                $class(request());
                    
        }
    }
}