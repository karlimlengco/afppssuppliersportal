<?php

namespace Revlv\Controllers\Procurements;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use DB;
use Excel;

use \Revlv\Procurements\UnitPurchaseRequests\UnitPurchaseRequestRepository;
use \Revlv\Procurements\UnitPurchaseRequests\Attachments\AttachmentRepository;
use \Revlv\Procurements\UnitPurchaseRequests\UnitPurchaseRequestRequest;
use \Revlv\Procurements\Items\ItemRepository;
use \Revlv\Settings\AccountCodes\AccountCodeRepository;
use \Revlv\Settings\Chargeability\ChargeabilityRepository;
use \Revlv\Settings\ModeOfProcurements\ModeOfProcurementRepository;
use \Revlv\Settings\ProcurementCenters\ProcurementCenterRepository;
use \Revlv\Settings\PaymentTerms\PaymentTermRepository;
use \Revlv\Settings\Units\UnitRepository;
use \Revlv\Settings\AuditLogs\AuditLogRepository;
use \Revlv\Settings\CateredUnits\CateredUnitRepository;

class UPRController extends Controller
{

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
    protected $terms;
    protected $items;
    protected $units;
    protected $logs;

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
        return $model->getDatatable();
    }

    /**
     * [logs description]
     *
     * @param  [type]                        $id    [description]
     * @param  UnitPurchaseRequestRepository $model [description]
     * @param  AuditLogRepository            $logs  [description]
     * @return [type]                               [description]
     */
    public function viewLogs($id, UnitPurchaseRequestRepository $model, AuditLogRepository $logs)
    {
        $modelType  =   'Revlv\Procurements\UnitPurchaseRequests\UnitPurchaseRequestEloquent';
        $result     =   $logs->findByModelAndId($modelType, $id);
        $upr_model  =   $model->findById($id);

        return $this->view('modules.procurements.upr.logs',[
            'indexRoute'    =>  $this->baseUrl."show",
            'data'          =>  $result,
            'upr'           =>  $upr_model,
        ]);
    }

    /**
     * [uploadView description]
     *
     * @return [type] [description]
     */
    public function uploadView()
    {
        return $this->view('modules.procurements.upr.import',[
            'indexRoute'    =>  $this->baseUrl."index",
            'modelConfig'   =>  [
                'importFile'    =>  [
                    'route' =>  $this->baseUrl.'import-file',
                    'method'=>  'POST',
                    'files' =>  true
                ]
            ]
        ]);
    }

    /**
     * [uploadFile description]
     *
     * @param  Request                       $request [description]
     * @param  UnitPurchaseRequestRepository $model   [description]
     * @return [type]                                 [description]
     */
    public function uploadFile(
        Request $request,
        UnitPurchaseRequestRepository $model,
        AccountCodeRepository $accounts,
        ChargeabilityRepository $chargeability,
        ModeOfProcurementRepository $modes,
        ProcurementCenterRepository $centers,
        CateredUnitRepository $units,
        PaymentTermRepository $terms)
    {
        $path           =   $request->file('file')->getRealPath();

        $data           =   [];
        $reader         =   Excel::load($path, function($reader) {});
        // $reader->formatDates(true, 'd F Y');
        $fields         =   $reader->limitColumns(5)->limitRows(10)->get();
        $items          =   $reader->skipRows(12)->limitColumns(5)->get();

        $array          =   [];
        $itemArray      =   [];

        $array['units'] =   \Sentinel::getUser()->unit_id;

        foreach($fields->toArray() as $row)
        {
            switch ($row[0]) {
                case 'UPR NO':
                    $array['upr_number'] = $row[2];
                    break;
                case 'DATE PREPARED':
                    $date   =   \Carbon\Carbon::createFromFormat('d F Y', ($row[2]));
                    $array['date_prepared'] = $date;
                    break;
                case 'PROJECT NAME':
                    $array['project_name'] = $row[2];
                    break;
                case 'PLACE OF DELIVERY':
                    $centerModel    =   $centers->findByName($row[2]);
                    if($centerModel != null)
                    {
                        $array['place_of_delivery'] = $centerModel->id;
                    }
                    break;
                case 'MODE OF PROCUREMENT':
                    $modesModel    =   $modes->findByName($row[2]);
                    if($modesModel != null)
                    {
                        $array['mode_of_procurement'] = $modesModel->id;
                    }
                    break;
                case 'CHARGEABILITY':
                    $chargeabilityModel    =   $chargeability->findByName($row[2]);
                    if($chargeabilityModel != null)
                    {
                        $array['chargeability'] = $chargeabilityModel->id;
                    }
                    break;
                case 'ACCOUNT CODE':
                    $accountsModel    =   $accounts->findByName($row[2]);
                    if($accountsModel != null)
                    {
                        $array['account_code'] = $accountsModel->id;
                    }
                    break;
                case 'FUND VALIDITY':
                    $array['fund_validity'] = $row[2];
                    break;
                case 'TERMS OF PAYMENTS':
                    $termsModel    =   $terms->findByName($row[2]);
                    if($termsModel != null)
                    {
                        $array['terms_of_payment'] = $termsModel->id;
                    }
                        break;
                case 'OTHER ESSENTIAL INFO':
                    $array['other_infos'] = $row[2];
                    break;
                case 'PURPOSE':
                    $array['purpose'] = $row[2];
                        break;

                default:

                    break;
            }

        }

        foreach($items->toArray() as $itemRow)
        {
            if($itemRow[0] != "ITEM DESCRIPTION")
            {
                $itemArray[]    =   [
                    'item_description'      =>  $itemRow[0],
                    'quantity'              =>  $itemRow[1],
                    'unit'                  =>  $itemRow[2],
                    'unit_price'            =>  $itemRow[3],
                    'total_amount'          =>  $itemRow[4],
                ];
            }
        }

        $account_codes      =    $accounts->lists('id', 'new_account_code');
        $charges            =    $chargeability->lists('id', 'name');
        $procurement_modes  =    $modes->lists('id', 'name');
        $procurement_center =    $centers->lists('id', 'name');
        $payment_terms      =    $terms->lists('id', 'name');
        $unit               =    $units->lists('id', 'short_code');


        return $this->view('modules.procurements.upr.import-validate',[
            'indexRoute'        =>  $this->baseUrl."index",
            'data'              =>  $array,
            'items'             =>  $itemArray,
            'account_codes'     =>  $account_codes,
            'payment_terms'     =>  $payment_terms,
            'unit'              =>  $unit,
            'charges'           =>  $charges,
            'procurement_modes' =>  $procurement_modes,
            'procurement_center'=>  $procurement_center,
        ]);
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
        PaymentTermRepository $terms)
    {

        $account_codes      =    $accounts->lists('id', 'new_account_code');
        $charges            =    $chargeability->lists('id', 'name');
        $procurement_modes  =    $modes->lists('id', 'name');
        $procurement_center =    $centers->lists('id', 'name');
        $payment_terms      =    $terms->lists('id', 'name');
        $unit               =    $units->lists('id', 'short_code');
        // $this->permissions->lists('permission','description')
        $this->view('modules.procurements.upr.create',[
            'indexRoute'        =>  $this->baseUrl.'index',
            'account_codes'     =>  $account_codes,
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
     * [saveFile description]
     *
     * @param  UnitPurchaseRequestRequest    $request [description]
     * @param  UnitPurchaseRequestRepository $model   [description]
     * @return [type]                                 [description]
     */
    public function saveFile(UnitPurchaseRequestRequest $request, UnitPurchaseRequestRepository $model)
    {
        $items                  =   $request->only([
            'item_description',
            'quantity',
            'unit_measurement',
            'unit_price',
            'total_amount'
        ]);

        $procs                  =   $request->getData();
        $date                   =   \Carbon\Carbon::now();

        $total_amount           =   array_sum($items['total_amount']);
        $prepared_by            =   \Sentinel::getUser()->id;
        $item_datas             =   [];

        $procs['total_amount']  =   $total_amount;
        $procs['prepared_by']   =   $prepared_by;

        $result = $model->save($procs);

        $counts                 =   $model->getCountByYear($date->format('Y'))->total;
        $counts                 += 1;

        $ref_name   =   "AMP-". $result->centers->name ."-". $counts ."-". $result->centers->name ."-". $date->format('Y');
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UnitPurchaseRequestRequest $request, UnitPurchaseRequestRepository $model)
    {
        $items                  =   $request->only([
            'item_description',
            'quantity',
            'unit_measurement',
            'unit_price',
            'total_amount'
        ]);

        $procs                  =   $request->getData();
        $date                   =   \Carbon\Carbon::now();

        $total_amount           =   array_sum($items['total_amount']);
        $prepared_by            =   \Sentinel::getUser()->id;
        $item_datas             =   [];

        $procs['total_amount']  =   $total_amount;
        $procs['prepared_by']   =   $prepared_by;

        $result = $model->save($procs);

        $counts                 =   $model->getCountByYear($date->format('Y'))->total;
        $counts                 += 1;

        $ref_name   =   "AMP-". $result->centers->name ."-". $counts ."-". $result->centers->name ."-". $date->format('Y');
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
    public function show($id, UnitPurchaseRequestRepository $model)
    {
        $result =   $model->with(['attachments'])->findById($id);

        return $this->view('modules.procurements.upr.show',[
            'data'          =>  $result,
            'indexRoute'    =>  $this->baseUrl.'index',
            'editRoute'     =>  $this->baseUrl.'edit',
            'modelConfig'   =>  [
                'request_quotation' =>  [
                    'route'     =>  'procurements.blank-rfq.store',
                ],
                'add_attachment' =>  [
                    'route'     =>  [$this->baseUrl.'attachments.store', $id],
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
        PaymentTermRepository $terms)
    {
        $result =   $model->findById($id);

        $account_codes      =    $accounts->lists('id', 'new_account_code');
        $charges            =    $chargeability->lists('id', 'name');
        $procurement_modes  =    $modes->lists('id', 'name');
        $procurement_center =    $centers->lists('id', 'name');
        $payment_terms      =    $terms->lists('id', 'name');
        $unit               =    $units->lists('id', 'short_code');

        return $this->view('modules.procurements.upr.edit',[
            'data'              =>  $result,
            'indexRoute'        =>  $this->baseUrl.'show',
            'account_codes'     =>  $account_codes,
            'payment_terms'     =>  $payment_terms,
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
    public function update(UnitPurchaseRequestRequest $request, $id, UnitPurchaseRequestRepository $model)
    {
        $model->update($request->getData(), $id);

        return redirect()->route($this->baseUrl.'edit', $id)->with([
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
    public function terminateUPR($id, Request $request, UnitPurchaseRequestRepository $model)
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * [uploadAttachment description]
     *
     * @param  Request $request [description]
     * @param  [type]  $id      [description]
     * @return [type]           [description]
     */
    public function uploadAttachment(
        Request $request,
        $id,
        AttachmentRepository $attachments)
    {

        $file       = md5_file($request->file);
        $file       = $file.".".$request->file->getClientOriginalExtension();

        $validator = \Validator::make($request->all(), [
            'file' => 'required',
        ]);

        $result     = $attachments->save([
            'upr_id'        =>  $id,
            'name'          =>  $request->file->getClientOriginalName(),
            'file_name'     =>  $file,
            'user_id'       =>  \Sentinel::getUser()->id,
            'upload_date'   =>  \Carbon\Carbon::now()
        ]);

        if($result)
        {
            $path       = $request->file->storeAs('upr-attachments', $file);
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
    public function downloadAttachment(
        Request $request,
        $id,
        AttachmentRepository $attachments)
    {
        $result     = $attachments->findById($id);

        $directory      =   storage_path("app/upr-attachments/".$result->file_name);

        if(!file_exists($directory))
        {
            return 'Sorry. File does not exists.';
        }

        return response()->download($directory);
    }

    /**
     * [timelines description]
     *
     * @param  [type]                        $id    [description]
     * @param  UnitPurchaseRequestRepository $model [description]
     * @return [type]                               [description]
     */
    public function timelines($id, UnitPurchaseRequestRepository $model)
    {
        $upr_model  =   $model->findTimelineById($id);


        return $this->view('modules.procurements.upr.timelines',[
            'data'              =>  $upr_model,
            'indexRoute'        =>  $this->baseUrl."show"
        ]);
    }
}
