<?php

namespace App\Http\Controllers\Api\v1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Services\Auth\AuthService;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    private $service;

    public function __construct(AuthService $service)
    {
        $this->service = $service;
    }



    public function index(LoginRequest $request)
    {
        $result = $this->service->login($request->all());

        return response()->json($result);
    }



    public function check(Request $request)
    {
        return $this->service->check($request->all());
    }
}
