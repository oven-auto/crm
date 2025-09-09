<?php

namespace App\Classes\Socket\Action;

use App\Classes\Socket\Auth\WSAuth;
use Illuminate\Http\Request;

Class LoginAction
{
    public function login(Request $request)
    {
        $resourceId = WSAuth::getInstance()->getConn()->resourceId;
        
        $res[$resourceId] = [
            'auth' => WSAuth::getInstance()->getAuth(),
        ];

        dump($res);
    }



    public function __invoke(Request $request)
    {
        $this->login($request);
    }
}