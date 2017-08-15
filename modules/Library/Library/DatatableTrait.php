<?php

namespace Revlv\Library\Library;

use Illuminate\Http\Request;
use DB;
use Datatables;

trait DatatableTrait
{


    /**
     * [getDatatable description]
     *
     * @param  [int]    $company_id ['company id ']
     * @return [type]               [description]
     */
    public function getDatatable($status)
    {
        $model  =   $this->model;

        $model  =   $model->select([
            'library.id',
            'library.name',
            'library.tags',
            'library.uploaded_by',
            'library.file_name',
            'library.catalog_id',
            'library.created_at',
            'library_catalogs.name as catalog',
        ]);

        $model  =   $model->where('status', '=', $status);

        $model  =   $model->leftJoin('library_catalogs', 'library_catalogs.id', '=', 'library.catalog_id');

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
                $route  =  route( 'library.files.edit',[$data->id] );
                return ' <a  href="'.$route.'" > '. $data->name .'</a>';
            })
            ->editColumn('description', function($data){
                return $data->description;
            })
            ->editColumn('print_button', function ($data) {
                $route  =   route('library.file.download',$data->id);
                return '<a target="_blank" href="'.$route.'" tooltip="Download"> <span class="nc-icon-mini arrows-1_cloud-download-95"></span>  </a>';
            })
            ->rawColumns(['name', 'print_button'])
            ->make(true);
    }
}