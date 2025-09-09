<?php

namespace App\Http\Controllers\Api\v1\Back\Client;

use App\Http\Controllers\Controller;
use App\Http\Resources\Client\Event\Link\ClientEventLinkCollection;
use App\Http\Resources\Client\Event\Link\ClientEventLinkResource;
use App\Models\ClientEvent;
use App\Models\ClientEventLink;
use App\Models\ClientEventStatus;
use App\Services\Comment\EventComment;
use App\Services\GetShortCutFromURL\GetShortCutFromURL;
use Illuminate\Http\Request;

class EventLinkController extends Controller
{
    public function index(ClientEventStatus $clientEventStatus, Request $request)
    {
        $links = $clientEventStatus->links;

        return new ClientEventLinkCollection($links);
    }

    public function store(ClientEventLink $link, Request $request)
    {
        $eventStatus = ClientEventStatus::findOrFail($request->event_id);
        $link->fill([
            'event_id' => $eventStatus->event_id,
            'author_id' => auth()->user()->id,
            'icon' => GetShortCutFromURL::get($request->url),
            'url' => $request->url,
        ])->save();

        EventComment::appendLink($eventStatus, $link);

        return response()->json([
            'data' => new ClientEventLinkResource($link),
            'success' => 1,
            'message' => 'Ссылка создана'
        ]);
    }

    public function update(ClientEventLink $link, Request $request)
    {
        $link->fill([
            'author_id' => auth()->user()->id,
            'icon' => GetShortCutFromURL::get($request->url),
            'url' => $request->url,
        ])->save();

        return response()->json([
            'data' => new ClientEventLinkResource($link),
            'success' => 1,
            'message' => 'Ссылка изменена'
        ]);
    }

    public function show(ClientEventLink $link)
    {
        return response()->json([
            'data' => new ClientEventLinkResource($link),
            'success' => 1,
        ]);
    }

    public function delete(ClientEventLink $link)
    {
        $eventStatus = ClientEvent::with('lastStatus')->findOrFail($link->event_id);

        EventComment::deleteLink($eventStatus->lastStatus, $link);

        $link->delete();

        return response()->json([
            'message' => 'Ссылка удалена',
            'success' => 1,
        ]);
    }
}
