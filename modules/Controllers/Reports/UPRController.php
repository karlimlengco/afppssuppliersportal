<?php

namespace Revlv\Controllers\Reports;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;


use \Revlv\Settings\Units\UnitRepository;
use \Revlv\Procurements\BlankRequestForQuotation\BlankRFQRepository;
use \Revlv\Procurements\UnitPurchaseRequests\UnitPurchaseRequestRepository;

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
