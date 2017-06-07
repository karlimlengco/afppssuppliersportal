<?php

namespace Revlv\Controllers\Sentinel;

use App\Http\Controllers\Controller;
use Revlv\Sentinel\Roles\RoleRequest;
use Revlv\Sentinel\Roles\RoleRepository;
use Revlv\Sentinel\Roles\RoleCreateRequest;
use Revlv\Sentinel\Permissions\PermissionRepository;

class RoleController extends Controller
{

    /**
     * @var
     */
    private $model;

    /**
     * Permission Repository
     *
     */
    // protected $permissions;

    /**
     * @param model $model
     */
    public function __construct(RoleRepository $model, PermissionRepository $permissions)
    {
        parent::__construct();

        $this->model        = $model;
        $this->permissions  = $permissions;
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
        return $this->model->getDatatable();
    }

    /**
     *
     */
    public function index()
    {
        $this->view('modules.roles.index', [
            'roles' => $this->model->paginate()
        ]);
    }

    /**
     * @param $slug
     * @internal param $role
     */
    public function edit($id)
    {
        $this->view('modules.roles.edit', [
            'role' => $this->model->findById($id),
            'permissions'   => $this->permissions->lists('permission','description'),
            'modelConfig'   => [
                'destroy'   => [
                    'route' => ['settings.roles.destroy',$id],
                    'method'=> 'DELETE'
                ]
            ],
        ]);
    }

    /**
     * @param RoleCreateRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(RoleCreateRequest $request)
    {
        $role = $this->model->create($request->getData());

        return redirect()->route('settings.roles.edit', $role->id)->with([
            'success'  => "New record has been successfully added."
        ]);
    }

    /**
     *
     */
    public function create()
    {
        // lists($id = 'id', $value = 'name')
        $this->view('modules.roles.create',[
            'permissions'   => $this->permissions->lists('permission','description')
        ]);
    }

    /**
     * @param $slug
     * @param RoleRepository $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update($id, RoleRequest $request)
    {

        $this->model->update($request->getData(), $id);

        return redirect()->route('settings.roles.edit', $id)->with([
            'success'  => "Record has been successfully updated."
        ]);
    }

    /**
     * [destroy description]
     * @param  [type] $user [description]
     * @return [type]       [description]
     */
    public function destroy($id)
    {
        $role = $this->model->findById($id);
        $role->delete();

        return redirect()->route('settings.roles.index')->with([
            'success'  => "Record has been successfully deleted."
        ]);
    }
}