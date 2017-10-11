<?php

namespace App\Http\Controllers;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use JWTAuth;
use Revlv\Users\UserRepository;
use Revlv\Users\RoleUserRepository;

class UserController extends ApiController
{
    protected $user;
    protected $roles;

    public function index(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();
        return response()->json([
            'data' => $user,
        ]);
    }

    /**
     * [userRole description]
     *
     * @param  Request            $request [description]
     * @param  RoleUserRepository $roles   [description]
     * @return [type]                      [description]
     */
    public function userRole(Request $request, RoleUserRepository $roles)
    {
        $role_list = $roles->all();
        return response()->json([
            'data' => $role_list,
        ]);
    }

    /**
     * [listAll description]
     *
     * @param  Request        $request [description]
     * @param  UserRepository $user    [description]
     * @return [type]                  [description]
     */
    public function listAll(Request $request, UserRepository $user)
    {
        $users = $user->getAllAdmins();
        return response()->json([
            'data' => $users,
        ]);
    }

    /**
     * [store description]
     *
     * @param  Request        $request [description]
     * @param  UserRepository $user    [description]
     * @return [type]                  [description]
     */
    public function store(Request $request, UserRepository $user)
    {
        foreach($request->model as $data)
        {
            unset($data['permissions']);
            $model = $user->create($data);

            $role = \Sentinel::findRoleById(2);

            if($role != null && $role->users() != null)
            {
                $role->users()->attach($model);
            }
        }
        return $request->all();
    }
}
