<?php

namespace Revlv\Users\Traits;

use Illuminate\Http\Request;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Cartalyst\Sentinel\Laravel\Facades\Reminder;

trait ChangesPassword
{

    /**
     * Login route
     *
     * @var
     */
    protected $loginRoute = 'admin.users.index';

    /**
     * Validate the authentication request
     *
     * @param Request $request
     * @return bool
     */
    protected function validateAuthentication(Request $request)
    {
        if ($request->has('user', 'code'))
        {
            $code = $request->get('code');

            if ($user = Sentinel::findByCredentials(['username' => $request->get('user')]))
            {
                if (Reminder::exists($user, $code))
                {
                    return $user;
                }
            }
        }

        return false;
    }

    /**
     * Handles invalid activation request
     */
    protected function sendInvalidAuthenticationResponse()
    {
        return redirect()
            ->route($this->loginRoute)
            ->withErrors(
                ['expired' => 'Invalid or expired activation code.']
            );
    }

    /**
     * Show the activation screen
     *
     * @param Request $request
     * @return $this|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        if ($user = $this->validateAuthentication($request))
        {
            return view('warden::reset', [
                'user' => $user,
                'activationParameters' => [
                    'user' => $request->get('user'),
                    'code' => $request->get('code')
                ]
            ]);
        }

        return $this->sendInvalidAuthenticationResponse();
    }

    /**
     * Change password
     *
     * @param Request $request
     * @return bool
     */
    public function change(Request $request)
    {
        if ($user = $this->validateAuthentication($request))
        {
            $this->validate($request, ['password' => 'confirmed|required|min:6|max:30']);
            $code = $request->get('code');
            $newPassword = $request->get('password');
            // $model  =   new \App\User();
            // $newPassword    =   str_random(20);

            // if ($user = Sentinel::findByCredentials(['username' => $request->get('user')]))
            // {
                // if (Reminder::complete($user, $code, $password))
                // {
                    // Login the user and redirect
                    $userTalaga = $this->model->findByUsername($user->username);
                    $data = $this->model->updatePassword($userTalaga, $newPassword);
                    // $data   =   Sentinel::update($user, [
                    //     'password' => $newPassword
                    // ]);
                    // dd($newPassword);
                    // Sentinel::login($user);

                    return redirect('/');
                // }
                // else
                // {
                //     dd('error');
                // }
            // }
        }

        return $this->sendInvalidAuthenticationResponse();
    }

}