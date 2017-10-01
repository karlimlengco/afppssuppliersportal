<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

use Revlv\Events\NotificationRepository;
use Revlv\Procurements\UnitPurchaseRequests\UnitPurchaseRequestRepository;
use Revlv\Procurements\UnitPurchaseRequests\UnitPurchaseRequestEloquent;

class Event
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $model;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(UnitPurchaseRequestEloquent $model, $events)
    {
        $this->model    =   $model;
        $this->events   =   $events;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
