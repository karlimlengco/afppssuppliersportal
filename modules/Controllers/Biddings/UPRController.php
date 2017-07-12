<?php

namespace Revlv\Controllers\Biddings;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use DB;
use Excel;
use PDF;

use \Revlv\Settings\Holidays\HolidayRepository;
use \Revlv\Procurements\UnitPurchaseRequests\UnitPurchaseRequestRepository;
use \Revlv\Procurements\UnitPurchaseRequests\Attachments\AttachmentRepository;
use \Revlv\Procurements\UnitPurchaseRequests\UnitPurchaseRequestRequest;
use \Revlv\Procurements\UnitPurchaseRequests\UnitPurchaseRequestUpdateRequest;
use \Revlv\Procurements\Items\ItemRepository;
use \Revlv\Settings\Signatories\SignatoryRepository;
use \Revlv\Settings\AccountCodes\AccountCodeRepository;
use \Revlv\Settings\Chargeability\ChargeabilityRepository;
use \Revlv\Settings\ModeOfProcurements\ModeOfProcurementRepository;
use \Revlv\Settings\ProcurementCenters\ProcurementCenterRepository;
use \Revlv\Settings\PaymentTerms\PaymentTermRepository;
use \Revlv\Settings\Units\UnitRepository;
use \Revlv\Settings\AuditLogs\AuditLogRepository;
use \Revlv\Settings\ProcurementTypes\ProcurementTypeRepository;
use \Revlv\Settings\CateredUnits\CateredUnitRepository;
use \Revlv\Users\Logs\UserLogRepository;

use Revlv\Procurements\UnitPurchaseRequests\Traits\FileTrait;
use Revlv\Procurements\UnitPurchaseRequests\Traits\ImportTrait;

class UPRController extends Controller
{
    use FileTrait, ImportTrait;

    /**
     * [Base Route of Controller]
     *
     * @var string
     */
    protected $baseUrl  =   "biddings.unit-purchase-requests.";

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
    protected $items;
    protected $units;
    protected $holidays;
    protected $logs;
    protected $signatories;
    protected $types;
    protected $users;
    protected $userLogs;

    /**
     * [$model description]
     *
     * @var [type]
     */
    protected $model;

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
        $user   =   \Sentinel::getUser();
        if(array_key_exists("superuser", $user->permissions) || array_key_exists("admin", $user->permissions))
        {
            return $model->getDatatable(null, 'bidding');
        }
        return $model->getDatatable($user->id, 'bidding');
    }



    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->view('modules.biddings.upr.index',[
            'createRoute'   =>  $this->baseUrl."create",
            'importRoute'   =>  $this->baseUrl."imports"
        ]);
    }

}
