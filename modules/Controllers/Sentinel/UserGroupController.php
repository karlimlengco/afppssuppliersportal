<?php

namespace Revlv\Controllers\Sentinel;

use App\Http\Controllers\Controller;
use Revlv\Users\UserRoleRequest;
use Revlv\Users\UserRepository;

class UserGroupController extends Controller
{

    /**
     * @var
     */
    private $userRepository;

    /**
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        parent::__construct();

        $this->userRepository = $userRepository;
    }


    /**
     * @param $user
     * @param UserRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update($user, UserRoleRequest $request)
    {
        $user = \Sentinel::findById($user);

        $user->roles()->detach();

        $role = \Sentinel::findRoleById($request->get('role'));

        foreach($role as $rol)
        {
            $rol->users()->attach($user);
        }

        return redirect()->route('settings.users.show', $user->username)->with([
            'success'  => "Role has been successfully updated."
        ]);
    }

}