<?php

namespace Revlv\Controllers\Users;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;

use \Revlv\Users\ProfileUpdateRequest;
use \Revlv\Users\UserPasswordRequest;
use \Revlv\Users\UserRepository;

class ProfileController extends Controller
{

    /**
     * [Base Route of Controller]
     *
     * @var string
     */
    protected $baseUrl  =   "profile.";

    /**
     * [$users description]
     *
     * @var [type]
     */
    protected $users;

    /**
     * @param model $model
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user       =   Auth::user();

        return $this->view('modules.backend.users.profile',[
            'data'          =>  $user,
            'modelConfig'   =>  [
                'update'        =>  [
                    'route'         => [$this->baseUrl.'update', $user->id],
                    'method'        => 'PUT',
                    'files'         =>  true
                ],
                'updatePassword'=> [
                    'route'         => [$this->baseUrl.'updatePasswod', $user->id],
                    'method'        => 'PUT',
                ]
            ]
        ]);
        //
    }

    /**
     * [changePassword description]
     *
     * @param  UserPasswordRequest $request [description]
     * @return [type]                       [description]
     */
    public function changePassword(UserPasswordRequest $request, UserRepository $users)
    {
        $users->update(['password' =>$request->get('password') ], Auth::user()->id);

        return redirect()->route($this->baseUrl.'index')->with([
            'success'  => "Profile has been successfully updated."
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProfileUpdateRequest $request, $id, UserRepository $users)
    {
        $users->update($request->getData(), Auth::user()->id);

        return redirect()->route($this->baseUrl.'index')->with([
            'success'  => "Profile has been successfully updated."
        ]);
    }

}
