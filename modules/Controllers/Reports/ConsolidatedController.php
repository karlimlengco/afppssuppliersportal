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

class ConsolidatedController extends Controller
{

    /**
     * [Base Route of Controller]
     *
     * @var string
     */
    protected $baseUrl  =   "reports.consolidated.";


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
            \DB::raw('COUNT(unit_purchase_requests.total_amount) as countAbc'),
            \DB::raw('SUM(purchase_orders.bid_amount) as bid_amount'),
            'mode_of_procurements.name',
            \DB::raw('COUNT(notice_of_awards.awarded_date) as award_count'),
        ])
        ->leftJoin('mode_of_procurements', 'mode_of_procurements.id', '=', 'unit_purchase_requests.mode_of_procurement')
        ->leftJoin('purchase_orders', 'purchase_orders.upr_id', '=', 'unit_purchase_requests.id')
        ->leftJoin('notice_of_awards', 'notice_of_awards.upr_id', '=', 'unit_purchase_requests.id')
        
        ->where('unit_purchase_requests.status', '<>', 'cancelled')
        ->groupBy([
            'mode_of_procurements.name'
        ])
        ->get();
        $arr = [];
        foreach($model as $data){
            if($data->name == null){
                $arr['Competitive Bidding'][]  = [ 'award_count' => $data->award_count, 'abc_count' => $data->countAbc, 'awarded' => $data->awarded_date, 'name' => $data->name,  'status' => $data->status, 'abc' => $data->abc, 'bid_amount' => $data->bid_amount];
            }elseif(strpos($data->name, 'Negotiated') !== false ){
                $arr['Negotiated Procurement'][]  = [ 'award_count' => $data->award_count, 'abc_count' => $data->countAbc, 'awarded' => $data->awarded_date,'name' => $data->name,  'status' => $data->status, 'abc' => $data->abc, 'bid_amount' => $data->bid_amount];
            }elseif(strpos($data->name, 'Direct') !== false ){
                $arr['Direct Contracting'][]  = [ 'award_count' => $data->award_count, 'abc_count' => $data->countAbc, 'awarded' => $data->awarded_date,'name' => $data->name,  'status' => $data->status, 'abc' => $data->abc, 'bid_amount' => $data->bid_amount];
            }elseif(strpos($data->name, 'Shop') !== false ){
                $arr['Shopping'][]  = [ 'award_count' => $data->award_count, 'abc_count' => $data->countAbc, 'awarded' => $data->awarded_date,'name' => $data->name,  'status' => $data->status, 'abc' => $data->abc, 'bid_amount' => $data->bid_amount];
            }else{
                $arr[$data->name][]  = [ 'award_count' => $data->award_count, 'abc_count' => $data->countAbc, 'awarded' => $data->awarded_date,'name' => $data->name,  'status' => $data->status, 'abc' => $data->abc, 'bid_amount' => $data->bid_amount];
            }
        }
        return $this->view('modules.reports.consolidated.index',[
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
            \DB::raw('COUNT(unit_purchase_requests.total_amount) as countAbc'),
            \DB::raw('SUM(purchase_orders.bid_amount) as bid_amount'),
            'unit_purchase_requests.procurement_office',
            'unit_purchase_requests.mode_of_procurement',
            'mode_of_procurements.name',
            \DB::raw('COUNT(notice_of_awards.awarded_date) as award_count'),
        ])
        ->leftJoin('mode_of_procurements', 'mode_of_procurements.id', '=', 'unit_purchase_requests.mode_of_procurement')
        ->leftJoin('purchase_orders', 'purchase_orders.upr_id', '=', 'unit_purchase_requests.id')
        ->leftJoin('notice_of_awards', 'notice_of_awards.upr_id', '=', 'unit_purchase_requests.id')
        
        ->where('unit_purchase_requests.status', '<>', 'cancelled')
        ->groupBy([
            'unit_purchase_requests.procurement_office',
            'unit_purchase_requests.mode_of_procurement',
            'mode_of_procurements.name'
        ])
        ->get();
        $arr = [];
        foreach($model as $data){
            if($data->name == null){
                $arr['Competitive Bidding'][]  = [ 'award_count' => $data->award_count, 'abc_count' => $data->countAbc, 'awarded' => $data->awarded_date, 'name' => $data->name,  'status' => $data->status, 'abc' => $data->abc, 'bid_amount' => $data->bid_amount];
            }elseif(strpos($data->name, 'Negotiated') !== false ){
                $arr['Negotiated Procurement'][]  = [ 'award_count' => $data->award_count, 'abc_count' => $data->countAbc, 'awarded' => $data->awarded_date,'name' => $data->name,  'status' => $data->status, 'abc' => $data->abc, 'bid_amount' => $data->bid_amount];
            }elseif(strpos($data->name, 'Direct') !== false ){
                $arr['Direct Contracting'][]  = [ 'award_count' => $data->award_count, 'abc_count' => $data->countAbc, 'awarded' => $data->awarded_date,'name' => $data->name,  'status' => $data->status, 'abc' => $data->abc, 'bid_amount' => $data->bid_amount];
            }elseif(strpos($data->name, 'Shop') !== false ){
                $arr['Shopping'][]  = [ 'award_count' => $data->award_count, 'abc_count' => $data->countAbc, 'awarded' => $data->awarded_date,'name' => $data->name,  'status' => $data->status, 'abc' => $data->abc, 'bid_amount' => $data->bid_amount];
            }else{
                $arr[$data->name][]  = [ 'award_count' => $data->award_count, 'abc_count' => $data->countAbc, 'awarded' => $data->awarded_date,'name' => $data->name,  'status' => $data->status, 'abc' => $data->abc, 'bid_amount' => $data->bid_amount];
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
        Excel::create("Consolidated Procurement Monitoring Report", function($excel)use ($result, $from ,$to, $type) {
            $excel->sheet('Page1', function($sheet)use ($result, $from ,$to, $type) {

                if($from != null)
                $from = \Carbon\Carbon::createFromFormat('!Y-m-d', $from)->format('d F Y');

                if($to != null)
                $to = \Carbon\Carbon::createFromFormat('!Y-m-d', $to)->format('d F Y');

                $count = 6;
                $sheet->row(1, ['Consolidated Procurement Monitoring Report']);
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
                        '',
                        'Total Amount of Approved APP ',
                        'Total No. of Procurement Activities',
                        'No. of Contract Awarded',
                        'Total Amount of Contract Awarded',
                    ]);

                }
                $arr = array();

                foreach($result as $key => $data)
                {
                    $totalAmount = 0; 
                    $totalApproved = 0;
                    $awardedCount = 0;
                    $awardedAmount = 0;
                    $totalCount = 0;
                    $name = 0;
                
                    $newdata    =   [
                        $key,
                    ];

                    $count ++;
                    $sheet->row($count, $newdata);

                    foreach($data as $dat){
                        $amount = 0;
                        $totalAmount = $totalAmount + $dat['abc'];
                        $totalApproved = $totalApproved + $dat['abc'];
                        $totalCount = $totalCount + $dat['abc_count'];
                        $awardedCount = $awardedCount + $dat['award_count'];
                        if($dat['award_count'] != 0){
                            $awardedAmount = $awardedAmount + $dat['abc'];
                            $amount = $dat['abc'];
                        }
                        if($dat['name'] == null){

                            $name = 'Public Bidding';
                        }
                        else{

                            $name = $dat['name'];
                        }

                        $newdata    =   [
                            $name,
                            formatPrice($dat['abc']),
                            $dat['abc_count'],
                            $dat['award_count'],
                            formatPrice($amount)

                        ];
    
                        $count ++;
                        $sheet->row($count, $newdata);
                    }

                    $newdata    =   [
                        $name,
                        formatPrice($totalApproved),
                        $totalCount,
                        $awardedCount,
                        formatPrice($awardedAmount),
                    ];

                    $count ++;
                    $sheet->row($count, $newdata);
                   

                }


            });

        })->export('xlsx');
    }


}
