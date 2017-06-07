<?php

namespace Revlv\Controllers\Sentinel;

use Revlv\Users\UserRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Revlv\Users\Traits\ChangesPassword;

class ResetPasswordController extends Controller
{
    use ChangesPassword;


    /**
     * @param User $model
     */
    public function __construct(UserRepository $model)
    {
        $this->model = $model;
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
            return view('modules.sentinel.reset', [
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
