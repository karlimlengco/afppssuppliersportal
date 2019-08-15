<?php

namespace Revlv\Controllers;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use PDF;
use Excel;
use Sentinel;

use \Revlv\Settings\Units\UnitRepository;
use \Revlv\Procurements\BlankRequestForQuotation\BlankRFQRepository;
use \Revlv\Procurements\UnitPurchaseRequests\UnitPurchaseRequestRepository;
use \Revlv\Settings\ProcurementCenters\ProcurementCenterRepository;

class DashboardController extends Controller
{

    /**
     * [$blankRfq description]
     *
     * @var [type]
     */
    protected $model;


    /**
     * @param center $center
     */
    public function __construct(UnitPurchaseRequestRepository $model)
    {
        $this->model  =   $model;
        parent::__construct();
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(UnitPurchaseRequestRepository $model)
    {

        return $this->view('modules.dashboard');
    }
}
