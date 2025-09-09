<?php

namespace App\Http\Controllers\Api\v1\Back\Client;

use App\Http\Controllers\Controller;
use App\Models\ClientEventStatus;
use Illuminate\Http\Request;

class EventTraficListController extends Controller
{
    public function __invoke(ClientEventStatus $eventstatus)
    {
        return response()->json([
            'data' => $eventstatus->trafics->map(function($item){
                return [
                    'id' => $item->id,
                    'date_at' => $item->created_at->format('d.m.Y (H:i)'),
                    'status' => $item->status->description,
                    'appeal' => $item->appeal ? $item->appeal->name : '',
                    'company' => $item->salon ? $item->salon->name : '',
                    'structure' => $item->structure ? $item->structure->name : '',
                ];
            }),
            'success' => 1,
        ]);
    }
}
