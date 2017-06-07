<?php

namespace Revlv\Users\Traits;

use Illuminate\Http\Request;
use Cartalyst\Sentinel\Native\Facades\Sentinel;

trait RegistersUsers
{
    use SendsActivationCode;

    /**
     * Handle registration process
     *
     * @param Request $request
     */
    public function register(Request $request, $activate = false)
    {
        $this->validate($request, [
            'email'    => 'required|email',
            'username' => 'required|min:5',
            'password' => 'required|min:6|max:30|confirmed'
        ]);

        if ($user = Sentinel::register($this->getAllowedData($request)))
        {
            $user->username = $request->get('username');
            $user->display_name = $request->get('display_name');
            $user->save();

            $this->sendActivationCode($user);
        }
    }

    /**
     * A list of allowable data
     *
     * @param Request $request
     * @return array
     */
    protected function getAllowedData(Request $request)
    {
        return $request->only([
            'email',
            'username',
            'password',
            'first_name',
            'last_name',
            'display_name'
        ]);
    }

}