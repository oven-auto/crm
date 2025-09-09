<?php

namespace App\Http\Controllers\Api\v1\Back\Trafic;

use App\Http\Controllers\Controller;
use App\Repositories\User\UserRepository;

class TraficUserController extends Controller
{
    public function index($structure_id = 0, $appeal_id = 0, UserRepository $service)
    {
        $data = $service->getListWithCoutTrafic($structure_id, $appeal_id);

        return \response()->json([
            'data' =>  $data,
            'success' => 1,
        ]);
    }
}
