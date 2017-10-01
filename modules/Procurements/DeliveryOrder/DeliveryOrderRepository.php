<?php

namespace Revlv\Procurements\DeliveryOrder;

use DB;

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

    /**
     * [getAllItems description]
     *
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function getAllItems($id)
    {

        $model =    $this->model;

        $model =    $model->select([
            'delivery_order_items.description',
            DB::raw("COUNT(delivery_orders.delivery_number) as dr_count"),
            DB::raw("SUM(delivery_order_items.quantity) as quantity"),
            DB::raw("SUM(delivery_order_items.received_quantity) as received_quantity"),
        ]);

        $model  =   $model->leftJoin('delivery_order_items', 'delivery_order_items.order_id','=','delivery_orders.id');

        $model  =   $model->where('upr_id','=',$id);

        $model  =   $model->groupBy([
            'delivery_order_items.description',
        ]);

        return $model->get();
    }

    public function GetItemOrders($upr, $item)
    {

        $model =    $this->model;

        $model =    $model->select([
            'delivery_order_items.description',
            'delivery_order_items.quantity',
            'delivery_order_items.received_quantity',
            'delivery_orders.delivery_number',
            'delivery_orders.delivery_date',
        ]);

        $model  =   $model->leftJoin('delivery_order_items', 'delivery_order_items.order_id','=','delivery_orders.id');

        $model  =   $model->where('delivery_orders.upr_id','=',$upr);
        $model  =   $model->where('delivery_order_items.description','=', $item);

        return $model->get();
    }

}
