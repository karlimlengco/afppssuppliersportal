<?php

namespace Revlv\Controllers\Biddings;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use PDF;
use Carbon\Carbon;
use Validator;
use \App\Support\Breadcrumb;
use App\Events\Event;

use Revlv\Biddings\BidDocs\BidDocsRepository;
use \Revlv\Procurements\NoticeOfAward\NOARepository;
use \Revlv\Procurements\UnitPurchaseRequests\UnitPurchaseRequestRepository;
use \Revlv\Settings\Signatories\SignatoryRepository;
use Revlv\Biddings\PostQualification\PostQualificationRepository;
use \Revlv\Settings\AuditLogs\AuditLogRepository;
use \Revlv\Settings\Holidays\HolidayRepository;

class NOAController extends Controller
{

    /**
     * [Base Route of Controller]
     *
     * @var string
     */
    protected $baseUrl  =   "biddings.noa.";

    /**
     * [$blank description]
     *
     * @var [type]
     */
    protected $blank;
    protected $upr;
    protected $noa;
    protected $post_qual;
    protected $signatories;
    protected $audits;
    protected $holidays;
    protected $bid_docs;

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
     * [awardToProponent description]
     *
     * @return [type] [description]
     */
    public function awardToProponent(
        Request $request,
        PostQualificationRepository $model,
        UnitPurchaseRequestRepository $upr,
        BidDocsRepository $bid_docs,
        NOARepository $noa,
        $pq_id,
        $proponentId,
        HolidayRepository $holidays
        )
    {
        $pq_model               =   $model->findById($pq_id);
        $pqDate                 =   Carbon::createFromFormat('!Y-m-d', $pq_model->transaction_date);
        $transaction_date       =   Carbon::createFromFormat('Y-m-d', $request->get('awarded_date') );

        $proponent_model        =   $bid_docs->findById($proponentId);

        $holiday_lists          =   $holidays->lists('id','holiday_date');
        $supplier_name          =   $proponent_model->proponent_name;

        $day_delayed            =   $pqDate->diffInDaysFiltered(function(Carbon $date)use ($holiday_lists) {
            return $date->isWeekday() && !in_array($date->format('Y-m-d'), $holiday_lists);
        }, $transaction_date);

        $cd                     =   $pqDate->diffInDays($transaction_date);
        $wd                     =   ($day_delayed > 0) ?  $day_delayed - 1 : 0;

        if($day_delayed  > 15)
        {
            $day_delayed = $day_delayed - 15;
        }

        $validator = Validator::make($request->all(),[
            'awarded_date'  =>   'required_without:return_date|after_or_equal:'.$pq_model->transaction_date,
            'awarded_by'    =>   'required',
            'seconded_by'   =>   'required',
        ]);

        $validator->after(function ($validator)use($day_delayed, $request) {
            if ( $request->get('remarks') == null && $day_delayed > 15) {
                $validator->errors()->add('remarks', 'This field is required when your process is delay');
            }
            if ( $request->get('action') == null && $day_delayed > 15) {
                $validator->errors()->add('action', 'This field is required when your process is delay');
            }
        });

        if ($validator->fails()){
            return redirect()
                        ->back()
                        ->with(['error' => 'Please Check Your Fields.'])
                        ->withErrors($validator)
                        ->withInput();
        }

        $data   =   [
            'canvass_id'    =>  $pq_id,
            'upr_id'        =>  $pq_model->upr_id,
            'upr_number'    =>  $pq_model->upr_number,
            'proponent_id'  =>  $proponentId,
            'awarded_by'    =>  $request->awarded_by,
            'seconded_by'   =>  $request->seconded_by,
            'awarded_date'  =>  $request->awarded_date,
            'remarks'       =>  $request->remarks,
            'action'       =>  $request->action,
            'days'          =>  $wd,
        ];

        $noaModal   =   $noa->save($data);

        // // update upr
        $upr_result  = $upr->update([
            'next_allowable'=> 1,
            'next_step'     => 'Approved NOA',
            'next_due'      => $transaction_date->addDays(1),
            'last_date'     => $transaction_date,
            'status'        => "Awarded To $supplier_name",
            'delay_count'   => $wd,
            'calendar_days' => $cd + $noaModal->upr->calendar_days,
            'last_action'   => $request->action,
            'last_remarks'  => $request->remarks
            ],  $pq_model->upr_id);


        event(new Event($upr_result, $upr_result->ref_number." Award NOA"));

        return redirect()->route('biddings.noa.show', $noaModal->id)->with([
            'success'  => "New record has been successfully added."
        ]);
    }

    /**
     * [getDatatable description]
     *
     * @return [type]            [description]
     */
    public function getDatatable(NOARepository $model)
    {
        return $model->getDatatable('bidding');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->view('modules.biddings.noa.index',[
            'breadcrumbs' => [
                new Breadcrumb('Public Bidding'),
                new Breadcrumb('Notice of Award', 'biddings.noa.index'),
            ]
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(
        Request $request,
        $id,
        UnitPurchaseRequestRepository $upr,
        NOARepository $model,
        HolidayRepository $holidays
        )
    {
        $noaModel       =   $model->findById($id);
        $holiday_lists  =   $holidays->lists('id','holiday_date');

        $accepted_date =   Carbon::createFromFormat('!Y-m-d', $noaModel->accepted_date);
        $award_accepted_date  =   Carbon::createFromFormat('Y-m-d', $request->award_accepted_date);

        $day_delayed    =   $accepted_date->diffInDaysFiltered(function(Carbon $date)use ($holiday_lists) {
            return $date->isWeekday() && !in_array($date->format('Y-m-d'), $holiday_lists);
        }, $award_accepted_date);

        $validator = Validator::make($request->all(),[
            'received_by'           =>  'required',
            'award_accepted_date'   =>  'required',
            'received_action'       =>   'required_with:received_remarks'
        ]);

        $validator->after(function ($validator)use($day_delayed, $request) {
            if ( $request->get('received_remarks') == null && $day_delayed > 1) {
                $validator->errors()->add('received_remarks', 'This field is required when your process is delay');
            }
        });

        if ($validator->fails()){
            return redirect()
                        ->back()
                        ->with(['error' => 'Please Check Your Fields.'])
                        ->withErrors($validator)
                        ->withInput();
        }

        $input  =   [
            'received_by'           =>  $request->received_by,
            'award_accepted_date'   =>  $request->award_accepted_date,
            'status'                =>  'accepted',
            'received_days'         =>  $day_delayed,
            'received_remarks'      =>  $request->received_remarks,
            'received_action'      =>  $request->received_action,
        ];

        $result             =   $model->findById($id);

        $model->update($input, $id);
        $upr_result    =  $upr->update([
            'status' => 'NOA Received',
            'next_allowable'=> 10,
            'next_step'     => 'Create Contract',
            'next_due'      => $award_accepted_date->addDays(10),
            'last_date'     => $award_accepted_date,
            'status'        => 'NOA Received',
            'delay_count'   => ($day_delayed > 1 )? $day_delayed - 1 : 0,
            'calendar_days' => $day_delayed + $result->upr->calendar_days,
            'last_action'   => $request->action,
            'last_remarks'  => $request->remarks
            ], $result->upr_id);


        event(new Event($upr_result, $upr_result->ref_number." NOA Received"));

        return redirect()->route($this->baseUrl.'show', $id)->with([
            'success'  => "Record has been successfully updated."
        ]);
    }

    /**
     * [addPerformanceBond description]
     *
     * @param Request       $request [description]
     * @param NOARepository $model   [description]
     */
    public function addPerformanceBond(Request $request, NOARepository $model)
    {

        $this->validate($request,[
            'perfomance_bond'=>  'required',
            'noa_id'=>  'required',
        ]);

        $model->update([
            'perfomance_bond'   =>  $request->perfomance_bond,
            'amount'            =>  $request->amount,
            'notes'             =>  $request->notes,
        ], $request->noa_id);

        return redirect()->route($this->baseUrl.'show', $request->noa_id)->with([
            'success'  => "Record has been successfully updated."
        ]);

    }

    /**
     * [acceptNOA description]
     *
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function acceptNOA(
        Request $request,
        NOARepository $model,
        UnitPurchaseRequestRepository $upr,
        HolidayRepository $holidays)
    {
        $id             =   $request->id;
        $noaModel       =   $model->findById($id);
        $holiday_lists  =   $holidays->lists('id','holiday_date');

        $noa_award_date =   Carbon::createFromFormat('Y-m-d H:i:s', $noaModel->awarded_date)->format('Y-m-d');
        $noa_award_date =   Carbon::createFromFormat('!Y-m-d', $noa_award_date);
        $accepted_date  =   Carbon::createFromFormat('Y-m-d', $request->accepted_date);

        $day_delayed            =   $noa_award_date->diffInDaysFiltered(function(Carbon $date)use ($holiday_lists) {
            return $date->isWeekday() && !in_array($date->format('Y-m-d'), $holiday_lists);
        }, $accepted_date);

        $validator = Validator::make($request->all(),[
            'file'          =>   'required',
            'accepted_date' =>   'required',
            'approved_action'=>   'required_with:approved_remarks'
        ]);

        $validator->after(function ($validator)use($day_delayed, $request) {
            if ( $request->get('approved_remarks') == null && $day_delayed > 1) {
                $validator->errors()->add('approved_remarks', 'This field is required when your process is delay');
            }
        });

        if ($validator->fails()){
            return redirect()
                        ->back()
                        ->with(['error' => 'Please Check Your Fields.'])
                        ->withErrors($validator)
                        ->withInput();
        }

        $data       =   [
            'accepted_date'     =>  $request->accepted_date,
            'status'            =>  'approved',
            'file'              =>  '',
            'approved_days'     =>  $day_delayed,
            'approved_remarks'  =>  $request->approved_remarks,
            'approved_action'  =>  $request->approved_action,
        ];

        if($request->file)
        {
            $file       = md5_file($request->file);
            $file       = $file.".".$request->file->getClientOriginalExtension();
            $data['file']   =   $file;
        }


        $result =   $model->update($data, $id);

        if($result && $request->has('file'))
        {
            $path       = $request->file->storeAs('noa-attachments', $file);
        }

         // // update upr
        $upr_result  = $upr->update([
            'status' => "Approved NOA",
            'delay_count'   => ($day_delayed > 1 )? $day_delayed - 1 : 0,
            'calendar_days' => $day_delayed + $result->upr->calendar_days,
            'last_action'   => $request->action,
            'last_remarks'  => $request->remarks
            ],  $result->upr_id);


        event(new Event($upr_result, $upr_result->ref_number." NOA Approved"));


        return redirect()->route($this->baseUrl.'show', $id)->with([
            'success'  => "NOA has been successfully accepted."
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(
        $id,
        SignatoryRepository $signatories,
        NOARepository $model)
    {
        $result             =   $model->findById($id);
        $signatory_list     =   $signatories->lists('id','name');

        return $this->view('modules.procurements.noa.edit',[
            'data'              =>  $result,
            'signatory_lists'   =>  $signatory_list,
            'indexRoute'        =>  $this->baseUrl.'show',
            'modelConfig'       =>  [
                'update' =>  [
                    'route'     =>  [$this->baseUrl.'update-dates', $id],
                    'method'    =>  'PUT',
                    'novalidate'=>  'novalidate'
                ]
            ],
            'breadcrumbs' => [
                new Breadcrumb('Public Bidding'),
                new Breadcrumb('Notice of Award', 'biddings.noa.show', $result->id),
                new Breadcrumb('Update'),
            ]
        ]);
    }

    /**
     * [philgepsPosting description]
     *
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function philgepsPosting(
        Request $request,
        $id,
        NOARepository $model,
        UnitPurchaseRequestRepository $upr,
        HolidayRepository $holidays)
    {
        $noaModel       =   $model->findById($id);
        $holiday_lists  =   $holidays->lists('id','holiday_date');

        $accepted_date =   Carbon::createFromFormat('Y-m-d', $noaModel->award_accepted_date);

        $award_accepted_date  =   Carbon::createFromFormat('Y-m-d', $request->philgeps_posting);

        $cd                   =   $accepted_date->diffInDays($award_accepted_date);

        $day_delayed          =   $accepted_date->diffInDaysFiltered(function(Carbon $date)use ($holiday_lists) {
            return $date->isWeekday() && !in_array($date->format('Y-m-d'), $holiday_lists);
        }, $award_accepted_date);

        $wd                     =   ($day_delayed > 0) ?  $day_delayed - 1 : 0;
        if($day_delayed > 1)
        {
            $day_delayed = $day_delayed - 1;
        }

        $validator = Validator::make($request->all(),[
            'philgeps_posting'   =>  'required|after_or_equal:'. $accepted_date->format('Y-m-d'),
        ]);

        $validator->after(function ($validator)use($day_delayed, $request) {
            if ( $request->get('philgeps_remarks') == null && $day_delayed > 1) {
                $validator->errors()->add('philgeps_remarks', 'This field is required when your process is delay');
            }
        });

        if ($validator->fails()){
            return redirect()
                        ->back()
                        ->with(['error' => 'Please Check Your Fields.'])
                        ->withErrors($validator)
                        ->withInput();
        }

        $input  =   [
            'philgeps_posting'      =>  $request->philgeps_posting,
            'philgeps_days'         =>  $day_delayed,
            'philgeps_remarks'      =>  $request->philgeps_remarks,
            'philgeps_action'       =>  $request->philgeps_action,
        ];

        $result             =   $model->findById($id);

        $model->update($input, $id);
        $upr_result = $upr->update([
            'next_allowable'=> 2,
            'next_step'     => 'Create PO',
            'next_due'      => $award_accepted_date->addDays(2),
            'last_date'     => $award_accepted_date,
            'status'        => 'NOA Philgeps Posting',
            'delay_count'   => $day_delayed,
            'calendar_days' => $cd + $result->upr->calendar_days,
            'last_action'   => $request->action,
            'last_remarks'  => $request->remarks
            ], $result->upr_id);

        event(new Event($upr_result, $upr_result->ref_number." NOA Philgeps Posting"));


        return redirect()->back()->with([
            'success'  => "Record has been successfully updated."
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(
        PostQualificationRepository $model,
        NOARepository $noa,
        $id,
        BidDocsRepository $proponents,
        SignatoryRepository $signatories,
        UnitPurchaseRequestRepository $upr)
    {
        $result             =   $noa->with(['winner', 'upr'])->findById($id);
        $pq_model           =   $model->findById($result->pq_id);

        if($result->upr->mode_of_procurement == 'public_bidding')
        {
            $proponent_awardee  =   $result->biddingWinner->supplier;

            if(!$result->biddingWinner)
            {
                return redirect()->route('procurements.blank-rfq.show', $id)->with([
                    'success'    =>  'Awardee is not yet present. Go to canvassing and select proponent.'
                ]);
            }

        }
        else
        {
            $proponent_awardee  =   $result->winner->supplier;

            if(!$result->winner)
            {
                return redirect()->route('procurements.blank-rfq.show', $id)->with([
                    'success'    =>  'Awardee is not yet present. Go to canvassing and select proponent.'
                ]);
            }

        }

        $upr_model          =   $result->upr;

        $signatory_list     =   $signatories->lists('id','name');

        return $this->view('modules.biddings.noa.show',[
            'data'          =>  $result,
            'upr_model'     =>  $upr_model,
            'pq_model'      =>  $pq_model,
            'supplier'      =>  $proponent_awardee,
            'signatory_list'=>  $signatory_list,
            'printRoute'    =>  'procurements.noa.print',
            'indexRoute'    =>  $this->baseUrl.'index',
            'editRoute'     =>  $this->baseUrl.'edit',
            'modelConfig'   =>  [
                'receive_award' =>  [
                    'route'     =>  [$this->baseUrl.'update', $result->id]
                ],
                'ntp-philgeps' =>  [
                    'route'     =>  [$this->baseUrl.'philgeps', $id],
                    'method'    =>  'PUT'
                ],
                'update' =>  [
                    'route'     =>  [$this->baseUrl.'update-signatory', $id],
                    'method'    =>  'PUT'
                ],
            ],
            'breadcrumbs' => [
                new Breadcrumb('Public Bidding'),
                new Breadcrumb($result->upr_number, 'biddings.unit-purchase-requests.show', $result->upr_id ),
                new Breadcrumb('Notice Of Award', 'biddings.noa.index'),
            ]
        ]);
    }

    /**
     * [updateSignatory description]
     *
     * @param  Request $request [description]
     * @param  [type]  $id      [description]
     * @return [type]           [description]
     */
    public function updateSignatory(Request $request, $id, NOARepository $model)
    {
        $this->validate($request, [
            'signatory_id'   =>  'required',
        ]);

        $model->update(['signatory_id' =>$request->signatory_id], $id);

        return redirect()->route($this->baseUrl.'show', $id)->with([
            'success'  => "Record has been successfully updated."
        ]);
    }

}
