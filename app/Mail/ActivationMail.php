<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ActivationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $event;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($event)
    {
        $this->event    =   $event;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $route  =   route('activation.activate', [ "user=".$this->event['user']['id'] , "code=".$this->event['code'] ]);

        return $this->markdown('modules.sentinel.email.activation')
                    ->with([
                        'user' => $this->event['user'],
                        'activation'    =>  $this->event['code'],
                        'activationParameters'  => $this->event,
                        'route' => $route
                    ]);
    }
}
