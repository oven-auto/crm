<?php

namespace App\Http\Controllers\Api\v1\Back\TaskList;

use App\Http\Controllers\Controller;
use App\Services\TaskList\EventTaskList;
use App\Services\TaskList\TraficTaskList;
use App\Services\TaskList\WorksheetTaskList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OverdueCountController extends Controller
{
    private $repo;

    public function __construct(
        TraficTaskList $traficRepo,
        WorksheetTaskList $worksheetRepo,
        EventTaskList $eventRepo 
    )
    {
        $this->repo['trafic']       = $traficRepo;
        $this->repo['worksheet']    = $worksheetRepo;
        $this->repo['event']        = $eventRepo;
    }



    public function index(Request $request)
    {
        $user = Auth::id();

        $request->merge(['control_date' => now()->format('d.m.Y')]);

        $trafic     =   $this->repo['trafic']->getUserTraficCount($request->all());

        $worksheet  =   $this->repo['worksheet']->getUserWorksheetActionCount($user);

        $subAction  =   $this->repo['worksheet']->getUserWorksheetSubActionCount($user);

        $events     =   $this->repo['event']->getUserEventCount($user);

        return response()->json([
            'data' => [
                'trafics'           => $trafic,
                'worksheets'        => $subAction + $worksheet,
                'events'            => $events,
            ],
            'success' => 1
        ]);
    }
}
