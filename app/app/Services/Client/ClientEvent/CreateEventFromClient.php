<?php

namespace App\Services\Client\ClientEvent;

use App\Models\Client;
use App\Models\ClientEvent;
use App\Models\ClientPassport;
use App\Repositories\Client\ClientEventTemplateRepository;
use Illuminate\Support\Facades\DB;

Class CreateEventFromClient
{
    private const PROCESS_ID = ['process' => 2];

    private Client $client;

    private $templates;

    private Event $event;

    public function __construct(ClientPassport $passport)
    {
        $this->client = $passport->client;

        $repo = new ClientEventTemplateRepository();

        $this->templates = $repo->get(self::PROCESS_ID);

        $this->event = new Event();
    }



    public static function fromTemplate(ClientPassport $passport)
    {
        $handler = new self(
            passport: $passport,
        );
        
        if($passport->getChanges('birthday_at'))
            foreach($handler->templates as $template)
            {
                $event = $handler->event->write($handler->client, $template);
            }
    }
}