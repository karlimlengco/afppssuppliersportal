<?php

namespace Revlv\Settings\MasterLists;

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
    public function getDatatable()
    {
        $model  =   $this->model;

        $model  =   $model->select([
            'master_lists.id',
            'master_lists.name',
            'master_lists.file_name',
            'master_lists.user_id',
            'master_lists.created_at',
            \DB::raw("CONCAT(users.first_name,' ', users.surname) AS full_name")
        ]);

        $model  =   $model->leftJoin('users', 'users.id' ,'=', 'master_lists.user_id');

        $model->orderBy('created_at', 'desc');

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

                $route  =  route( 'settings.master-lists.master-download',[$data->id] );
                return ' <a  href="'.$route.'" > '. $data->name .'</a>';
            })
            ->editColumn('status', function($data){
                return ($data->status == 1) ? "Available" : "Disabled";
            })
            ->editColumn('use_button', function($data){
                $route  =  route('settings.master-lists.show', $data->id);
                return ' <a href="'.$route.'" data-toggle="tooltip" data-placement="top" title="Parse Data" > <span class="nc-icon-glyph arrows-2_file-upload-88 lg"></span> </a>';
            })
            ->editColumn('downloads', function($data){
                $route  =  route('settings.master-lists.downloads', $data->id);
                return ' <a href="'.$route.'" data-toggle="tooltip" data-placement="top" title="Downloads" > <span class="nc-icon-glyph files_folder-18 lg"></span> </a>';
            })
            ->editColumn('charts', function($data){
                $route  =  route('settings.master-lists.items', $data->id);
                return ' <a href="'.$route.'" data-toggle="tooltip" data-placement="top" title="View Charts" > <span class="nc-icon-glyph business_board-28 lg"></span> </a>';
            })
            ->editColumn('delete', function($data){
                $route  =  route('settings.master-lists.delete', $data->id);
                return ' <a href="'.$route.'" data-toggle="tooltip" data-placement="top" title="Delete" > <span class="nc-icon-glyph ui-1_trash-simple lg"></span> </a>';
            })
            ->rawColumns(['name', 'use_button', 'downloads', 'charts', 'delete'])
            ->make(true);
    }
}