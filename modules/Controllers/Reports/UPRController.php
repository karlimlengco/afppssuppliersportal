<?php

namespace Revlv\Controllers\Reports;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon;
use Excel;

use \Revlv\Settings\Units\UnitRepository;
use \Revlv\Procurements\BlankRequestForQuotation\BlankRFQRepository;
use \Revlv\Procurements\UnitPurchaseRequests\UnitPurchaseRequestRepository;
use \Revlv\Settings\ProcurementCenters\ProcurementCenterRepository;

class UPRController extends Controller
{

    /**
     * [$blankRfq description]
     *
     * @var [type]
     */
    protected $blankRfq;
    protected $model;
    protected $units;
    protected $centers;


    /**
     * @param model $model
     */
    public function __construct()
    {
        parent::__construct();
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getPSRPrograms(Request $request, ProcurementCenterRepository $model, $type = null)
    {
        return $model->getPSRPrograms($type, $request);
    }

    /**
     * [getUprCenters description]
     *
     * @param  [type]                        $id    [description]
     * @param  UnitPurchaseRequestRepository $model [description]
     * @return [type]                               [description]
     */
    public function getPSRUprCenters($program, UnitPurchaseRequestRepository $model, $type = null)
    {
        $items      =   $model->getPSRUprCenters($program,  $type);

        $response   = [
            'program' => $program,
            'data' => $items
        ];

        return $response;
    }

    /**
     * [getUnits description]
     *
     * @param  [type]                        $center [description]
     * @param  UnitPurchaseRequestRepository $model  [description]
     * @param  [type]                        $type   [description]
     * @return [type]                                [description]
     */
    public function getPSRUnits($programs, $center, UnitPurchaseRequestRepository $model, $type = null)
    {
        $items      =   $model->getPSRUnit($center, $programs,  $type);

        $response   = [
            'program' => $programs,
            'center'  => $center,
            'data' => $items
        ];
        return $response;
    }

    /**
     * [getUprs description]
     *
     * @param  [type]                        $id    [description]
     * @param  UnitPurchaseRequestRepository $model [description]
     * @return [type]                               [description]
     */
    public function getPSRUprs($programs, $center, UnitPurchaseRequestRepository $model, $type = null)
    {
        $items      =   $model->getPSRUprs($center, $programs,  $type);

        $response   = [
            'program' => $programs,
            'center'  => $center,
            'data' => $items
        ];

        return $response;
    }

    /**
     * [getPSR description]
     *
     * @param  [type]                        $type    [description]
     * @param  Request                       $request [description]
     * @param  UnitPurchaseRequestRepository $upr     [description]
     * @return [type]                                 [description]
     */
    public function getPSR($type, Request $request, UnitPurchaseRequestRepository $upr)
    {
        $result     =   $upr->findTimelineByUnit($request, $type, ['document_accept', 'itb', 'bid_conference', 'bid_open', 'post_qual', 'rfq', 'philgeps', 'invitations','noa', 'purchase_order', 'ntp', 'delivery_order', 'inspections', 'diir', 'voucher']);

        $response   = [
            'data' => $result
        ];

        return $response;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getPrograms(Request $request, ProcurementCenterRepository $model, $type = null)
    {
        return $model->getPrograms(null, $type, $request);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getCenters($program, ProcurementCenterRepository $model, $type = null)
    {
        $items      =   $model->getCenters($program,  $type);

        $response   = [
            'program' => $program,
            'data' => $items
        ];

        return $response;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getUprPrograms(UnitPurchaseRequestRepository $model, $type = null)
    {
        return $model->getProgramAnalytics(null, $type);
    }

    /**
     * [getUprCenters description]
     *
     * @param  [type]                        $id    [description]
     * @param  UnitPurchaseRequestRepository $model [description]
     * @return [type]                               [description]
     */
    public function getUprCenters($program, UnitPurchaseRequestRepository $model, $type = null)
    {
        $items      =   $model->getUprCenters($program,  $type);

        $response   = [
            'program' => $program,
            'data' => $items
        ];

        return $response;
    }

    /**
     * [getUnits description]
     *
     * @param  [type]                        $center [description]
     * @param  UnitPurchaseRequestRepository $model  [description]
     * @param  [type]                        $type   [description]
     * @return [type]                                [description]
     */
    public function getUnits($programs, $center, UnitPurchaseRequestRepository $model, $type = null)
    {
        $items      =   $model->getUnits($center, $programs,  $type);

        $response   = [
            'program' => $programs,
            'center'  => $center,
            'data' => $items
        ];
        return $response;
    }

    /**
     * [getUprs description]
     *
     * @param  [type]                        $id    [description]
     * @param  UnitPurchaseRequestRepository $model [description]
     * @return [type]                               [description]
     */
    public function getUprs($programs, $center, UnitPurchaseRequestRepository $model, $type = null)
    {
        $items      =   $model->getUprs($center, $programs,  $type);

        $response   = [
            'program' => $programs,
            'center'  => $center,
            'data' => $items
        ];

        return $response;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Respons e
     */
    public function downloadTimeline(UnitPurchaseRequestRepository $model, $id)
    {
        $result     =   $model->findTimelineById($id);

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
        Excel::create("Timeline", function($excel)use ($result) {
            $excel->sheet('Page1', function($sheet)use ($result) {
                $count = 6;
                $sheet->row(1, ['Timeline']);
                if($result->mode_of_procurement != 'public_bidding')
                {
                    $sheet->row(2, ["ALTERNATIVE METHOD OF PROCUREMENT"]);
                }
                else
                {
                    $sheet->row(2, ["PUBLIC BIDDING"]);
                }
                $sheet->row(3, [""]);

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
                if($result->mode_of_procurement != 'public_bidding')
                {
                    $sheet->row(6, [
                        'UPR #',
                        'Ref #',
                        'UPR',
                        'ISPQ',
                        'PhilGeps',
                        'Close RFQ',
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
                        'Received Items',
                        'Delivery To COA',
                        'Technical Inspection',
                        'Inspection Acceptance',
                        'Inspection of Delivered Items',
                        'Prepare Certificate of Inspection',
                        'Create Voucher',
                        'PreAudit',
                    ]);

                    $newdata    =   [
                        $result->upr_number,
                        $result->ref_number,
                        $result->date_prepared->format('d F Y'),
                        ($result->ispq_transaction_date) ?
                            \Carbon\Carbon::createFromFormat('Y-m-d',$result->ispq_transaction_date)->format('d F Y')
                            : "",
                        ($result->pp_completed_at) ?
                            \Carbon\Carbon::createFromFormat('Y-m-d',$result->pp_completed_at)->format('d F Y')
                            : "",
                        ($result->rfq_completed_at) ?
                            \Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$result->rfq_completed_at)->format('d F Y')
                            : "",
                        ($result->canvass_start_date) ?
                            \Carbon\Carbon::createFromFormat('Y-m-d',$result->canvass_start_date)->format('d F Y')
                            : "",
                        ($result->noa_award_date) ?
                            \Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$result->noa_award_date)->format('d F Y')
                            : "",
                        ($result->noa_approved_date) ?
                            \Carbon\Carbon::createFromFormat('Y-m-d',$result->noa_approved_date)->format('d F Y')
                            : "",
                        ($result->noa_award_accepted_date) ?
                            \Carbon\Carbon::createFromFormat('Y-m-d',$result->noa_award_accepted_date)->format('d F Y')
                            : "",
                        ($result->po_create_date) ?
                            \Carbon\Carbon::createFromFormat('Y-m-d',$result->po_create_date)->format('d F Y')
                            : "",
                        ($result->funding_received_date) ?
                            \Carbon\Carbon::createFromFormat('Y-m-d',$result->funding_received_date)->format('d F Y')
                            : "",
                        ($result->mfo_received_date) ?
                            \Carbon\Carbon::createFromFormat('Y-m-d',$result->mfo_received_date)->format('d F Y')
                            : "",
                        ($result->coa_approved_date) ?
                            \Carbon\Carbon::createFromFormat('Y-m-d',$result->coa_approved_date)->format('d F Y')
                            : "",
                        ($result->ntp_date) ?
                            \Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$result->ntp_date)->format('d F Y')
                            : "",
                        ($result->ntp_award_date) ?
                            \Carbon\Carbon::createFromFormat('Y-m-d',$result->ntp_award_date)->format('d F Y')
                            : "",
                        ($result->delivery_date) ?
                            \Carbon\Carbon::createFromFormat('Y-m-d',$result->delivery_date)->format('d F Y')
                            : "",
                        ($result->dr_coa_date) ?
                            \Carbon\Carbon::createFromFormat('Y-m-d',$result->dr_coa_date)->format('d F Y')
                            : "",
                        ($result->dr_inspection) ?
                            \Carbon\Carbon::createFromFormat('Y-m-d',$result->dr_inspection)->format('d F Y')
                            : "",
                        ($result->iar_accepted_date) ?
                            \Carbon\Carbon::createFromFormat('Y-m-d',$result->iar_accepted_date)->format('d F Y')
                            : "",
                        ($result->di_start) ?
                            \Carbon\Carbon::createFromFormat('Y-m-d',$result->di_start)->format('d F Y')
                            : "",
                        ($result->di_close) ?
                            \Carbon\Carbon::createFromFormat('Y-m-d',$result->di_close)->format('d F Y')
                            : "",
                        ($result->v_transaction_date) ?
                            \Carbon\Carbon::createFromFormat('Y-m-d',$result->v_transaction_date)->format('d F Y')
                            : "",
                        ($result->preaudit_date) ?
                            \Carbon\Carbon::createFromFormat('Y-m-d',$result->preaudit_date)->format('d F Y')
                            : "",

                    ];
                }
                else
                {
                    $sheet->row(6, [
                        'UPR #',
                        'Ref #',
                        'UPR',
                        'Document Acceptance',
                        'Pre Proc Conference',
                        'Invitation to Bid',
                        'PhilGeps Posting',
                        'Pre Bid Conference',
                        'SOBE',
                        'Post Qualification',
                        'NOA',
                        'NOA Approved',
                        'NOA Accepted',
                        'Contract Creation',
                        'Funding',
                        'Issuance of Certificate',
                        'COA Approval',
                        'NTP',
                        'NTPA',
                        'Received Items',
                        'Delivery To COA',
                        'Technical Inspection',
                        'Inspection Acceptance',
                        'Inspection of Delivered Items',
                        'Prepare Certificate of Inspection',
                        'Create Voucher',
                        'PreAudit',
                    ]);

                    $documentAcceptance  = "";
                    $preprocConference  = "";
                    $invitationToBid  = "";
                    $philgepsPosting  = "";
                    $prebidConference  = "";
                    $sobe  = "";
                    $postQual  = "";

                    foreach($result->documents as $docs)
                    {
                        if($docs->approved_date != null)
                        {
                            $documentAcceptance .= \Carbon\Carbon::createFromFormat('Y-m-d',$docs->approved_date)->format('d F Y').'/ ';
                        }
                    }

                    foreach($result->preprocs as $procs)
                    {
                        if($procs->pre_proc_date != null)
                        {
                            $preprocConference .= \Carbon\Carbon::createFromFormat('Y-m-d',$procs->pre_proc_date)->format('d F Y').'/ ';
                        }
                    }

                    foreach($result->itbs as $itb)
                    {
                        if($itb->transaction_date != null)
                        {
                            $invitationToBid .= \Carbon\Carbon::createFromFormat('Y-m-d',$itb->transaction_date)->format('d F Y').'/ ';
                        }
                    }

                    foreach($result->philgeps_many as $pps)
                    {
                        if($pps->transaction_date != null)
                        {
                            $philgepsPosting .= \Carbon\Carbon::createFromFormat('Y-m-d',$pps->transaction_date)->format('d F Y').'/ ';
                        }
                    }

                    foreach($result->bid_conferences as $prebid)
                    {
                        if($prebid->transaction_date != null)
                        {
                            $prebidConference .= \Carbon\Carbon::createFromFormat('Y-m-d',$prebid->transaction_date)->format('d F Y').'/ ';
                        }
                    }

                    foreach($result->bid_opens as $bidops)
                    {
                        if($bidops->transaction_date != null)
                        {
                            $sobe .= \Carbon\Carbon::createFromFormat('Y-m-d',$bidops->transaction_date)->format('d F Y').'/ ';
                        }
                    }

                    foreach($result->post_quals as $pqual)
                    {
                        if($pqual->transaction_date != null)
                        {
                            $postQual .= \Carbon\Carbon::createFromFormat('Y-m-d',$pqual->transaction_date)->format('d F Y').'/ ';
                        }
                    }
                    // dd($result->documents);

                    $newdata    =   [
                        $result->upr_number,
                        $result->ref_number,
                        $result->date_prepared->format('d F Y'),
                        $documentAcceptance,
                        $preprocConference,
                        $invitationToBid,
                        $philgepsPosting,
                        $prebidConference,
                        $sobe,
                        $postQual,
                        ($result->noa_award_date) ?
                            \Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$result->noa_award_date)->format('d F Y')
                            : "",
                        ($result->noa_approved_date) ?
                            \Carbon\Carbon::createFromFormat('Y-m-d',$result->noa_approved_date)->format('d F Y')
                            : "",
                        ($result->noa_award_accepted_date) ?
                            \Carbon\Carbon::createFromFormat('Y-m-d',$result->noa_award_accepted_date)->format('d F Y')
                            : "",
                        ($result->po_create_date) ?
                            \Carbon\Carbon::createFromFormat('Y-m-d',$result->po_create_date)->format('d F Y')
                            : "",
                        ($result->funding_received_date) ?
                            \Carbon\Carbon::createFromFormat('Y-m-d',$result->funding_received_date)->format('d F Y')
                            : "",
                        ($result->mfo_received_date) ?
                            \Carbon\Carbon::createFromFormat('Y-m-d',$result->mfo_received_date)->format('d F Y')
                            : "",
                        ($result->coa_approved_date) ?
                            \Carbon\Carbon::createFromFormat('Y-m-d',$result->coa_approved_date)->format('d F Y')
                            : "",
                        ($result->ntp_date) ?
                            \Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$result->ntp_date)->format('d F Y')
                            : "",
                        ($result->ntp_award_date) ?
                            \Carbon\Carbon::createFromFormat('Y-m-d',$result->ntp_award_date)->format('d F Y')
                            : "",
                        ($result->delivery_date) ?
                            \Carbon\Carbon::createFromFormat('Y-m-d',$result->delivery_date)->format('d F Y')
                            : "",
                        ($result->dr_coa_date) ?
                            \Carbon\Carbon::createFromFormat('Y-m-d',$result->dr_coa_date)->format('d F Y')
                            : "",
                        ($result->dr_inspection) ?
                            \Carbon\Carbon::createFromFormat('Y-m-d',$result->dr_inspection)->format('d F Y')
                            : "",
                        ($result->iar_accepted_date) ?
                            \Carbon\Carbon::createFromFormat('Y-m-d',$result->iar_accepted_date)->format('d F Y')
                            : "",
                        ($result->di_start) ?
                            \Carbon\Carbon::createFromFormat('Y-m-d',$result->di_start)->format('d F Y')
                            : "",
                        ($result->di_close) ?
                            \Carbon\Carbon::createFromFormat('Y-m-d',$result->di_close)->format('d F Y')
                            : "",
                        ($result->v_transaction_date) ?
                            \Carbon\Carbon::createFromFormat('Y-m-d',$result->v_transaction_date)->format('d F Y')
                            : "",
                        ($result->preaudit_date) ?
                            \Carbon\Carbon::createFromFormat('Y-m-d',$result->preaudit_date)->format('d F Y')
                            : "",
                    ];
                }



                $sheet->row(7, $newdata);


            });

        })->export('xlsx');
    }


}
