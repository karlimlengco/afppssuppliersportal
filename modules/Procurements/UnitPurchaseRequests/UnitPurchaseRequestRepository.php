<?php

namespace Revlv\Procurements\UnitPurchaseRequests;

use Revlv\BaseRepository;
use DB;
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

    /**
     * [findTimelineById description]
     *
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function findTimelineById($id)
    {
        $model  =    $this->model;

        $model  =   $model->select([
            'unit_purchase_requests.id',
            'unit_purchase_requests.project_name',
            'unit_purchase_requests.upr_number',
            'unit_purchase_requests.ref_number',
            'unit_purchase_requests.date_prepared',
            'unit_purchase_requests.state',
            'unit_purchase_requests.date_processed',
            'unit_purchase_requests.created_at as upr_created_at',
            'request_for_quotations.id as rfq_id',
            'request_for_quotations.rfq_number',
            'request_for_quotations.status as rfq_status',
            'request_for_quotations.created_at as rfq_created_at',
            'request_for_quotations.completed_at as rfq_completed_at',
            'philgeps_posting.id as pp_id',
            'philgeps_posting.philgeps_posting as pp_completed_at',
            'invitation_for_quotation.transaction_date as ispq_transaction_date',
        ]);

        $model  =   $model->leftJoin('request_for_quotations', 'request_for_quotations.upr_id', '=', 'unit_purchase_requests.id');
        $model  =   $model->leftJoin('philgeps_posting', 'philgeps_posting.upr_id', '=', 'unit_purchase_requests.id');
        $model  =   $model->leftJoin('ispq_quotations', 'ispq_quotations.upr_id', '=', 'unit_purchase_requests.id');
        $model  =   $model->leftJoin('invitation_for_quotation', 'invitation_for_quotation.id', '=', 'ispq_quotations.ispq_id');

        $model  =   $model->where('unit_purchase_requests.id', '=', $id);

        return $model->first();
    }

    /**
     * [getCountByYear description]
     *
     * @param  [type] $year [description]
     * @return [type]       [description]
     */
    public function getCountByYear($year)
    {

        $model  =   $this->model;

        $model  =   $model->select([
            DB::raw("count(id) as total")
        ]);

        $model  =   $model->whereYear('prepared_by', '=', $year);

        return $model->first();
    }
}
