<?php

namespace App\Http\Controllers\Api\v1\Back\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use \App\Models\EventGroup;
use \Illuminate\Support\Str;

class EventGroupController extends Controller
{
    public function index()
    {
        $data = EventGroup::select('id','name')->get();

        return response()->json([
            'data' => $data,
            'success' => 1
        ]);
    }

    public function store(EventGroup $eventgroup, Request $request)
    {
        $eventgroup->fill([
            'name' => $request->get('name'),
            'slug' => Str::slug($request->get('name'))
        ])->save();

        return response()->json([
            'data' => $eventgroup,
            'success' => 1,
            'message' => 'Группа события добавлена'
        ]);
    }

    public function show(EventGroup $eventgroup)
    {
        return response()->json([
            'data' => $eventgroup,
            'success' => 1,
        ]);
    }

    public function update(EventGroup $eventgroup, Request $request)
    {
        $eventgroup->fill([
            'name' => $request->get('name'),
            'slug' => Str::slug($request->get('name'))
        ])->save();

        return response()->json([
            'data' => $eventgroup,
            'success' => 1,
            'message' => 'Группа события изменена'
        ]);
    }
}
