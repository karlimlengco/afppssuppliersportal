<?php

namespace Revlv\Users\Traits;

use Illuminate\Support\Facades\Mail;
use Cartalyst\Sentinel\Laravel\Facades\Activation;

trait SendsActivationCode
{
    /**
     * Sends activation code to user
     *
     * @param $user
     * @return bool
     */
    public function sendActivationCode($user)
    {
        $activation = $this->getActivationCode($user);

        $activation = $this->getActivationUrlToken($user, $activation);

        return $this->sendActivationMail($user, $activation);
    }

    /**
     * @param $user
     */
    protected function sendActivationMail($user, $activation)
    {
        $activationParameters= [
            'user' => $user,
            'code' => $activation
        ];

        Mail::to($user->email)->queue(new \App\Mail\ActivationMail( $activationParameters ));

        // return \Mail::queue('modules.sentinel.activation', compact('user', 'activation', 'activationParameters'), function($message) use ($user)
        // {
        //     $message->to($user->email)->subject("Welcome Human!");
        // });
    }

    /**
     * Generate an activation code for the system to queue
     *
     * @param $user
     * @return mixed
     */
    public function getActivationCode($user)
    {
        return Activation::create($user);
    }

    /**
     * Returns the link for the activation link
     *
     * @param $user
     * @param $activation
     * @return string
     */
    public function getActivationUrlToken($user, $activation)
    {
        return $activation->code;
    }
}