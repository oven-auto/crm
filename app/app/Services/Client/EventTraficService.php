<?php

namespace App\Services\Client;

use App\Http\DTO\Trafic\CreateTraficDTO;
use App\Models\ClientEventStatus;
use App\Models\Trafic;
use App\Repositories\Trafic\TraficRepository;

Class EventTraficService
{
    private $trafic;
    private $traficRepo;

    public function __construct(Trafic $trafic, TraficRepository $repo)
    {
        $this->trafic = $trafic;
        $this->traficRepo = $repo;
    }



    /**
     * СОЗДАТЬ ТРАФИК ИЗ СОБЫТИЯ
     */
    public function createTrafic(ClientEventStatus $eventStatus, bool $status = true)
    {
        if(!$status)
            return false;

        $client = $eventStatus->event->client;
        $clientArr = $client->toArray();
        $clientArr['author_id'] = auth()->user()->id;
        $clientArr['phone'] = $client->phones->count() ? $client->phones->first()->phone : '';
        $clientArr['email'] = $client->emails->count() ? $client->emails->first()->email : '';
        $clientArr['inn'] = $client->inn->number;
        $clientArr['trafic_chanel_id'] = 37;

        $this->traficRepo->save($this->trafic, CreateTraficDTO::fromArray($clientArr));
       
        \App\Models\ClientEventTrafic::create([
            'trafic_id' => $this->trafic->id,
            'event_id' => $eventStatus->event_id,
            'event_status_id' => $eventStatus->id
        ]);

        $eventStatus->lastComment()->create([
            'author_id' => auth()->user()->id,
            'text' => 'Создано обращение №'.$this->trafic->id,
            'event_id' => $eventStatus->event_id,
            'client_event_status_id' => $eventStatus->id
        ]);

        return $this->trafic->id;
    }
}
