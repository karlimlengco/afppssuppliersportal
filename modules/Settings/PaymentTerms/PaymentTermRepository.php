<?php

namespace Revlv\Settings\PaymentTerms;

use Revlv\BaseRepository;
use Illuminate\Database\Eloquent\Model;

class PaymentTermRepository extends BaseRepository
{
    use  DatatableTrait;

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return PaymentTermEloquent::class;
    }

    /**
     * [findByName description]
     *
     * @param  [type] $name [description]
     * @return [type]       [description]
     */
    public function findByName($name)
    {
        $model  =   $this->model;

        $model  =   $model->where('name', 'LIKE', "%$name%");

        return $model->first();
    }
}
