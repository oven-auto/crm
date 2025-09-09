<?php

namespace App\Listeners;

use App\Events\ClientEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendClientInfo
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\ClientEvent  $event
     * @return void
     */
    public function handle(ClientEvent $event)
    {
        $txt =  $event->message.PHP_EOL;
        $txt .= "Номер клиента: {$event->data->id} ".PHP_EOL;
        $txt .= "Тип клиента: {$event->data->type->name} ".PHP_EOL;
        if($event->data->client_type_id == 1)
            $txt .= "Клиент: {$event->data->lastname} {$event->data->firstname} {$event->data->fathername}".PHP_EOL;
        if($event->data->client_type_id == 2)
            $txt .= "Клиент: {$event->data->company_name}".PHP_EOL;
        $txt .= PHP_EOL;
        $txt .= "Автор изменения: ".auth()->user()->cut_name.PHP_EOL;

        $users = [
            auth()->user()->id,
        ];

        try{
            \App\Classes\Socket\SocketClient::socket(
                json_encode(
                    ['auth' => auth()->user()->id, 'message' => $txt, 'users' => $users]
                )
            )->send();
        } catch(\Exception $e){

        }
    }
}
