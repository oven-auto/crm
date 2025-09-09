<?php

namespace App\Http\Controllers\Api\v1\Back\Trafic;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Task;

class TraficTaskController extends Controller
{
    public function index(Request $request)
    {
        $query = Task::select('id','name','interval')->where('type', '<', 6);

        $tasks = $query->orderBy('sort')->get();

        return response()->json([
            'data' => $tasks,
            'success' => 1,
        ]);
    }
}
