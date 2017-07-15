<?php

namespace Revlv\Controllers\Procurements;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use DB;
use Excel;
use PDF;

use \Revlv\Settings\Holidays\HolidayRepository;
use \Revlv\Procurements\UnitPurchaseRequests\UnitPurchaseRequestRepository;
use \Revlv\Procurements\UnitPurchaseRequests\Attachments\AttachmentRepository;
use \Revlv\Procurements\UnitPurchaseRequests\UnitPurchaseRequestRequest;
use \Revlv\Procurements\UnitPurchaseRequests\UnitPurchaseRequestUpdateRequest;
use \Revlv\Procurements\Items\ItemRepository;
use \Revlv\Settings\Signatories\SignatoryRepository;
use \Revlv\Settings\AccountCodes\AccountCodeRepository;
use \Revlv\Settings\Chargeability\ChargeabilityRepository;
use \Revlv\Settings\ModeOfProcurements\ModeOfProcurementRepository;
use \Revlv\Settings\ProcurementCenters\ProcurementCenterRepository;
use \Revlv\Settings\PaymentTerms\PaymentTermRepository;
use \Revlv\Settings\Units\UnitRepository;
use \Revlv\Settings\AuditLogs\AuditLogRepository;
use \Revlv\Settings\ProcurementTypes\ProcurementTypeRepository;
use \Revlv\Settings\CateredUnits\CateredUnitRepository;
use \Revlv\Users\Logs\UserLogRepository;
use \Revlv\Settings\BacSec\BacSecRepository;

use Revlv\Procurements\UnitPurchaseRequests\Traits\FileTrait;
use Revlv\Procurements\UnitPurchaseRequests\Traits\ImportTrait;

class UPRController extends Controller
{
    use FileTrait, ImportTrait;

    /**
     * [Base Route of Controller]
     *
     * @var string
     */
    protected $baseUrl  =   "procurements.unit-purchase-requests.";

    /**
     * [$accounts description]
     *
     * @var [type]
     */
    protected $accounts;
    protected $chargeability;
    protected $modes;
    protected $centers;
    protected $bacsec;
    protected $terms;
    protected $items;
    protected $units;
    protected $holidays;
    protected $logs;
    protected $signatories;
    protected $types;
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
    public function getDatatable(UnitPurchaseRequestRepository $model)
    {
        $user   =   \Sentinel::getUser();
        if(array_key_exists("superuser", $user->permissions) || array_key_exists("admin", $user->permissions))
        {
            return $model->getDatatable();
        }
        return $model->getDatatable($user->id);
    }



    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->view('modules.procurements.upr.index',[
            'createRoute'   =>  $this->baseUrl."create",
            'importRoute'   =>  $this->baseUrl."imports"
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(
        AccountCodeRepository $accounts,
        ChargeabilityRepository $chargeability,
        ModeOfProcurementRepository $modes,
        ProcurementCenterRepository $centers,
        CateredUnitRepository $units,
        ProcurementTypeRepository $types,
        PaymentTermRepository $terms)
    {

        $account_codes      =    $accounts->lists('id', 'new_account_code');
        $charges            =    $chargeability->lists('id', 'name');
        $procurement_modes  =    $modes->lists('id', 'name');
        $procurement_center =    $centers->lists('id', 'name');
        $payment_terms      =    $terms->lists('id', 'name');
        $procurement_types  =    $types->lists('id', 'code');
        $unit               =    $units->lists('id', 'short_code');
        // $this->permissions->lists('permission','description')
        $this->view('modules.procurements.upr.create',[
            'indexRoute'        =>  $this->baseUrl.'index',
            'account_codes'     =>  $account_codes,
            'procurement_types' =>  $procurement_types,
            'payment_terms'     =>  $payment_terms,
            'unit'              =>  $unit,
            'charges'           =>  $charges,
            'procurement_modes' =>  $procurement_modes,
            'procurement_center'=>  $procurement_center,
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
        UnitPurchaseRequestRequest $request,
        UnitPurchaseRequestRepository $model)
    {
        $items                  =   $request->only([
            'item_description',
            'quantity',
            'unit_measurement',
            'unit_price',
            'total_amount'
        ]);

        if($items['item_description'] == null)
        {
            return redirect()->back()->with([
                'error' =>  'Pleased add item to continue.'
            ]);
        }

        $procs                  =   $request->getData();
        $date                   =   \Carbon\Carbon::now();

        $total_amount           =   array_sum($items['total_amount']);
        $prepared_by            =   \Sentinel::getUser()->id;
        $item_datas             =   [];

        $procs['total_amount']  =   $total_amount;
        $procs['prepared_by']   =   $prepared_by;

        $result = $model->save($procs);

        $counts                 =   $model->getCountByYear($date->format('Y'))->total;

        if($result->mode_of_procurement != 'public_bidding')
        {
            $ref_name   =   "AMP-". $result->centers->name ."-". $counts ."-". $result->unit->short_code ."-". $date->format('Y');
        }
        else
        {
            $ref_name   =   "PB-". $result->centers->name ."-". $counts ."-". $result->unit->short_code ."-". $date->format('Y');
        }

        $ref_name   =   str_replace(" ", "", $ref_name);

        $model->update(['ref_number' => $ref_name], $result->id);

        if($result)
        {
            for ($i=0; $i < count($items['item_description']); $i++) {
                $item_datas[]  =   [
                    'item_description'      =>  $items['item_description'][$i],
                    'quantity'              =>  $items['quantity'][$i],
                    'unit_measurement'      =>  $items['unit_measurement'][$i],
                    'unit_price'            =>  $items['unit_price'][$i],
                    'total_amount'          =>  $items['total_amount'][$i],
                    'upr_number'            =>  $request->get('upr_number'),
                    'ref_number'            =>  $request->get('ref_number'),
                    'prepared_by'           =>  $prepared_by,
                    'date_prepared'         =>  $request->get('date_prepared'),
                    'upr_id'                =>  $result->id
                ];
            }

            DB::table('unit_purchase_request_items')->insert($item_datas);
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
        BacSecRepository $bacsec,
        UnitPurchaseRequestRepository $model,
        SignatoryRepository $signatories)
    {
        $result         =   $model->with(['attachments'])->findById($id);
        $signatory_lists=   $signatories->lists('id', 'name');

        return $this->view('modules.procurements.upr.show',[
            'data'              =>  $result,
            'bacsec_list'       =>  $bacsec->lists('id', 'name'),
            'indexRoute'        =>  $this->baseUrl.'index',
            'signatory_list'    =>  $signatory_lists,
            'editRoute'         =>  $this->baseUrl.'edit',
            'modelConfig'       =>  [
                'request_quotation' =>  [
                    'route'     =>  'procurements.blank-rfq.store',
                ],
                'add_attachment' =>  [
                    'route'     =>  [$this->baseUrl.'attachments.store', $id],
                ],
                'cancelled'   => [
                    'route' => $this->baseUrl.'cancelled'
                ],
                'update'   => [
                    'route' => [$this->baseUrl.'update-signatories', $id],
                    'method'=> 'PUT'
                ]
            ]
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id,
        AccountCodeRepository $accounts,
        ChargeabilityRepository $chargeability,
        ModeOfProcurementRepository $modes,
        ProcurementCenterRepository $centers,
        UnitPurchaseRequestRepository $model,
        CateredUnitRepository $units,
        ProcurementTypeRepository $types,
        PaymentTermRepository $terms)
    {
        $result =   $model->findById($id);

        $account_codes      =    $accounts->lists('id', 'new_account_code');
        $charges            =    $chargeability->lists('id', 'name');
        $procurement_modes  =    $modes->lists('id', 'name');
        $procurement_center =    $centers->lists('id', 'name');
        $procurement_types  =    $types->lists('id', 'code');
        $payment_terms      =    $terms->lists('id', 'name');
        $unit               =    $units->lists('id', 'short_code');

        return $this->view('modules.procurements.upr.edit',[
            'data'              =>  $result,
            'indexRoute'        =>  $this->baseUrl.'show',
            'account_codes'     =>  $account_codes,
            'payment_terms'     =>  $payment_terms,
            'procurement_types' =>  $procurement_types,
            'charges'           =>  $charges,
            'unit'              =>  $unit,
            'procurement_modes' =>  $procurement_modes,
            'procurement_center'=>  $procurement_center,
            'modelConfig'   =>  [
                'update' =>  [
                    'route'     =>  [$this->baseUrl.'update', $id],
                    'method'    =>  'PUT'
                ],
                'destroy'   => [
                    'route' => [$this->baseUrl.'destroy',$id],
                    'method'=> 'DELETE'
                ]
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
        $id,
        UnitPurchaseRequestUpdateRequest $request,
        UserLogRepository $userLogs,
        AuditLogRepository $logs,
        \Revlv\Users\UserRepository $users,
        UnitPurchaseRequestRepository $model)
    {
        $result     =   $model->update($request->getData(), $id);

        $ref        =   explode('-', $result->ref_number);
        if($result->mode_of_procurement != 'public_bidding')
        {
            $ref_name   =   "AMP-". $result->centers->name ."-". $ref[2] ."-". $result->unit->short_code ."-". $ref[4];
        }
        else
        {
            $ref_name   =   "PB-". $result->centers->name ."-". $ref[2] ."-". $result->unit->short_code ."-". $ref[4];
        }

        $ref_name   =   str_replace(" ", "", $ref_name);

        $model->update(['ref_number' => $ref_name], $id);

        $modelType  =   'Revlv\Procurements\UnitPurchaseRequests\UnitPurchaseRequestEloquent';
        $resultLog  =   $logs->findLastByModelAndId($modelType, $id);

        $userAdmins =   $users->getAllAdmins();

        foreach($userAdmins as $admin)
        {
            if($admin->hasRole('Admin'))
            {
                $data   =   ['audit_id' => $resultLog->id, 'admin_id' => $admin->id];
                $x = $userLogs->save($data);
            }
        }

        return redirect()->route($this->baseUrl.'edit', $id)->with([
            'success'  => "Record has been successfully updated."
        ]);
    }

    /**
     * [updateSignatory description]
     *
     * @param  Request $request [description]
     * @param  [type]  $id      [description]
     * @return [type]           [description]
     */
    public function updateSignatory(Request $request, $id, UnitPurchaseRequestRepository $model)
    {
        $this->validate($request, [
            'requestor_id'      =>  'required',
            'fund_signatory_id' =>  'required',
            'approver_id'       =>  'required',
        ]);

        $data   =   [
            'requestor_id'      =>  $request->requestor_id,
            'fund_signatory_id' =>  $request->fund_signatory_id,
            'approver_id'       =>  $request->approver_id,
        ];

        $model->update($data, $id);

        return redirect()->route($this->baseUrl.'show', $id)->with([
            'success'  => "Record has been successfully updated."
        ]);
    }

    /**
     * [terminateUPR description]
     *
     * @param  [type]  $id      [description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function terminateUPR($id,
        Request $request,
        UnitPurchaseRequestRepository $model)
    {
        $this->validate($request, [
            'terminate_status'  => 'required',
            'remarks'           => 'required',
        ]);

        $model->update([
            'terminate_status'  =>  $request->terminate_status,
            'remarks'           =>  $request->remarks,
            'state'             =>  "Terminated (".$request->terminate_status.")",
            'terminated_date'   =>  \Carbon\Carbon::now(),
            'terminated_by'     =>  \Sentinel::getUser()->id,
        ], $id);

        return redirect()->route($this->baseUrl.'show', $id)->with([
            'success'  => "Record has been successfully updated."
        ]);
    }

    /**
     * [cancelled description]
     *
     * @param  [type]                        $id      [description]
     * @param  Request                       $request [description]
     * @param  UnitPurchaseRequestRepository $model   [description]
     * @return [type]                                 [description]
     */
    public function cancelled($id,
        Request $request,
        UnitPurchaseRequestRepository $model)
    {
        $this->validate($request, [
            'cancelled_at'      => 'required',
            'cancel_reason'     => 'required',
        ]);

        $model->update([
            'cancelled_at'  =>  $request->cancelled_at,
            'cancel_reason' =>  $request->cancel_reason,
            'state'         =>  "Cancelled",
            'status'        =>  "Cancelled",
        ], $id);

        return redirect()->route($this->baseUrl.'show', $id)->with([
            'success'  => "Record has been successfully updated."
        ]);
    }

    /**
     * [timelines description]
     *
     * @param  [type]                        $id    [description]
     * @param  UnitPurchaseRequestRepository $model [description]
     * @return [type]                               [description]
     */
    public function timelines($id,
        UnitPurchaseRequestRepository $model,
        HolidayRepository $holidays)
    {
        $upr_list   = $model->lists('id', 'upr_number');
        $upr_model  =   $model->findTimelineById($id);
        $holiday_lists  =   $holidays->lists('id','holiday_date');
        $h_lists        =   [];
        foreach($holiday_lists as $hols)
        {
            $h_lists[]  =   \Carbon\Carbon::createFromFormat('Y-m-d', $hols)->format('Y-m-d');
        }

        return $this->view('modules.procurements.upr.timelines',[
            'data'              =>  $upr_model,
            'h_lists'           =>  $h_lists,
            'upr_list'           =>  $upr_list,
            'indexRoute'        =>  $this->baseUrl."show"
        ]);
    }
}
