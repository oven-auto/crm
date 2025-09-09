<?php

namespace App\Services\Client;
use App\Models\ClientEventStatus;
use \App\Models\Client;

Class EventChangeClient
{
    public static function changeClient($eventStatusId, $newClientId)
    {
        $eventStatus = ClientEventStatus::with('event')->findOrFail($eventStatusId);

        $oldClient = $eventStatus->event->client;

        $client = Client::find($newClientId);

        $eventStatus->event->client_id = $client->id;

        $eventStatus->event->save();

        $eventStatus->lastComment()->create([
            'text' => 'Изменен клиент коммуникации. Новый клиент '.$client->full_name.'. Старый клиент '.$oldClient->full_name.'.',
            'author_id' => auth()->user()->id,
            'event_id' => $eventStatus->event_id,
        ]);

        return $client;
    }

    public static function changeAuthor($eventStatusId, $newClientId)
    {
        $eventStatus = ClientEventStatus::with('event')->findOrFail($eventStatusId);
        $event = $eventStatus->event;

        $oldAuthor = $event->author;
        $newAuthor =  \App\Models\User::findOrFail($newClientId);

        $event->author_id = $newClientId;

        if(!$event->executors->contains('id', $newClientId))
            $event->executors()->attach($newClientId);

        $event->save();

        $eventStatus->lastComment()->create([
            'text' => 'Изменен автор коммуникации '.$newAuthor->cut_name.' Старый автор '.$oldAuthor->cut_name.'',
            'author_id' => auth()->user()->id,
            'event_id' => $eventStatus->event_id,
        ]);

        return \App\Models\User::findOrFail($newClientId);
    }
}
