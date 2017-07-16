<?php

namespace Revlv\Controllers\Biddings;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use PDF;
use Auth;
use Carbon\Carbon;
use Validator;

use \Revlv\Procurements\PhilGepsPosting\PhilGepsPostingRepository;
use \Revlv\Procurements\PhilGepsPosting\PhilGepsPostingRequest;
use \Revlv\Procurements\UnitPurchaseRequests\UnitPurchaseRequestRepository;
use \Revlv\Settings\AuditLogs\AuditLogRepository;
use \Revlv\Settings\Holidays\HolidayRepository;

class PhilGepsController extends Controller
{

    /**
     * [Base Route of Controller]
     *
     * @var string
     */
    protected $baseUrl  =   "biddings.philgeps.";

    /**
     * [$upr description]
     *
     * @var [type]
     */
    protected $upr;
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
    public function getDatatable(PhilGepsPostingRepository $model)
    {
        return $model->getDatatable('public');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->view('modules.biddings.philgeps.index',[
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

        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(
        Request $request,
        PhilGepsPostingRepository $model,
        HolidayRepository $holidays,
        UnitPurchaseRequestRepository $upr)
    {
        $upr_model              =   $upr->findById($request->upr_id);
        $invitation             =   Carbon::createFromFormat('Y-m-d',$upr_model->itb->approved_date);
        $transaction_date       =   Carbon::createFromFormat('Y-m-d', $request->pp_transaction_date);
        $holiday_lists          =   $holidays->lists('id','holiday_date');

        $day_delayed            =   $invitation->diffInDaysFiltered(function(Carbon $date)use ($holiday_lists) {
            return $date->isWeekday() && !in_array($date->format('Y-m-d'), $holiday_lists);
        }, $transaction_date);

        $day_delayed            =   $day_delayed - 1;

        $validator = Validator::make($request->all(),[
            'pp_transaction_date'        =>  'required',
            'pp_philgeps_posting'        =>  'required',
            'philgeps_number'            =>  'required',
            'action'                     =>  'required_with:remarks',
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

        $inputs                  =  [
            'transaction_date'   =>  $request->pp_transaction_date,
            'philgeps_posting'   =>  $request->pp_philgeps_posting,
            'philgeps_number'    =>  $request->philgeps_number,
            'remarks'            =>  $request->remarks,
            'action'             =>  $request->action,
            'newspaper'          =>  $request->newspaper,
        ];
        $inputs['upr_id']           =   $upr_model->id;
        $inputs['upr_number']       =   $upr_model->upr_number;
        $inputs['days']             =   $day_delayed;

        $result = $model->save($inputs);

        $upr->update(['status' => 'Philgeps Posted', 'delay_count' => $day_delayed + $upr_model->delay_count], $upr_model->id);

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
        PhilGepsPostingRepository $model)
    {
        $result =   $model->findById($id);

        return $this->view('modules.biddings.philgeps.show',[
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
        PhilGepsPostingRepository $model,
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
        Request $request,
        PhilGepsPostingRepository $model)
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
    public function destroy($id, PhilGepsPostingRepository $model)
    {
        $model->delete($id);

        return redirect()->route($this->baseUrl.'index')->with([
            'success'  => "Record has been successfully deleted."
        ]);
    }

}
