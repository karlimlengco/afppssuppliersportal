<?php

namespace Revlv\Biddings\DocumentAcceptance;

use Revlv\BaseRepository;
use Illuminate\Database\Eloquent\Model;

class DocumentAcceptanceRepository extends BaseRepository
{
    use  DatatableTrait;
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return DocumentAcceptanceEloquent::class;
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
