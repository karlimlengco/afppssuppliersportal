<?php

namespace Revlv\Controllers\Reports;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Carbon;
use Excel;

use \Revlv\Procurements\BlankRequestForQuotation\BlankRFQRepository;
use \Revlv\Procurements\UnitPurchaseRequests\UnitPurchaseRequestRepository;
use \Revlv\Procurements\UnitPurchaseRequests\UnitPurchaseRequestEloquent;

class AggregateController extends Controller
{

    /**
     * [Base Route of Controller]
     *
     * @var string
     */
    protected $baseUrl  =   "reports.aggregates.";


    /**
     * [$blankRfq description]
     *
     * @var [type]
     */
    protected $blankRfq;
    protected $upr;


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
    public function getDatatable(UnitPurchaseRequestRepository $model, Request $request)
    {
        return $model->getTransactionDayDatatable($request);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $model  =   UnitPurchaseRequestEloquent::select([
            \DB::raw('SUM(unit_purchase_requests.total_amount) as abc'),
            \DB::raw('SUM(purchase_orders.bid_amount) as bid_amount'),
            'unit_purchase_requests.procurement_office',
            'unit_purchase_requests.mode_of_procurement',
            'unit_purchase_requests.status',
            'mode_of_procurements.name',
        ])
        ->leftJoin('mode_of_procurements', 'mode_of_procurements.id', '=', 'unit_purchase_requests.mode_of_procurement')
        ->leftJoin('purchase_orders', 'purchase_orders.upr_id', '=', 'unit_purchase_requests.id')
        
        ->where('unit_purchase_requests.status', '<>', 'cancelled')
        ->groupBy([
            'unit_purchase_requests.procurement_office',
            'unit_purchase_requests.mode_of_procurement',
            'unit_purchase_requests.status',
            'mode_of_procurements.name'
        ])
        ->get();
        $arr = [];
        foreach($model as $data){
            if($data->name == null){
                $arr['Competitive Bidding'][]  = [ 'status' => $data->status, 'abc' => $data->abc, 'bid_amount' => $data->bid_amount];
            }elseif(strpos($data->name, 'Negotiated') !== false ){
                $arr['Negotiated Procurement'][]  = [ 'status' => $data->status, 'abc' => $data->abc, 'bid_amount' => $data->bid_amount];
            }elseif(strpos($data->name, 'Direct') !== false ){
                $arr['Direct Contracting'][]  = [ 'status' => $data->status, 'abc' => $data->abc, 'bid_amount' => $data->bid_amount];
            }elseif(strpos($data->name, 'Shop') !== false ){
                $arr['Shopping'][]  = [ 'status' => $data->status, 'abc' => $data->abc, 'bid_amount' => $data->bid_amount];
            }else{
                $arr[$data->name][]  = [ 'status' => $data->status, 'abc' => $data->abc, 'bid_amount' => $data->bid_amount];
            }
        }
        return $this->view('modules.reports.aggregates.index',[
            'resources' => $arr
        ]);
    } 

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Respons e
     */
    public function downloadPSR($search = null, UnitPurchaseRequestRepository $model, Request $request)
    {
        $model  =   UnitPurchaseRequestEloquent::select([
            \DB::raw('SUM(unit_purchase_requests.total_amount) as abc'),
            \DB::raw('SUM(purchase_orders.bid_amount) as bid_amount'),
            'unit_purchase_requests.procurement_office',
            'unit_purchase_requests.mode_of_procurement',
            'unit_purchase_requests.status',
            'mode_of_procurements.name',
        ])
        ->leftJoin('mode_of_procurements', 'mode_of_procurements.id', '=', 'unit_purchase_requests.mode_of_procurement')
        ->leftJoin('purchase_orders', 'purchase_orders.upr_id', '=', 'unit_purchase_requests.id')
        
        ->where('unit_purchase_requests.status', '<>', 'cancelled')
        ->groupBy([
            'unit_purchase_requests.procurement_office',
            'unit_purchase_requests.mode_of_procurement',
            'unit_purchase_requests.status',
            'mode_of_procurements.name'
        ])
        ->get();
        $arr = [];
        foreach($model as $data){
            if($data->name == null){
                $arr['Competitive Bidding'][]  = [ 'status' => $data->status, 'abc' => $data->abc, 'bid_amount' => $data->bid_amount];
            }elseif(strpos($data->name, 'Negotiated') !== false ){
                $arr['Negotiated Procurement'][]  = [ 'status' => $data->status, 'abc' => $data->abc, 'bid_amount' => $data->bid_amount];
            }elseif(strpos($data->name, 'Direct') !== false ){
                $arr['Direct Contracting'][]  = [ 'status' => $data->status, 'abc' => $data->abc, 'bid_amount' => $data->bid_amount];
            }elseif(strpos($data->name, 'Shop') !== false ){
                $arr['Shopping'][]  = [ 'status' => $data->status, 'abc' => $data->abc, 'bid_amount' => $data->bid_amount];
            }else{
                $arr[$data->name][]  = [ 'status' => $data->status, 'abc' => $data->abc, 'bid_amount' => $data->bid_amount];
            }
        }

        $this->downloadExcelPSR($arr, $request->date_from, $request->date_to, $request->type);

    }

 
    /**
     * [downloadExcel description]
     *
     * @param  [type] $result [description]
     * @return [type]         [description]
     */
    public function downloadExcelPSR($result, $from ,$to, $type)
    {
        Excel::create("AGGREGATE TOTAL Completed/Ongoing", function($excel)use ($result, $from ,$to, $type) {
            $excel->sheet('Page1', function($sheet)use ($result, $from ,$to, $type) {

                if($from != null)
                $from = \Carbon\Carbon::createFromFormat('!Y-m-d', $from)->format('d F Y');

                if($to != null)
                $to = \Carbon\Carbon::createFromFormat('!Y-m-d', $to)->format('d F Y');

                $count = 6;
                $sheet->row(1, ['AGGREGATE TOTAL Completed/Ongoing']);
                $sheet->row(3, ["(Period Covered: $from to $to)"]);
                $sheet->freezeFirstRow();
                $sheet->freezeFirstColumn();

                // $sheet->cells('A3:AM3', function($cells) {
                //     $cells->setAlignment('center');
                //     $cells->setBorder('thin', 'thin', 'thin', 'thin');
                // });

                // $sheet->cells('A2:AM2', function($cells) {
                //     $cells->setAlignment('center');
                //     $cells->setBorder('thin', 'thin', 'thin', 'thin');
                // });

                // $sheet->cells('A1:AM1', function($cells) {
                //     $cells->setAlignment('center');
                //     $cells->setBorder('thin', 'thin', 'thin', 'thin');
                // });

                // $sheet->mergeCells('A1:AM1');
                // $sheet->mergeCells('A2:AM2');
                // $sheet->mergeCells('A3:AM3');
                if($result != null)
                {

                    $sheet->row(6, function($row){
                        $row->setFontWeight("bold");
                    });

                    $sheet->row(6, [
                        'Mode of Procurement',
                        'Target ',
                        'Actual',
                        '%',
                        'Target ',
                        'Actual',
                        '%',
                    ]);

                }
                $arr = array();

                foreach($result as $key => $data)
                {
                    $countTarget = 0;
                    $countActual = 0;
                    $totalTarget = 0;
                    $totalActual = 0;
                
                    foreach($data as $dat) {

                        if($dat['status'] != 'completed'){
                            $countTarget = $countTarget + 1;
                        }
                        else{
                            $countActual = $countActual + 1;
                        }

                        $totalTarget = $totalTarget + $dat['abc'];
                        $totalActual = $totalActual + $dat['bid_amount'];
                    }

                    $newdata    =   [
                        $key,
                        $countTarget,
                        $countActual,
                        ($countActual/$countTarget)* 100,
                        $totalTarget,
                        $totalActual,
                        formatPrice ( ($totalActual/$totalTarget)* 100 )
                    ];

                    $count ++;
                    $sheet->row($count, $newdata);
                   

                }


            });

        })->export('xlsx');
    }


}
