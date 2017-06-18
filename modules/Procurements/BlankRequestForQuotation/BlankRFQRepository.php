<?php

namespace Revlv\Procurements\BlankRequestForQuotation;

use Revlv\BaseRepository;
use Illuminate\Database\Eloquent\Model;

class BlankRFQRepository extends BaseRepository
{
    use  DatatableTrait;

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return BlankRFQEloquent::class;
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
     * [findAwardeeById description]
     *
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function findAwardeeById($id)
    {
        $model  =   $this->model;

        $model  =   $model->select([
            'request_for_quotations.*',
            'rfq_proponents.bid_amount',
        ]);

        $model  =   $model->leftJoin('rfq_proponents', 'rfq_proponents.rfq_id', '=', 'request_for_quotations.id');

        $model  =   $model->whereNotNull('rfq_proponents.is_awarded');
        $model  =   $model->where('request_for_quotations.id', '=', $id);
        return $model->first();
    }

    /**
     * Return the model by its key valued pair
     *
     * @param string $id
     * @param string $value
     * @return mixed
     */
    public function listsAccepted($id = 'id', $value = 'name')
    {
        $model =    $this->model;

        $model =    $model->whereNotNull('is_award_accepted');

        return $model->pluck($value, $id)->all();
    }

    /**
     * [getInfo description]
     *
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function getInfo($id)
    {
        $model  =    $this->model;

        $model  =   $model->select([
            'request_for_quotations.id',
            'request_for_quotations.upr_id',
            'request_for_quotations.deadline',
            'request_for_quotations.status as rfq_status',
            'unit_purchase_requests.terms_of_payment',
            'unit_purchase_requests.upr_number',
            'unit_purchase_requests.total_amount as abc',
            'unit_purchase_requests.afpps_ref_number',
            'unit_purchase_requests.purpose',
            'unit_purchase_request_items.id as item_id',
            'unit_purchase_request_items.item_description',
            'unit_purchase_request_items.item_description',
            'unit_purchase_request_items.quantity',
            'unit_purchase_request_items.unit_measurement',
            'unit_purchase_request_items.unit_price',
            'unit_purchase_request_items.total_amount',
        ]);

        $model  =   $model->leftJoin('unit_purchase_requests', 'unit_purchase_requests.id', '=', 'request_for_quotations.upr_id');

        $model  =   $model->leftJoin('unit_purchase_request_items', 'unit_purchase_request_items.upr_id', '=', 'request_for_quotations.upr_id');

        $model  =   $model->where('request_for_quotations.id','=', $id);
        $model  =   $model->whereNotNull('is_award_accepted');

        return $model->get();
    }
}
