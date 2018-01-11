<?php

namespace Revlv\Controllers\Procurements;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use DB;
use Excel;
use PDF;
use Uuid;
use \App\Support\Breadcrumb;
use App\Events\Event;

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
use \Revlv\Settings\Suppliers\SupplierRepository;
use \Revlv\Settings\BacSec\BacSecRepository;
use \Revlv\Settings\Forms\Header\HeaderRepository;

use Revlv\Procurements\UnitPurchaseRequests\Traits\FileTrait;
use Revlv\Procurements\UnitPurchaseRequests\Traits\ImportTrait;
use Revlv\Procurements\UnitPurchaseRequests\Traits\OverviewTrait;

class UPRController extends Controller
{
    use FileTrait, ImportTrait, OverviewTrait;

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
    protected $suppliers;
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

        if($user->hasRole('Admin'))
        {
            return $model->getDatatable();
        }

        $center =   0;
        if($user->units)
        {
            if($user->units->centers)
            {
                $center =   $user->units->centers->id;
            }
        }

        return $model->getDatatable($center);
    }

    /**
     * [getDatatable description]
     *
     * @return [type]            [description]
     */
    public function getDraftDatatable(UnitPurchaseRequestRepository $model)
    {
        $user   =   \Sentinel::getUser();

        if($user->hasRole('Admin'))
        {
            return $model->getDatatable(null, null, 'draft');
        }

        $center =   0;
        if($user->units)
        {
            if($user->units->centers)
            {
                $center =   $user->units->centers->id;
            }
        }

        return $model->getDatatable($center, null, 'draft');
    }

    public function drafts()
    {
      return $this->view('modules.procurements.upr.drafts',[
          'backRoute'   =>  $this->baseUrl."index",
          'breadcrumbs' => [
              new Breadcrumb('Unit Purchase Request Cancelled')
          ]
      ]);
    }

    /**
     * [confirmDrafts description]
     *
      * @param  [type]                        $id      [description]
     * @param  Request                       $request [description]
     * @param  UnitPurchaseRequestRepository $model   [description]
     * @return [type]                                 [description]
     */
    public function confirmDrafts($id, Request $request, UnitPurchaseRequestRepository $model)
    {
        $upr  = $model->findById($id);

        $date                   =   \Carbon\Carbon::now();
        $counts                 =   $model->getCountByYear($date->format('Y'))->total;
        if($upr->mode_of_procurement != 'public_bidding')
        {
            $ref_name   =   "AMP-". $upr->centers->short_code ."-". $counts ."-". $upr->unit->short_code ."-". $date->format('Y');
        }
        else
        {
            $ref_name   =   "PB-". $upr->centers->short_code ."-". $counts ."-". $upr->unit->short_code ."-". $date->format('Y');
        }

        $ref_name   =   str_replace(" ", "", $ref_name);

        $model->update(['ref_number' => $ref_name, 'date_prepared' => $request->date_prepared, 'status' => "upr_processing", 'state' => 'on-going'], $upr->id );


        return redirect()->route($this->baseUrl.'show', $upr->id )->with([
            'success'  => "New record has been successfully added."
        ]);
    }

    /**
     * [getCancelledDatatable description]
     *
     * @return [type]            [description]
     */
    public function getCancelledDatatable(UnitPurchaseRequestRepository $model)
    {
        $user   =   \Sentinel::getUser();

        if($user->hasRole('Admin'))
        {
            return $model->getDatatable(null, null, 'Cancelled');
        }


        $center =   null;
        if($user->units)
        {
            if($user->units->centers)
            {
                $center =   $user->units->centers->id;
            }
        }

        return $model->getDatatable($center, null, 'Cancelled');
    }

    /**
     * [viewCancelled description]
     *
     * @return [type] [description]
     */
    public function viewCancelled()
    {
        return $this->view('modules.procurements.upr.cancelled',[
            'backRoute'   =>  $this->baseUrl."index",
            'breadcrumbs' => [
                new Breadcrumb('Unit Purchase Request Cancelled')
            ]
        ]);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {        return $this->view('modules.procurements.upr.index',[
            'createRoute'   =>  $this->baseUrl."create",
            'importRoute'   =>  $this->baseUrl."imports",
            'breadcrumbs' => [
                new Breadcrumb('Unit Purchase Request')
            ]
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
        SignatoryRepository $signatories,
        ProcurementTypeRepository $types,
        PaymentTermRepository $terms)
    {
        $account_codes      =    $accounts->listCodes('id', 'new_account_code');

        $data = [];
        foreach($account_codes as $key)
        {
          $data[] = ['id' => $key->id, 'make' => 'nac', 'model' => $key->new_account_code];
          $data[] = ['id' => "old-".$key->id, 'make' => 'oac', 'model' => $key->old_account_code];
          $data[] = ['id' => "title-".$key->id, 'make' => 'title', 'model' => $key->name];
        }

        $signatory_lists=   $signatories->lists('id', 'name');

        $charges            =    $chargeability->lists('id', 'name');
        $procurement_modes  =    $modes->lists('id', 'name');
        $procurement_center =    $centers->lists('id', 'short_code');
        $payment_terms      =    $terms->lists('id', 'name');
        $procurement_types  =    $types->lists('id', 'code');
        $unit               =    $units->lists('id', 'short_code');
        $old_codes          =    $accounts->listOld();
        // $this->permissions->lists('permission','description')
        $this->view('modules.procurements.upr.create',[
            'indexRoute'        =>  $this->baseUrl.'index',
            'account_codes'     =>  $data,
            'signatory_list'    =>  $signatory_lists,
            'old_codes'         =>  $old_codes,
            'procurement_types' =>  $procurement_types,
            'payment_terms'     =>  $payment_terms,
            'unit'              =>  $unit,
            'charges'           =>  $charges,
            'user'              =>  \Sentinel::getUser(),
            'procurement_modes' =>  $procurement_modes,
            'procurement_center'=>  $procurement_center,
            'modelConfig'   =>  [
                'store' =>  [
                    'route'     =>  $this->baseUrl.'store'
                ]
            ],
            'breadcrumbs' => [
                new Breadcrumb('Unit Purchase Request', 'procurements.unit-purchase-requests.index'),
                new Breadcrumb('Create'),
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
        SignatoryRepository $signatories,
        UnitPurchaseRequestRepository $model)
    {
        $items                  =   $request->only([
            'item_description',
            'quantity',
            'new_account_code',
            'unit_measurement',
            'unit_price',
            'total_amount'
        ]);

        // if($request->items == null)
        // {
        //     return redirect()->back()->with([
        //         'error' =>  'Pleased add item to continue.'
        //     ])->withInput();
        // }

        if($request->has('items'))
        {

          foreach($request->items as $item)
          {
              if($item['item_description'] == null || $item['quantity'] == null || $item['unit_measurement'] == null || $item['unit_price'] == null || $item['total_amount'] == null) {
                return redirect()->back()->with([
                    'error' =>  'Pleased add item to continue.'
                ])->withInput();
              }
          }
        }

        $procs                  =   $request->getData();
        $date                   =   \Carbon\Carbon::now();

        $total_sum              =   0;


        if($request->has('items'))
        {
          foreach($request->items as $item)
          {
              $total_sum          += $item['total_amount'];
          }
        }
        $total_amount           =   $total_sum;
        $prepared_by            =   \Sentinel::getUser()->id;
        $item_datas             =   [];

        $transaction_date       =   \Carbon\Carbon::createFromFormat('Y-m-d', $request->date_prepared);

        $procs['total_amount']  =   $total_amount;
        $procs['prepared_by']   =   $prepared_by;
        $procs['last_date']     =   $transaction_date;
        if($request->requestor_id)
        {
            $requestor  =   $signatories->findById($request->requestor_id);
            $procs['requestor_text']   =   $requestor->name."/".$requestor->ranks."/".$requestor->branch."/".$requestor->designation;
        }
        if($request->fund_signatory_id)
        {
            $funder  =   $signatories->findById($request->fund_signatory_id);
            $procs['fund_signatory_text']   =   $funder->name."/".$funder->ranks."/".$funder->branch."/".$funder->designation;
        }

        if($request->approver_id)
        {
            $approver  =   $signatories->findById($request->approver_id);
            $procs['approver_text']   =   $approver->name."/".$approver->ranks."/".$approver->branch."/".$approver->designation;
        }


        if($request->mode_of_procurement != 'public_bidding'){
            $procs['next_allowable']=   3;
            $procs['next_step']     =   "Create Invitation";
            $procs['next_due']      =   $transaction_date->addDays(3);
        }
        else{

            $procs['next_allowable']=   1;
            $procs['next_step']     =   "Document Acceptance";
            $procs['next_due']      =   $transaction_date->addDays(1);
        }

        $result = $model->save($procs);

        $counts                 =   $model->getCountByYear($date->format('Y'))->total;

        if($result->mode_of_procurement != 'public_bidding')
        {
            $ref_name   =   "AMP-". $result->centers->short_code ."-". $counts ."-". $result->unit->short_code ."-". $date->format('Y');
        }
        else
        {
            $ref_name   =   "PB-". $result->centers->short_code ."-". $counts ."-". $result->unit->short_code ."-". $date->format('Y');
        }

        $ref_name   =   str_replace(" ", "", $ref_name);

        $model->update(['ref_number' => $ref_name], $result->id );

        if($result && $request->has('items'))
        {
            foreach($request->items as $item)
            {
                $item_datas[]  =   [
                    'item_description'      =>  $item['item_description'],
                    'new_account_code'      =>  $item['new_account_code'],
                    'quantity'              =>  $item['quantity'],
                    'unit_measurement'      =>  $item['unit_measurement'],
                    'unit_price'            =>  $item['unit_price'],
                    'total_amount'          =>  $item['total_amount'],
                    'upr_number'            =>  $request->get('upr_number'),
                    'ref_number'            =>  $request->get('ref_number'),
                    'prepared_by'           =>  $prepared_by,
                    'date_prepared'         =>  $request->get('date_prepared'),
                    'upr_id'                =>  $result->id,
                    'id'                    =>  Uuid::generate()->string
                ];
            }

            DB::table('unit_purchase_request_items')->insert($item_datas);
        }
        event(new Event($result, "UPR Created"));

        return redirect()->route($this->baseUrl.'show', $result->id )->with([
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
        AccountCodeRepository $accounts,
        UnitPurchaseRequestRepository $model,
        SupplierRepository $suppliers,
        SignatoryRepository $signatories)
    {
        $account_codes      =    $accounts->lists('id', 'new_account_code');
        $result             =   $model->with(['attachments'])->findById($id);
        $signatory_lists    =   $signatories->lists('id', 'name');
        $bid_issuance       =   $suppliers->lists('id', 'name');

        if($result->bid_issuances != null)
        {
            foreach($result->bid_issuances as $list)
            {
                unset($bid_issuance[$list->proponent_id]);
            }
        }

        $bid_amount = 0;
        $address    = '';
        if($result->purchase_order)
        {
          $bid_amount = $result->purchase_order->bid_amount;
        }

        if($result->noa)
        {
          if($result->procurement_type != 'public_bidding')
          {
              $winner = $result->noa->winner;
          }
          else
          {
              $winner = $result->noa->biddingWinner;
          }

          if($winner)
          {
            if($winner->supplier)
            {
              $address = $winner->supplier->address;
            }
          }
        }

        return $this->view('modules.procurements.upr.show',[
            'accounts'          =>  $accounts->lists('id', 'new_account_code'),
            'data'              =>  $result,
            'bid_amount'        =>  $bid_amount,
            'address'           =>  $address,
            'bid_issuance'      =>  $bid_issuance,
            'proponent_lists'   =>  $suppliers->lists('id', 'name'),
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
            ],
            'breadcrumbs' => [
                new Breadcrumb('Unit Purchase Request', 'procurements.unit-purchase-requests.index'),
                new Breadcrumb($result->upr_number),
            ]
        ]);
    }

    /**
     * [itemStore description]
     *
     * @param  [type]  $uprId   [description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function itemStore(
        $uprId,
        Request $request,
        UnitPurchaseRequestRepository $model,
        ItemRepository $items)
    {
      $this->validate($request, [
        'new_account_code'  => 'required',
        'item_description'  => 'required',
        'quantity'          => 'required',
        'unit_measurement'  => 'required',
        'unit_price'        => 'required',
      ]);

      $upr = $model->findById($uprId);
      $amount = $upr->total_amount;
      $total  = $request->unit_price * $request->quantity;
      $total_amount = $total + $amount;
      $id = Uuid::generate()->string;
      $inputs   =  $request->only([
        'new_account_code',
        'item_description',
        'quantity',
        'unit_measurement',
        'unit_price'
      ]);

      $inputs['upr_number'] = $upr->upr_number;
      $inputs['ref_number'] = $upr->ref_number;
      $inputs['upr_id'] = $upr->id;
      $inputs['prepared_by'] = \Sentinel::getUser()->id;
      $inputs['date_prepared'] = \Carbon\Carbon::now();
      $inputs['total_amount'] = $total;
      $inputs['id'] = $id;
      $items->save($inputs);
      $model->update(['total_amount' => $total_amount], $uprId);

        return redirect()->route($this->baseUrl.'show', $uprId)->with([
            'success'  => "Record has been successfully updated."
        ]);
    }

    /**
     * [itemStore description]
     *
     * @param  [type]  $uprId   [description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function itemDelete(
        $id,
        Request $request,
        UnitPurchaseRequestRepository $model,
        ItemRepository $items)
    {

      $item = $items->findById($id);
      $upr = $model->findById($item->upr_id);
      $amount = $upr->total_amount;
      $total  = $item->unit_price * $item->quantity;
      $total_amount = $amount - $total;

      $items->delete($id);

      $model->update(['total_amount' => $total_amount], $item->upr_id);

      return redirect()->route($this->baseUrl.'show', $item->upr_id)->with([
          'success'  => "Record has been successfully deleted."
      ]);
    }

    /**
     * [updateLineItem description]
     *
     * @param  Request                       $request [description]
     * @param  [type]                        $id      [description]
     * @param  UnitPurchaseRequestRepository $model   [description]
     * @param  ItemRepository                $item    [description]
     * @return [type]                                 [description]
     */
    public function updateLineItem(
        Request $request,
        $id,
        UnitPurchaseRequestRepository $model,
        ItemRepository $item)
    {
        $this->validate($request, [
          'unit_measurement' => 'required',
          'quantity' => 'required',
          'unit_price' => 'required'
        ]);

        $item = $item->update([
            'unit_measurement' => $request->unit_measurement,
            'quantity' => $request->quantity,
            'unit_price' => $request->unit_price,
            'total_amount' => $request->unit_price * $request->quantity
        ], $id);

        $upr = $model->findById($item->upr_id);
        $total = 0;
        foreach($upr->items as $it)
        {
          $total += $it['total_amount'];
        }

        $model->update(['total_amount' => $total], $item->upr_id);

        return redirect()->route($this->baseUrl.'show', $item->upr_id)->with([
            'success'  => "Record has been successfully deleted."
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
        SignatoryRepository $signatories,
        ProcurementTypeRepository $types,
        PaymentTermRepository $terms)
    {
        $result =   $model->findById($id);

        $account_codes      =    $accounts->lists('id', 'new_account_code');
        $charges            =    $chargeability->lists('id', 'name');
        $procurement_modes  =    $modes->lists('id', 'name');
        $procurement_center =    $centers->lists('id', 'short_code');
        $procurement_types  =    $types->lists('id', 'code');
        $payment_terms      =    $terms->lists('id', 'name');
        $unit               =    $units->lists('id', 'short_code');
        $signatory_lists=   $signatories->lists('id', 'name');

        return $this->view('modules.procurements.upr.edit',[
            'data'              =>  $result,
            'indexRoute'        =>  $this->baseUrl.'show',
            'account_codes'     =>  $account_codes,
            'signatory_list'    =>  $signatory_lists,
            'payment_terms'     =>  $payment_terms,
            'procurement_types' =>  $procurement_types,
            'charges'           =>  $charges,
            'unit'              =>  $unit,
            'procurement_modes' =>  $procurement_modes,
            'procurement_center'=>  $procurement_center,
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
                new Breadcrumb('Unit Purchase Request', 'procurements.unit-purchase-requests.index'),
                new Breadcrumb($result->upr_number, 'procurements.unit-purchase-requests.show', $result->id),
                new Breadcrumb('Update'),
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
        SignatoryRepository $signatories,
        \Revlv\Users\UserRepository $users,
        UnitPurchaseRequestRepository $model)
    {
        $upr_model  =   $model->findById($id);
        $this->validate($request,[
            'requestor_id'  =>  "required",
            'fund_signatory_id'  =>  "required",
            'approver_id'  =>  "required",
        ]);

        $inputs     =   $request->getData();

        if($upr_model->requestor_id != $request->requestor_id)
        {
            $requestor  =   $signatories->findById($request->requestor_id);
            $inputs['requestor_text']   =   $requestor->name."/".$requestor->ranks."/".$requestor->branch."/".$requestor->designation;
        }
        if($upr_model->fund_signatory_id != $request->fund_signatory_id)
        {
            $funder  =   $signatories->findById($request->fund_signatory_id);
            $inputs['fund_signatory_text']   =   $funder->name."/".$funder->ranks."/".$funder->branch."/".$funder->designation;
        }

        if($upr_model->approver_id != $request->approver_id)
        {
            $approver  =   $signatories->findById($request->approver_id);
            $inputs['approver_text']   =   $approver->name."/".$approver->ranks."/".$approver->branch."/".$approver->designation;
        }


        // $inputs['ref_number']   =   $ref_name;

        $result     =   $model->update($inputs, $id);
        $ref_name   =   "";

        // update ref number
        $ref        =   explode('-', $result->ref_number);
        if(count($ref) != 1)
        {

          if($result->mode_of_procurement != 'public_bidding')
          {
              $ref_name   =   "AMP-". $result->centers->short_code ."-". $ref[2] ."-". $result->unit->short_code ."-". $ref[4];
          }
          else
          {
              $ref_name   =   "PB-". $result->centers->short_code ."-". $ref[2] ."-". $result->unit->short_code ."-". $ref[4];
          }
        }

        $ref_name   =   str_replace(" ", "", $ref_name);
        $model->update(['ref_number' => $ref_name], $id);
        // update ref number

        // Adding logs
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
        event(new Event($result, "Update UPR"));

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

        $result =   $model->update([
            'terminate_status'  =>  $request->terminate_status,
            'remarks'           =>  $request->remarks,
            'state'             =>  "Terminated (".$request->terminate_status.")",
            'terminated_date'   =>  \Carbon\Carbon::now(),
            'terminated_by'     =>  \Sentinel::getUser()->id,
        ], $id);

        event(new Event($result, "UPR Terminated"));

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

        $result =   $model->update([
            'cancelled_at'  =>  $request->cancelled_at,
            'cancel_reason' =>  $request->cancel_reason,
            'state'         =>  "Cancelled",
            'status'        =>  "Cancelled",
        ], $id);

        event(new Event($result, "UPR Cancelled"));

        return redirect()->route($this->baseUrl.'show', $id)->with([
            'success'  => "Record has been successfully updated."
        ]);
    }

    /**
     * [addJustification description]
     *
     * @param [type]                        $id      [description]
     * @param Request                       $request [description]
     * @param UnitPurchaseRequestRepository $model   [description]
     */
    public function addJustification($id, Request $request, UnitPurchaseRequestRepository $model)
    {
        $this->validate($request, [
            'remarks'      => 'required',
            'action'       => 'required',
        ]);

        $response = $model->update([
            'last_remarks'  =>  $request->remarks,
            'last_action' =>  $request->action,
        ], $id);

        return response()->json($response, 200);
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
            $h_lists[]  =   \Carbon\Carbon::createFromFormat('!Y-m-d', $hols)->format('Y-m-d');
        }

        return $this->view('modules.procurements.upr.timelines',[
            'data'              =>  $upr_model,
            'h_lists'           =>  $h_lists,
            'upr_list'           =>  $upr_list,
            'indexRoute'         =>  $this->baseUrl."show",
            'breadcrumbs' => [
                new Breadcrumb('Unit Purchase Request', 'procurements.unit-purchase-requests.index'),
                new Breadcrumb($upr_model->upr_number, 'procurements.unit-purchase-requests.show', $upr_model->id),
                new Breadcrumb('Timelines'),
            ]
        ]);
    }

    /**
     * [destroy description]
     *
     * @param  [type]                        $id    [description]
     * @param  UnitPurchaseRequestRepository $model [description]
     * @return [type]                               [description]
     */
    public function destroy($id, UnitPurchaseRequestRepository $model)
    {
      $upr  = $model->findById($id);
      $upr->itb()->delete();
      $upr->itbs()->delete();
      $upr->document_accept()->delete();
      $upr->preproc()->delete();
      $upr->preprocs()->delete();
      $upr->documents()->delete();
      $upr->bid_conference()->delete();
      $upr->bid_conferences()->delete();
      $upr->bid_issuance()->delete();
      $upr->bid_issuances()->delete();
      $upr->bid_open()->delete();
      $upr->bid_opens()->delete();
      $upr->post_qual()->delete();
      $upr->post_quals()->delete();
      $upr->bid_proponents()->delete();
      $upr->philgeps()->delete();
      $upr->philgeps_many()->delete();
      $upr->rfq()->delete();
      $upr->invitations()->delete();
      $upr->canvassing()->delete();
      $upr->noa()->delete();
      $upr->ntp()->delete();
      $upr->purchase_order()->delete();
      $upr->delivery_order()->delete();
      $upr->delivery_orders()->delete();
      $upr->diir()->delete();
      $upr->inspections()->delete();
      $upr->voucher()->delete();
      $upr->items()->delete();
      $upr->delete();


      return redirect()->route($this->baseUrl.'index')->with([
          'success'  => "Record has been successfully deleted."
      ]);
    }
}
