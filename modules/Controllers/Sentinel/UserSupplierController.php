<?php

namespace Revlv\Controllers\Sentinel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Sentinel;

use \Revlv\Settings\Suppliers\SupplierRepository;
use Revlv\Users\UserRequest;
use Revlv\Users\ProfileRequest;
use Revlv\Users\PasswordRequest;
use Revlv\Users\UserRepository;
use Revlv\Users\UserCreateRequest;
use Revlv\Sentinel\Roles\RoleRepository;
use \Revlv\Settings\CateredUnits\CateredUnitRepository;

class UserSupplierController extends Controller
{

    /**
     * @var
     */
    private $userRepository;
    protected $roleRepository;
    protected $units;
    protected $suppliers;

    private $gender = ['male'=> 'Male', 'female' => 'Female'];

    /**lists
     * @param UserRepository $userRepository
     */
    public function __construct(
        SupplierRepository $suppliers,
        UserRepository $userRepository,
        RoleRepository $roleRepository)
    {
        parent::__construct();

        // $this->middleware('revlv.user.locked', ['only' => ['create']]);

        $this->userRepository   = $userRepository;
        $this->suppliers   = $suppliers;
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
        return $this->userRepository->getDatatable();
    }

    /**
     *
     */
    public function index(Request $request )
    {
        $resources = $this->userRepository->getLists($request, 30, 'supplier');
        $this->view('modules.user-supplier.index',[
            'suppliers' => $resources
        ]);
    }

    /**
     * @param $username
     * @internal param $user
     */
    public function edit($username)
    {
        $gender         =   $this->gender;

        $this->view('modules.user-supplier.edit', [
            'modelConfig' => [
                'destroy' => [
                    'route'  => ['settings.user-suppliers.destroy',$username],
                    'method' => 'DELETE'
                ],
                'update'  => [
                    'route' =>  ['settings.user-suppliers.update',$username],
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
    public function show($username,CateredUnitRepository $units)
    {

        $gender         =   $this->gender;
        $unit_lists     =   $units->lists('id', 'short_code');
        $roles          =   $this->roleRepository->lists('id','name');
        $user           =   $this->userRepository->findByUsername($username);
        $suppliers     =   $this->suppliers->lists('id', 'name');
        $this->view('modules.user-supplier.show', [
            'user'          =>  $user,
            'suppliers'     =>  $suppliers,
            'unit_lists'    =>  $unit_lists,
            'editRoute'   => 'settings.user-suppliers.edit',
            'modelConfig' => [
                'update'  => [
                    'route' =>  ['settings.user-suppliers.update',$username],
                    'method'=>  'PATCH',
                    'files' =>  true,
                ],
                'destroy'       => [
                    'route'         => ['settings.user-suppliers.destroy',$username],
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
        $inputs['id']           =   \Uuid::generate()->string;
        $inputs['user_type']    =   'supplier';
        $inputs['suppliers']    = json_encode($request->suppliers);
        // $role = \Sentinel::findRoleById("666a03f0-b9d3-11e9-bc14-791eb08e81e3");
        $user = $this->userRepository->create($inputs);

        $role = \Sentinel::findRoleById("3965eb00-b9d3-11e9-bdc5-4d761a1f2ed8");

        if($role != null && $role->users() != null)
        {
            $role->users()->attach($user);
        }

        return redirect()->route('settings.user-suppliers.show', $user->username)->with([
            'success'  => "New record has been successfully added."
        ]);
    }

    /**
     *
     */
    public function create(CateredUnitRepository $units)
    {
        $gender         =   $this->gender;
        $unit_lists     =   $units->lists('id', 'short_code');
        $suppliers     =   $this->suppliers->lists('id', 'name');
        $this->view('modules.user-supplier.create', [
            'suppliers'       =>  $suppliers
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

        if($request->password == null)
        {
            unset($inputs['password']);
        }
        else
        {
            $inputs['password'] = bcrypt($inputs['password']);
        }

        $inputs['suppliers'] = json_encode($request->suppliers);
        $inputs['user_type']    =   'supplier';

        $this->userRepository->update($inputs, $user->id);

        return redirect()->route('settings.user-suppliers.show', $user->username)->with([
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

        $user->delete();

        return redirect()->route('settings.user-suppliers.index')->with([
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
     * [userLists description]
     *
     * @return [type] [description]
     */
    public function userLists(Request $request)
    {
        $result =   $this->userRepository->getLists($request, 20);

        $response = [
            'pagination' => [
                'total' => $result->total(),
                'per_page' => $result->perPage(),
                'current_page' => $result->currentPage(),
                'last_page' => $result->lastPage(),
                'from' => $result->firstItem(),
                'to' => $result->lastItem()
            ],
            'data' => $result
        ];

        return  $response;
    }

}