<?php

namespace App\Classes\Socket\Action;

use App\Classes\Socket\Auth\WSAuth;
use Illuminate\Http\Request;

class CarAction
{
    public function block(Request $request)
    {
        $resourceId = WSAuth::getInstance()->getConn()->resourceId;
        
        $res[$resourceId] = [
            'auth' => WSAuth::getInstance()->getAuth(),
            'car_id' => $request->carId,
        ];

        dump($res);
    }



    public function unblock(Request $request)
    {
        $resourceId = WSAuth::getInstance()->getConn()->resourceId;

        unset($res[$resourceId]['car_id']);
    }
}