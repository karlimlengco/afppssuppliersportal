<?php

namespace Revlv\Procurements\UnitPurchaseRequests;

use Revlv\BaseRepository;
use Illuminate\Database\Eloquent\Model;

class UnitPurchaseRequestRepository extends BaseRepository
{
    use  DatatableTrait;

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return UnitPurchaseRequestEloquent::class;
    }

    /**
     * Return the model by its key valued pair
     *
     * @param string $id
     * @param string $value
     * @return mixed
     */
    public function listPending($id = 'id', $value = 'name')
    {

        $model =    $this->model;

        $model =    $model->whereStatus('pending');

        return $model->pluck($value, $id)->all();
    }

    /**
     * [findByRFQId description]
     *
     * @param  [type] $rfq [description]
     * @return [type]      [description]
     */
    public function findByRFQId($rfq)
    {
        $model  =    $this->model;

        $model  =   $model->leftJoin('request_for_quotations', 'request_for_quotations.upr_id', '=', 'unit_purchase_requests.id');

        $model  =   $model->where('request_for_quotations.id', '=', $rfq);

        return $model->first();
    }
}
