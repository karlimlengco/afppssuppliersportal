<?php

namespace Revlv\Settings\Forms\PCCOHeader;

use Revlv\BaseRepository;
use Illuminate\Database\Eloquent\Model;

class PCCOHeaderRepository extends BaseRepository
{
    use DatatableTrait;
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return PCCOHeaderEloquent::class;
    }

    /**
     * [findByUnit description]
     *
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function findByPCCO($id)
    {
        $model  =   $this->model;

        $model  =   $model->where('pcco_id','=', $id);

        $model  =   $model->first();

        return $model;
    }

    /**
     * [findByRFQId description]
     *
     * @param  [type] $rfq [description]
     * @return [type]      [description]
     */
    public function getById($id)
    {
        $model  =    $this->model;

        $model  =   $model->where('id', '=', $id);

        return $model->first();
    }
}
