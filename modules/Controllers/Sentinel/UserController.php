<?php

namespace Revlv\Controllers\Sentinel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Sentinel;

use Revlv\Users\UserRequest;
use Revlv\Users\ProfileRequest;
use Revlv\Users\PasswordRequest;
use Revlv\Users\UserRepository;
use Revlv\Users\UserCreateRequest;
use Revlv\Sentinel\Roles\RoleRepository;

class UserController extends Controller
{

    /**
     * @var
     */
    private $userRepository;

    /**
     * Role Repository
     *
     * @var [type]
     */
    protected $roleRepository;

    private $gender = ['male'=> 'Male', 'female' => 'Female'];

    /**
     * @param UserRepository $userRepository
     */
    public function __construct(
        UserRepository $userRepository,
        RoleRepository $roleRepository)
    {
        parent::__construct();

        // $this->middleware('revlv.user.locked', ['only' => ['create']]);

        $this->userRepository   = $userRepository;
        $this->roleRepository   = $roleRepository;
    }

    /**
     * [getAll description]
     *
     * @param  integer $limit    [description]
     *
     * @param  boolean $paginate [description]
     *
     * @return [type]            [description]
     */
    public function getDatatable()
    {
        $company_id =   \Sentinel::getUser()->company_id;
        return $this->userRepository->getDatatable($company_id);
    }

    /**
     *
     */
    public function index()
    {
        $this->view('modules.users.index', [
            // 'users' => $this->userRepository->paginate()
        ]);
    }

    /**
     * @param $username
     * @internal param $user
     */
    public function edit($username)
    {
        $gender         =   $this->gender;

        $this->view('modules.users.edit', [
            'modelConfig' => [
                'destroy' => [
                    'route'  => ['settings.users.destroy',$username],
                    'method' => 'DELETE'
                ],
                'update'  => [
                    'route' =>  ['settings.users.update',$username],
                    'method'=>  'PATCH',
                    'class' => 'form-horizontal form-label-left',
                ]
            ],
            'user' => $this->userRepository->findByUsername($username),
            'genders'   =>  $gender,
        ]);
    }

    /**
     * @param $username
     * @internal param $user
     */
    public function show($username)
    {

        $gender         =   $this->gender;
        $roles          =   $this->roleRepository->lists('id','name');
        $user           =   $this->userRepository->findByUsername($username);

        $this->view('modules.users.show', [
            'user'          =>  $user,
            'editRoute'   => 'settings.users.edit',
            'modelConfig' => [
                'update'  => [
                    'route' =>  ['settings.users.update',$username],
                    'method'=>  'PATCH',
                    'files' =>  true,
                    'class' => 'form-horizontal form-label-left',
                ],
                'destroy'       => [
                    'route'         => ['settings.users.destroy',$username],
                    'method'        => 'DELETE'
                ],
            ],
            'roles'         => $roles,
            'genders'       =>  $gender,
        ]);
    }


    /**
     * @param UserCreateRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(UserCreateRequest $request)
    {
        $inputs                 =   $request->getData();

        $user = $this->userRepository->create($inputs);

        $role = \Sentinel::findRoleById(2);

        if($role != null && $role->users() != null)
        {
            $role->users()->attach($user);
        }

        return redirect()->route('settings.users.show', $user->username)->with([
            'success'  => "New record has been successfully added."
        ]);
    }

    /**
     *
     */
    public function create()
    {
        $gender         =   $this->gender;

        $this->view('modules.users.create', [
            'genders'       =>  $gender,
        ]);
    }

    /**
     * @param $user
     * @param UserRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update($user, UserRequest $request)
    {
        $user               =   $this->userRepository->findByUsername($user);
        $inputs             =   $request->getData();

        $this->userRepository->update($inputs, $user->id);

        return redirect()->route('settings.users.show', $user->username)->with([
            'success'  => "Record has been successfully updated."
        ]);
    }

    /**
     * [destroy description]
     * @param  [type] $user [description]
     * @return [type]       [description]
     */
    public function destroy($user)
    {
        $user = $this->userRepository->findByUsername($user);

        $user->forceDelete();

        return redirect()->route('settings.users.index')->with([
            'success'  => "Record has been successfully deleted."
        ]);
    }


    /**
     * @param $id
     */
    public function showAvatar($id)
    {
        $user = $this->userRepository->findById($id);
        // Erase output buffer, bug in laravel controller
        ob_end_clean();
        if($user->avatar != null)
        {
            return \Image::make(public_path('avatars').'/'.$user->avatar)->response('jpg');
        }
        else
        {
            return \Image::make(public_path('avatars/default.png'))->response('jpg');
        }
    }

    /**
     * [showProfile description]
     *
     * @return [type] [description]
     */
    public function showProfile()
    {
        $user           =   \Sentinel::getUser();
        $gender         =   $this->gender;

        $this->view('modules.users.profile', [
            'user' => $user,
            'profile'=> true,
            'modelConfig' => [
                'update'  => [
                    'route' =>  ['profile.update',$user->username],
                    'method'=>  'PATCH',
                    'files' =>  true,
                    'class' => 'form-horizontal form-label-left',
                ],
                'updatePaypal'  => [
                    'route' =>  ['settings.account-settings.update',$user->username],
                    'method'=>  'PATCH',
                    'files' =>  true,
                    'class' => 'form-horizontal form-label-left',
                ]
            ],
            'genders'       =>  $gender,
        ]);
    }

    /**
     * [updateProfile description]
     *
     * @return [type] [description]
     */
    public function updateProfile($user, ProfileRequest $request)
    {
        $user               =   $this->userRepository->findByUsername($user);
        $input              =   $request->getData();

        $this->userRepository->update($input, $user->id);

        return redirect()->route('profile.show')->with([
            'success'  => "Record has been successfully updated."
        ]);
    }

}