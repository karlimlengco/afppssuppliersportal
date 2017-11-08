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
        $fields         =   $reader->limitColumns(10)->limitRows(97)->get();
        $items          =   $reader->skipRows(97)->limitColumns(10)->get();
        $array          =   [];
        $itemArray      =   [];

        $array['units'] =   \Sentinel::getUser()->unit_id;

        foreach($fields->toArray() as $key => $row)
        {
            $val = $key + 3;
            switch ($row[0]) {
                case 'UPR NO':
                    $array['upr_number'] = $fields->toArray()[$val][0];
                    break;
                case 'DATE PREPARED':
                    $date   =   \Carbon\Carbon::createFromFormat('Y-m-d', ($fields->toArray()[$val][0]));
                    $array['date_prepared'] = $date;
                    break;
                case 'PROJECT NAME':
                    $array['project_name'] = $fields->toArray()[$val][0];
                    break;
                case 'Procurement Center / Office':
                    $centerModel    =   $centers->findById($fields->toArray()[$val][0]);
                    if($centerModel != null)
                    {
                        $array['procurement_office'] = $centerModel->id;
                    }
                    break;
                case 'MODE OF PROCUREMENT':
                    $modesModel    =   $modes->findById($fields->toArray()[$val][0]);
                    if($modesModel != null && $modesModel->name != 'Public Bidding')
                    {
                        $array['mode_of_procurement'] = $modesModel->id;
                    }
                    elseif($row[2] == 'Public Bidding'){
                        $array['mode_of_procurement'] = 'public_bidding';
                    }
                    else
                    {

                    }
                    break;
                case 'CHARGEABILITY':
                    $chargeabilityModel    =   $chargeability->findById($fields->toArray()[$val][0]);
                    if($chargeabilityModel != null)
                    {
                        $array['chargeability'] = $chargeabilityModel->id;
                    }
                    break;
                case 'ACCOUNT CODE':
                    $accountsModel    =   $accounts->findByName($row[2]);
                    if($accountsModel != null)
                    {
                        $array['new_account_code'] = $accountsModel->id;
                    }
                    break;
                case 'FUND VALIDITY':
                    $array['fund_validity'] = $fields->toArray()[$val][0];
                    break;
                case 'PLACE OF DELIVERY':
                    $array['place_of_delivery'] = $fields->toArray()[$val][0];
                    break;
                case 'TERMS OF PAYMENT':
                    $termsModel    =   $terms->findByName($fields->toArray()[$val][0]);
                    if($termsModel != null)
                    {
                        $array['terms_of_payment'] = $termsModel->id;
                    }
                        break;
                case 'OTHER ESSENTIAL INFO':
                    $array['other_infos'] = $fields->toArray()[$val][0];
                    break;
                case 'PURPOSE':
                    $array['purpose'] = $fields->toArray()[$val][0];
                        break;

                default:

                    break;
            }

        }

        $item = [];
        foreach($items->toArray() as $itemRow)
        {
          if($itemRow[0] != "<tr></tr><tr> <td> ITEM DESCRIPTION</td> <td>QTY</td> <td>UNIT</td> <td>UNIT PRICE</td> <td>TOTAL AMOUNT</td><td>Account Code</td> </tr><tr>" && trim($itemRow[0]) != "<td>" && trim($itemRow[0]) != "</thead>" && trim($itemRow[0]) != "</tbody>" && trim($itemRow[0]) != "</table>" && trim($itemRow[0]) != "</html>" && trim($itemRow[0]) != "</body>"&& trim($itemRow[0]) != "</td>" && trim($itemRow[0]) != "<tr>")
          {
            array_push($item, $itemRow[0]);
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
        $decodes = [];
        $count  = 0;
        foreach($item as $newItem)
        {
          if($newItem == '</tr>')
          {
            $count ++;
          }
          else{
            if($newItem != null)
            {
              $decodes[$count][] = $newItem;
            }
          }

        }
        foreach($decodes as $new)
        {
          $accountsModel    =   $accounts->findById($new[5] );
          if($accountsModel != null)
          {
              $code = $accountsModel->id;
          }
          else
          {
              $code = 0;
          }

          $itemArray[]    =   [
              'item_description'      =>  $new[0],
              'new_account_code'      =>  $code,
              'quantity'              =>  $new[1],
              'unit'                  =>  $new[2],
              'unit_price'            =>  $new[3],
              'total_amount'          =>  $new[4],
          ];
        }

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


        return $this->view('modules.procurements.upr.import-validate',[
            'indexRoute'        =>  $this->baseUrl."index",
            'data'              =>  $data,
            'user'              =>  \Sentinel::getUser(),
            'items'             =>  $items,
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
    public function saveFile(UnitPurchaseRequestRequest $request, UnitPurchaseRequestRepository $model)
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