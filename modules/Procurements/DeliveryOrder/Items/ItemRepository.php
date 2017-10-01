<?php

namespace Revlv\Procurements\DeliveryOrder\Items;

use Revlv\BaseRepository;
use Illuminate\Database\Eloquent\Model;

class ItemRepository extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return ItemEloquent::class;
    }

    /**
     * Return the model by its key valued pair
     *
     * @param string $id
     * @param string $value
     * @return mixed
     */
    public function getById($id)
    {
        $model =    $this->model;

        $model  =   $model->whereId($id);

        return $model->first();
    }
}
