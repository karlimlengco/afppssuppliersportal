<?php

namespace Revlv\Settings\MasterLists\Items;

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
    public function getDatatable($id, $requests)
    {
        $model  =   $this->model;

        $model  =   $model->select([
            'master_items.id',
            'master_items.article',
            'master_items.master_id',
            'master_items.article',
            'master_items.created_at',
        ]);

        $model  =   $model->where('master_id', '=', $id);

        if(isset($requests['table_search']))
        {
            $model  =   $model->where('id', '=', $requests['table_search']);
        }

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
            ->addColumn('article', function ($data) {
                return ' <a  href="#" > '. $data->article .'</a>';
            })
            ->editColumn('chart', function($data){
                return ' <a href="'.route('settings.master-lists.items.show',$data->id).'" data-toggle="tooltip" data-placement="top" title="View Chart" > <span class="nc-icon-glyph business_chart-bar-33 lg"></span> </a>';
            })
            ->rawColumns(['article', 'chart'])
            ->make(true);
    }
}