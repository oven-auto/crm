<?php

namespace App\Services\Client\ClientEvent;

use App\Helpers\String\StringHelper;
use App\Models\Client;
use App\Models\ClientEvent;
use App\Models\ClientEventTemplate;
use App\Models\Worksheet\Service\WSMService;
use Illuminate\Support\Facades\Auth;

Class CreateEventFromService
{
    private Client $client;

    private $templates;

    private Event $event;

    private WSMService $service;



    public function __construct(WSMService $service)
    {
        $this->event = new Event();

        $this->service = $service;

        $this->client = $service->worksheet->client;

        $this->templates = array($service->service->prolongation->template);
    }



    public static function fromTemplate(WSMService &$service) 
    {   
        $handler = new self(
            service:        $service,
        );

        foreach($handler->templates as $template)
            if(!$handler->service->event)
            {   
                if($template && $handler->service->isAwardCompleted())
                {   
                    $event = $handler->event->write($handler->client, $template);
                    $handler->saveLink($event);
                    $handler->event->saveComment($event, $handler->getServiceMessage());

                    $service->refresh();
                }
            }
            else{
                if($template)
                    $handler->changeEvent($template);
            }
    }



    public function saveLink(ClientEvent $event)
    {
        $this->service->event()->create([
            'client_event_id' => $event->id,
        ]);
    }



    public function closeEvent(ClientEvent $event)
    {
        $event->lastStatus->fill([
            'confirm' => 'processed',
            'processed_at' => now(),
            'author_id' => Auth::id()
        ])->save();
    }



    public function restoreEvent(ClientEvent $event)
    {
        $event->lastStatus->fill([
            'confirm' => 'waiting',
            'processed_at' => null,
            'author_id' => null
        ])->save();
    }



    private function changeEvent(ClientEventTemplate $template)
    {
        $event = ClientEvent::where('id', $this->service->event->client_event_id)->first();
        
        $title = $template->title;

        if($this->service->isClosed())
        {
           $title =  ' (расторгнут)';
           $this->closeEvent($event);
        }
        else 
            $this->restoreEvent($event);
        
        $event->fill(['title' => $title])->save();
    }



    protected function getServiceMessage() : string
    {
        $service = $this->service;
        
        $message = [
            'Рабочий лист №'.$service->worksheet_id.'.',
            'Пролонгация', 
            $service->service->name,
            '('.$service->service->category->name.').',
            $service->provider->full_name ?? '',
        ];

        if($service->contract)
            $message = array_merge($message, [
                '('.$service->contract->number,
                'от',
                $service->contract->begin_at->format('d.m.Y').').',
                'Действует с',
                $service->contract->register_at->format('d.m.Y').'.',
            ]);

        if($service->award)
            $message = array_merge($message, [
                'Стоимость',
                StringHelper::moneyMask($service->cost),
                '(КВ '. $service->getProcentAward() . '%)',
            ]);

        return join(' ', $message);
    }
}