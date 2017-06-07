<?php

namespace Revlv\Users\Traits;

use Cartalyst\Sentinel\Laravel\Facades\Activation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Sentinel;
// use Cartalyst\Sentinel\Native\Facades\Sentinel;
use Cartalyst\Sentinel\Laravel\Facades\Reminder;

trait RemindsUsers
{
    /**
     * Login route
     *
     * @var
     */
    protected $loginRoute = 'auth.index';

    /***
     *
     */
    public function index()
    {
        return view('emails.user.reminder');
    }

    /**
     * @param Request $request
     * @return $this
     */
    public function remind(Request $request)
    {
        $this->validate($request, ['email' => 'email|required' ]);

        $email = $request->only('email');

        // If the user exists we will create a user reminder
        if ($user = Sentinel::findByCredentials($email))
        {
            // Prevents a user to request a forgot password if the account is not yet activated
            if ( ! Activation::exists($user))
            {
                $reminderCode = Reminder::create($user);

                // If the reminder email was not successfully sent
                $reminderCode = $this->getReminderUrlToken($user, $reminderCode);

                if ( ! $this->sendReminderEmail($user, $reminderCode))
                {
                    return redirect()
                        ->route($this->loginRoute)
                        ->withErrors(
                            ['email' => 'Unable to complete request, please try again later.']
                        );
                }
            }
        }

        return redirect()
            ->route($this->loginRoute)
            ->withErrors(
                ['email' => 'If the provided details is correct you should be receiving a email']
            );
    }

    /**
     * @param Request $request
     * @return $this
     */
    public function adminremind(Request $request)
    {
        $this->validate($request, ['email' => 'email|required' ]);

        $email = $request->only('email');
        // If the user exists we will create a user reminder
        if ($user = Sentinel::findByCredentials($email))
        {

            // Prevents a user to request a forgot password if the account is not yet activated
            if ( ! Activation::exists($user))
            {
                $reminderCode = Reminder::create($user);
                // If the reminder email was not successfully sent
                $reminderCode = $this->getReminderUrlToken($user, $reminderCode);

                if ( ! $this->sendReminderEmail($user, $reminderCode))
                {
                    return redirect()
                        ->back()
                        ->withErrors(
                            ['email' => 'Unable to complete request, please try again later.']
                        );
                }
            }

        }

        return redirect()
            ->back()
            ->withErrors(
                ['email' => 'If the provided details is correct this should be receiving a email']
            );
    }

    /**
     * Sends a reminder email to a user
     *
     * @param $user
     * @param $reminder
     * @return mixed
     */
    protected function sendReminderEmail($user, $reminder)
    {
        $mail   =   Mail::to($user->email)->queue(new \App\Mail\ResetPassword( ['user' => $user, 'reminder' => $reminder] ));
        return true;
        // return Mail::queue('emails.user.reminder', compact('user', 'reminder'), function($message) use ($user)
        // {
        //     $message->to($user->email)->subject('Reset your password');
        // });
    }

    /**
     * Returns the link for the activation link
     *
     * @param $user
     * @param $reminder
     * @return string
     */
    public function getReminderUrlToken($user, $reminder)
    {
        return url('users/password') . '?' . http_build_query(['user' => $user->username, 'code' => $reminder->code]);
    }
}