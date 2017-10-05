<?php

namespace Revlv\Settings\Forms\RFQ;

use Revlv\BaseRepository;
use Illuminate\Database\Eloquent\Model;

class RFQRepository extends BaseRepository
{
    use DatatableTrait;
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return RFQEloquent::class;
    }

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
