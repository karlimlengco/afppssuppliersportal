<?php

namespace Revlv\Controllers\Procurements;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Validator;
use \Carbon\Carbon;
use \App\Support\Breadcrumb;
use App\Events\Event;

use \Revlv\Procurements\PhilGepsPosting\Attachments\AttachmentRepository;
use \Revlv\Procurements\PhilGepsPosting\PhilGepsPostingRepository;
use \Revlv\Procurements\PhilGepsPosting\PhilGepsPostingRequest;
use \Revlv\Procurements\PhilGepsPosting\UpdateRequest;
use \Revlv\Procurements\UnitPurchaseRequests\UnitPurchaseRequestRepository;
use \Revlv\Procurements\BlankRequestForQuotation\BlankRFQRepository;
use \Revlv\Settings\AuditLogs\AuditLogRepository;
use \Revlv\Users\Logs\UserLogRepository;
use \Revlv\Users\UserRepository;
use \Revlv\Settings\Holidays\HolidayRepository;

class PhilGepsPostingController extends Controller
{


    /**
     * [Base Route of Controller]
     *
     * @var string
     */
    protected $baseUrl  =   "procurements.philgeps-posting.";

    /**
     * [$upr description]
     *
     * @var [type]
     */
    protected $upr;
    protected $rfq;
    protected $attachments;
    protected $audits;
    protected $holidays;
    protected $users;
    protected $userLogs;

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
        return $model->getDatatable();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->view('modules.procurements.philgeps.index',[
            'createRoute'   =>  $this->baseUrl."create",
            'breadcrumbs' => [
                new Breadcrumb('Alternative'),
                new Breadcrumb('PhilGeps Posting', 'procurements.philgeps-posting.index'),
            ]
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(BlankRFQRepository $rfq)
    {
        $rfq_list   =   $rfq->listPending('id', 'rfq_number');
        $this->view('modules.procurements.philgeps.create',[
            'indexRoute'    =>  $this->baseUrl.'index',
            'rfq_list'      =>  $rfq_list,
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
        PhilGepsPostingRequest $request,
        PhilGepsPostingRepository $model,
        UnitPurchaseRequestRepository $upr,
        BlankRFQRepository $rfq,
        HolidayRepository $holidays)
    {
        $upr_model              =   $upr->findById($request->upr_id);
        $transaction_date       =   Carbon::createFromFormat('Y-m-d', $request->transaction_date);

        $holiday_lists          =   $holidays->lists('id','holiday_date');
        $cd                     =   $upr_model->date_processed->diffInDays($transaction_date);

        $day_delayed            =   $upr_model->date_processed->diffInDaysFiltered(function(Carbon $date)use ($holiday_lists) {
            return $date->isWeekday() && !in_array($date->format('Y-m-d'), $holiday_lists);
        }, $transaction_date);

        $wd                     =   ($day_delayed > 0) ?  $day_delayed - 1 : 0;

        if($day_delayed >= 3)
        {
            $day_delayed            =   $day_delayed - 3;
        }

        // Validate Remarks when  delay
        $validator = Validator::make($request->all(),[
            'transaction_date'  =>  'required|after_or_equal:'.$upr_model->date_processed,
        ]);

        $validator->after(function ($validator)use($day_delayed, $request) {
            if ( $request->get('remarks') == null && $day_delayed > 3) {
                $validator->errors()->add('remarks', 'This field is required when your process is delay');
            }
            if ( $request->get('action') == null && $day_delayed > 3) {
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
        // Validate Remarks when  delay

        $inputs                 =   $request->getData();
        $inputs['opening_time'] =   $request->pp_opening_time;
        $inputs['rfq_number']   =   $upr_model->ref_number;
        $inputs['upr_number']   =   $upr_model->ref_number;
        $inputs['remarks']      =   $request->remarks;
        $inputs['upr_id']       =   $upr_model->id;
        $inputs['days']         =   $wd;
        $status  = 'Philgeps Approved';

        if($request->status == 0)
        {
            $status  = 'Philgeps Need Repost';
        }

        $upr_result = $upr->update([
            'next_allowable'=> 3,
            'next_step'     => 'Prepare RFQ',
            'next_due'      => $upr_model->date_processed->addDays(3),
            'last_date'     => $transaction_date,
            'status'        => $status,
            'delay_count'   => $wd,
            'calendar_days' => $cd + $upr_model->calendar_days,
            'last_action'   => $request->action,
            'last_remarks'  => $request->remarks
            ], $upr_model->id);

        event(new Event($upr_result, $upr_result->upr_number." ". $status));

        $result = $model->save($inputs);

        return redirect()->back()->with([
            'success'  => "New record has been successfully added."
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, PhilGepsPostingRepository $model)
    {
        $result     =   $model->findById($id);

        return $this->view('modules.procurements.philgeps.show',[
            'data'          =>  $result,
            'indexRoute'    =>  $this->baseUrl.'index',
            'editRoute'     =>  $this->baseUrl.'edit',
            'modelConfig'   =>  [
                'add_attachment' =>  [
                    'route'     =>  [$this->baseUrl.'attachments.store', $id]
                ]
            ],
            'breadcrumbs' => [
                new Breadcrumb('Alternative'),
                new Breadcrumb($result->upr_number, 'procurements.unit-purchase-requests.show', $result->upr_id),
                new Breadcrumb($result->philgeps_number)
            ]
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, PhilGepsPostingRepository $model, BlankRFQRepository $rfq)
    {
        $result     =   $model->findById($id);
        $rfq_list   =   $rfq->lists('id', 'rfq_number');

        return $this->view('modules.procurements.philgeps.edit',[
            'data'          =>  $result,
            'rfq_list'      =>  $rfq_list,
            'indexRoute'    =>  $this->baseUrl.'show',
            'modelConfig'   =>  [
                'update' =>  [
                    'route'     =>  [$this->baseUrl.'update', $id],
                    'method'    =>  'PUT',
                    'novalidate'=>  'novalidate'
                ],
                'destroy'   => [
                    'route' => [$this->baseUrl.'destroy',$id],
                    'method'=> 'DELETE'
                ]
            ],
            'breadcrumbs' => [
                new Breadcrumb('Alternative'),
                new Breadcrumb($result->philgeps_number, 'procurements.philgeps-posting.show', $result->id),
                new Breadcrumb('Update')
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
        UpdateRequest $request,
        $id,
        \Revlv\Users\UserRepository $users,
        UnitPurchaseRequestRepository $upr,
        BlankRFQRepository $rfq,
        AuditLogRepository $audits,
        HolidayRepository $holidays,
        UserLogRepository $userLogs,
        PhilGepsPostingRepository $model)
    {
        $old                    =   $model->findById($id);
        $result                 =   $model->update($request->getData(), $id);
        if($result->upr->mode_of_procurement != 'public_bidding')
        {

            $transaction_date       =   Carbon::createFromFormat('Y-m-d', $request->transaction_date);

            $holiday_lists          =   $holidays->lists('id','holiday_date');
            $cd                     =   $result->upr->date_processed->diffInDays($transaction_date);

            $day_delayed            =   $result->upr->date_processed->diffInDaysFiltered(function(Carbon $date)use ($holiday_lists) {
                return $date->isWeekday() && !in_array($date->format('Y-m-d'), $holiday_lists);
            }, $transaction_date);

            $wd                     =   ($day_delayed > 0) ?  $day_delayed - 1 : 0;

            if($day_delayed >= 3)
            {
                $day_delayed            =   $day_delayed - 3;
            }

            if($wd != $result->days)
            {
                $model->update(['days' => $wd], $id);
            }
        }
        else
        {
            $upr_model              =   $upr->findById($result->upr_id);
            $invitation             =   Carbon::createFromFormat('!Y-m-d',$upr_model->itb->approved_date);
            $transaction_date       =   Carbon::createFromFormat('Y-m-d', $request->transaction_date);
            $holiday_lists          =   $holidays->lists('id','holiday_date');

            $day_delayed            =   $invitation->diffInDaysFiltered(function(Carbon $date)use ($holiday_lists) {
                return $date->isWeekday() && !in_array($date->format('Y-m-d'), $holiday_lists);
            }, $transaction_date);

            if($day_delayed >= 3)
            {
                $day_delayed            =   $day_delayed - 3;
            }

            if($day_delayed != $result->days)
            {
                $model->update(['days' => $day_delayed], $id);
            }

        }
// For logs
        $modelType  =   'Revlv\Procurements\PhilGepsPosting\PhilGepsPostingEloquent';
        $resultLog  =   $audits->findLastByModelAndId($modelType, $id);

        $userAdmins =   $users->getAllAdmins();

        foreach($userAdmins as $admin)
        {
            if($admin->hasRole('Admin'))
            {
                $data   =   ['audit_id' => $resultLog->id, 'admin_id' => $admin->id];
                $x = $userLogs->save($data);
            }
        }

// For logs
        if($old->status == 1 && $result->status == 0)
        {
            $upr->update([
            'status'        =>  'Philgeps Need Repost',
            'last_action'   => $request->action,
            'last_remarks'  => $request->remarks
            ], $result->upr->id);
        }

        return redirect()->route($this->baseUrl.'edit', $id)->with([
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

    /**
     * [uploadAttachment description]
     *
     * @param  Request $request [description]
     * @param  [type]  $id      [description]
     * @return [type]           [description]
     */
    public function uploadAttachment(Request $request, $id, AttachmentRepository $attachments)
    {

        $file       = md5_file($request->file);
        $file       = $file.".".$request->file->getClientOriginalExtension();

        $validator = \Validator::make($request->all(), [
            'file' => 'required',
        ]);

        $result     = $attachments->save([
            'philgeps_id'   =>  $id,
            'name'          =>  $request->file->getClientOriginalName(),
            'file_name'     =>  $file,
            'user_id'       =>  \Sentinel::getUser()->id,
            'upload_date'   =>  \Carbon\Carbon::now()
        ]);

        if($result)
        {
            $path       = $request->file->storeAs('philgeps-attachments', $file);
        }

        return redirect()->route($this->baseUrl.'show', $id)->with([
            'success'  => "Attachment has been successfully added."
        ]);
    }



    /**
     * [downloadAttachment description]
     *
     * @param  Request $request [description]
     * @param  [type]  $id      [description]
     * @return [type]           [description]
     */
    public function downloadAttachment(Request $request, $id, AttachmentRepository $attachments)
    {
        $result     = $attachments->findById($id);

        $directory      =   storage_path("app/philgeps-attachments/".$result->file_name);

        if(!file_exists($directory))
        {
            return 'Sorry. File does not exists.';
        }

        return response()->download($directory);
    }

    /**
     * [viewLogs description]
     *
     * @param  [type]             $id    [description]
     * @param  BlankRFQRepository $model [description
]     * @return [type]                    [description]
     */
    public function viewLogs($id, PhilGepsPostingRepository $model, AuditLogRepository $logs)
    {

        $modelType  =   'Revlv\Procurements\PhilGepsPosting\PhilGepsPostingEloquent';
        $result     =   $logs->findByModelAndId($modelType, $id);
        $data_model =   $model->findById($id);

        return $this->view('modules.procurements.philgeps.logs',[
            'indexRoute'    =>  $this->baseUrl."show",
            'data'          =>  $result,
            'model'         =>  $data_model,
            'breadcrumbs' => [
                new Breadcrumb('Alternative'),
                new Breadcrumb($data_model->philgeps_number, 'procurements.philgeps-posting.show', $data_model->id),
                new Breadcrumb('Logs')
            ]
        ]);
    }
}
