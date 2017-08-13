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
        Excel::create("Timeline", function($excel)use ($result, $from ,$to) {
            $excel->sheet('Page1', function($sheet)use ($result, $from ,$to) {
                if($from != null)
                $from = \Carbon\Carbon::createFromFormat('Y-m-d', $from)->format('d F Y');

                if($to != null)
                $to = \Carbon\Carbon::createFromFormat('Y-m-d', $to)->format('d F Y');

                $count = 6;
                $sheet->row(1, ['Timeline']);
                $sheet->row(2, ["ALTERNATIVE METHOD OF PROCUREMENT"]);
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

                $sheet->row(6, [
                    'UPR',
                    'Create RFQ',
                    'Close RFQ',
                    'ISPQ',
                    'PhilGeps',
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
                    'Received DR',
                    'Delivery To COA',
                    'Inspection',
                    'Inspection Acceptance',
                    'Inspection of Delivered Items',
                    'Prepare Certificate of Inspection',
                    'Create Voucher',
                    'PreAudit',
                    'Certify',
                    'JEV',
                    'Voucher Approval',
                    'Release Payment',
                    // 'Received Payment',
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
