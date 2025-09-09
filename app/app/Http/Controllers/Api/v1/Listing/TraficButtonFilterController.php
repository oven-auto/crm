<?php

namespace App\Http\Controllers\Api\v1\Listing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TraficButtonFilterController extends Controller
{
    public function __invoke()
    {
        return response()->json([
            'data' => [
                [
                    'name' => 'В работе',
                    'values' => [1,2],
                ],
                [
                    'name' => 'Удален',
                    'values' => [5],
                ],
                [
                    'name' => 'Закрыт',
                    'values' => [3,4]
                ],
            ],
            'success' => 1
        ]);
    }
}
