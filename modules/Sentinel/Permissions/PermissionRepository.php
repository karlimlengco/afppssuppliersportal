<?php

namespace Revlv\Sentinel\Permissions;

use App\Permission;
use Datatables;
use Revlv\BaseRepository;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Revlv\Sentinel\Permissions\PermissionEloquent;

class PermissionRepository extends BaseRepository
{

    /**
     * @var Group
     */
    protected $model;

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return PermissionEloquent::class;
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
            ->addColumn('permission', function ($data) {
                $route  =  route('settings.permissions.edit', $data->id);
                return '<a href="'.$route.'" > '. $data->permission .'</a>';
            })
            ->editColumn('description', function ($data) {
                return $data->description;
            })
            ->editColumn('created_at', function ($data) {
                if($data->created_at == null)
                {
                    return "";
                }
                return $data->created_at->toFormattedDateString();
            })
            ->rawColumns(['permission'])
            ->make(true);
    }

}
