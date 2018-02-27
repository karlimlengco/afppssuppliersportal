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

        $result     =   $model->getPcooPSRDatatable($request, $search);

        $this->downloadExcel($result, $request->date_from, $request->date_to, $request->type);

    }

    /**
     * [downloadExcel description]
     *
     * @param  [type] $result [description]
     * @return [type]         [description]
     */
    public function downloadExcel($result, $from ,$to, $type)
    {
        Excel::create("PSR Transactions", function($excel)use ($result, $from ,$to, $type) {
            $excel->sheet('Page1', function($sheet)use ($result, $from ,$to, $type) {
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
                if($result->last() != null)
                {
                    if($type != 'bidding')
                    {
                        $sheet->row(6, [
                            'PC/CO',
                            'UPR',
                            'ISPQ',
                            'PhilGeps Posting',
                            'Close RFQ',
                            'Canvassing',
                            'Prepare NOA',
                            'Approved NOA',
                            'Received NOA',
                            'PO/JO/WO Creation',
                            'Funding',
                            'MFO Funding/Obligation',
                            'PO COA Approval',
                            'Prepare NTP',
                            'Received NTP',
                            'Received Delivery',
                            'Complete COA Delivery',
                            'Technical Inspection',
                            'IAR Acceptance',
                            'DIIR Inspection Start',
                            'DIIR Inspection Close',
                            'Prepare Voucher',
                            'Preaudit Voucher /End',
                            'LDAP-DAP',
                        ]);
                    }
                    else
                    {
                        $sheet->row(6, [
                            'PC/CO',
                            'UPR',
                            'Document Acceptance',
                            'Pre Proc',
                            'ITB',
                            'PhilGeps Posting',
                            'Pre Bid Conference',
                            'SOBE',
                            'Post Qualification',
                            'Prepare NOA',
                            'Approved NOA',
                            'Received NOA',
                            'PO/Contract Creation',
                            'Funding',
                            'MFO Funding/Obligation',
                            'PO/Contract COA Approval',
                            'Prepare NTP',
                            'Received NTP',
                            'Received Delivery',
                            'Complete COA Delivery',
                            'Technical Inspection',
                            'IAR Acceptance',
                            'DIIR Inspection Start',
                            'DIIR Inspection Close',
                            'Prepare Voucher',
                            'Preaudit Voucher /End',
                            'LDAP-DAP',
                        ]);
                    }
                }

                foreach($result as $data)
                {

                    $count ++;
                    if($type != 'bidding')
                    {
                        $newdata    =   [
                            $data->unit_name,
                            $data->upr,
                            $data->ispq,
                            $data->philgeps,
                            $data->rfq,
                            $data->canvass,
                            $data->noa,
                            $data->noaa,
                            $data->noar,
                            $data->po,
                            $data->po_mfo_received,
                            $data->po_pcco_received,
                            $data->po_coa_approved,
                            $data->ntp,
                            $data->ntpa,
                            $data->delivery,
                            $data->date_delivered_to_coa,
                            $data->tiac,
                            $data->coa_inspection,
                            $data->diir_start,
                            $data->diir_close,
                            $data->voucher,
                            $data->end_process,
                            $data->ldad,
                        ];
                    }
                    else
                    {

                        $newdata    =   [
                            $data->unit_name,
                            $data->upr,
                            $data->doc,
                            $data->pre_proc,
                            $data->itb,
                            $data->philgeps,
                            $data->prebid,
                            $data->bidop,
                            $data->pq,
                            $data->noa,
                            $data->noaa,
                            $data->noar,
                            $data->po,
                            $data->po_mfo_received,
                            $data->po_pcco_received,
                            $data->po_coa_approved,
                            $data->ntp,
                            $data->ntpa,
                            $data->delivery,
                            $data->date_delivered_to_coa,
                            $data->tiac,
                            $data->coa_inspection,
                            $data->diir_start,
                            $data->diir_close,
                            $data->voucher,
                            $data->end_process,
                            $data->ldad,
                        ];
                    }
                    $sheet->row($count, $newdata);

                }

            });

        })->export('xlsx');
    }


}
