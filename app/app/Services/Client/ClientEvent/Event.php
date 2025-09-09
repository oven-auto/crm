<?php 

namespace App\Services\Client\ClientEvent;

use App\Models\Client;
use App\Models\ClientEvent;
use App\Models\ClientEventStatus;
use App\Models\ClientEventTemplate;
use App\Services\GetShortCutFromURL\GetShortCutFromURL;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Event
{
    private $template;

    private $client;



    public function write(Client $client, ClientEventTemplate $template) : ClientEvent
    {
        $this->template = $template;

        $this->client = $client;

        $event = DB::transaction(function() {
            $event = $this->saveEvent($this->template);

            $this->saveStatus($event, $this->template);
            
            $this->saveComment($event, $this->template->comment);

            $this->saveExecutors($event, $this->template);

            $this->saveLinks($event, $this->template);
            
            return $event;
        }, 3);

        return $event;
    }



    public function saveLinks(ClientEvent $event, ClientEventTemplate $template)
    {
        $arr = json_decode($template->links);

        if(is_array($arr) && count($arr))
            $event->links()->createMany(array_map(function($item) use($event){
                return [
                    'event_id' => $event->id,
                    'author_id' => $event->author_id,
                    'icon' => GetShortCutFromURL::get($item),
                    'url' => $item,
                ];
            }, $arr));
    }



    protected function saveEvent(ClientEventTemplate $template) : ClientEvent
    {
        $event = ClientEvent::create([
            'client_id'     => $this->client->id,
            'author_id'     => $template->author_id,
            'group_id'      => $template->group_id,
            'type_id'       => $template->type_id,
            'title'         => $template->title,
            'resolve'       => $template->resolve,
        ]);
        
        return $event;
    }



    protected function saveStatus(ClientEvent $event, ClientEventTemplate $template) : ClientEventStatus
    {
        $status = $event->statuses()->create([
            'date_at' => Carbon::now()->addDays($template->begin)
        ]);

        return $status;
    }



    public function saveComment(ClientEvent $event, string $message) : void
    {
        $status = $event->lastStatus;
        
        $status->comments()->create([
            'text'          => $message,
            'author_id'     => $event->author_id,
            'event_id'      => $status->event_id
        ]);
    }



    protected function saveExecutors(ClientEvent $event, ClientEventTemplate $template) : void
    {
        $event->lastStatus->executors()->sync(array_merge(
            json_decode($template->executors), 
            [$template->author_id]
        ));
    }
}