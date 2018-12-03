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

class RecapitulationController extends Controller
{

    /**
     * [Base Route of Controller]
     *
     * @var string
     */
    protected $baseUrl  =   "reports.recapitulations.";


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
            'mode_of_procurements.name',
            'procurement_centers.short_code'
        ])
        ->leftJoin('mode_of_procurements', 'mode_of_procurements.id', '=', 'unit_purchase_requests.mode_of_procurement')
        ->leftJoin('procurement_centers', 'procurement_centers.id', '=', 'unit_purchase_requests.procurement_office')
        ->leftJoin('purchase_orders', 'purchase_orders.upr_id', '=', 'unit_purchase_requests.id')
        
        ->where('unit_purchase_requests.status', 'completed')
        ->groupBy([
            'unit_purchase_requests.procurement_office',
            'unit_purchase_requests.mode_of_procurement',
            'mode_of_procurements.name',
            'procurement_centers.short_code'
        ])
        ->orderBy('procurement_centers.short_code')
        ->get();
        $arr = [];
        foreach($model as $data){
            $arr[$data->short_code][]  = ['name' => $data->name, 'abc' => $data->abc, 'bid_amount' => $data->bid_amount];
        }
        return $this->view('modules.reports.recapitulations.index',[
            'resources' => $arr
        ]);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
            'mode_of_procurements.name',
            'procurement_centers.short_code'
        ])
        ->leftJoin('mode_of_procurements', 'mode_of_procurements.id', '=', 'unit_purchase_requests.mode_of_procurement')
        ->leftJoin('procurement_centers', 'procurement_centers.id', '=', 'unit_purchase_requests.procurement_office')
        ->leftJoin('purchase_orders', 'purchase_orders.upr_id', '=', 'unit_purchase_requests.id')
        
        ->where('unit_purchase_requests.status', 'completed')
        ->groupBy([
            'unit_purchase_requests.procurement_office',
            'unit_purchase_requests.mode_of_procurement',
            'mode_of_procurements.name',
            'procurement_centers.short_code'
        ])
        ->orderBy('procurement_centers.short_code')
        ->get();
        $arr = [];
        foreach($model as $data){
            $arr[$data->short_code][]  = ['name' => $data->name, 'abc' => $data->abc, 'bid_amount' => $data->bid_amount];
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
        Excel::create("Recapitulation of Monthly PMR", function($excel)use ($result, $from ,$to, $type) {
            $excel->sheet('Page1', function($sheet)use ($result, $from ,$to, $type) {

                if($from != null)
                $from = \Carbon\Carbon::createFromFormat('!Y-m-d', $from)->format('d F Y');

                if($to != null)
                $to = \Carbon\Carbon::createFromFormat('!Y-m-d', $to)->format('d F Y');

                $count = 6;
                $sheet->row(1, ['Recapitulation of Monthly PMR']);
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
                        'UNITS',
                        'SHOPPING',
                        'Negotiated',
                        'Public Bidding',
                        'Direct Contracting',
                        'Procurement Thru PS DBM',
                        'Procurement Thru Other Agencies',
                        'Procurement Residual',
                        'Number of POs/WOs Contracts',
                    ]);

                }
                $arr = array();

                foreach($result as $key => $data)
                {
                    $totalabc = 0;
                    $totalbid = 0;
                    $shopping = 0;
                    $shoppingbid = 0;
                    $negoabc = 0;
                    $negobid = 0;
                    $pubabc = 0;
                    $pubbid = 0;
                    $drabc = 0;
                    $drbid = 0;
                    $totalpo = 0;
                    foreach($data as $dat){
                        if(strpos($dat['name'], 'Shopping') !== false ){
                            $shopping =  $shopping + $dat['abc'];
                            $shoppingbid = $shoppingbid +  $dat['bid_amount'];
                        }
                        if(strpos($dat['name'], 'Negotiated') !== false){
                            $negoabc = $negoabc + $dat['abc'];
                            $negobid = $negobid + $dat['bid_amount'];
                        }
                        if($dat['name'] == null){
                            $pubabc = $pubabc + $dat['abc'];
                            $pubbid = $pubbid + $dat['bid_amount'];
                        }
                        if(strpos($dat['name'], 'Direct') !== false){
                            $drabc = $drabc + $dat['abc'];
                            $drbid = $drbid + $dat['bid_amount'];
                        }
                        $totalabc =  $totalabc + $dat['abc'];
                        $totalbid =  $totalbid + $dat['bid_amount'];
                        $totalpo  = $totalpo +1;    
                    }
                    $newdata    =   [
                        $key,
                        formatPrice($shopping),
                        formatPrice($negoabc),
                        formatPrice($pubabc),
                        formatPrice($drabc),
                        '',
                        '',
                        formatPrice($totalabc - $totalbid),
                        $totalpo
                    ];

                    $count ++;
                    $sheet->row($count, $newdata);
                    $newdata    =   [
                        '',
                        formatPrice($shoppingbid),
                        formatPrice($negobid),
                        formatPrice($pubbid),
                        formatPrice($drbid),
                    ];
                    $count ++;
                    $sheet->row($count, $newdata);

                }


            });

        })->export('xlsx');
    }


}
