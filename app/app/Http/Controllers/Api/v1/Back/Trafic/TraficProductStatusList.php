<?php

namespace App\Http\Controllers\Api\v1\Back\Trafic;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TraficProductStatusList extends Controller
{
    public function __invoke()
    {
        return response()->json([
            'data' => [
                [
                    'id' => 1,
                    'name' => 'Нет'
                ],
                [
                    'id' => 2,
                    'name' => 'Есть'
                ]
            ],
            'success' => 1
        ]);
    }
}
