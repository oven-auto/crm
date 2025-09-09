<?php

namespace App\Http\Controllers\Api\v1\Back\Trafic;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Trafic\TraficRepository;

class TraficCountController extends Controller
{
    public $repo;

    public function __construct(TraficRepository $repo)
    {
        $this->repo = $repo;
    }

    public function index(Request $request)
    {   
        $count = $this->repo->counter($request->all());

        return response()->json([
            'data' => $count[0]['count'],
            'count' => $count,
            'success' => 1,
            'message' => ''
        ]);
    }
}
