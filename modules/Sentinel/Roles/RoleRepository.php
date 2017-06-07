<?php

namespace Revlv\Sentinel\Roles;

use App\Role;
use Datatables;
use Revlv\BaseRepository;
use Illuminate\Database\Eloquent\Model;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;

class RoleRepository extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Role::class;
    }

    /**
     * Create a new record in the model
     * @param array $data
     * @return mixed
     * @throws \Exception
     */
    public function create(array $data)
    {
        try
        {
            $permissions = $this->setPermissionsAttribute($data['permissions']);

            $role = Sentinel::getRoleRepository()->createModel()->create([
                'name'          => $data['name'],
                'slug'          => $data['slug'],
                'permissions'   =>  $permissions
            ]);

            return $role;
        }
        catch (\Exception $e)
        {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * Update the model
     *
     * @param Model $model
     * @param array $data
     * @return Model
     * @throws \Exception
     */
    public function update($data, $id)
    {
        $model = $this
            ->model
            ->find($id);

        try
        {
            $this->revokePermissions($model);

            // $permissions = $this->setPermissionsAttribute($data['permissions']);
            $permissions = $data['permissions'];

            foreach($data as $key => $value)
            {
                if ($key == 'permissions')
                {
                    $model->permissions = $this->formatPermission($data['permissions']);
                    // $model->permissions = $permissions;

                    continue;
                }

                $model->$key = ($value != '') ? $value : null;
            }

            $model->save();

            return $model;
        }
        catch (\Exception $e)
        {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * Format the permission to save
     *
     * @param $permissions
     * @return json
     */
    private function formatPermission($permissions)
    {
        $formattedPermissions = [];

        foreach($permissions as $permission)
        {
            $formattedPermissions[$permission] = true;
        }

        return $formattedPermissions;

        return json_encode($formattedPermissions);
    }

    /**
     * Set the permission to null
     *
     * @param $model
     */
    private function revokePermissions($model)
    {
        $model->permissions = [];
        $model->save();
    }

    /**
     * [findBySlug description]
     *
     * @param  [type] $slug [description]
     * @return [type]       [description]
     */
    public function findBySlug($slug)
    {
        $model  =   $this->model;

        $model  =   $model->whereSlug($slug);

        $model  =   $model->first();

        return $model;
    }

    /**
     * Set mutator for the "permissions" attribute.
     *
     * @param  mixed  $permissions
     * @return void
     */
    public function setPermissionsAttribute(array $permissions)
    {
        $newPermission =    [];
        foreach($permissions as $permission)
        {
            $newPermission  +=   [$permission=>true];
        }
        return $newPermission;
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
        $model  =   $this->model;

        return $this->dataTable($model->get());
    }

    /**
     * [dataTable description]
     *
     * @param  [type] $model [description]
     * @return [type]        [description]
     */
    public function dataTable($model)
    {
        return Datatables::of($model)
            ->addColumn('name', function ($data) {
                $route  =  route('settings.roles.edit', $data->id);
                return '<a href="'.$route.'" > '. $data->name .'</a>';
            })
            ->editColumn('slug', function ($data) {
                return $data->slug;
            })
            ->editColumn('created_at', function ($data) {
                if($data->created_at == null)
                {
                    return "";
                }
                return $data->created_at->toFormattedDateString();
            })
            ->rawColumns(['name'])
            ->make(true);
    }

}
