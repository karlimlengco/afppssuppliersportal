<?php

namespace Revlv\Controllers\Biddings;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use PDF;
use Auth;
use Carbon\Carbon;
use Validator;

use Revlv\Biddings\BidOpening\BidOpeningRepository;
use Revlv\Biddings\BidOpening\BidOpeningRequest;
use \Revlv\Procurements\UnitPurchaseRequests\UnitPurchaseRequestRepository;
use \Revlv\Settings\Suppliers\SupplierRepository;
use \Revlv\Settings\Holidays\HolidayRepository;
use \Revlv\Settings\AuditLogs\AuditLogRepository;

class BidOpeningController extends Controller
{

    /**
     * [Base Route of Controller]
     *
     * @var string
     */
    protected $baseUrl  =   "biddings.bid-openings.";

    /**
     * [$upr description]
     *
     * @var [type]
     */
    protected $upr;
    protected $holidays;
    protected $suppliers;
    protected $audits;

    /**
     * [$model description]
     *
     * @var [type]
     */
    protected $model;

    /**
     * @param model $model
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * [getDatatable description]
     *
     * @return [type]            [description]
     */
    public function getDatatable(BidOpeningRepository $model)
    {
        return $model->getDatatable();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->view('modules.biddings.bid-openings.index',[
            'createRoute'   =>  $this->baseUrl."create"
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->view('modules.biddings.bid-openings.create',[
            'indexRoute'    =>  $this->baseUrl.'index',
            'modelConfig'   =>  [
                'store' =>  [
                    'route'     =>  $this->baseUrl.'store'
                ]
            ]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(
        BidOpeningRequest $request,
        BidOpeningRepository $model,
        HolidayRepository $holidays,
        UnitPurchaseRequestRepository $upr)
    {
        $upr_model              =   $upr->findById($request->upr_id);
        $pre_bid                =   Carbon::createFromFormat('Y-m-d',$upr_model->bid_conference->transaction_date);
        $transaction_date       =   Carbon::createFromFormat('Y-m-d', $request->op_transaction_date);
        $holiday_lists          =   $holidays->lists('id','holiday_date');

        $day_delayed            =   $pre_bid->diffInDaysFiltered(function(Carbon $date)use ($holiday_lists) {
            return $date->isWeekday() && !in_array($date->format('Y-m-d'), $holiday_lists);
        }, $transaction_date);

        if($day_delayed < 0)
        {
            $day_delayed            =   $day_delayed - 1;
        }

        $validator = Validator::make($request->all(),[
            'op_transaction_date'  =>  'required',
            'action'                =>  'required_with:remarks',
        ]);

        $validator->after(function ($validator)use($day_delayed, $request) {
            if ( $request->get('remarks') == null && $day_delayed >= 1) {
                $validator->errors()->add('remarks', 'This field is required when your process is delay');
            }
        });

        if ($validator->fails()){
            return redirect()
                        ->back()
                        ->with(['error' => 'Your process is delay. Please add remarks to continue.'])
                        ->withErrors($validator)
                        ->withInput();
        }
        $inputs['processed_by']     =   \Sentinel::getUser()->id;
        $inputs['transaction_date'] =   $request->op_transaction_date;
        $inputs['action']           =   $request->action;
        $inputs['remarks']          =   $request->remarks;
        $inputs['upr_number']       =   $upr_model->upr_number;
        $inputs['upr_id']           =   $upr_model->id;
        $inputs['ref_number']       =   $upr_model->ref_number;
        $inputs['days']             =   $day_delayed;

        $result = $model->save($inputs);

        $upr->update(['status' => 'Bid Open', 'delay_count' => $day_delayed + $upr_model->delay_count], $upr_model->id);

        return redirect()->route($this->baseUrl.'show', $result->id)->with([
            'success'  => "New record has been successfully added."
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(
        $id,
        BidOpeningRepository $model)
    {
        $result =   $model->findById($id);

        return $this->view('modules.biddings.bid-openings.show',[
            'data'              =>  $result,
            'indexRoute'        =>  $this->baseUrl.'index',
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id,
        BidOpeningRepository $model,
        UnitPurchaseRequestRepository $upr)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(
        $id,
        BidOpeningRequest $request,
        BidOpeningRepository $model)
    {
        $model->update($request->getData(), $id);

        return redirect()->route($this->baseUrl.'show', $id)->with([
            'success'  => "Record has been successfully updated."
        ]);
    }

    /**
     * [closed description]
     *
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function closed($id,
        BidOpeningRepository $model)
    {
        $model->update(['closing_date' => Carbon::now()], $id);

        return redirect()->route($this->baseUrl.'show', $id)->with([
            'success'  => "Record has been successfully updated."
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, BidOpeningRepository $model)
    {
        $model->delete($id);

        return redirect()->route($this->baseUrl.'index')->with([
            'success'  => "Record has been successfully deleted."
        ]);
    }

}
