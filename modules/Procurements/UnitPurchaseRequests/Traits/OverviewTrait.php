<?php

namespace Revlv\Procurements\UnitPurchaseRequests\Traits;

use Illuminate\Http\Request;
use DB;
use Datatables;
use Excel;
use PDF;
use \App\Support\Breadcrumb;
use App\Events\Event;

use \Revlv\Procurements\UnitPurchaseRequests\UnitPurchaseRequestRepository;
use \Revlv\Settings\AccountCodes\AccountCodeRepository;
use \Revlv\Settings\Chargeability\ChargeabilityRepository;
use \Revlv\Settings\ModeOfProcurements\ModeOfProcurementRepository;
use \Revlv\Settings\ProcurementCenters\ProcurementCenterRepository;
use \Revlv\Procurements\UnitPurchaseRequests\UnitPurchaseRequestRequest;
use \Revlv\Settings\PaymentTerms\PaymentTermRepository;
use \Revlv\Settings\ProcurementTypes\ProcurementTypeRepository;
use \Revlv\Settings\CateredUnits\CateredUnitRepository;

trait OverviewTrait
{
    /**
     * [$accounts description]
     *
     * @var [type]
     */
    protected $accounts;
    protected $chargeability;
    protected $modes;
    protected $centers;
    protected $terms;
    protected $units;
    protected $logs;
    protected $types;
    protected $upr;

    /**
     * [overviewCompleted description]
     *
     * @param  [type]                        $programs [description]
     * @param  UnitPurchaseRequestRepository $upr      [description]
     * @return [type]                                  [description]
     */
    public function overviewCompleted(Request $request, $programs, $pcco = null, $unit = null, UnitPurchaseRequestRepository $upr)
    {
        $result     =   $upr->findByPrograms($request->type, 'completed', $programs, $pcco, $unit);

        return $this->view('modules.overview.completed',[
            'result'    =>  $result,
            'breadcrumbs' => [
                new Breadcrumb('Overview'),
                new Breadcrumb('Programs '.$programs),
                new Breadcrumb('Completed'),
            ]
        ]);
    }

    /**
     * [overviewCancelled description]
     *
     * @param  [type]                        $programs [description]
     * @param  UnitPurchaseRequestRepository $upr      [description]
     * @return [type]                                  [description]
     */
    public function overviewCancelled(Request $request, $programs, $pcco = null, $unit = null, UnitPurchaseRequestRepository $upr)
    {
        $result     =   $upr->findByPrograms($request->type, 'cancelled', $programs, $pcco, $unit);

        return $this->view('modules.overview.cancelled',[
            'result'    =>  $result,
            'breadcrumbs' => [
                new Breadcrumb('Overview'),
                new Breadcrumb('Programs '.$programs),
                new Breadcrumb('Cancelled'),
            ]
        ]);
    }


    /**
     * [overviewOngoing description]
     *
     * @param  [type]                        $programs [description]
     * @param  UnitPurchaseRequestRepository $upr      [description]
     * @return [type]                                  [description]
     */
    public function overviewOngoing(Request $request, $programs, $pcco =null, $unit = null, UnitPurchaseRequestRepository $upr)
    {
        $result     =   $upr->findByPrograms($request->type, 'ongoing', $programs, $pcco, $unit);
        return $this->view('modules.overview.ongoing',[
            'result'    =>  $result,
            'breadcrumbs' => [
                new Breadcrumb('Overview'),
                new Breadcrumb('Programs '.$programs),
                new Breadcrumb('Ongoing'),
            ]
        ]);
    }


    /**
     * [overviewDelay description]
     *
     * @param  [type]                        $programs [description]
     * @param  UnitPurchaseRequestRepository $upr      [description]
     * @return [type]                                  [description]
     */
    public function overviewDelay(Request $request, $programs, $pcco =null, $unit = null, UnitPurchaseRequestRepository $upr)
    {
        $result     =   $upr->findByPrograms($request->type, 'delay', $programs, $pcco, $unit);
        return $this->view('modules.overview.delay',[
            'result'    =>  $result,
            'breadcrumbs' => [
                new Breadcrumb('Overview'),
                new Breadcrumb('Programs '.$programs),
                new Breadcrumb('Delay'),
            ]
        ]);
    }

}