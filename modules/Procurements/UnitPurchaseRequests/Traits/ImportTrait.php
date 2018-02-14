<?php

namespace Revlv\Procurements\UnitPurchaseRequests\Traits;

use Illuminate\Http\Request;
use DB;
use Datatables;
use Uuid;
use Excel;
use PDF;
use \App\Support\Breadcrumb;
use App\Events\Event;

use \Revlv\Procurements\UnitPurchaseRequests\UnitPurchaseRequestRepository;
use \Revlv\Settings\AccountCodes\AccountCodeRepository;
use \Revlv\Settings\Signatories\SignatoryRepository;
use \Revlv\Settings\Chargeability\ChargeabilityRepository;
use \Revlv\Settings\ModeOfProcurements\ModeOfProcurementRepository;
use \Revlv\Settings\ProcurementCenters\ProcurementCenterRepository;
use \Revlv\Procurements\UnitPurchaseRequests\UnitPurchaseRequestRequest;
use \Revlv\Settings\PaymentTerms\PaymentTermRepository;
use \Revlv\Settings\ProcurementTypes\ProcurementTypeRepository;
use \Revlv\Settings\CateredUnits\CateredUnitRepository;

trait ImportTrait
{
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
    protected $units;
    protected $logs;
    protected $types;

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
            ],
            'breadcrumbs' => [
                new Breadcrumb('Unit Purchase Request Import')
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
        SignatoryRepository $signatories,
        ModeOfProcurementRepository $modes,
        ProcurementCenterRepository $centers,
        ProcurementTypeRepository $types,
        CateredUnitRepository $units,
        PaymentTermRepository $terms)
    {
        $this->validate($request,['file'=>'required']);
        $path           =   $request->file('file')->getRealPath();

        $data           =   [];
        $reader         =   Excel::load($path, function($reader) {});
        // Loop through all sheets
        // $reader->formatDates(true, 'd F Y');
        // $fields         =   $reader->limitColumns(10)->get();
        $fields         =   $reader->limitColumns(6)->limitRows(30)->get();
        $items          =   $reader->skipRows(30)->limitColumns(6)->get();
        $pcco           =   $fields[10][0];
        // $projectName    =   $fields[10][3];
        $cateredUnit    =   $fields[12][0];
        // $upr_number     =   $fields[12][3];
        // $place          =   $fields[14][0];
        $datePrepared   =   $fields[14][3]; // y/m/d
        $procurementProg=   $fields[16][0];
        $mode           =   $fields[16][3];
        $charge         =   $fields[18][3];
        $termPayments   =   $fields[20][0];

        // $fund           =   $fields[18][0];
        // $others         =   $fields[20][3];
        // $purpose        =   $fields[22][0];
        $requestBy      =   $fields[25][0];
        $fundBy         =   $fields[25][2];
        $preparedBy     =   $fields[25][4];
        $requestByR     =   $fields[26][0];
        $requestByB     =   $fields[26][1];
        $fundByR        =   $fields[26][3];
        $fundByB        =   $fields[26][4];
        $preparedByR    =   $fields[26][5];
        $preparedByB    =   $fields[26][5];
        $requestByD     =   $fields[27][0];
        $fundByD        =   $fields[27][2];
        $preparedByD    =   $fields[27][4];
        $array          =   [];
        $itemArray      =   [];
        $requestId      = $funderId   =  $approverId = '';

        if($requestBy != null)
        {
            $requestId   =   $signatories->findByName($requestBy);
            if($requestId)
            {
                $requestId = $requestId->id;
            }
        }

        if($fundBy != null)
        {
            $funderId   =   $signatories->findByName($fundBy);
            if($funderId)
            {
                $funderId = $funderId->id;
            }
        }

        if($preparedBy != null)
        {
            $approverId   =   $signatories->findByName($preparedBy);
            if($approverId)
            {
                $approverId = $approverId->id;
            }
        }

        if($datePrepared != null)
        {
            $datePrepared = \Carbon\Carbon::createFromFormat('Y/m/d', ($datePrepared))->format('Y-m-d');
        }

        if($pcco != null)
        {
            if( $centers->findByName(trim($pcco)) ){
                $pcco =  $centers->findByName(trim($pcco))->id;
            }
        }

        if($cateredUnit != null)
        {
            if( $units->getByCode(trim($cateredUnit)) ){
                $cateredUnit =  $units->getByCode(trim($cateredUnit))->id;
            }
        }

        if($charge != null)
        {
            if( $chargeability->findByName(trim($charge)) ){
                $charge =  $chargeability->findByName(trim($charge))->id;
            }
        }

        if($termPayments != null)
        {
            if( $terms->findByName(trim($termPayments)) ){
                $termPayments =  $terms->findByName(trim($termPayments))->id;
            }
        }

        if($procurementProg != null)
        {
            if( $types->findByCode(trim($procurementProg)) ){
                $procurementProg =  $types->findByCode(trim($procurementProg))->id;
            }
        }

        if($mode != null && $mode != 'Public Bidding')
        {
            if( $modes->findByName(trim($mode)) ){
                $mode =  $modes->findByName(trim($mode))->id;
            }
        } elseif($mode == 'Public Bidding'){
          $model = 'public_bidding';
        }

        // dd($termPayments);
        // $array['units']               =   \Sentinel::getUser()->unit_id;
        $array['units']               = $cateredUnit;
        $array['upr_number']          = $fields[12][3];
        $array['project_name']        = $fields[10][3];
        $array['date_prepared']       = $datePrepared;
        $array['place_of_delivery']   = $fields[14][0];
        $array['fund_validity']       = $fields[18][0];
        $array['procurement_office']  = $pcco;
        $array['mode_of_procurement'] = $mode;
        $array['chargeability']       = $charge;
        $array['procurement_type']    = $procurementProg;
        $array['terms_of_payment']    = $termPayments;
        $array['other_infos']         = $fields[20][3];
        $array['purpose']             = $fields[22][0];
        $array['requestor_id']        = $requestId;
        $array['fund_signatory_id']   = $funderId;
        $array['approver_id']         = $approverId;
        // dd($array);
        // foreach($fields->toArray() as $key => $row)
        // {
        //     $val = $key + 3;
        //     switch ($row[0]) {
        //         case 'UPR NO':
        //             $array['upr_number'] = $fields->toArray()[$val][0];
        //             break;
        //         case 'DATE PREPARED':
        //             $date   =   \Carbon\Carbon::createFromFormat('Y-m-d', ($fields->toArray()[$val][0]));
        //             $array['date_prepared'] = $date;
        //             break;
        //         case 'PROJECT NAME':
        //             $array['project_name'] = $fields->toArray()[$val][0];
        //             break;
        //         case 'Procurement Center / Office':
        //             $centerModel    =   $centers->findById($fields->toArray()[$val][0]);
        //             if($centerModel != null)
        //             {
        //                 $array['procurement_office'] = $centerModel->id;
        //             }
        //             break;
        //         case 'MODE OF PROCUREMENT':
        //             $modesModel    =   $modes->findById($fields->toArray()[$val][0]);
        //             if($modesModel != null && $modesModel->name != 'Public Bidding')
        //             {
        //                 $array['mode_of_procurement'] = $modesModel->id;
        //             }
        //             elseif($row[2] == 'Public Bidding'){
        //                 $array['mode_of_procurement'] = 'public_bidding';
        //             }
        //             else
        //             {

        //             }
        //             break;
        //         case 'CHARGEABILITY':
        //             $chargeabilityModel    =   $chargeability->findById($fields->toArray()[$val][0]);
        //             if($chargeabilityModel != null)
        //             {
        //                 $array['chargeability'] = $chargeabilityModel->id;
        //             }
        //             break;
        //         case 'Approved By':
        //             $approverModel    =   $signatories->findById($fields->toArray()[$val][0]);
        //             if($approverModel != null)
        //             {
        //                 $array['approver_id'] = $approverModel->id;
        //             }
        //             break;
        //         case 'Request By':
        //             $requestorModel    =   $signatories->findById($fields->toArray()[$val][0]);
        //             if($requestorModel != null)
        //             {
        //                 $array['requestor_id'] = $requestorModel->id;
        //             }
        //             break;
        //         case 'Fund Certified Available':
        //             $funderModel    =   $signatories->findById($fields->toArray()[$val][0]);
        //             if($funderModel != null)
        //             {
        //                 $array['fund_signatory_id'] = $funderModel->id;
        //             }
        //             break;
        //         case 'ACCOUNT CODE':
        //             $accountsModel    =   $accounts->findByName($row[2]);
        //             if($accountsModel != null)
        //             {
        //                 $array['new_account_code'] = $accountsModel->id;
        //             }
        //             break;
        //         case 'FUND VALIDITY':
        //             $array['fund_validity'] = $fields->toArray()[$val][0];
        //             break;
        //         case 'PLACE OF DELIVERY':
        //             $array['place_of_delivery'] = $fields->toArray()[$val][0];
        //             break;
        //         case 'TERMS OF PAYMENT':
        //             $termsModel    =   $terms->findByName($fields->toArray()[$val][0]);
        //             if($termsModel != null)
        //             {
        //                 $array['terms_of_payment'] = $termsModel->id;
        //             }
        //                 break;
        //         case 'OTHER ESSENTIAL INFO':
        //             $array['other_infos'] = $fields->toArray()[$val][0];
        //             break;
        //         case 'PURPOSE':
        //             $array['purpose'] = $fields->toArray()[$val][0];
        //                 break;

        //         default:

        //             break;
        //     }

        // }

        $item = [];
        $total = 0;
        foreach($items->toArray() as $itemRow)
        {
            // if($itemRow[0] != "<tr></tr><tr> <td> ITEM DESCRIPTION</td> <td>QTY</td> <td>UNIT</td> <td>UNIT PRICE</td> <td>TOTAL AMOUNT</td><td>Account Code</td> </tr><tr>" && trim($itemRow[0]) != "<td>" && trim($itemRow[0]) != "</thead>" && trim($itemRow[0]) != "</tbody>" && trim($itemRow[0]) != "</table>" && trim($itemRow[0]) != "</html>" && trim($itemRow[0]) != "</body>"&& trim($itemRow[0]) != "</td>" && trim($itemRow[0]) != "<tr>")
            if($itemRow[0] != "ITEMS" && $itemRow[0] != "ACCOUNT CODE")
            {
                // dd($itemRow);
                $code = '';
                $accountsModel    =   $accounts->findByName(trim($itemRow[0]) );
                if($accountsModel != null)
                {
                    $code = $accountsModel->id;
                }
                // dd($itemRow[0]);
                $itemArray[]    =   [
                    'new_account_code'      =>  $code,
                    'item_description'      =>  $itemRow[1],
                    'quantity'              =>  $itemRow[2],
                    'unit'                  =>  $itemRow[3],
                    'unit_price'            =>  $itemRow[4],
                    'total_amount'          =>  $itemRow[5],
                ];
                $total  = $total + $itemRow[5];
                // dd($itemArray);
                // array_push($item, $itemRow[0]);
            }

            // if($itemRow[0] != "ITEM DESCRIPTION")
            // {
            //     $accountsModel    =   $accounts->findByName(trim($itemRow[1]) );

            //     if($accountsModel != null)
            //     {
            //         $code = $accountsModel->id;
            //     }
            //     else
            //     {
            //         $code = 0;
            //     }


            //     $itemArray[]    =   [
            //         'item_description'      =>  $itemRow[0],
            //         'new_account_code'      =>  $code,
            //         'quantity'              =>  $itemRow[2],
            //         'unit'                  =>  $itemRow[3],
            //         'unit_price'            =>  $itemRow[4],
            //         'total_amount'          =>  $itemRow[5],
            //     ];
            // }
        }
        // $decodes = [];
        // $count  = 0;
        // dd($item);
        // foreach($item as $newItem)
        // {
        //   if($newItem == '</tr>')
        //   {
        //     $count ++;
        //   }
        //   else{
        //     if($newItem != null)
        //     {
        //       $decodes[$count][] = $newItem;
        //     }
        //   }

        // }
        // foreach($decodes as $new)
        // {
        //   $accountsModel    =   $accounts->findById($new[5] );
        //   if($accountsModel != null)
        //   {
        //       $code = $accountsModel->id;
        //   }
        //   else
        //   {
        //       $code = 0;
        //   }

        //   $itemArray[]    =   [
        //       'item_description'      =>  $new[0],
        //       'new_account_code'      =>  $code,
        //       'quantity'              =>  $new[1],
        //       'unit'                  =>  $new[2],
        //       'unit_price'            =>  $new[3],
        //       'total_amount'          =>  $new[4],
        //   ];
        // }
        $array['total_amount']  =   $total;
        // dd($array);
        session([
            'data'  =>  $array,
            'items' =>  $itemArray
        ]);

        return redirect()->route($this->baseUrl.'second-step');
    }

    /**
     * [secondStep description]
     *
     * @param  [type] $data  [description]
     * @param  [type] $items [description]
     * @return [type]        [description]
     */
    public function secondStep(
        AccountCodeRepository $accounts,
        ChargeabilityRepository $chargeability,
        ModeOfProcurementRepository $modes,
        ProcurementCenterRepository $centers,
        ProcurementTypeRepository $types,
        CateredUnitRepository $units,
        SignatoryRepository $signatories,
        PaymentTermRepository $terms)
    {
        $data               =    session('data');
        $items              =    session('items');

        $account_codes      =    $accounts->lists('id', 'new_account_code');
        $old_codes          =    $accounts->listOld();
        $charges            =    $chargeability->lists('id', 'name');
        $procurement_modes  =    $modes->lists('id', 'name');
        $procurement_center =    $centers->lists('id', 'short_code');
        $payment_terms      =    $terms->lists('id', 'name');
        $unit               =    $units->lists('id', 'short_code');
        $procurement_types  =    $types->lists('id', 'code');
        $signatory_lists    =    $signatories->lists('id', 'name');


        return $this->view('modules.procurements.upr.import-validate',[
            'indexRoute'        =>  $this->baseUrl."index",
            'data'              =>  $data,
            'user'              =>  \Sentinel::getUser(),
            'items'             =>  $items,
            'signatory_list'    =>  $signatory_lists,
            'account_codes'     =>  $account_codes,
            'old_codes'         =>  $old_codes,
            'procurement_types' =>  $procurement_types,
            'payment_terms'     =>  $payment_terms,
            'unit'              =>  $unit,
            'charges'           =>  $charges,
            'procurement_modes' =>  $procurement_modes,
            'procurement_center'=>  $procurement_center,
            'breadcrumbs' => [
                new Breadcrumb('Unit Purchase Request Import')
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
    public function saveFile(UnitPurchaseRequestRequest $request, SignatoryRepository $signatories, UnitPurchaseRequestRepository $model)
    {
        $items                  =   $request->only([
            'item_description',
            'quantity',
            'unit_measurement',
            'unit_price',
            'new_account_code',
            'total_amount'
        ]);

        $procs                  =   $request->getData();
        $date                   =   \Carbon\Carbon::now();

        $total_amount           =   array_sum($items['total_amount']);
        $prepared_by            =   \Sentinel::getUser()->id;
        $item_datas             =   [];


        $transaction_date       =   \Carbon\Carbon::createFromFormat('Y-m-d', $request->date_prepared);

        $procs['total_amount']  =   $total_amount;
        $procs['prepared_by']   =   $prepared_by;
        $procs['last_date']     =   $transaction_date;

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

        $result = $model->save($procs);

        $counts                 =   $model->getCountByYear($date->format('Y'))->total;
        $counts                 += 1;

        if($result->mode_of_procurement != 'public_bidding')
        {
            $ref_name   =   "AMP-". $result->centers->short_code ."-". $counts ."-". $result->unit->short_code ."-". $date->format('Y');
        }
        else
        {
            $ref_name   =   "PB-". $result->centers->short_code ."-". $counts ."-". $result->unit->short_code ."-". $date->format('Y');
        }

        $ref_name   =   str_replace(" ", "", $ref_name);

        $model->update(['ref_number' => $ref_name], $result->id);

        if($result)
        {
            for ($i=0; $i < count($items['item_description']); $i++) {
                $item_datas[]  =   [
                    'item_description'      =>  $items['item_description'][$i],
                    'new_account_code'      =>  $items['new_account_code'][$i],
                    'quantity'              =>  $items['quantity'][$i],
                    'unit_measurement'      =>  $items['unit_measurement'][$i],
                    'unit_price'            =>  $items['unit_price'][$i],
                    'total_amount'          =>  $items['total_amount'][$i],
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
        return redirect()->route($this->baseUrl.'show', $result->id)->with([
            'success'  => "New record has been successfully added."
        ]);
    }
}