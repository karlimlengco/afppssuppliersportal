<?php

namespace Revlv\Controllers\Reports;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Carbon;
use Excel;

use \Revlv\Procurements\BlankRequestForQuotation\BlankRFQRepository;
use \Revlv\Procurements\UnitPurchaseRequests\UnitPurchaseRequestRepository;

class TransactionDayController extends Controller
{

    /**
     * [Base Route of Controller]
     *
     * @var string
     */
    protected $baseUrl  =   "reports.transaction-days.";


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
    public function getDatatable(UnitPurchaseRequestRepository $model)
    {
        return $model->getTransactionDayDatatable();
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->view('modules.reports.transaction-days.index');
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
    public function download($search = null, UnitPurchaseRequestRepository $model)
    {
        $result     =   $model->getTransactionDay($search);

        $this->downloadExcel($result);

    }

    /**
     * [downloadExcel description]
     *
     * @param  [type] $result [description]
     * @return [type]         [description]
     */
    public function downloadExcel($result)
    {
        Excel::create("Transaction Days", function($excel)use ($result) {
            $excel->sheet('Page1', function($sheet)use ($result) {

                $count = 6;
                $sheet->row(1, ['TRANSACTION DAYS']);
                $sheet->row(2, ["ALTERNATIVE METHOD OF PROCUREMENT "]);
                $sheet->row(3, ['(Period Covered: 01 January to 17 March 2017)']);

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
                    'UPR',
                    'Blank RFQ',
                    'PhilGeps Posting',
                    'ISPQ',
                    'Canvass',
                    'NOA',
                    'NOA Approved',
                    'NOA Accepted',
                    'PO Create',
                    'Funding PO',
                    'Issuance of Certificate',
                    'PO COA Approval',
                    'NTP',
                    'NTPA',
                    'Delivery',
                    'Delivery To COA',
                    'Conduct Inspection',
                    'Conduct Inspection of Delivered Items',
                    'Prepare Certificate of Inspection',
                    'Preparation of Voucher',
                    'Release Payment',
                    'Received Payment',
                ]);

                foreach($result as $data)
                {
                    $d_rfq_completed_at = 0;
                    if($data->rfq_completed_at)
                    {
                        $dt                 = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $data->rfq_completed_at);
                        $upr_create         = Carbon\Carbon::createFromFormat('Y-m-d', $data->date_prepared);
                        $d_rfq_completed_at  = $dt->diffInDays($upr_create);
                    }

                    $d_pp_completed_at = 0;
                    if($data->pp_completed_at != null && $data->rfq_completed_at != null)
                    {
                        $dt                 = Carbon\Carbon::createFromFormat('Y-m-d', $data->pp_completed_at);
                        $upr_create         = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $data->rfq_completed_at);
                        $d_pp_completed_at  = $dt->diffInDays($upr_create);
                    }

                    $dispq_transaction_date = 0;
                    if($data->ispq_transaction_date && $data->rfq_completed_at != null)
                    {
                        $dt                         = Carbon\Carbon::createFromFormat('Y-m-d', $data->ispq_transaction_date);
                        $upr_create                 = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $data->rfq_completed_at);
                        $dispq_transaction_date     = $dt->diffInDays($upr_create);
                    }


                    $d_canvass_start_date = 0;
                    if($data->canvass_start_date && $data->ispq_transaction_date)
                    {
                        $dt         = Carbon\Carbon::createFromFormat('Y-m-d', $data->canvass_start_date);
                        $upr_create = Carbon\Carbon::createFromFormat('Y-m-d', $data->ispq_transaction_date);
                        $d_canvass_start_date       = $dt->diffInDays($upr_create);
                    }

                    $d_noa_award_date = 0;
                    if($data->noa_award_date)
                    {
                        $dt         = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $data->noa_award_date);
                        $upr_create = Carbon\Carbon::createFromFormat('Y-m-d', $data->canvass_start_date);
                        $d_noa_award_date       = $dt->diffInDays($upr_create);
                    }

                    $d_noa_approved_date = 0;
                    if($data->noa_approved_date)
                    {
                        $dt         = Carbon\Carbon::createFromFormat('Y-m-d', $data->noa_approved_date);
                        $upr_create = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $data->noa_award_date);
                        $d_noa_approved_date       = $dt->diffInDays($upr_create);
                    }

                    $d_noa_award_accepted_date = 0;
                    if($data->noa_award_accepted_date)
                    {
                        $dt         = Carbon\Carbon::createFromFormat('Y-m-d', $data->noa_award_accepted_date);
                        $upr_create = Carbon\Carbon::createFromFormat('Y-m-d', $data->noa_approved_date);
                        $d_noa_award_accepted_date       = $dt->diffInDays($upr_create);
                    }

                    $d_po_create_date = 0;
                    if($data->po_create_date)
                    {
                        $dt         = Carbon\Carbon::createFromFormat('Y-m-d', $data->po_create_date);
                        $upr_create = Carbon\Carbon::createFromFormat('Y-m-d', $data->noa_award_accepted_date);
                        $d_po_create_date       = $dt->diffInDays($upr_create);
                    }

                    $d_mfo_received_date = 0;
                    if($data->mfo_received_date)
                    {
                        $dt         = Carbon\Carbon::createFromFormat('Y-m-d', $data->mfo_received_date);
                        $upr_create = Carbon\Carbon::createFromFormat('Y-m-d', $data->po_create_date);
                        $d_mfo_received_date       = $dt->diffInDays($upr_create);
                    }

                    $d_funding_released_date = 0;
                    if($data->funding_received_date)
                    {
                        $dt         = Carbon\Carbon::createFromFormat('Y-m-d', $data->funding_received_date);
                        $upr_create = Carbon\Carbon::createFromFormat('Y-m-d', $data->mfo_received_date);
                        $d_funding_released_date       = $dt->diffInDays($upr_create);
                    }

                    $d_coa_approved_date = 0;
                    if($data->coa_approved_date)
                    {
                        $dt         = Carbon\Carbon::createFromFormat('Y-m-d', $data->coa_approved_date);
                        $upr_create = Carbon\Carbon::createFromFormat('Y-m-d', $data->funding_received_date);
                        $d_coa_approved_date       = $dt->diffInDays($upr_create);
                    }

                    $d_ntp_date = 0;
                    if($data->ntp_date)
                    {
                        $dt         = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $data->ntp_date);
                        $upr_create = Carbon\Carbon::createFromFormat('Y-m-d', $data->coa_approved_date);
                        $d_ntp_date       = $dt->diffInDays($upr_create);
                    }

                    $d_ntp_award_date = 0;
                    if($data->ntp_award_date)
                    {
                        $dt         = Carbon\Carbon::createFromFormat('Y-m-d', $data->ntp_award_date);
                        $upr_create = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $data->ntp_date);
                        $d_ntp_award_date       = $dt->diffInDays($upr_create);
                    }

                    $d_delivery_date = 0;
                    if($data->delivery_date)
                    {
                        $dt         = Carbon\Carbon::createFromFormat('Y-m-d', $data->delivery_date);
                        $upr_create = Carbon\Carbon::createFromFormat('Y-m-d', $data->dr_date);
                        $d_delivery_date       = $dt->diffInDays($upr_create);
                    }

                    $d_dr_coa_date = 0;
                    if($data->dr_coa_date)
                    {
                        $dt         = Carbon\Carbon::createFromFormat('Y-m-d', $data->dr_coa_date);
                        $upr_create = Carbon\Carbon::createFromFormat('Y-m-d', $data->delivery_date);
                        $d_dr_coa_date       = $dt->diffInDays($upr_create);
                    }

                    $d_dr_inspection = 0;
                    if($data->dr_inspection)
                    {
                        $dt         = Carbon\Carbon::createFromFormat('Y-m-d', $data->dr_inspection);
                        $upr_create = Carbon\Carbon::createFromFormat('Y-m-d', $data->dr_coa_date);
                        $d_dr_inspection       = $dt->diffInDays($upr_create);
                    }

                    $d_di_start = 0;
                    if($data->di_start)
                    {
                        $dt         = Carbon\Carbon::createFromFormat('Y-m-d', $data->di_start);
                        $upr_create = Carbon\Carbon::createFromFormat('Y-m-d', $data->dr_inspection);
                        $d_di_start       = $dt->diffInDays($upr_create);
                    }

                    $d_di_close = 0;
                    if($data->di_close)
                    {
                        $dt         = Carbon\Carbon::createFromFormat('Y-m-d', $data->di_close);
                        $upr_create = Carbon\Carbon::createFromFormat('Y-m-d', $data->di_start);
                        $d_di_close       = $dt->diffInDays($upr_create);
                    }

                    $d_vou_start = 0;
                    if($data->vou_start)
                    {
                        $dt         = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $data->vou_start);
                        $upr_create = Carbon\Carbon::createFromFormat('Y-m-d', $data->di_close);
                        $d_vou_start       = $dt->diffInDays($upr_create);
                    }

                    $d_vou_release = 0;
                    if($data->vou_release)
                    {
                        $dt         = Carbon\Carbon::createFromFormat('Y-m-d', $data->vou_release);
                        $upr_create = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $data->vou_start);
                        $d_vou_release       = $dt->diffInDays($upr_create);
                    }

                    $d_vou_received = 0;
                    if($data->vou_received)
                    {
                        $dt         = Carbon\Carbon::createFromFormat('Y-m-d', $data->vou_received);
                        $upr_create = Carbon\Carbon::createFromFormat('Y-m-d', $data->vou_release);
                        $days       = $dt->diffInDays($upr_create);
                    }

                    $count ++;
                    $newdata    =   [
                        $data->upr_number,
                        $d_rfq_completed_at,
                        $d_pp_completed_at,
                        $dispq_transaction_date,
                        $d_canvass_start_date,
                        $d_noa_award_date,
                        $d_noa_approved_date,
                        $d_noa_award_accepted_date,
                        $d_po_create_date,
                        $d_mfo_received_date,
                        $d_funding_released_date,
                        $d_coa_approved_date,
                        $d_ntp_date,
                        $d_ntp_award_date,
                        $d_delivery_date,
                        $d_dr_coa_date,
                        $d_dr_inspection,
                        $d_di_start,
                        $d_di_close,
                        $d_vou_start,
                        $d_vou_release,
                        $d_vou_received,
                    ];
                    $sheet->row($count, $newdata);

                }

            });

        })->export('xlsx');
    }


}
