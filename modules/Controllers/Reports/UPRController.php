<?php

namespace Revlv\Controllers\Reports;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;


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
    public function getPrograms(ProcurementCenterRepository $model, $type = null)
    {
        return $model->getPrograms(null, $type);
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


}
