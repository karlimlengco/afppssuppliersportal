<?php

namespace Revlv\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Revlv\Users\Traits\ActivatesUsers;

class ActivationController extends Controller
{
    use ActivatesUsers;

    public function __construct()
    {
    }

    /**
     * Show the activation screen
     */
    public function index(Request $request)
    {
        if ($user = $this->validateAuthentication($request))
        {
            return view('modules.sentinel.activation', [
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

            if (\Activation::complete($user, $request->get('code')))
            {

                $user->password = bcrypt( $request->get('password') );
                $user->save();

                return redirect()->to('/');
            }
        }

        return $this->sendInvalidAuthenticationResponse();
    }

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
            if ($user = \Sentinel::findById( $request->get('user')))
            {
                $activation = \Activation::exists($user, $request->get('code'));

                // Check if the user exists and if the activation is successful
                if ($user && \Activation::completed($user) !== true && $activation)
                {
                    return $user;
                }
            }
        }

        return false;
    }
}
