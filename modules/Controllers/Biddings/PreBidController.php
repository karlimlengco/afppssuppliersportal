<?php

namespace Revlv\Controllers\Biddings;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use PDF;
use Auth;
use Carbon\Carbon;
use Validator;

use Revlv\Biddings\PreBid\PreBidRepository;
use Revlv\Biddings\PreBid\PreBidRequest;
use \Revlv\Procurements\UnitPurchaseRequests\UnitPurchaseRequestRepository;
use \Revlv\Settings\Suppliers\SupplierRepository;
use \Revlv\Settings\AuditLogs\AuditLogRepository;
use \Revlv\Settings\Holidays\HolidayRepository;

class PreBidController extends Controller
{

    /**
     * [Base Route of Controller]
     *
     * @var string
     */
    protected $baseUrl  =   "biddings.pre-bids.";

    /**
     * [$upr description]
     *
     * @var [type]
     */
    protected $upr;
    protected $suppliers;
    protected $holidays;
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
    public function getDatatable(PreBidRepository $model)
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
        return $this->view('modules.biddings.pre-bids.index',[
            'createRoute'   =>  $this->baseUrl."create"
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $this->view('modules.biddings.pre-bids.create',[
            'indexRoute'    =>  $this->baseUrl.'index',
            'id'            =>  $id,
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
        PreBidRequest $request,
        PreBidRepository $model,
        HolidayRepository $holidays,
        UnitPurchaseRequestRepository $upr)
    {
        $upr_model              =   $upr->findById($request->upr_id);
        $philgeps               =   Carbon::createFromFormat('Y-m-d',$upr_model->philgeps->transaction_date);
        $transaction_date       =   Carbon::createFromFormat('Y-m-d', $request->transaction_date);
        $holiday_lists          =   $holidays->lists('id','holiday_date');

        $day_delayed            =   $philgeps->diffInDaysFiltered(function(Carbon $date)use ($holiday_lists) {
            return $date->isWeekday() && !in_array($date->format('Y-m-d'), $holiday_lists);
        }, $transaction_date);

        $day_delayed            =   $day_delayed - 1;

        $validator = Validator::make($request->all(),[
            'transaction_date'  =>  'required',
            'action'            =>  'required_with:remarks',
        ]);

        $validator->after(function ($validator)use($day_delayed, $request) {
            if($request->resched_date == null)
            {
                if ( $request->get('remarks') == null && $day_delayed >= 1) {
                    $validator->errors()->add('remarks', 'This field is required when your process is delay');
                }
            }
        });

        if ($validator->fails()){
            return redirect()
                        ->back()
                        ->with(['error' => 'Your process is delay. Please add remarks to continue.'])
                        ->withErrors($validator)
                        ->withInput();
        }
        $inputs                     =   $request->getData();
        $inputs['processed_by']     =   \Sentinel::getUser()->id;
        $inputs['upr_number']       =   $upr_model->upr_number;
        $inputs['ref_number']       =   $upr_model->ref_number;
        $inputs['days']             =   $day_delayed;

        $result = $model->save($inputs);

        if($request->resched_date == null)
        {
            $upr->update(['status' => 'Pre Bid Conference', 'delay_count' => $day_delayed + $result->upr->delay_count], $result->upr_id);
        }

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
        PreBidRepository $model)
    {
        $result =   $model->findById($id);

        return $this->view('modules.biddings.pre-bids.show',[
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
        PreBidRepository $model,
        UnitPurchaseRequestRepository $upr)
    {
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
        PreBidRequest $request,
        PreBidRepository $model)
    {
        $model->update($request->getData(), $id);

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
    public function destroy($id, PreBidRepository $model)
    {
        $model->delete($id);

        return redirect()->route($this->baseUrl.'index')->with([
            'success'  => "Record has been successfully deleted."
        ]);
    }

}
