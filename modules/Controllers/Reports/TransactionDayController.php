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
    public function download($search = null, UnitPurchaseRequestRepository $model, Request $request)
    {
        $result     =   $model->getTransactionDay($request, $search);

        $this->downloadExcel($result, $request->date_from, $request->date_to);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Respons e
     */
    public function downloadPSR($search = null, UnitPurchaseRequestRepository $model, Request $request)
    {
        $result     =   $model->getTransactionDay($request, $search);

        $this->downloadExcelPSR($result, $request->date_from, $request->date_to);

    }

    /**
     * [downloadExcel description]
     *
     * @param  [type] $result [description]
     * @return [type]         [description]
     */
    public function downloadExcel($result, $from ,$to)
    {
        Excel::create("Transaction Days", function($excel)use ($result, $from ,$to) {
            $excel->sheet('Page1', function($sheet)use ($result, $from ,$to) {

                if($from != null)
                $from = \Carbon\Carbon::createFromFormat('!Y-m-d', $from)->format('d F Y');

                if($to != null)
                $to = \Carbon\Carbon::createFromFormat('!Y-m-d', $to)->format('d F Y');

                $count = 6;
                $sheet->row(1, ['TRANSACTION DAYS']);
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

                if($result->last()->mode_of_procurement != 'public_bidding')
                {
                    $sheet->row(2, ["ALTERNATIVE METHOD OF PROCUREMENT "]);
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
                        // 'Received Payment',
                    ]);
                }
                else
                {
                    $sheet->row(2, ["PUBLIC BIDDING "]);

                    $sheet->row(6, [
                        'UPR',
                        'Document Acceptance',
                        'Invitation To Bid',
                        'Philgeps',
                        'Pre Bid',
                        'SOBE',
                        'Post Qual',
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
                        // 'Received Payment',
                    ]);
                }

                foreach($result as $data)
                {

                    if($data->mode_of_procurement != 'public_bidding')
                    {

                        $d_rfq_completed_at = 0;
                        if($data->rfq_completed_at != null)
                        {
                            $dt                 = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $data->rfq_completed_at);
                            $upr_create         = Carbon\Carbon::createFromFormat('!Y-m-d', $data->pp_completed_at);
                            $d_rfq_completed_at  = $dt->diffInDays($upr_create);
                        }

                        $d_pp_completed_at = 0;
                        if($data->pp_completed_at != null && $data->rfq_completed_at != null)
                        {
                            $dt                 = Carbon\Carbon::createFromFormat('!Y-m-d', $data->pp_completed_at);
                            $upr_create         = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $data->rfq_completed_at);
                            $d_pp_completed_at  = $dt->diffInDays($upr_create);
                        }

                        $dispq_transaction_date = 0;
                        if($data->ispq_transaction_date )
                        {
                            $upr_create                 = $data->date_prepared;
                            $ispq_transaction_date    =   \Carbon\Carbon::createFromFormat('!Y-m-d', $data->ispq_transaction_date);

                            $dispq_transaction_date = $ispq_transaction_date->diffInDaysFiltered(function (\Carbon\Carbon $date) {return $date->isWeekday(); }, $upr_create );
                        }


                        $d_canvass_start_date = 0;
                        if($data->canvass_start_date && $data->ispq_transaction_date)
                        {
                            $dt         = Carbon\Carbon::createFromFormat('!Y-m-d', $data->canvass_start_date);
                            $upr_create = Carbon\Carbon::createFromFormat('!Y-m-d', $data->ispq_transaction_date);
                            $d_canvass_start_date       = $dt->diffInDays($upr_create);
                        }
                    }
                    else
                    {
                        $d_docs = 0;
                        if($data->doc_days != 0)
                        {
                            $d_docs = $data->doc_days;
                        }

                        $d_itb_days = 0;
                        if($data->itb_days != 0)
                        {
                            $d_itb_days = $data->itb_days;
                        }

                        $d_pp_completed_at = 0;
                        if($data->pp_completed_at != null && $data->rfq_completed_at != null)
                        {
                            $dt                 = Carbon\Carbon::createFromFormat('!Y-m-d', $data->pp_completed_at);
                            $upr_create         = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $data->rfq_completed_at);
                            $d_pp_completed_at  = $dt->diffInDays($upr_create);
                        }

                        $d_prebid_days = 0;
                        if($data->prebid_days != 0)
                        {
                            $d_prebid_days = $data->prebid_days;
                        }

                        $d_bid_days = 0;
                        if($data->bid_days != 0)
                        {
                            $d_bid_days = $data->bid_days;
                        }

                        $d_pq_days = 0;
                        if($data->pq_days != 0)
                        {
                            $d_pq_days = $data->pq_days;
                        }

                    }

                    $d_noa_award_date = 0;
                    if($data->noa_award_date && $data->canvass_start_date)
                    {
                        $dt         = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $data->noa_award_date);
                        $upr_create = Carbon\Carbon::createFromFormat('!Y-m-d', $data->canvass_start_date);
                        $d_noa_award_date       = $dt->diffInDays($upr_create);
                    }

                    $d_noa_approved_date = 0;
                    if($data->noa_approved_date)
                    {
                        $dt         = Carbon\Carbon::createFromFormat('!Y-m-d', $data->noa_approved_date);
                        $upr_create = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $data->noa_award_date);
                        $d_noa_approved_date       = $dt->diffInDays($upr_create);
                    }

                    $d_noa_award_accepted_date = 0;
                    if($data->noa_award_accepted_date)
                    {
                        $dt         = Carbon\Carbon::createFromFormat('!Y-m-d', $data->noa_award_accepted_date);
                        $upr_create = Carbon\Carbon::createFromFormat('!Y-m-d', $data->noa_approved_date);
                        $d_noa_award_accepted_date       = $dt->diffInDays($upr_create);
                    }

                    $d_po_create_date = 0;
                    if($data->po_create_date)
                    {
                        $dt         = Carbon\Carbon::createFromFormat('!Y-m-d', $data->po_create_date);
                        $upr_create = Carbon\Carbon::createFromFormat('!Y-m-d', $data->noa_award_accepted_date);
                        $d_po_create_date       = $dt->diffInDays($upr_create);
                    }

                    $d_mfo_received_date = 0;
                    if($data->mfo_received_date)
                    {
                        $dt         = Carbon\Carbon::createFromFormat('!Y-m-d', $data->mfo_received_date);
                        $upr_create = Carbon\Carbon::createFromFormat('!Y-m-d', $data->po_create_date);
                        $d_mfo_received_date       = $dt->diffInDays($upr_create);
                    }

                    $d_funding_released_date = 0;
                    if($data->funding_received_date)
                    {
                        $dt         = Carbon\Carbon::createFromFormat('!Y-m-d', $data->funding_received_date);
                        $upr_create = Carbon\Carbon::createFromFormat('!Y-m-d', $data->mfo_received_date);
                        $d_funding_released_date       = $dt->diffInDays($upr_create);
                    }

                    $d_coa_approved_date = 0;
                    if($data->coa_approved_date)
                    {
                        $dt         = Carbon\Carbon::createFromFormat('!Y-m-d', $data->coa_approved_date);
                        $upr_create = Carbon\Carbon::createFromFormat('!Y-m-d', $data->funding_received_date);
                        $d_coa_approved_date       = $dt->diffInDays($upr_create);
                    }

                    $d_ntp_date = 0;
                    if($data->ntp_date)
                    {
                        $dt         = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $data->ntp_date);
                        $upr_create = Carbon\Carbon::createFromFormat('!Y-m-d', $data->coa_approved_date);
                        $d_ntp_date       = $dt->diffInDays($upr_create);
                    }

                    $d_ntp_award_date = 0;
                    if($data->ntp_award_date)
                    {
                        $dt         = Carbon\Carbon::createFromFormat('!Y-m-d', $data->ntp_award_date);
                        $upr_create = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $data->ntp_date);
                        $d_ntp_award_date       = $dt->diffInDays($upr_create);
                    }

                    $d_delivery_date = 0;
                    if($data->delivery_date)
                    {
                        $dt         = Carbon\Carbon::createFromFormat('!Y-m-d', $data->delivery_date);
                        $upr_create = Carbon\Carbon::createFromFormat('!Y-m-d', $data->dr_date);
                        $d_delivery_date       = $dt->diffInDays($upr_create);
                    }

                    $d_dr_coa_date = 0;
                    if($data->dr_coa_date)
                    {
                        $dt         = Carbon\Carbon::createFromFormat('!Y-m-d', $data->dr_coa_date);
                        $upr_create = Carbon\Carbon::createFromFormat('!Y-m-d', $data->delivery_date);
                        $d_dr_coa_date       = $dt->diffInDays($upr_create);
                    }

                    $d_dr_inspection = 0;
                    if($data->dr_inspection)
                    {
                        $dt         = Carbon\Carbon::createFromFormat('!Y-m-d', $data->dr_inspection);
                        $upr_create = Carbon\Carbon::createFromFormat('!Y-m-d', $data->dr_coa_date);
                        $d_dr_inspection       = $dt->diffInDays($upr_create);
                    }

                    $d_di_start = 0;
                    if($data->di_start)
                    {
                        $dt         = Carbon\Carbon::createFromFormat('!Y-m-d', $data->di_start);
                        $upr_create = Carbon\Carbon::createFromFormat('!Y-m-d', $data->dr_inspection);
                        $d_di_start       = $dt->diffInDays($upr_create);
                    }

                    $d_di_close = 0;
                    if($data->di_close)
                    {
                        $dt         = Carbon\Carbon::createFromFormat('!Y-m-d', $data->di_close);
                        $upr_create = Carbon\Carbon::createFromFormat('!Y-m-d', $data->di_start);
                        $d_di_close       = $dt->diffInDays($upr_create);
                    }

                    $d_vou_start = 0;
                    if($data->vou_start)
                    {
                        $dt         = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $data->vou_start);
                        $upr_create = Carbon\Carbon::createFromFormat('!Y-m-d', $data->di_close);
                        $d_vou_start       = $dt->diffInDays($upr_create);
                    }

                    $d_vou_release = 0;
                    if($data->vou_release)
                    {
                        $dt         = Carbon\Carbon::createFromFormat('!Y-m-d', $data->vou_release);
                        $upr_create = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $data->vou_start);
                        $d_vou_release       = $dt->diffInDays($upr_create);
                    }

                    // $d_vou_received = 0;
                    // if($data->vou_received)
                    // {
                    //     $dt         = Carbon\Carbon::createFromFormat('!Y-m-d', $data->vou_received);
                    //     $upr_create = Carbon\Carbon::createFromFormat('!Y-m-d', $data->vou_release);
                    //     $days       = $dt->diffInDays($upr_create);
                    // }

                    $count ++;

                    if($data->mode_of_procurement != 'public_bidding')
                    {
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
                            // $d_vou_received,
                        ];
                    }
                    else
                    {
                        $newdata    =   [
                            $data->upr_number,
                            $d_docs,
                            $d_itb_days,
                            $d_pp_completed_at,
                            $d_prebid_days,
                            $d_bid_days,
                            $d_pq_days,
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
                            // $d_vou_received,
                        ];
                    }

                    $sheet->row($count, $newdata);

                }

            });

        })->export('xlsx');
    }

    /**
     * [downloadExcel description]
     *
     * @param  [type] $result [description]
     * @return [type]         [description]
     */
    public function downloadExcelPSR($result, $from ,$to)
    {
        Excel::create("PSR", function($excel)use ($result, $from ,$to) {
            $excel->sheet('Page1', function($sheet)use ($result, $from ,$to) {

                if($from != null)
                $from = \Carbon\Carbon::createFromFormat('!Y-m-d', $from)->format('d F Y');

                if($to != null)
                $to = \Carbon\Carbon::createFromFormat('!Y-m-d', $to)->format('d F Y');

                $count = 6;
                $sheet->row(1, ['PSR']);
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

                if($result->last()->mode_of_procurement != 'public_bidding')
                {
                    $sheet->row(2, ["ALTERNATIVE METHOD OF PROCUREMENT "]);
                    $sheet->row(6, [
                        'UPR',
                        'PROJECT',
                        'ABC',
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
                        'Create NOD',
                        'Received Delivery',
                        'Complete COA Delivery',
                        'Technical Inspection',
                        'IAR Acceptance',
                        'DIIR Inspection Start',
                        'DIIR Inspection Close',
                        'Prepare Voucher',
                        'Preaudit Voucher /End',
                        'Total CD',
                    ]);
                }
                else
                {
                    $sheet->row(2, ["PUBLIC BIDDING "]);

                    $sheet->row(6, [
                        'UPR',
                        'PROJECT',
                        'ABC',
                        'Stage 1',
                        'Stage 2',
                        'Stage 3',
                        'Stage 4',
                        'Stage 5',
                        'Stage 6',
                        'Stage 7',
                        'Stage 8',
                        'Stage 9',
                        'Stage 10',
                        'Stage 11',
                        'Stage 12',
                        'Stage 13',
                        'Stage 14',
                        'Stage 15',
                        'Stage 16',
                        'Stage 17',
                        'Stage 18',
                        'Stage 19',
                        'Stage 20',
                        'Stage 21',
                        'Stage 22',
                        'Stage 23',
                        'Stage 24',
                        'Stage 25',
                        'Stage 26',
                        'Stage 27',
                        'Stage 28',
                        'Stage 29',
                        // 'Stage 30',
                        'Total CD',
                    ]);
                }

                foreach($result as $data)
                {

                    if($data->mode_of_procurement != 'public_bidding')
                    {

                        $d_rfq_completed_at = 0;
                        if($data->rfq_completed_at != null)
                        {
                            $dt                 = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $data->rfq_completed_at);
                            $upr_create         = Carbon\Carbon::createFromFormat('!Y-m-d', $data->pp_completed_at);
                            $d_rfq_completed_at  = $dt->diffInDays($upr_create);
                        }

                        $d_pp_completed_at = 0;
                        if($data->pp_completed_at != null && $data->rfq_completed_at != null)
                        {
                            $dt                 = Carbon\Carbon::createFromFormat('!Y-m-d', $data->pp_completed_at);

                            $upr_create                 = $data->date_prepared;
                            $d_pp_completed_at  = $dt->diffInDays($upr_create );
                        }

                        $dispq_transaction_date = 0;
                        if($data->ispq_transaction_date )
                        {
                            $upr_create                 = $data->date_prepared;
                            $ispq_transaction_date    =   \Carbon\Carbon::createFromFormat('!Y-m-d', $data->ispq_transaction_date);

                            $dispq_transaction_date = $ispq_transaction_date->diffInDaysFiltered(function (\Carbon\Carbon $date) {return $date->isWeekday(); }, $upr_create );
                        }


                        $d_canvass_start_date = 0;
                        if($data->canvass_start_date && $data->ispq_transaction_date)
                        {
                            $dt         = Carbon\Carbon::createFromFormat('!Y-m-d', $data->canvass_start_date);
                            $upr_create = Carbon\Carbon::createFromFormat('!Y-m-d', $data->ispq_transaction_date);
                            $d_canvass_start_date       = $dt->diffInDays($upr_create);
                        }
                    }
                    else
                    {
                        $d_docs = 0;
                        if($data->doc_days != 0)
                        {
                            $d_docs = $data->doc_days;
                        }

                        $d_itb_days = 0;
                        if($data->itb_days != 0)
                        {
                            $d_itb_days = $data->itb_days;
                        }

                        $d_pp_completed_at = 0;
                        if($data->pp_completed_at != null && $data->rfq_completed_at != null)
                        {
                            $dt                 = Carbon\Carbon::createFromFormat('!Y-m-d', $data->pp_completed_at);

                            $upr_create                 = $data->date_prepared;
                            $d_pp_completed_at  = $dt->diffInDaysFiltered(function (\Carbon\Carbon $date) {return $date->isWeekday(); }, $upr_create );
                        }

                        $d_prebid_days = 0;
                        if($data->prebid_days != 0)
                        {
                            $d_prebid_days = $data->prebid_days;
                        }

                        $d_bid_days = 0;
                        if($data->bid_days != 0)
                        {
                            $d_bid_days = $data->bid_days;
                        }

                        $d_pq_days = 0;
                        if($data->pq_days != 0)
                        {
                            $d_pq_days = $data->pq_days;
                        }

                    }

                    $d_noa_award_date = 0;
                    if($data->noa_award_date && $data->canvass_start_date)
                    {
                        $dt         = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $data->noa_award_date);
                        $upr_create = Carbon\Carbon::createFromFormat('!Y-m-d', $data->canvass_start_date);
                        $d_noa_award_date       = $dt->diffInDays($upr_create);
                    }

                    $d_noa_approved_date = 0;
                    if($data->noa_approved_date)
                    {
                        $dt         = Carbon\Carbon::createFromFormat('!Y-m-d', $data->noa_approved_date);
                        $upr_create = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $data->noa_award_date);
                        $d_noa_approved_date       = $dt->diffInDays($upr_create);
                    }

                    $d_noa_award_accepted_date = 0;
                    if($data->noa_award_accepted_date)
                    {
                        $dt         = Carbon\Carbon::createFromFormat('!Y-m-d', $data->noa_award_accepted_date);
                        $upr_create = Carbon\Carbon::createFromFormat('!Y-m-d', $data->noa_approved_date);
                        $d_noa_award_accepted_date       = $dt->diffInDays($upr_create);
                    }

                    $d_po_create_date = 0;
                    if($data->po_create_date)
                    {
                        $dt         = Carbon\Carbon::createFromFormat('!Y-m-d', $data->po_create_date);
                        $upr_create = Carbon\Carbon::createFromFormat('!Y-m-d', $data->noa_award_accepted_date);
                        $d_po_create_date       = $dt->diffInDays($upr_create);
                    }

                    $d_mfo_received_date = 0;
                    if($data->mfo_received_date)
                    {
                        $dt         = Carbon\Carbon::createFromFormat('!Y-m-d', $data->mfo_received_date);
                        $upr_create = Carbon\Carbon::createFromFormat('!Y-m-d', $data->po_create_date);
                        $d_mfo_received_date       = $dt->diffInDays($upr_create);
                    }

                    $d_funding_released_date = 0;
                    if($data->funding_received_date)
                    {
                        $dt         = Carbon\Carbon::createFromFormat('!Y-m-d', $data->funding_received_date);
                        $upr_create = Carbon\Carbon::createFromFormat('!Y-m-d', $data->mfo_received_date);
                        $d_funding_released_date       = $dt->diffInDays($upr_create);
                    }

                    $d_coa_approved_date = 0;
                    if($data->coa_approved_date)
                    {
                        $dt         = Carbon\Carbon::createFromFormat('!Y-m-d', $data->coa_approved_date);
                        $upr_create = Carbon\Carbon::createFromFormat('!Y-m-d', $data->funding_received_date);
                        $d_coa_approved_date       = $dt->diffInDays($upr_create);
                    }

                    $d_ntp_date = 0;
                    if($data->ntp_date)
                    {
                        $dt         = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $data->ntp_date);
                        $upr_create = Carbon\Carbon::createFromFormat('!Y-m-d', $data->coa_approved_date);
                        $d_ntp_date       = $dt->diffInDays($upr_create);
                    }

                    $d_ntp_award_date = 0;
                    if($data->ntp_award_date)
                    {
                        $dt         = Carbon\Carbon::createFromFormat('!Y-m-d', $data->ntp_award_date);
                        $upr_create = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $data->ntp_date);
                        $d_ntp_award_date       = $dt->diffInDays($upr_create);
                    }

                    $d_delivery_date = 0;
                    if($data->delivery_date)
                    {
                        $dt         = Carbon\Carbon::createFromFormat('!Y-m-d', $data->delivery_date);
                        $upr_create = Carbon\Carbon::createFromFormat('!Y-m-d', $data->dr_date);
                        $d_delivery_date       = $dt->diffInDays($upr_create);
                    }

                    $d_dr_coa_date = 0;
                    if($data->dr_coa_date && $data->delivery_date)
                    {
                        $dt         = Carbon\Carbon::createFromFormat('!Y-m-d', $data->dr_coa_date);
                        $upr_create = Carbon\Carbon::createFromFormat('!Y-m-d', $data->delivery_date);
                        $d_dr_coa_date       = $dt->diffInDays($upr_create);
                    }

                    $d_dr_inspection = 0;
                    if($data->dr_inspection)
                    {
                        $dt         = Carbon\Carbon::createFromFormat('!Y-m-d', $data->dr_inspection);
                        $upr_create = Carbon\Carbon::createFromFormat('!Y-m-d', $data->dr_coa_date);
                        $d_dr_inspection       = $dt->diffInDays($upr_create);
                    }

                    $d_di_start = 0;
                    if($data->di_start)
                    {
                        $dt         = Carbon\Carbon::createFromFormat('!Y-m-d', $data->di_start);
                        $upr_create = Carbon\Carbon::createFromFormat('!Y-m-d', $data->dr_inspection);
                        $d_di_start       = $dt->diffInDays($upr_create);
                    }

                    $d_di_close = 0;
                    if($data->di_close)
                    {
                        $dt         = Carbon\Carbon::createFromFormat('!Y-m-d', $data->di_close);
                        $upr_create = Carbon\Carbon::createFromFormat('!Y-m-d', $data->di_start);
                        $d_di_close       = $dt->diffInDays($upr_create);
                    }

                    $d_vou_start = 0;
                    if($data->vou_start)
                    {
                        $dt         = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $data->vou_start);
                        $upr_create = Carbon\Carbon::createFromFormat('!Y-m-d', $data->di_close);
                        $d_vou_start       = $dt->diffInDays($upr_create);
                    }

                    $d_vou_release = 0;
                    if($data->vou_certify_days)
                    {
                        $dt         = Carbon\Carbon::createFromFormat('!Y-m-d', $data->vou_certify_days);
                        $upr_create = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $data->vou_start);
                        $d_vou_release       = $dt->diffInDays($upr_create);
                    }

                    // $d_vou_received = 0;
                    // if($data->vou_received)
                    // {
                    //     $dt         = Carbon\Carbon::createFromFormat('!Y-m-d', $data->vou_received);
                    //     $upr_create = Carbon\Carbon::createFromFormat('!Y-m-d', $data->vou_release);
                    //     $days       = $dt->diffInDays($upr_create);
                    // }

                    $count ++;

                    if($data->mode_of_procurement != 'public_bidding')
                    {
                        $newdata    =   [
                            $data->upr_number,
                            $data->project_name,
                            formatPrice($data->total_amount),
                            $data->date_prepared->format('d F Y'),
                            $dispq_transaction_date,
                            $d_pp_completed_at,
                            $d_rfq_completed_at,
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
                            // $d_vou_received,
                        ];
                    }
                    else
                    {
                        $newdata    =   [
                            $data->upr_number,
                            $data->project_name,
                            formatPrice($data->total_amount),
                            $data->date_prepared->format('d F Y'),
                            $d_docs,
                            $d_itb_days,
                            $d_pp_completed_at,
                            $d_prebid_days,
                            $d_bid_days,
                            $d_pq_days,
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
                            // $d_vou_received,
                        ];
                    }

                    $sheet->row($count, $newdata);

                }

            });

        })->export('xlsx');
    }


}
