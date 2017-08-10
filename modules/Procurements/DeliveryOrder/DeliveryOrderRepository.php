<?php

namespace Revlv\Procurements\DeliveryOrder;

use Revlv\BaseRepository;
use Illuminate\Database\Eloquent\Model;

class DeliveryOrderRepository extends BaseRepository
{
    use  DatatableTrait, DTCTrait;

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return DeliveryOrderEloquent::class;
    }

    /**
     * Return the model by its key valued pair
     *
     * @param string $id
     * @param string $value
     * @return mixed
     */
    public function listCompleted($id = 'id', $value = 'name')
    {
        $model =    $this->model;
        $model =    $model->whereStatus('completed');

        return $model->pluck($value, $id)->all();
    }

    /**
     * Return the model by its key valued pair
     *
     * @param string $id
     * @param string $value
     * @return mixed
     */
    public function listNotInspected($id = 'id', $value = 'name')
    {
        $model =    $this->model;

        $model  =   $model->select([
            'delivery_orders.*',
            'delivery_inspection.id as inspection_id',
        ]);

        $model  =   $model->leftJoin('delivery_inspection', 'delivery_inspection.dr_id', '=', 'delivery_orders.id');

        $model  =   $model->whereNull('delivery_inspection.id');
        // $model =    $model->whereStatus('completed');

        return $model->pluck($value, $id)->all();
    }

    /**
     * Return the model by its key valued pair
     *
     * @param string $id
     * @param string $value
     * @return mixed
     */
    public function listDelivered($id = 'id', $value = 'name')
    {
        $model =    $this->model;

        $model  =   $model->whereNotNull('date_delivered_to_coa');

        return $model->pluck($value, $id)->all();
    }

}
