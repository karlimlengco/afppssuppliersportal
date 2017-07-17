<?php

namespace Revlv\Settings\Suppliers;

use Revlv\BaseRepository;
use Illuminate\Database\Eloquent\Model;

class SupplierRepository extends BaseRepository
{
    use  DatatableTrait;

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return SupplierEloquent::class;
    }

    /**
     * Return the model by its key valued pair
     *
     * @param string $id
     * @param string $value
     * @return mixed
     */
    public function lists($id = 'id', $value = 'name')
    {

        $model =  $this->model;

        $model =  $model->where('is_blocked','=',0);

        return $model->pluck($value, $id)->all();
    }
}
