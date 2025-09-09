<?php

namespace App\Http\Controllers\Api\v1\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public function register(\App\Services\Auth\AuthService $service,Request $request)
    {
        $res = $service->register($request->all());
        return \response()->json([
            'data' => $res,
            'success' => 1,
            'message' => 'Пользователь зарегестрирован'
        ]);
    }
}
