<?php

namespace Revlv\Controllers\Biddings;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use PDF;
use Carbon\Carbon;
use Validator;

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
        $pqDate                 =   Carbon::createFromFormat('Y-m-d', $pq_model->transaction_date);
        $transaction_date       =   Carbon::createFromFormat('Y-m-d', $request->get('awarded_date') );

        $proponent_model        =   $bid_docs->findById($proponentId);

        $holiday_lists          =   $holidays->lists('id','holiday_date');
        $supplier_name          =   $proponent_model->proponent_name;

        $day_delayed            =   $pqDate->diffInDaysFiltered(function(Carbon $date)use ($holiday_lists) {
            return $date->isWeekday() && !in_array($date->format('Y-m-d'), $holiday_lists);
        }, $transaction_date);

        $validator = Validator::make($request->all(),[
            'awarded_date'  =>   'required',
            'awarded_by'    =>   'required',
            'seconded_by'   =>   'required',
            'action'        =>   'required_with:remarks'
        ]);

        $validator->after(function ($validator)use($day_delayed, $request) {
            if ( $request->get('remarks') == null && $day_delayed > 1) {
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
            'days'          =>  $day_delayed,
        ];

        $noaModal   =   $noa->save($data);

        // // update upr
        $upr->update([
            'status' => "Awarded To $supplier_name",
            'delay_count'   => ($day_delayed > 2 )? $day_delayed - 2 : 0,
            'calendar_days' => $day_delayed + $pq_model->upr->calendar_days,
            'action'        => $request->action,
            'remarks'       => $request->remarks
            ],  $pq_model->upr_id);

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
        return $this->view('modules.biddings.noa.index');
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

        $accepted_date =   Carbon::createFromFormat('Y-m-d', $noaModel->accepted_date);
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
                        ->with(['error' => 'Your process is delay. Please add remarks to continue.'])
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
        $upr->update([
            'status' => 'NOA Received',
            'delay_count'   => ($day_delayed > 1 )? $day_delayed - 1 : 0,
            'calendar_days' => $day_delayed + $result->upr->calendar_days,
            'action'        => $request->action,
            'remarks'       => $request->remarks
            ], $result->upr_id);

        return redirect()->route($this->baseUrl.'show', $id)->with([
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
        $noa_award_date =   Carbon::createFromFormat('Y-m-d', $noa_award_date);
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
                        ->with(['error' => 'Your process is delay. Please add remarks to continue.'])
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
        $upr->update([
            'status' => "Approved NOA",
            'delay_count'   => ($day_delayed > 1 )? $day_delayed - 1 : 0,
            'calendar_days' => $day_delayed + $result->upr->calendar_days,
            'action'        => $request->action,
            'remarks'       => $request->remarks
            ],  $result->upr_id);


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
        NOARepository $model)
    {
        $result             =   $model->findById($id);

        return $this->view('modules.procurements.noa.edit',[
            'data'              =>  $result,
            'indexRoute'        =>  $this->baseUrl.'show',
            'modelConfig'       =>  [
                'update' =>  [
                    'route'     =>  [$this->baseUrl.'update-dates', $id],
                    'method'    =>  'PUT'
                ]
            ]
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
        $proponent_awardee  =   $result->winner->supplier;

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
                'update' =>  [
                    'route'     =>  [$this->baseUrl.'update-signatory', $id],
                    'method'    =>  'PUT'
                ],
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
