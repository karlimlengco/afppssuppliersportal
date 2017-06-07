<?php

namespace Revlv\Controllers\Sentinel;

use App\Http\Controllers\Controller;
use Revlv\Sentinel\Permissions\PermissionRequest;
use Revlv\Sentinel\Permissions\PermissionRepository;
use Revlv\Sentinel\Permissions\PermissionCreateRequest;

class PermissionController extends Controller
{

    /**
     * @var
     */
    private $model;

    /**
     * @param model $model
     */
    public function __construct(PermissionRepository $model)
    {
        parent::__construct();

        $this->model = $model;
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
        $this->view('modules.permissions.index', [
            'permissions' => $this->model->paginate()
        ]);
    }

    /**
     * @param $slug
     * @internal param $permission
     */
    public function edit($id)
    {
        $this->view('modules.permissions.edit', [
            'modelConfig' => [
                'destroy' => [
                    'route'  => ['settings.permissions.destroy',$id],
                    'method' => 'DELETE'
                ]
            ],
            'permission' => $this->model->findById($id)
        ]);
    }

    /**
     * @param PermissionCreateRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(PermissionCreateRequest $request)
    {
        $permission = $this->model->save($request->getData());

        return redirect()->route('settings.permissions.edit', $permission->id)->with([
            'success'  => "New record has been successfully added."
        ]);
    }

    /**
     *
     */
    public function create()
    {
        $this->view('modules.permissions.create');
    }

    /**
     * @param $slug
     * @param PermissionRepository $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update($id, PermissionRequest $request)
    {
        $this->model->update($request->getData(), $id);

        return redirect()->route('settings.permissions.edit', $id)->with([
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

        $permission = $this->model->findById($id);

        $permission->delete();

        return redirect()->route('settings.permissions.index')->with([
            'success'  => "Record has been successfully deleted."
        ]);
    }
}