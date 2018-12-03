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
        $result     =   $model->getUnitPSRItemAll($request->type, $request, $search);
        // $result     =   $model->getTransactionDay($request, $search);
        $this->downloadExcelPSR($result, $request->date_from, $request->date_to, $request->type);

    }

    /**
     * [downloadExcel description]
     *
     * @param  [type] $result [description]
     * @return [type]         [description]
     */
    public function downloadExcel($result, $from ,$to)
    {
        Excel::create("Procurement Monitoring Report", function($excel)use ($result, $from ,$to) {
            $excel->sheet('Page1', function($sheet)use ($result, $from ,$to) {

                if($from != null)
                $from = \Carbon\Carbon::createFromFormat('!Y-m-d', $from)->format('d F Y');

                if($to != null)
                $to = \Carbon\Carbon::createFromFormat('!Y-m-d', $to)->format('d F Y');

                $count = 6;
                $sheet->row(1, ['Procurement Monitoring Report']);
                $sheet->row(3, ["(Period Covered: $from to $to)"]);
                $sheet->freezeFirstRow();
                $sheet->freezeFirstColumn();

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

                $sheet->mergeCells('N5:P5');
                $sheet->mergeCells('N4:P4');
                $sheet->mergeCells('Q5:S5');
                $sheet->mergeCells('Q4:S4');
                $sheet->mergeCells('A2:AA2');
                $sheet->mergeCells('A3:AA3');
                $sheet->mergeCells('A3:AA3');

                if($result->last() != null)
                {
                    if($result->last()->mode_of_procurement != 'public_bidding')
                    {
                        $sheet->row(2, ["ALTERNATIVE METHOD OF PROCUREMENT "]);
                        $sheet->row(6, [
                            'PCCO',
                            'End User',
                            'Mode of Procurement',
                            'UPR',
                            'ISPQ',
                            'PhilGeps Posting',
                            'RFQ',
                            'Canvassing',
                            'Prepare NOA',
                            'Approved NOA',
                            'Received NOA',
                            'PO/JO/WO Preparation',
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
                            'LDAP-ADA',
                            'Source of Funds',
                            'Total',
                            'MODE',
                            'CO',
                            'Total',
                            'MODE',
                            'CO',
                    ]);
                    }
                    else
                    {
                        $sheet->row(5, ['PUBLIC BIDDING ',
                        '',
                        '',
                        '',
                        '',
                        '',
                        '',
                        '',
                        '',
                        '',
                        '',
                        '',
                        '',
                        'ABC(Php)',
                        '',
                        '',
                        'Contract Cost(Php)'
                        ]);

                        $sheet->row(6, [
                            'PCCO',
                            'End User',
                            'Mode of Procurement',
                            'UPR',
                            'Document Acceptance',
                            'Pre Proc',
                            'ITB',
                            'PhilGeps Posting',
                            'Pre Bid Conference',
                            'SOBE',
                            'Post Qualification',
                            'Notice of Award',
                            'Source of Funds',
                            'Total',
                            'MODE',
                            'CO',
                            'Total',
                            'MODE',
                            'CO',
                            'Remarks',
                            // 'Received Payment',
                        ]);
                    }
                }

                foreach($result as $data)
                {   
                    if($data->mode_of_procurement != 'public_bidding')
                    {

                        $d_rfq_completed_at = $data->rfq_close_days;

                        $d_pp_completed_at = $data->pp_days;

                        $dispq_transaction_date = 0;
                        if($data->ispq_transaction_date )
                        {
                            $upr_create                 = $data->date_processed;
                            $ispq_transaction_date    =   \Carbon\Carbon::createFromFormat('!Y-m-d', $data->ispq_transaction_date);

                            $dispq_transaction_date = $ispq_transaction_date->diffInDaysFiltered(function (\Carbon\Carbon $date) {return $date->isWeekday(); }, $upr_create );
                            if($dispq_transaction_date > 0){ $dispq_transaction_date -= 1; }
                        }


                        $d_canvass_start_date = $data->canvass_days;

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

                        $d_pp_completed_at = $data->pp_days;

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
                    $d_noa_award_date = $data->noa_days;

                    $d_noa_approved_date = $data->noa_approved_days;


                    $d_noa_award_accepted_date = $data->noa_received_days;

                    $d_po_create_date = $data->po_days;

                    $d_mfo_received_date = $data->po_mfo_days;

                    $d_funding_released_date = $data->po_funding_days;
                    $d_coa_approved_date = $data->po_coa_days;
                    $d_ntp_date = $data->ntp_days;


                    $d_ntp_award_date = $data->ntp_accepted_days;

                    $d_delivery_date = $data->dr_delivery_days;

                    $d_dr_coa_date = $data->dr_dr_coa_days;

                    $d_dr_inspection = $data->dr_inspection_days;
                    $d_dr_inspection_accept = $data->dr_inspection_accept_days;
                    $d_di_start = $data->di_days;

                    $d_di_close = $data->di_close_days;

                    $d_vou_start = $data->vou_days;

                    $d_vou_release = $data->vou_preaudit_days;

                    // $d_vou_received = 0;
                    // if($data->vou_received)
                    // {
                    //     $dt         = Carbon\Carbon::createFromFormat('!Y-m-d', $data->vou_received);
                    //     $upr_create = Carbon\Carbon::createFromFormat('!Y-m-d', $data->vou_release);
                    //     $days       = $dt->diffInDays($upr_create);
                    // }

                    // $count ++;

                    if($data->mode_of_procurement != 'public_bidding')
                    { 
                        $newdata    =   [
                            $data->upr_number,
                            $data->unit->short_code,
                            $data->modes->name,
                            \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $data->date_processed)->format('m-d-Y'),
                            $data->ispq_transaction_date,
                            $data->pp_completed_at,
                            $data->rfq_created_at,
                            $data->canvass_start_date,
                            $data->noa_award_date,
                            $data->noa_approved_date,
                            $data->noa_award_accepted_date,
                            $data->po_create_date,
                            $data->funding_received_date,
                            $data->mfo_received_date,
                            $data->coa_approved_date,
                            $data->ntp_date,
                            $data->ntp_award_date,
                            $data->dr_date,
                            $data->dr_coa_date,
                            $data->dr_inspection,
                            $data->iar_accepted_date,
                            $data->di_start,
                            $data->di_close,
                            $data->v_transaction_date,
                            $data->preaudit_date,
                            $data->vou_release,
                            ($data->charges) ?  $data->charges->name : "",
                            formatPrice($data->total_amount),
                            '',
                            '',
                            formatPrice($data->bid_amount),
                            '',
                            '',
                            '',
                        ];
                    }
                    else
                    {

                        $newdata    =   [
                            $data->upr_number,
                            $data->unit->short_code,
                            'Public Bidding',
                            $data->date_processed,
                            $data->doc_date,
                            $data->proc_days,
                            $data->itb_date,
                            $data->pp_completed_at,
                            $data->prebid_date,
                            $data->bid_date,
                            $data->pq_date,
                            $data->noa_award_date,
                            ($data->charges) ?  $data->charges->name : "",
                            formatPrice($data->total_amount),
                            '',
                            '',
                            formatPrice($data->bid_amount),
                            '',
                            '',
                            '',
                            // $d_vou_received,
                        ];
                    }

                    $arr[$data->short_code."/".$data->name][] = $newdata;
                    // $sheet->row($count, $newdata);

                }
                foreach($arr as $key => $value){
                    $count ++;
                    $sheet->row($count, [$key]);
                    $sheet->row($count, function($row){
                      $row->setBackground("#eeeeee");
                    });
                    foreach($value as $val)
                    {
                        $count ++;

                        $sheet->row($count, $val);
                    }
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
    public function downloadExcelPSR($result, $from ,$to, $type)
    {
        Excel::create("PSR", function($excel)use ($result, $from ,$to, $type) {
            $excel->sheet('Page1', function($sheet)use ($result, $from ,$to, $type) {

                if($from != null)
                $from = \Carbon\Carbon::createFromFormat('!Y-m-d', $from)->format('d F Y');

                if($to != null)
                $to = \Carbon\Carbon::createFromFormat('!Y-m-d', $to)->format('d F Y');

                $count = 6;
                $sheet->row(1, ['PSR']);
                $sheet->row(3, ["(Period Covered: $from to $to)"]);
                $sheet->freezeFirstRow();
                $sheet->freezeFirstColumn();

                $sheet->cells('A3:AM3', function($cells) {
                    $cells->setAlignment('center');
                    $cells->setBorder('thin', 'thin', 'thin', 'thin');
                });

                $sheet->cells('A2:AM2', function($cells) {
                    $cells->setAlignment('center');
                    $cells->setBorder('thin', 'thin', 'thin', 'thin');
                });

                $sheet->cells('A1:AM1', function($cells) {
                    $cells->setAlignment('center');
                    $cells->setBorder('thin', 'thin', 'thin', 'thin');
                });

                $sheet->mergeCells('A1:AM1');
                $sheet->mergeCells('A2:AM2');
                $sheet->mergeCells('A3:AM3');
                if($result->last() != null)
                {

                    if($type != 'bidding')
                    {
                        $sheet->row(6, function($row){
                          $row->setFontWeight("bold");
                        });
                        $sheet->row(2, ["ALTERNATIVE METHOD OF PROCUREMENT "]);
                        $sheet->row(6, [
                            'PC/CO (UPR NUMBER)',
                            'PROJECT NAME/ ACTIVITY',
                            'END USER',
                            'ABC',
                            'UPR RECEIPT',
                            'ITSPQ',
                            'PhilGeps Posting',
                            'RFQ',
                            'Canvass',
                            'Prepare NOA (PC/CO)',
                            'Issue NOA (PC/CO)',
                            'Conforme NOA (Supplier)',
                            'Posting of NOA to Philgeps (PC/CO)',
                            'Preparation of PO (PC/CO)',
                            'MFO Obligation (PC/CO)',
                            'Issuance of CAF (Accounting)',
                            'PO/WO/JO/CA Approval and NTP Preparation (PC/CO)',
                            'Serving of NTP (PC/CO)',
                            'Conforme of NTP (Supplier)',
                            'Philgeps Posting of NTP (PC/CO)',
                            'Issuance of Notice of Delivery (PC/CO)',
                            'Delivery of Items (Supplier)',
                            'Notification of Delivery to COA (SAO)',
                            'Conduct of TIAC (END-USER)',
                            'Inspection and Acceptance Report (SAO)',
                            'Delivered Items and Inspection Report (MFO)',
                            'Preparation of DV (PC/CO)',
                            'Sign Box `A` of DV (MFO)',
                            'Accomplish Box `B` of DV And Sign Box `C` of DV (Accounting)',
                            'Sign Box `D` of DV (END USER CMDR)',
                            'Pre-audit (MFO)',
                            'Prepare and Sign LDDAP-ADA (Accounting)',
                            'Sign LDDAP-ADA or Prepare Cheque (Finance)',
                            'Sign LDDAP-ADA or Counter-Sign Cheque (PC/CO)',
                            'Receipt of Cheques and Issue Official Receipt (Supplier)',
                            'Total Number of Days',
                        ]);
                    }
                    else
                    {
                        $sheet->row(2, ["PUBLIC BIDDING "]);

                        $sheet->row(6, function($row){
                          $row->setFontWeight("bold");
                        });

                        $sheet->row(6, [
                            'PC/CO (UPR NUMBER)',
                            'PROJECT NAME/ ACTIVITY',
                            'END USER',
                            'ABC',
                            'UPR RECEIPT',
                            'Document Acceptance (PC/CO)',
                            'Pre Proc  (PC/CO)',
                            'Invitation to Bid (PC/CO)',
                            'PhilGeps Posting (PC/CO)',
                            'Pre Bid Conference (PC/CO)',
                            'SOBE (PC/CO)',
                            'Post Qualification (PC/CO)',
                            'Prepare NOA (PC/CO)',
                            'Issue NOA (PC/CO)',
                            'Conforme NOA (Supplier)',
                            'Posting of NOA to Philgeps (PC/CO)',
                            'Preparation of PO (PC/CO)',
                            'MFO Obligation (PC/CO)',
                            'Issuance of CAF (Accounting)',
                            'PO/WO/JO/CA Approval and NTP Preparation (PC/CO)',
                            'Serving of NTP (PC/CO)',
                            'Conforme of NTP (Supplier)',
                            'Philgeps Posting of NTP (PC/CO)',
                            'Issuance of Notice of Delivery (PC/CO)',
                            'Delivery of Items (Supplier)',
                            'Notification of Delivery to COA (SAO)',
                            'Conduct of TIAC (END-USER)',
                            'Inspection and Acceptance Report (SAO)',
                            'Delivered Items and Inspection Report (MFO)',
                            'Preparation of DV (PC/CO)',
                            'Sign Box `A` of DV (MFO)',
                            'Accomplish Box `B` of DV And Sign Box `C` of DV (Accounting)',
                            'Sign Box `D` of DV (END USER CMDR)',
                            'Pre-audit (MFO)',
                            'Prepare and Sign LDDAP-ADA (Accounting)',
                            'Sign LDDAP-ADA or Prepare Cheque (Finance)',
                            'Sign LDDAP-ADA or Counter-Sign Cheque (PC/CO)',
                            'Receipt of Cheques and Issue Official Receipt (Supplier)',
                            'Total Number of Days',
                        ]);
                    }
                }

                $arr = array();

                foreach($result as $data)
                {
                    if($data->mode_of_procurement != 'public_bidding')
                    {

                        $d_rfq_completed_at = $data->rfq_close_days;

                        $d_pp_completed_at = $data->pp_days;

                        $dispq_transaction_date = 0;
                        if($data->ispq_transaction_date )
                        {
                            $upr_create                 = $data->date_processed;
                            $ispq_transaction_date    =   \Carbon\Carbon::createFromFormat('!Y-m-d', $data->ispq_transaction_date);

                            $dispq_transaction_date = $ispq_transaction_date->diffInDaysFiltered(function (\Carbon\Carbon $date) {return $date->isWeekday(); }, $upr_create );
                            if($dispq_transaction_date > 0){ $dispq_transaction_date -= 1; }
                        }


                        $d_canvass_start_date = $data->canvass_days;

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

                        $d_pp_completed_at = $data->pp_days;

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
                    $noa_days = 0;
                    if($data->canvass_start_date != null){

                      $canvass_start_date = createCarbon('Y-m-d',$data->canvass_start_date);
                      if($data->noa_award_date != null){
                        $noa_award_date = createCarbon('Y-m-d H:i:s',$data->noa_award_date)->format('Y-m-d');
                        $noa_award_date = createCarbon('Y-m-d',$noa_award_date);
                        $noa_days            =   $canvass_start_date->diffInDaysFiltered(function(\Carbon\Carbon $date){
                            return $date->isWeekday();
                        }, $noa_award_date);
                      }
                    }

                    $d_noa_award_date = $noa_days;

                    $d_noa_approved_date = $data->noa_approved_days;


                    $d_noa_award_accepted_date = $data->noa_received_days;

                    $d_po_create_date = $data->po_days;

                    $d_mfo_received_date = $data->po_mfo_days;

                    $d_funding_released_date = $data->po_funding_days;
                    $d_coa_approved_date = $data->po_coa_days;
                    $d_ntp_date = $data->ntp_days;


                    $d_ntp_award_date = $data->ntp_accepted_days;

                    $d_delivery_date = $data->dr_delivery_days;

                    $d_dr_coa_date = $data->dr_dr_coa_days;

                    $d_dr_inspection = $data->dr_inspection_days;
                    $d_dr_inspection_accept = $data->dr_inspection_accept_days;
                    $d_di_start = $data->di_days;

                    $d_di_close = $data->di_close_days;

                    $d_vou_start = $data->vou_days;

                    $d_vou_release = $data->vou_preaudit_days;

                    // $d_vou_received = 0;
                    // if($data->vou_received)
                    // {
                    //     $dt         = Carbon\Carbon::createFromFormat('!Y-m-d', $data->vou_received);
                    //     $upr_create = Carbon\Carbon::createFromFormat('!Y-m-d', $data->vou_release);
                    //     $days       = $dt->diffInDays($upr_create);
                    // }

                    // $count ++;

                    if($type != 'bidding')
                    {
                        $newdata    =   [
                            $data->upr_number,
                            $data->project_name,
                            $data->end_user,
                            formatPrice($data->total_amount),
                            $data->date_processed->format('d F Y'),
                            $data->ispq_days,
                            $data->pp_days,
                            $data->rfq_days,
                            $data->canvass_days,
                            $data->noa_days,
                            $data->noa_approved_days,
                            $data->noa_received_days,
                            $data->noa_pp_days,
                            $data->po_days,
                            $data->po_mfo_days,
                            $data->po_funding_days,
                            $data->po_coa_days,
                            $data->ntp_days,
                            $data->ntp_accepted_days,
                            $data->ntp_pp_days,
                            $data->dr_days,
                            $data->dr_delivery_days,
                            $data->dr_dr_coa_days,
                            $data->dr_inspection_days,
                            $data->dr_inspection_accept_days,
                            $data->di_days,
                            $data->vou_days,
                            $data->vou_certify_days,
                            $data->vou_jev_days,
                            $data->vou_approved_days,
                            $data->vou_preaudit_days,
                            $data->voucher_pc_days,
                            $data->vou_released_days,
                            $data->voucher_counter_days,
                            $data->voucher_received_days,
                            $data->calendar_days,
                        ];
                    }
                    else
                    {
                        $newdata    =   [
                            $data->upr_number,
                            $data->project_name,
                            $data->end_user,
                            formatPrice($data->total_amount),
                            $data->date_processed->format('d F Y'),
                            $data->doc_days,
                            $data->proc_days,
                            $data->itb_days,
                            $data->pp_days,
                            $data->prebid_days,
                            $data->bid_days,
                            $data->pq_days,
                            $data->noa_days,
                            $data->noa_approved_days,
                            $data->noa_received_days,
                            $data->noa_pp_days,
                            $data->po_days,
                            $data->po_mfo_days,
                            $data->po_funding_days,
                            $data->po_coa_days,
                            $data->ntp_days,
                            $data->ntp_accepted_days,
                            $data->ntp_pp_days,
                            $data->dr_days,
                            $data->dr_delivery_days,
                            $data->dr_dr_coa_days,
                            $data->dr_inspection_days,
                            $data->dr_inspection_accept_days,
                            $data->di_days,
                            $data->vou_days,
                            $data->vou_certify_days,
                            $data->vou_jev_days,
                            $data->vou_approved_days,
                            $data->vou_preaudit_days,
                            $data->voucher_pc_days,
                            $data->vou_released_days,
                            $data->voucher_counter_days,
                            $data->voucher_received_days,
                            $data->calendar_days,
                        ];
                    }

                       $arr[$data->short_code."/".$data->name][] = $newdata;


                }

                foreach($arr as $key => $value){
                  $count ++;
                  $sheet->row($count, [$key]);
                  $sheet->row($count, function($row){
                    $row->setBackground("#eeeeee");
                  });
                  foreach($value as $val)
                  {
                      $count ++;

                      $sheet->row($count, $val);
                  }
                }

            });

        })->export('xlsx');
    }


}
