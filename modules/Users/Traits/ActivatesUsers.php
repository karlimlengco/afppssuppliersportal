<?php

namespace Revlv\Users\Traits;

use Illuminate\Http\Request;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Cartalyst\Sentinel\Laravel\Facades\Activation;

trait ActivatesUsers
{
    /**
     * Login route
     *
     * @var
     */
    protected $loginRoute = 'auth.index';

    /**
     * The registered activation route
     *
     * @var string
     */
    protected $activationRoute = 'activation.index';

    /**
     * Activate an account based on the code provided
     *
     * @param Request $request
     * @internal param $code
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function activate(Request $request)
    {
        if ($user = $this->validateAuthentication($request))
        {
            $this->validate($request, [
                'password' => 'confirmed|required|min:6|max:30'
            ]);

            if (Activation::complete($user, $request->get('code')))
            {
                return redirect()->to('/');
            }
        }

        return $this->sendInvalidAuthenticationResponse();
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
     */
    public function index(Request $request)
    {
        if ($user = $this->validateAuthentication($request))
        {
            return view('warden::activation', [
                'user' => $user,
                'activationParameters' => [
                    'user' => $request->get('user'),
                    'code' => $request->get('code')
                ]
            ]);
        }

        return $this->sendInvalidAuthenticationResponse();
    }
}