<?php

namespace App\Http\Controllers\Api\v1\Back\Client;

use App\Http\Controllers\Controller;
use App\Models\ClientEvent;
use App\Models\ClientEventComment;
use App\Models\ClientEventStatus;

class EventCommentController extends Controller
{
    public function __invoke(ClientEventStatus $clientEvent)
    {
        $comments = ClientEventComment::where('event_id', $clientEvent->event_id)->orderBy('id', 'DESC')->get();

        return response()->json([
            'data' => $comments->map(function($item){
                return [
                    'writer' => $item->author->cut_name,
                    'text' => $item->text,
                    'created_at' => $item->created_at->format('d.m.Y'),
                ];
            }),
            'success' => 1,
        ]);
    }
}
