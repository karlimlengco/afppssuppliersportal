<?php

namespace Revlv\Controllers\Notifications;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Carbon\Carbon;


use \Revlv\Settings\Units\UnitRepository;
use \Revlv\Settings\Holidays\HolidayRepository;
use \Revlv\Procurements\BlankRequestForQuotation\BlankRFQRepository;
use \Revlv\Procurements\UnitPurchaseRequests\UnitPurchaseRequestRepository;

class NotificationController extends Controller
{

    /**
     * [$blankRfq description]
     *
     * @var [type]
     */
    protected $blankRfq;
    protected $model;
    protected $units;
    protected $holidays;


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
    public function index(UnitPurchaseRequestRepository $model, HolidayRepository $holidays)
    {

        $holiday_lists  =   $holidays->lists('id','holiday_date');
        $h_lists        =   [];
        foreach($holiday_lists as $hols)
        {
            $h_lists[]  =   Carbon::createFromFormat('Y-m-d', $hols)->format('Y-m-d');
        }

        $delays         =   $model->getDelays();
        $newCollection  =   collect([]);
        $today          =   Carbon::now()->format('Y-m-d');
        $today          =   Carbon::createFromFormat('Y-m-d', $today);

        foreach($delays as $key => $item)
        {
            $upr_created    = Carbon::createFromFormat('Y-m-d H:i:s', $item->upr_created_at);

            //UPR to RFQ Count
            if($item->rfq_created_at == null && $today->gte($upr_created) )
            {
                // Count working days
                $days = $today->diffInDaysFiltered(function (Carbon $date) use ($h_lists) {
                    return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists);
                }, $upr_created);

                $data   =   [
                    'id'                =>  $item->id,
                    'project_name'      =>  $item->project_name,
                    'upr_number'        =>  $item->upr_number,
                    'ref_number'        =>  $item->ref_number,
                    'total_amount'      =>  $item->total_amount,
                    'date_prepared'     =>  $item->date_prepared,
                    'state'             =>  $item->state,
                    'event'             =>  "UPR to RFQ",
                    'status'            =>  "Delay",
                    'days'              =>  $days,
                    'transaction_date'  =>  $upr_created->format('Y-m-d'),
                ];

                if($days >= 1)
                {
                    $newCollection->push($data);
                }
            }
            //UPR to RFQ Count

            // Close RFQ
            if($item->rfq_created_at != null && $item->rfq_completed_at == null )
            {
                $rfq_created_at    = Carbon::createFromFormat('Y-m-d', $item->rfq_created_at);
                // Count working days
                $days = $today->diffInDaysFiltered(function (Carbon $date) use ($h_lists) {
                    return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists);
                }, $rfq_created_at);


                $data   =   [
                    'id'                =>  $item->id,
                    'project_name'      =>  $item->project_name,
                    'upr_number'        =>  $item->upr_number,
                    'ref_number'        =>  $item->ref_number,
                    'total_amount'      =>  $item->total_amount,
                    'date_prepared'     =>  $item->date_prepared,
                    'state'             =>  $item->state,
                    'event'             =>  "Closing RFQ",
                    'status'            =>  "Delay",
                    'days'              =>  $days,
                    'transaction_date'  =>  $rfq_created_at->format('Y-m-d'),
                ];

                if($days >= 1)
                {
                    $newCollection->push($data);
                }
            }
            // Close RFQ

            // RFQ Create Invitation
            if($item->rfq_completed_at != null && $item->ispq_transaction_date == null )
            {
                $rfq_completed_at    = Carbon::createFromFormat('Y-m-d H:i:s', $item->rfq_completed_at);
                // Count working days
                $days = $today->diffInDaysFiltered(function (Carbon $date) use ($h_lists) {
                    return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists);
                }, $rfq_completed_at);


                $data   =   [
                    'id'                =>  $item->id,
                    'project_name'      =>  $item->project_name,
                    'upr_number'        =>  $item->upr_number,
                    'ref_number'        =>  $item->ref_number,
                    'total_amount'      =>  $item->total_amount,
                    'date_prepared'     =>  $item->date_prepared,
                    'state'             =>  $item->state,
                    'event'             =>  "Create Invitation",
                    'status'            =>  "Delay",
                    'days'              =>  $days,
                    'transaction_date'  =>  $rfq_completed_at->format('Y-m-d'),
                ];

                if($days >= 1)
                {
                    $newCollection->push($data);
                }
            }
            // RFQ Create Invitation


            // Philgeps posting
            if($item->ispq_transaction_date != null && $item->pp_completed_at == null )
            {
                $ispq_transaction_date    = Carbon::createFromFormat('Y-m-d', $item->ispq_transaction_date);
                // Count working days
                $days = $today->diffInDaysFiltered(function (Carbon $date) use ($h_lists) {
                    return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists);
                }, $ispq_transaction_date);


                $data   =   [
                    'id'                =>  $item->id,
                    'project_name'      =>  $item->project_name,
                    'upr_number'        =>  $item->upr_number,
                    'ref_number'        =>  $item->ref_number,
                    'total_amount'      =>  $item->total_amount,
                    'date_prepared'     =>  $item->date_prepared,
                    'state'             =>  $item->state,
                    'event'             =>  "PhilGeps Posting",
                    'status'            =>  "Delay",
                    'days'              =>  $days,
                    'transaction_date'  =>  $ispq_transaction_date->addDays(3)->format('Y-m-d'),
                ];

                if($days >= 3)
                {
                    $newCollection->push($data);
                }
            }
            // Philgeps posting


            // Canvass posting
            if($item->ispq_transaction_date != null && $item->canvass_start_date == null )
            {
                $ispq_transaction_date    = Carbon::createFromFormat('Y-m-d', $item->ispq_transaction_date);
                // Count working days
                $days = $today->diffInDaysFiltered(function (Carbon $date) use ($h_lists) {
                    return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists);
                }, $ispq_transaction_date);


                $data   =   [
                    'id'                =>  $item->id,
                    'project_name'      =>  $item->project_name,
                    'upr_number'        =>  $item->upr_number,
                    'ref_number'        =>  $item->ref_number,
                    'total_amount'      =>  $item->total_amount,
                    'date_prepared'     =>  $item->date_prepared,
                    'state'             =>  $item->state,
                    'event'             =>  "Opening Canvass",
                    'status'            =>  "Delay",
                    'days'              =>  $days,
                    'transaction_date'  =>  $ispq_transaction_date->format('Y-m-d'),
                ];

                if($days >= 1)
                {
                    $newCollection->push($data);
                }
            }
            // Canvass posting

            // Awarding
            if($item->canvass_start_date != null && $item->noa_award_date == null )
            {
                $canvass_start_date    = Carbon::createFromFormat('Y-m-d', $item->canvass_start_date);
                // Count working days
                $days = $today->diffInDaysFiltered(function (Carbon $date) use ($h_lists) {
                    return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists);
                }, $canvass_start_date);


                $data   =   [
                    'id'                =>  $item->id,
                    'project_name'      =>  $item->project_name,
                    'upr_number'        =>  $item->upr_number,
                    'ref_number'        =>  $item->ref_number,
                    'total_amount'      =>  $item->total_amount,
                    'date_prepared'     =>  $item->date_prepared,
                    'state'             =>  $item->state,
                    'event'             =>  "Prepare NOA For Signature",
                    'status'            =>  "Delay",
                    'days'              =>  $days,
                    'transaction_date'  =>  $canvass_start_date->addDays(2)->format('Y-m-d'),
                ];

                if($days >= 2)
                {
                    $newCollection->push($data);
                }
            }
            // Awarding
            //
            // NOA Approved
            if($item->noa_award_date != null && $item->noa_approved_date == null )
            {
                $noa_award_date    = Carbon::createFromFormat('Y-m-d H:i:s', $item->noa_award_date);
                // Count working days
                $days = $today->diffInDaysFiltered(function (Carbon $date) use ($h_lists) {
                    return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists);
                }, $noa_award_date);


                $data   =   [
                    'id'                =>  $item->id,
                    'project_name'      =>  $item->project_name,
                    'upr_number'        =>  $item->upr_number,
                    'ref_number'        =>  $item->ref_number,
                    'total_amount'      =>  $item->total_amount,
                    'date_prepared'     =>  $item->date_prepared,
                    'state'             =>  $item->state,
                    'event'             =>  "Approval of NOA",
                    'status'            =>  "Delay",
                    'days'              =>  $days,
                    'transaction_date'  =>  $noa_award_date->addDays(1)->format('Y-m-d'),
                ];

                if($days >= 1)
                {
                    $newCollection->push($data);
                }
            }
            // NOA Approved

            // NOA Receieved
            if($item->noa_approved_date != null && $item->noa_award_accepted_date == null )
            {
                $noa_approved_date    = Carbon::createFromFormat('Y-m-d', $item->noa_approved_date);
                // Count working days
                $days = $today->diffInDaysFiltered(function (Carbon $date) use ($h_lists) {
                    return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists);
                }, $noa_approved_date);

                $data   =   [
                    'id'                =>  $item->id,
                    'project_name'      =>  $item->project_name,
                    'upr_number'        =>  $item->upr_number,
                    'ref_number'        =>  $item->ref_number,
                    'total_amount'      =>  $item->total_amount,
                    'date_prepared'     =>  $item->date_prepared,
                    'state'             =>  $item->state,
                    'event'             =>  "Receiving of NOA",
                    'status'            =>  "Delay",
                    'days'              =>  $days,
                    'transaction_date'  =>  $noa_approved_date->addDays(1)->format('Y-m-d'),
                ];

                if($days >= 1)
                {
                    $newCollection->push($data);
                }
            }
            // NOA Receieved

            // PO Creation
            if($item->noa_award_accepted_date != null && $item->po_create_date == null )
            {
                $noa_award_accepted_date    = Carbon::createFromFormat('Y-m-d', $item->noa_award_accepted_date);
                // Count working days
                $days = $today->diffInDaysFiltered(function (Carbon $date) use ($h_lists) {
                    return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists);
                }, $noa_award_accepted_date);

                $data   =   [
                    'id'                =>  $item->id,
                    'project_name'      =>  $item->project_name,
                    'upr_number'        =>  $item->upr_number,
                    'ref_number'        =>  $item->ref_number,
                    'total_amount'      =>  $item->total_amount,
                    'date_prepared'     =>  $item->date_prepared,
                    'state'             =>  $item->state,
                    'event'             =>  "Creating PO",
                    'status'            =>  "Delay",
                    'days'              =>  $days,
                    'transaction_date'  =>  $noa_award_accepted_date->addDays(2)->format('Y-m-d'),
                ];

                if($days >= 2)
                {
                    $newCollection->push($data);
                }
            }
            // PO Creation

            // PO Fund Release
            if($item->po_create_date != null && $item->funding_received_date == null )
            {
                $po_create_date    = Carbon::createFromFormat('Y-m-d', $item->po_create_date);
                // Count working days
                $days = $today->diffInDaysFiltered(function (Carbon $date) use ($h_lists) {
                    return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists);
                }, $po_create_date);

                $data   =   [
                    'id'                =>  $item->id,
                    'project_name'      =>  $item->project_name,
                    'upr_number'        =>  $item->upr_number,
                    'ref_number'        =>  $item->ref_number,
                    'total_amount'      =>  $item->total_amount,
                    'date_prepared'     =>  $item->date_prepared,
                    'state'             =>  $item->state,
                    'event'             =>  "PO Funds Approval",
                    'status'            =>  "Delay",
                    'days'              =>  $days,
                    'transaction_date'  =>  $po_create_date->addDays(2)->format('Y-m-d'),
                ];

                if($days >= 2)
                {
                    $newCollection->push($data);
                }
            }
            // PO Fund Release

            // PO MFO Release
            if($item->funding_received_date != null && $item->mfo_received_date == null )
            {
                $funding_received_date    = Carbon::createFromFormat('Y-m-d', $item->funding_received_date);
                // Count working days
                $days = $today->diffInDaysFiltered(function (Carbon $date) use ($h_lists) {
                    return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists);
                }, $funding_received_date);

                $data   =   [
                    'id'                =>  $item->id,
                    'project_name'      =>  $item->project_name,
                    'upr_number'        =>  $item->upr_number,
                    'ref_number'        =>  $item->ref_number,
                    'total_amount'      =>  $item->total_amount,
                    'date_prepared'     =>  $item->date_prepared,
                    'state'             =>  $item->state,
                    'event'             =>  "PO MFO Approval",
                    'status'            =>  "Delay",
                    'days'              =>  $days,
                    'transaction_date'  =>  $funding_received_date->addDays(2)->format('Y-m-d'),
                ];

                if($days >= 2)
                {
                    $newCollection->push($data);
                }
            }
            // PO MFO Release

            // PO COA Approval
            if($item->mfo_received_date != null && $item->coa_approved_date == null )
            {
                $mfo_received_date    = Carbon::createFromFormat('Y-m-d', $item->mfo_received_date);
                // Count working days
                $days = $today->diffInDaysFiltered(function (Carbon $date) use ($h_lists) {
                    return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists);
                }, $mfo_received_date);

                $data   =   [
                    'id'                =>  $item->id,
                    'project_name'      =>  $item->project_name,
                    'upr_number'        =>  $item->upr_number,
                    'ref_number'        =>  $item->ref_number,
                    'total_amount'      =>  $item->total_amount,
                    'date_prepared'     =>  $item->date_prepared,
                    'state'             =>  $item->state,
                    'event'             =>  "PO Approval",
                    'status'            =>  "Delay",
                    'days'              =>  $days,
                    'transaction_date'  =>  $mfo_received_date->addDays(1)->format('Y-m-d'),
                ];

                if($days >= 1)
                {
                    $newCollection->push($data);
                }
            }
            // PO COA Approval

            // NTP Create
            if($item->coa_approved_date != null && $item->ntp_date == null )
            {
                $coa_approved_date    = Carbon::createFromFormat('Y-m-d', $item->coa_approved_date);
                // Count working days
                $days = $today->diffInDaysFiltered(function (Carbon $date) use ($h_lists) {
                    return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists);
                }, $coa_approved_date);

                $data   =   [
                    'id'                =>  $item->id,
                    'project_name'      =>  $item->project_name,
                    'upr_number'        =>  $item->upr_number,
                    'ref_number'        =>  $item->ref_number,
                    'total_amount'      =>  $item->total_amount,
                    'date_prepared'     =>  $item->date_prepared,
                    'state'             =>  $item->state,
                    'event'             =>  "Prepare NTP",
                    'status'            =>  "Delay",
                    'days'              =>  $days,
                    'transaction_date'  =>  $coa_approved_date->addDays(1)->format('Y-m-d'),
                ];

                if($days >= 1)
                {
                    $newCollection->push($data);
                }
            }
            // NTP Create

            // NTP award
            if($item->ntp_date != null && $item->ntp_award_date == null )
            {
                $ntp_date    = Carbon::createFromFormat('Y-m-d H:i:s', $item->ntp_date);
                // Count working days
                $days = $today->diffInDaysFiltered(function (Carbon $date) use ($h_lists) {
                    return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists);
                }, $ntp_date);

                $data   =   [
                    'id'                =>  $item->id,
                    'project_name'      =>  $item->project_name,
                    'upr_number'        =>  $item->upr_number,
                    'ref_number'        =>  $item->ref_number,
                    'total_amount'      =>  $item->total_amount,
                    'date_prepared'     =>  $item->date_prepared,
                    'state'             =>  $item->state,
                    'event'             =>  "Received NTP",
                    'status'            =>  "Delay",
                    'days'              =>  $days,
                    'transaction_date'  =>  $ntp_date->addDays(1)->format('Y-m-d'),
                ];

                if($days >= 1)
                {
                    $newCollection->push($data);
                }
            }
            // NTP award

            // NOD
            if($item->ntp_award_date != null && $item->dr_date == null )
            {
                $ntp_award_date    = Carbon::createFromFormat('Y-m-d', $item->ntp_award_date);
                // Count working days
                $days = $today->diffInDaysFiltered(function (Carbon $date) use ($h_lists) {
                    return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists);
                }, $ntp_award_date);

                $data   =   [
                    'id'                =>  $item->id,
                    'project_name'      =>  $item->project_name,
                    'upr_number'        =>  $item->upr_number,
                    'ref_number'        =>  $item->ref_number,
                    'total_amount'      =>  $item->total_amount,
                    'date_prepared'     =>  $item->date_prepared,
                    'state'             =>  $item->state,
                    'event'             =>  "Notice of Delivery",
                    'status'            =>  "Delay",
                    'days'              =>  $days,
                    'transaction_date'  =>  $ntp_award_date->addDays(1)->format('Y-m-d'),
                ];

                if($days >= 1)
                {
                    $newCollection->push($data);
                }
            }
            // NOD

            // NOD delivery
            if($item->dr_date != null && $item->delivery_date == null )
            {
                $dr_date    = Carbon::createFromFormat('Y-m-d', $item->dr_date);
                // Count working days
                $days = $today->diffInDaysFiltered(function (Carbon $date) use ($h_lists) {
                    return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists);
                }, $dr_date);

                $data   =   [
                    'id'                =>  $item->id,
                    'project_name'      =>  $item->project_name,
                    'upr_number'        =>  $item->upr_number,
                    'ref_number'        =>  $item->ref_number,
                    'total_amount'      =>  $item->total_amount,
                    'date_prepared'     =>  $item->date_prepared,
                    'state'             =>  $item->state,
                    'event'             =>  "Receive Delivery",
                    'status'            =>  "Delay",
                    'days'              =>  $days,
                    'transaction_date'  =>  $dr_date->addDays(7)->format('Y-m-d'),
                ];

                if($days >= 7)
                {
                    $newCollection->push($data);
                }
            }
            // NOD delivery

            // COA Delivery
            if($item->delivery_date != null && $item->dr_coa_date == null )
            {
                $delivery_date    = Carbon::createFromFormat('Y-m-d', $item->delivery_date);
                // Count working days
                $days = $today->diffInDaysFiltered(function (Carbon $date) use ($h_lists) {
                    return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists);
                }, $delivery_date);

                $data   =   [
                    'id'                =>  $item->id,
                    'project_name'      =>  $item->project_name,
                    'upr_number'        =>  $item->upr_number,
                    'ref_number'        =>  $item->ref_number,
                    'total_amount'      =>  $item->total_amount,
                    'date_prepared'     =>  $item->date_prepared,
                    'state'             =>  $item->state,
                    'event'             =>  "COA Delivery",
                    'status'            =>  "Delay",
                    'days'              =>  $days,
                    'transaction_date'  =>  $delivery_date->addDays(1)->format('Y-m-d'),
                ];

                if($days >= 1)
                {
                    $newCollection->push($data);
                }
            }
            // COA Delivery

            // Delivery Inspection
            if($item->dr_coa_date != null && $item->dr_inspection == null )
            {
                $dr_coa_date    = Carbon::createFromFormat('Y-m-d', $item->dr_coa_date);
                // Count working days
                $days = $today->diffInDaysFiltered(function (Carbon $date) use ($h_lists) {
                    return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists);
                }, $dr_coa_date);

                $data   =   [
                    'id'                =>  $item->id,
                    'project_name'      =>  $item->project_name,
                    'upr_number'        =>  $item->upr_number,
                    'ref_number'        =>  $item->ref_number,
                    'total_amount'      =>  $item->total_amount,
                    'date_prepared'     =>  $item->date_prepared,
                    'state'             =>  $item->state,
                    'event'             =>  "TIAC",
                    'status'            =>  "Delay",
                    'days'              =>  $days,
                    'transaction_date'  =>  $dr_coa_date->addDays(1)->format('Y-m-d'),
                ];

                if($days >= 1)
                {
                    $newCollection->push($data);
                }
            }
            // Delivery Inspection

            // IAR Acceptance
            if($item->dr_inspection != null && $item->iar_accepted_date == null )
            {
                $dr_inspection    = Carbon::createFromFormat('Y-m-d', $item->dr_inspection);
                // Count working days
                $days = $today->diffInDaysFiltered(function (Carbon $date) use ($h_lists) {
                    return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists);
                }, $dr_inspection);

                $data   =   [
                    'id'                =>  $item->id,
                    'project_name'      =>  $item->project_name,
                    'upr_number'        =>  $item->upr_number,
                    'ref_number'        =>  $item->ref_number,
                    'total_amount'      =>  $item->total_amount,
                    'date_prepared'     =>  $item->date_prepared,
                    'state'             =>  $item->state,
                    'event'             =>  "IAR Acceptance",
                    'status'            =>  "Delay",
                    'days'              =>  $days,
                    'transaction_date'  =>  $dr_inspection->addDays(1)->format('Y-m-d'),
                ];

                if($days >= 1)
                {
                    $newCollection->push($data);
                }
            }
            // IAR Acceptance

            // DR Inspection Start
            if($item->iar_accepted_date != null && $item->di_start == null )
            {
                $iar_accepted_date    = Carbon::createFromFormat('Y-m-d', $item->iar_accepted_date);
                // Count working days
                $days = $today->diffInDaysFiltered(function (Carbon $date) use ($h_lists) {
                    return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists);
                }, $iar_accepted_date);

                $data   =   [
                    'id'                =>  $item->id,
                    'project_name'      =>  $item->project_name,
                    'upr_number'        =>  $item->upr_number,
                    'ref_number'        =>  $item->ref_number,
                    'total_amount'      =>  $item->total_amount,
                    'date_prepared'     =>  $item->date_prepared,
                    'state'             =>  $item->state,
                    'event'             =>  "Start Delivered Inspection",
                    'status'            =>  "Delay",
                    'days'              =>  $days,
                    'transaction_date'  =>  $iar_accepted_date->addDays(1)->format('Y-m-d'),
                ];

                if($days >= 1)
                {
                    $newCollection->push($data);
                }
            }
            if($item->di_start != null && $item->di_close == null )
            {
                $di_start    = Carbon::createFromFormat('Y-m-d', $item->di_start);
                // Count working days
                $days = $today->diffInDaysFiltered(function (Carbon $date) use ($h_lists) {
                    return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists);
                }, $di_start);

                $data   =   [
                    'id'                =>  $item->id,
                    'project_name'      =>  $item->project_name,
                    'upr_number'        =>  $item->upr_number,
                    'ref_number'        =>  $item->ref_number,
                    'total_amount'      =>  $item->total_amount,
                    'date_prepared'     =>  $item->date_prepared,
                    'state'             =>  $item->state,
                    'event'             =>  "Close Delivered Inspection",
                    'status'            =>  "Delay",
                    'days'              =>  $days,
                    'transaction_date'  =>  $di_start->addDays(1)->format('Y-m-d'),
                ];

                if($days >= 1)
                {
                    $newCollection->push($data);
                }
            }
            // DR Inspection Start
            //
            // Voucher Create
            if($item->di_close != null && $item->v_transaction_date == null )
            {
                $di_close    = Carbon::createFromFormat('Y-m-d', $item->di_close);
                // Count working days
                $days = $today->diffInDaysFiltered(function (Carbon $date) use ($h_lists) {
                    return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists);
                }, $di_close);

                $data   =   [
                    'id'                =>  $item->id,
                    'project_name'      =>  $item->project_name,
                    'upr_number'        =>  $item->upr_number,
                    'ref_number'        =>  $item->ref_number,
                    'total_amount'      =>  $item->total_amount,
                    'date_prepared'     =>  $item->date_prepared,
                    'state'             =>  $item->state,
                    'event'             =>  "Create Voucher",
                    'status'            =>  "Delay",
                    'days'              =>  $days,
                    'transaction_date'  =>  $di_close->addDays(1)->format('Y-m-d'),
                ];

                if($days >= 1)
                {
                    $newCollection->push($data);
                }
            }
            // Voucher Create

            // Voucher Preaudit
            if($item->v_transaction_date != null && $item->preaudit_date == null )
            {
                $v_transaction_date    = Carbon::createFromFormat('Y-m-d', $item->v_transaction_date);
                // Count working days
                $days = $today->diffInDaysFiltered(function (Carbon $date) use ($h_lists) {
                    return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists);
                }, $v_transaction_date);

                $data   =   [
                    'id'                =>  $item->id,
                    'project_name'      =>  $item->project_name,
                    'upr_number'        =>  $item->upr_number,
                    'ref_number'        =>  $item->ref_number,
                    'total_amount'      =>  $item->total_amount,
                    'date_prepared'     =>  $item->date_prepared,
                    'state'             =>  $item->state,
                    'event'             =>  "Voucher PreAudit",
                    'status'            =>  "Delay",
                    'days'              =>  $days,
                    'transaction_date'  =>  $v_transaction_date->addDays(1)->format('Y-m-d'),
                ];

                if($days >= 1)
                {
                    $newCollection->push($data);
                }
            }
            // Voucher Preaudit

            // Voucher Certify
            if($item->preaudit_date != null && $item->certify_date == null )
            {
                $preaudit_date    = Carbon::createFromFormat('Y-m-d', $item->preaudit_date);
                // Count working days
                $days = $today->diffInDaysFiltered(function (Carbon $date) use ($h_lists) {
                    return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists);
                }, $preaudit_date);

                $data   =   [
                    'id'                =>  $item->id,
                    'project_name'      =>  $item->project_name,
                    'upr_number'        =>  $item->upr_number,
                    'ref_number'        =>  $item->ref_number,
                    'total_amount'      =>  $item->total_amount,
                    'date_prepared'     =>  $item->date_prepared,
                    'state'             =>  $item->state,
                    'event'             =>  "Voucher Certificate",
                    'status'            =>  "Delay",
                    'days'              =>  $days,
                    'transaction_date'  =>  $preaudit_date->addDays(1)->format('Y-m-d'),
                ];

                if($days >= 1)
                {
                    $newCollection->push($data);
                }
            }
            // Voucher Certify

            // Voucher JEV
            if($item->certify_date != null && $item->journal_entry_date == null )
            {
                $certify_date    = Carbon::createFromFormat('Y-m-d', $item->certify_date);
                // Count working days
                $days = $today->diffInDaysFiltered(function (Carbon $date) use ($h_lists) {
                    return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists);
                }, $certify_date);

                $data   =   [
                    'id'                =>  $item->id,
                    'project_name'      =>  $item->project_name,
                    'upr_number'        =>  $item->upr_number,
                    'ref_number'        =>  $item->ref_number,
                    'total_amount'      =>  $item->total_amount,
                    'date_prepared'     =>  $item->date_prepared,
                    'state'             =>  $item->state,
                    'event'             =>  "Voucher Journal Entry",
                    'status'            =>  "Delay",
                    'days'              =>  $days,
                    'transaction_date'  =>  $certify_date->addDays(1)->format('Y-m-d'),
                ];

                if($days >= 1)
                {
                    $newCollection->push($data);
                }
            }
            // Voucher JEV

            // Voucher Approval
            if($item->journal_entry_date != null && $item->vou_approval_date == null )
            {
                $journal_entry_date    = Carbon::createFromFormat('Y-m-d', $item->journal_entry_date);
                // Count working days
                $days = $today->diffInDaysFiltered(function (Carbon $date) use ($h_lists) {
                    return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists);
                }, $journal_entry_date);

                $data   =   [
                    'id'                =>  $item->id,
                    'project_name'      =>  $item->project_name,
                    'upr_number'        =>  $item->upr_number,
                    'ref_number'        =>  $item->ref_number,
                    'total_amount'      =>  $item->total_amount,
                    'date_prepared'     =>  $item->date_prepared,
                    'state'             =>  $item->state,
                    'event'             =>  "Voucher Approval",
                    'status'            =>  "Delay",
                    'days'              =>  $days,
                    'transaction_date'  =>  $journal_entry_date->addDays(1)->format('Y-m-d'),
                ];

                if($days >= 1)
                {
                    $newCollection->push($data);
                }
            }
            // Voucher Approva

            // Voucher Release
            if($item->vou_approval_date != null && $item->vou_release == null )
            {
                $vou_approval_date    = Carbon::createFromFormat('Y-m-d', $item->vou_approval_date);
                // Count working days
                $days = $today->diffInDaysFiltered(function (Carbon $date) use ($h_lists) {
                    return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists);
                }, $vou_approval_date);

                $data   =   [
                    'id'                =>  $item->id,
                    'project_name'      =>  $item->project_name,
                    'upr_number'        =>  $item->upr_number,
                    'ref_number'        =>  $item->ref_number,
                    'total_amount'      =>  $item->total_amount,
                    'date_prepared'     =>  $item->date_prepared,
                    'state'             =>  $item->state,
                    'event'             =>  "Voucher Release",
                    'status'            =>  "Delay",
                    'days'              =>  $days,
                    'transaction_date'  =>  $vou_approval_date->addDays(1)->format('Y-m-d'),
                ];

                if($days >= 1)
                {
                    $newCollection->push($data);
                }
            }
            // Voucher Release

            // Voucher Received1
            if($item->vou_release != null && $item->vou_received == null )
            {
                $vou_release    = Carbon::createFromFormat('Y-m-d', $item->vou_release);
                // Count working days
                $days = $today->diffInDaysFiltered(function (Carbon $date) use ($h_lists) {
                    return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists);
                }, $vou_release);

                $data   =   [
                    'id'                =>  $item->id,
                    'project_name'      =>  $item->project_name,
                    'upr_number'        =>  $item->upr_number,
                    'ref_number'        =>  $item->ref_number,
                    'total_amount'      =>  $item->total_amount,
                    'date_prepared'     =>  $item->date_prepared,
                    'state'             =>  $item->state,
                    'event'             =>  "Voucher Received1",
                    'status'            =>  "Delay",
                    'days'              =>  $days,
                    'transaction_date'  =>  $vou_release->addDays(1)->format('Y-m-d'),
                ];

                if($days >= 1)
                {
                    $newCollection->push($data);
                }
            }
            // Voucher Received1


        }

        return $this->view('modules.notifications.upr.index',[
            'resources'     =>  $newCollection
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


}
