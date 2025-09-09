<?php

namespace App\Events;

use App\Models\Client;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ClientEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $data;
    public $message;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Client $client, $message)
    {
        $this->data = $client;
        $this->message = $message;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }

    

    public function broadcastWith()
    {
        return [
            'data' => $this->data,
            'message' => $this->message,
        ];
    }
}
