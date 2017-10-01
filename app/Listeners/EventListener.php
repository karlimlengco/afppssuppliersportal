<?php

namespace App\Listeners;

use App\Events\Event;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use Revlv\Events\NotificationRepository;
use Revlv\Procurements\UnitPurchaseRequests\UnitPurchaseRequestRepository;
use Revlv\Users\UserRepository;

class EventListener
{
    // implements ShouldQueue
    // use InteractsWithQueue;

    // public $connection = 'database';
    // public $queue = 'listeners';


    public $users;
    public $notify;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(UserRepository $users, NotificationRepository $notify)
    {
        $this->users    = $users;
        $this->notify   = $notify;
    }

    /**
     * Handle the event.
     *
     * @param  Event  $event
     * @return void
     */
    public function handle(Event $event)
    {
        $model      =   $event->model;
        $your_event =   $event->events;
        $loggedin   =   \Sentinel::getUser();

        $users      =   $this->users->getAdminAndPCCO($model->procurement_office);

        foreach($users  as $user)
        {
            $this->notify->save([
                'user_id'   =>  $user->id,
                'event'     =>  $your_event,
                'model_id'  =>  $model->id,
            ]);
        }
    }

    /**
     * [failed description]
     *
     * @param  OrderShipped $event     [description]
     * @param  [type]       $exception [description]
     * @return [type]                  [description]
     */
    public function failed(Event $even, $exception)
    {
        //
    }
}
