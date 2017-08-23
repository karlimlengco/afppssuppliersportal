<?php

namespace Revlv\Controllers\Reports;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Excel;

use \Revlv\Procurements\BlankRequestForQuotation\BlankRFQRepository;
use \Revlv\Procurements\UnitPurchaseRequests\UnitPurchaseRequestRepository;

class PSRController extends Controller
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
        return $model->getPSRDatatable($request);
    }

    public function getPSRDatatable(UnitPurchaseRequestRepository $model, Request $request)
    {
        return $model->getProcurementDatatable($request);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->view('modules.reports.psr-transactions.index');
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
    public function download($search = null, UnitPurchaseRequestRepository $model, Request $request)
    {

        $result     =   $model->getPSR($request, $search);

        $this->downloadExcel($result, $request->date_from, $request->date_to);

    }

    /**
     * [downloadExcel description]
     *
     * @param  [type] $result [description]
     * @return [type]         [description]
     */
    public function downloadExcel($result, $from ,$to)
    {
        Excel::create("PSR Transactions", function($excel)use ($result, $from ,$to) {
            $excel->sheet('Page1', function($sheet)use ($result, $from ,$to) {
                if($from != null)
                $from = \Carbon\Carbon::createFromFormat('!Y-m-d', $from)->format('d F Y');

                if($to != null)
                $to = \Carbon\Carbon::createFromFormat('!Y-m-d', $to)->format('d F Y');

                $count = 6;
                $sheet->row(1, ['TRANSACTION SUMMARY OF PROCUREMENT CENTER']);
                $sheet->row(2, ["ALTERNATIVE METHOD OF PROCUREMENT"]);
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

                $sheet->mergeCells('A1:AA1');
                $sheet->mergeCells('A2:AA2');
                $sheet->mergeCells('A3:AA3');

                $sheet->row(6, [
                    'PC/CO',
                    'UPR',
                    'RFQ',
                    'RFQ Closed',
                    'PhilGeps',
                    'ISPQ',
                    'Canvass',
                    'NOA',
                    'NOAA',
                    'PO',
                    'MFO OB',
                    'ACCTG OB',
                    'MFO Received',
                    'ACCTG Received',
                    'COA Approved',
                    'NTP',
                    'NTPA',
                    'NOD',
                    'Delivery',
                    'TIAC',
                    'COA Delivery',
                    'DIIR',
                    'Voucher',
                    'End'
                ]);

                foreach($result as $data)
                {
                    $count ++;
                    $newdata    =   [
                        $data->unit_name,
                        $data->upr,
                        $data->rfq,
                        $data->rfq_close,
                        $data->philgeps,
                        $data->ispq,
                        $data->canvass,
                        $data->noa,
                        $data->noaa,
                        $data->po,
                        $data->po_mfo_released,
                        $data->po_mfo_received,
                        $data->po_pcco_released,
                        $data->po_pcco_received,
                        $data->po_coa_approved,
                        $data->ntp,
                        $data->ntpa,
                        $data->nod,
                        $data->delivery,
                        $data->tiac,
                        $data->coa_inspection,
                        $data->diir,
                        $data->voucher,
                        $data->end_process,
                    ];
                    $sheet->row($count, $newdata);

                }

            });

        })->export('xlsx');
    }


}
