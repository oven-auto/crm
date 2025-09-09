<?php

namespace App\Http\Controllers\Api\v1\Back\TaskList;

use App\Http\Controllers\Controller;
use App\Http\Resources\TaskList\EventListCollection;
use App\Services\TaskList\EventTaskList;
use Illuminate\Http\Request;

class EventListController extends Controller
{
    public function __invoke(EventTaskList $repo, Request $request)
    {
        $events = $repo->getEventsForTaskList($request->input());

        return (new EventListCollection($events));
    }
}
