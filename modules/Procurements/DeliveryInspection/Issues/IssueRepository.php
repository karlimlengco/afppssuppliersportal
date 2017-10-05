<?php

namespace Revlv\Procurements\DeliveryInspection\Issues;

use Revlv\BaseRepository;
use Illuminate\Database\Eloquent\Model;

class IssueRepository extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return IssueEloquent::class;
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
