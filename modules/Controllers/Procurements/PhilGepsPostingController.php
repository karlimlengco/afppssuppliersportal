<?php

namespace Revlv\Controllers\Procurements;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Validator;
use \Carbon\Carbon;
use \App\Support\Breadcrumb;

use \Revlv\Procurements\PhilGepsPosting\Attachments\AttachmentRepository;
use \Revlv\Procurements\PhilGepsPosting\PhilGepsPostingRepository;
use \Revlv\Procurements\PhilGepsPosting\PhilGepsPostingRequest;
use \Revlv\Procurements\UnitPurchaseRequests\UnitPurchaseRequestRepository;
use \Revlv\Procurements\BlankRequestForQuotation\BlankRFQRepository;
use \Revlv\Settings\AuditLogs\AuditLogRepository;
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
        $rfq_model              =   $rfq->findById($request->rfq_id);
        $transaction_date       =   Carbon::createFromFormat('Y-m-d', $request->transaction_date);

        if($invitation = $rfq_model->invitations)
        {
            $ispq_transaction_date   = Carbon::createFromFormat('Y-m-d', $invitation->ispq->transaction_date);
        }
        else
        {
            $ispq_transaction_date   = $rfq_model->completed_at;
        }

        $holiday_lists          =   $holidays->lists('id','holiday_date');

        $day_delayed            =   $ispq_transaction_date->diffInDaysFiltered(function(Carbon $date)use ($holiday_lists) {
            return $date->isWeekday() && !in_array($date->format('Y-m-d'), $holiday_lists);
        }, $transaction_date);

        if($day_delayed != 0)
        {
            $day_delayed            =   $day_delayed - 1;
        }

        // Validate Remarks when  delay
        $validator = Validator::make($request->all(),[
            'transaction_date'  =>  'required',
            'action'            =>  'required_with:remarks'
        ]);

        $validator->after(function ($validator)use($day_delayed, $request) {
            if ( $request->get('remarks') == null && $day_delayed > 3) {
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
        // Validate Remarks when  delay

        $inputs                 =   $request->getData();
        $inputs['opening_time'] =   $request->pp_opening_time;
        $inputs['rfq_number']   =   $rfq_model->rfq_number;
        $inputs['upr_number']   =   $rfq_model->upr_number;
        $inputs['remarks']      =   $request->remarks;
        $inputs['upr_id']       =   $rfq_model->upr_id;
        $inputs['days']         =   $day_delayed;
        $status  = 'Philgeps Approved';
        if($request->status == 0)
        {
            $status  = 'Philgeps Need Repost';
        }
        $upr->update([
            'status'        =>  $status,
            'delay_count'   => ($day_delayed > 1 )? $day_delayed - 1 : 0,
            'calendar_days' => $day_delayed + $rfq_model->upr->calendar_days,
            'last_action'   => $request->action,
            'last_remarks'  => $request->remarks
            ], $rfq_model->upr->id);



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
                    'method'    =>  'PUT'
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
    public function update(PhilGepsPostingRequest $request, $id, PhilGepsPostingRepository $model)
    {
        $model->update($request->getData(), $id);

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
