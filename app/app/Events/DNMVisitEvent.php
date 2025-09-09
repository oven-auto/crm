<?php

namespace App\Events;

use App\Models\WsmReserveNewCar;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DNMVisitEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $reserve;

    public $eventType;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(WsmReserveNewCar $reserve, string $eventType)
    {
        $this->reserve = $reserve;

        $this->eventType = $eventType;
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
}
