<?php

namespace Revlv\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Revlv\Users\Traits\AuthenticatesUsers;
use Cartalyst\Sentinel\Checkpoints\ThrottlingException;
use Cartalyst\Sentinel\Checkpoints\ThrottleCheckpoint;
use Cartalyst\Sentinel\Checkpoints\NotActivatedException;

use Revlv\Users\UserCreateRequest;
use Revlv\Users\UserRepository;

class AuthController extends Controller
{
    use AuthenticatesUsers;

    /**
     * [$model description]
     *
     * @var [type]
     */
    protected $model;

    /**
     * @param content $content
     */
    public function __construct()
    {
        // parent::__construct();
    }

    /**
     * Show login page
     */
    public function index()
    {
        if (\Sentinel::check())
        {
            return redirect()->route('dashboard.index');
        }

        return view('auth.login');
    }

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
        // If the response is successful redirect the user\
        if ($response ===true)
        {
            if(\Sentinel::getUser()->user_type != 'supplier'){

                \Sentinel::logout();

                return redirect()
                    ->route($this->loginRoute)
                    ->withInput($request->only($this->getLoginKey($request)))
                    ->withErrors([
                        'auth' => 'Invalid username or password.'
                    ]);
            }


            if ($request->has('_redirect'))
            {
                return redirect($request->get('_redirect'));
            }

            return redirect()->route('procurements.ongoing');
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
            if ($user   =   \Sentinel::authenticate($credentials, $persist))
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
        catch(ThrottleCheckpoint $e)
        {
            return "Too many unsuccessful attempts have been made globally login is temporarily blocked.";
        }

        return "Invalid username or password.";
    }

    /**
     * Destroy the current session of the user
     */
    public function logout()
    {
        \Sentinel::logout();

        return redirect()->route('auth.login');
    }

    /**
     * [create description]
     *
     * @return [type] [description]
     */
    public function create()
    {
        return view('auth.register');
    }

    /**
     * [register description]
     * @return [type] [description]
     */
    public function register(UserCreateRequest $request, UserRepository $model)
    {
        $model->create($request->getData());

        return redirect()->route('auth.login')->with(['success'=>'check your email to activate your account.']);
    }
}