<?php

namespace App\Http\Controllers\Api\v1\Back\Client;

use App\Http\Controllers\Controller;
use App\Http\Resources\Client\Event\File\ClientEventFileCollection;
use App\Http\Resources\Client\Event\File\ClientEventFileResource;
use App\Models\ClientEvent;
use App\Models\ClientEventFile;
use App\Models\ClientEventStatus;
use App\Services\Comment\EventComment;
use Illuminate\Http\Request;

class EventFileController extends Controller
{
    public function index(ClientEventStatus $clientEventStatus, Request $request)
    {
        $files = $clientEventStatus->files;

        return new ClientEventFileCollection($files);
    }

    public function store(ClientEventFile $file, Request $request)
    {
        $eventStatus = ClientEventStatus::findOrFail($request->event_id);

        $arr = collect();

        foreach (collect($request->allFiles())->collapse() as $file)
            $arr->push($eventStatus->files()->create([
                'event_id' => $eventStatus->event_id,
                'author_id' => auth()->user()->id,
                'file' => \App\Services\Download\ClientEventFileLoad::download($eventStatus->event_id, $file),
                'client_event_status_id' => $eventStatus->id,
            ]));

        EventComment::appendFile($eventStatus);

        return response()->json([
            'data' => ClientEventFileResource::collection($arr),
            'success' => 1,
            'message' => 'Данные добавлены',
        ]);
    }

    public function show(ClientEventFile $file)
    {
        return response()->json([
            'data' => new ClientEventFileResource($file),
            'success' => 1,
        ]);
    }

    public function delete(ClientEventFile $file)
    {
        $event = ClientEvent::with('lastStatus')->findOrFail($file->event_id);
        EventComment::deleteFile($event->lastStatus);

        $file->delete();

        return response()->json([
            'message' => 'Фаил удален',
            'success' => 1,
        ]);
    }
}
