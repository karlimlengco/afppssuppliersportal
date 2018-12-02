<?php

namespace Revlv\Controllers\Reports;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Revlv\Procurements\UnitPurchaseRequests\UnitPurchaseRequestEloquent;
use Auth;
use Excel;

use \Revlv\Procurements\BlankRequestForQuotation\BlankRFQRepository;
use \Revlv\Procurements\UnitPurchaseRequests\UnitPurchaseRequestRepository;

class ComplianceController extends Controller
{

    /**
     * [Base Route of Controller]
     *
     * @var string
     */
    protected $baseUrl  =   "reports.psr-transactions.";


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
        // return $model->getPSRDatatable($request);
        return $model->getPcooPSRDatatable($request);
    }

    public function getPSRDatatable(UnitPurchaseRequestRepository $model, Request $request)
    {
        return $model->getProcurementDatatable($request);
    }

    public function PccoPSR(UnitPurchaseRequestRepository $model, Request $request){
      return $model->getPcooPSRDatatable($request);
    }

    public function getPccoPsrItem($type, $pcco, UnitPurchaseRequestRepository $model, Request $request){
        return $model->getPcooItemPSRDatatable($request);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->view('modules.reports.level-of-compliance.index');
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
    public function store(UnitPurchaseRequestEloquent $model, Request $request)
    {
        $this->validate($request, [
            'date_from' => 'required',
            'date_to' => 'required',
        ]);

        $result     =   $model->select([
                        'units',
                        // 'mode_of_procurements.name as mode',
                        // 'mode_of_procurement',
                        'catered_units.short_code',
                        'procurement_centers.programs',
                        'procurement_centers.name',
                        \DB::raw('SUM(total_amount)'),
                        \DB::raw('(SELECT SUM(total_amount) from unit_purchase_requests as up where up.units = unit_purchase_requests.units and up.mode_of_procurement = "0116f977-6e17-32ae-8c77-3e05b6101769") as direct '),
                    ])
                    ->leftJoin('catered_units', 'catered_units.id', '=', 'unit_purchase_requests.units')
                    ->leftJoin('procurement_centers', 'procurement_centers.id', '=', 'catered_units.pcco_id')
                    // ->leftJoin('mode_of_procurements', 'mode_of_procurements.id', '=', 'unit_purchase_requests.mode_of_procurement')
                    ->groupBy(
                        [
                            'units',
                        // 'mode_of_procurement',
                            // 'mode_of_procurements.name',
                            'catered_units.short_code',
                            'procurement_centers.programs',
                            'procurement_centers.name',
                        ]
                    )
                    ->get();
        foreach($result->toArray()  as $data){
            $programs[$data['programs']][] = $data;
        }

        // dd($programs);
        $this->downloadExcel($programs, $request->date_from, $request->date_to);
    }


    /**
     * [downloadExcel description]
     *
     * @param  [type] $result [description]
     * @return [type]         [description]
     */
    public function downloadExcel($result, $from, $to)
    {
        Excel::create("Level of Compliance", function($excel)use ($result, $from, $to) {
            $excel->sheet('Page1', function($sheet)use ($result, $from, $to) {
                if($from != null)
                $from = \Carbon\Carbon::createFromFormat('!Y-m-d', $from)->format('d F Y');

                if($to != null)
                $to = \Carbon\Carbon::createFromFormat('!Y-m-d', $to)->format('d F Y');

                $count = 6;
                $sheet->row(1, ['AFP Vision 2028: A World-class Armed Forces, Source of National Pride']);
                $sheet->row(2, []);
                $sheet->row(3, ["(Period Covered: $from to $to)"]);

                $sheet->cells('A3:AA3', function($cells) {
                    $cells->setAlignment('center');
                    $cells->setBorder('thin', 'thin', 'thin', 'thin');
                });

                $sheet->cells('A2:AA2', function($cells) {
                    $cells->setAlignment('center');
                    $cells->setBorder('thin', 'thin', 'thin', 'thin');
                });

                $sheet->cells('A1:AA1', function($cells) {
                    $cells->setAlignment('center');
                    $cells->setBorder('thin', 'thin', 'thin', 'thin');
                });

                $sheet->mergeCells('A1:AF1');
                $sheet->mergeCells('A2:AF2');
                $sheet->mergeCells('A3:AF3');

                $sheet->row(6, [
                    'NR',
                    'Catered Units'
                ]);

                foreach($result as $key => $data)
                {
                    $count++;
                    $newdata    =   [
                        null,
                        'Program '.$key,
                    ];

                    $sheet->row($count, $newdata);
                    foreach($data as $k => $v){

                        $count++;
                        $newdata    =   [
                            $k+1,
                            $v['short_code'],
                        ];
                    $sheet->row($count, $newdata);
                    }
                }

            });

        })->export('xlsx');
    }


}
