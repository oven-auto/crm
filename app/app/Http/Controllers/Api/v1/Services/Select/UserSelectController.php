<?php

namespace App\Http\Controllers\Api\v1\Services\Select;

use App\Http\Controllers\Controller;
use App\Models\User;

class UserSelectController extends Controller
{
    /**
     * @OA\Get(
     *  path="/services/html/select/users",
     *  tags={"Списки"},
     *  operationId="getUsersSelect",
     *  summary="Список пользователей системы",
     *  description="Список пользователей системы",
     *  @OA\Response(
     *      response=200,
     *      description="OK"
     *  )
     * )
     */
    public function index()
    {
        $users = User::get();
        
        return response()->json([
            'data'=>$users,
            'success'=>$users->count() ? 1 : 0,
            'count' => $users->count()
        ]);
    }
}
