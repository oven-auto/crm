<?php

namespace App\Http\Controllers\Api\v1\Back\TaskList;

use App\Http\Controllers\Controller;
use App\Http\Resources\TaskList\TraficListCollection;
use App\Services\TaskList\TraficTaskList;
use Illuminate\Http\Request;

class TraficListController extends Controller
{
    public function __invoke(TraficTaskList $repo, Request $request)
    {
        $trafics = $repo->getTraficsForTaskList($request->all());

        return (new TraficListCollection($trafics));
    }
}
