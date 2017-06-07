<?php

namespace Revlv\Users\Traits;

use Sentinel;
use Illuminate\Http\Request;
use Cartalyst\Sentinel\Checkpoints\ThrottlingException;
use Cartalyst\Sentinel\Checkpoints\NotActivatedException;

trait AuthenticatesUsers
{
    /**
     * Login route
     *
     * @var
     */
    protected $loginRoute = 'auth.index';

    /**
     * Handle login request
     *
     * @param Request $request
     * @return $this
     */
    public function login(Request $request)
    {
        $this->validate($request, [
            'login' => 'required', 'password' => 'required|min:5|max:25'
        ]);

        $credentials = $this->getCredentials($request);

        $response = $this->authenticate($credentials, $request->has('remember'));
        // If the response is successful redirect the user
        if ($response === true)
        {
            return redirect()->intended();
        }

        // If the authentication process fails, redirect the user back to the login form
        // and show an appropriate response
        return redirect()
            ->route($this->loginRoute)
            ->withInput($request->only($this->getLoginKey($request)))
            ->withErrors([
                'auth' => $response
            ]);
    }

    /**
     * Attempt to authenticate the user
     *
     * @param $credentials
     * @return bool
     */
    protected function authenticate($credentials, $persist = false)
    {
        try
        {
            if (Sentinel::authenticate($credentials, $persist))
            {
                return true;
            }
        }
        catch(NotActivatedException $e)
        {
            return "Your account is not activated.";
        }
        catch(ThrottlingException $e)
        {
            return "Your account is temporarily blocked.";
        }

        return "Invalid username or password.";
    }

    /**
     * Destroy the current session of the user
     */
    public function logout()
    {
        Sentinel::logout();

        return redirect()->to('/');
    }

    /**
     * Get the required credentials
     *
     * @param Request $request
     * @return array
     */
    protected function getCredentials(Request $request)
    {
        $loginKey = $this->getLoginKey($request);

        // Merge the loginkey data to the actual request
        $request->merge([$loginKey => $request->get('login')]);

        // Return the altered request
        return $request->only($loginKey, 'password');
    }

    /**
     * Return the login key used to authenticate
     *
     * @param Request $request
     * @return string
     */
    protected function getLoginKey(Request $request)
    {
        return filter_var($request->get('login'), FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
    }

}