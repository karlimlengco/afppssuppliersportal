<?php

namespace Revlv\Controllers;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use PDF;
use Excel;
use Carbon\Carbon;
use Sentinel;

use \Revlv\Settings\Units\UnitRepository;
use \Revlv\Procurements\BlankRequestForQuotation\BlankRFQRepository;
use \Revlv\Procurements\UnitPurchaseRequests\UnitPurchaseRequestEloquent;
use \Revlv\Procurements\UnitPurchaseRequests\UnitPurchaseRequestRepository;
use \Revlv\Settings\ProcurementCenters\ProcurementCenterRepository;

class ProcurementController extends Controller
{

    protected $accounting = [
        'Preparation of DV',
        'Prepare LDDAP-ADA',
        'Pre-Audit',
        'Journal Entry Voucher',
        'Sign Box A of DV',
        'MFO Obligation',
        'Counter Sign Cheque',
        'Receive Payment',
        'Sign LDDAP-ADA or Prepare Cheque',
        'Counter Sign Cheque',
        'Release LDDAP-ADA',
        'Prepare LDDAP-ADA',
        'Sign Box D of DV',
        'Sign Box C of DV',
    ];

    /**
     * [$blankRfq description]
     *
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
    public function ongoing(UnitPurchaseRequestEloquent $model, Request $request)
    {
        $user      = \Sentinel::getUser();
        $suppliers = $user->suppliers;
        $year      = '2021';
        $suppliers = json_decode($suppliers);

        // $resources  =    $resources->leftJoin('notice_of_awards', 'notice_of_awards.proponent_id', '=', 'rfq_proponents.id');
        if($user->user_type == 'supplier'){

            $resources =    $model->select([
                'unit_purchase_requests.id',
                'unit_purchase_requests.upr_number',
                'unit_purchase_requests.total_amount',
                'unit_purchase_requests.project_name',
                'unit_purchase_requests.date_prepared',
                'unit_purchase_requests.status',
                'unit_purchase_requests.units',
                'unit_purchase_requests.delay_count',
                'unit_purchase_requests.next_due',
                'unit_purchase_requests.next_allowable',
                'unit_purchase_requests.*',
                'unit_purchase_requests.next_step',
                'rfq_proponents.bid_amount',
                'rfq_proponents.id as rfq',
                'notice_of_awards.proponent_id'
            ]);

            $resources  =   $resources->leftJoin('notice_of_awards', 'notice_of_awards.upr_id', '=', 'unit_purchase_requests.id');


            $resources =    $resources->whereNull('notice_of_awards.id');
            // $resources =    $resources->leftJoin('request_for_quotations', 'request_for_quotations.upr_id', 'unit_purchase_requests.id');

            $resources =    $resources->leftJoin('rfq_proponents', 'rfq_proponents.id', 'notice_of_awards.proponent_id');

            $resources =    $resources->whereNotNull('rfq_proponents.id');

            $resources =    $resources->whereIn('rfq_proponents.proponents', $suppliers);

        }else{
            $resources =    $model->select([
                'unit_purchase_requests.id',
                'unit_purchase_requests.upr_number',
                'unit_purchase_requests.total_amount',
                'unit_purchase_requests.project_name',
                'unit_purchase_requests.date_prepared',
                'unit_purchase_requests.status',
                'unit_purchase_requests.units',
                'unit_purchase_requests.delay_count',
                'unit_purchase_requests.next_due',
                'unit_purchase_requests.next_allowable',
                'unit_purchase_requests.*',
                'unit_purchase_requests.next_step',
                'rfq_proponents.bid_amount',
                'rfq_proponents.id as rfq',
                'notice_of_awards.proponent_id'
            ]);



            $resources  =   $resources->leftJoin('notice_of_awards', 'notice_of_awards.upr_id', '=', 'unit_purchase_requests.id');

            $resources =    $resources->leftJoin('rfq_proponents', 'rfq_proponents.id', 'notice_of_awards.proponent_id');

            $resources =    $resources->whereNull('notice_of_awards.id');
            $resources =    $resources->where('unit_purchase_requests.units', $user->unit_id);
        }

        $resources =    $resources->where('unit_purchase_requests.status', '<>','completed');
        $resources =    $resources->where('unit_purchase_requests.status', '<>','cancelled');
        $resources =    $resources->where('unit_purchase_requests.status', '<>','cancel');

        if($request->has('search')){
            $search = $request->get('search');
            $resources =    $resources->where(function($query) use ($search){
                 $query->where('unit_purchase_requests.project_name', 'like', "%$search%");
                 $query->orWhere('unit_purchase_requests.upr_number', 'like', "%$search%");
                 $query->orWhere('unit_purchase_requests.status', 'like', "%$search%");
             });
        }

        $resources =    $resources->where(function($query) use ($year){
            $query->whereYear('unit_purchase_requests.date_processed', '>=', '2021');
            // $query->whereYear('unit_purchase_requests.date_processed', $year);
            // $query->orWhere(function($nest) use($year) {
            //     $nest->whereYear('unit_purchase_requests.date_processed', '2020');
            // });
         });
        $resources =    $resources->orderBy('date_prepared', 'desc');
        // dd($resources->get());
        $resources =    $resources->paginate(20);

        return $this->view('modules.procurements.ongoing',[
            'resources' => $resources,
            'accounting' => $this->accounting,
            'today' => Carbon::now()->format('Y-m-d')
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function awarded(UnitPurchaseRequestEloquent $model, Request $request)
    {
        $user      = \Sentinel::getUser();
        $suppliers = $user->suppliers;

        $suppliers = json_decode($suppliers);
        $year = '2021';

        $resources =    $model->select([
            'unit_purchase_requests.id',
            'unit_purchase_requests.upr_number',
            'unit_purchase_requests.total_amount',
            'unit_purchase_requests.project_name',
            'unit_purchase_requests.status',
            'unit_purchase_requests.date_prepared',
            'rfq_proponents.bid_amount',
            'unit_purchase_requests.units',
            'unit_purchase_requests.delay_count',
            'unit_purchase_requests.next_due',
            'unit_purchase_requests.next_allowable',
            'unit_purchase_requests.next_step',
            // 'notice_of_awards.*',
        ]);

        $resources  =   $resources->leftJoin('notice_of_awards', 'notice_of_awards.upr_id', '=', 'unit_purchase_requests.id');
        $resources =    $resources->leftJoin('rfq_proponents', 'rfq_proponents.id', 'notice_of_awards.proponent_id');

        if($user->user_type == 'supplier'){
            $resources =    $resources->whereIn('rfq_proponents.proponents', $suppliers);
        }else{
            $resources =    $resources->where('unit_purchase_requests.units', $user->unit_id);
        }
        $resources =    $resources->where('unit_purchase_requests.status', '<>','completed');
        $resources =    $resources->where('unit_purchase_requests.status', '<>','cancelled');
        $resources =    $resources->where('unit_purchase_requests.status', '<>','cancel');
        $resources =    $resources->where('unit_purchase_requests.status', '<>','completed');
        $resources =    $resources->whereNotNull('notice_of_awards.id');
        if($request->has('search')){
            $search = $request->get('search');
            $resources =    $resources->where(function($query) use ($search){
                 $query->where('unit_purchase_requests.project_name', 'like', "%$search%");
                 $query->orWhere('unit_purchase_requests.upr_number', 'like', "%$search%");
                 $query->orWhere('unit_purchase_requests.status', 'like', "%$search%");
             });
        }
        $resources =    $resources->where(function($query) use ($year){
            $query->whereYear('unit_purchase_requests.date_processed', '>=', '2021');
            // $query->whereYear('unit_purchase_requests.date_processed', $year);
            // $query->orWhere(function($nest) use($year) {
            //     $nest->whereYear('unit_purchase_requests.date_processed', '2020');
            // });
         });
        $resources =    $resources->orderBy('date_prepared', 'desc');
        // dd($resources->get());
        $resources =    $resources->paginate(20);


        return $this->view('modules.procurements.awarded',[
            'resources' => $resources,
            'accounting' => $this->accounting,
            'today' => Carbon::now()->format('Y-m-d')
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function failed(UnitPurchaseRequestEloquent $model, Request $request)
    {
        $user      = \Sentinel::getUser();
        $suppliers = $user->suppliers;
        $year = '2021';

        $suppliers = json_decode($suppliers);

        $resources =    $model->select([
            'unit_purchase_requests.id',
            'unit_purchase_requests.upr_number',
            'unit_purchase_requests.total_amount',
            'unit_purchase_requests.project_name',
            'unit_purchase_requests.status',
            'unit_purchase_requests.date_prepared',
            'rfq_proponents.bid_amount',
            'unit_purchase_requests.units',
            'unit_purchase_requests.delay_count',
            'unit_purchase_requests.next_due',
            'unit_purchase_requests.next_allowable',
            'unit_purchase_requests.next_step',
            'rfq_proponents.id as rfq',
            'notice_of_awards.proponent_id'
            // 'notice_of_awards.*',
        ]);

        $resources  =   $resources->leftJoin('notice_of_awards', 'notice_of_awards.upr_id', '=', 'unit_purchase_requests.id');
        $resources =    $resources->leftJoin('rfq_proponents', 'rfq_proponents.id', 'notice_of_awards.proponent_id');

        $resources =    $resources->whereNotNull('rfq_proponents.id');
        $resources =    $resources->where('unit_purchase_requests.status', '<>','completed');
        $resources =    $resources->where('unit_purchase_requests.status', '<>','cancelled');
        $resources =    $resources->where('unit_purchase_requests.status', '<>','cancel');
        if($user->user_type == 'supplier'){
            $resources =    $resources->whereIn('rfq_proponents.proponents', $suppliers);
        }else{
            $resources =    $resources->where('unit_purchase_requests.units', $user->unit_id);
        }
        // $resources =    $resources->whereNotNull('notice_of_awards.proponent_id');
        //
        if($request->has('search')){
            $search = $request->get('search');
            $resources =    $resources->where(function($query) use ($search){
                 $query->where('unit_purchase_requests.project_name', 'like', "%$search%");
                 $query->orWhere('unit_purchase_requests.upr_number', 'like', "%$search%");
                 $query->orWhere('unit_purchase_requests.status', 'like', "%$search%");
             });
        }
        $resources =    $resources->where(function($query) use ($year){
            $query->whereYear('unit_purchase_requests.date_processed', '>=', '2021');
            // $query->whereYear('unit_purchase_requests.date_processed', $year);
            // $query->orWhere(function($nest) use($year) {
            //     $nest->whereYear('unit_purchase_requests.date_processed', '2020');
            // });
        });
        $resources =    $resources->orderBy('date_prepared', 'desc');
        // dd($resources->get());
        $resources =    $resources->paginate(20);


        return $this->view('modules.procurements.failed',[
            'resources' => $resources,
            'today' => Carbon::now()->format('Y-m-d')
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function all(UnitPurchaseRequestEloquent $model, Request $request)
    {
        $user      = \Sentinel::getUser();
        $suppliers = $user->suppliers;
        $year      = '2021';

        $suppliers = json_decode($suppliers);

        if($user->user_type == 'supplier'){
            $resources =    $model->select([
                'unit_purchase_requests.id',
                'unit_purchase_requests.upr_number',
                'unit_purchase_requests.total_amount',
                'unit_purchase_requests.project_name',
                'unit_purchase_requests.date_prepared',
                'unit_purchase_requests.status',
                'rfq_proponents.bid_amount',
                'rfq_proponents.proponents',
                'unit_purchase_requests.units',
                'unit_purchase_requests.delay_count',
                'unit_purchase_requests.next_due',
                'unit_purchase_requests.next_allowable',
                'unit_purchase_requests.next_step',
                'rfq_proponents.id as rfq',
                'notice_of_awards.proponent_id'
            ]);
        }else{

            $resources =    $model->select([
                'unit_purchase_requests.id',
                'unit_purchase_requests.upr_number',
                'unit_purchase_requests.total_amount',
                'unit_purchase_requests.project_name',
                'unit_purchase_requests.date_prepared',
                'unit_purchase_requests.status',
                'unit_purchase_requests.units',
                'unit_purchase_requests.delay_count',
                'unit_purchase_requests.next_due',
                'rfq_proponents.bid_amount',
                'rfq_proponents.proponents',
                'unit_purchase_requests.next_allowable',
                'unit_purchase_requests.next_step'
            ]);
        }


            $resources  =   $resources->leftJoin('notice_of_awards', 'notice_of_awards.upr_id', '=', 'unit_purchase_requests.id');
            $resources =    $resources->leftJoin('rfq_proponents', 'rfq_proponents.id', 'notice_of_awards.proponent_id');
            // $resources =    $resources->whereNotNull('request_for_quotations.id');

        if($user->user_type == 'supplier'){
            $resources =    $resources->whereIn('rfq_proponents.proponents', $suppliers);
        }else{
            $resources =    $resources->where('unit_purchase_requests.units', $user->unit_id);
        }

        if($request->has('search')){
            $search = $request->get('search');
            $resources =    $resources->where(function($query) use ($search){
                 $query->where('unit_purchase_requests.project_name', 'like', "%$search%");
                 $query->orWhere('unit_purchase_requests.upr_number', 'like', "%$search%");
                 $query->orWhere('unit_purchase_requests.status', 'like', "%$search%");
             });
        }

        $resources =    $resources->where(function($query) use ($year){
            $query->whereYear('unit_purchase_requests.date_processed', '>=', '2021');
            // $query->orWhere(function($nest) use($year) {
            //     $nest->whereYear('unit_purchase_requests.date_processed', '2020');
            // });
        });

        $resources =    $resources->orderBy('date_prepared', 'desc');
        $resources =    $resources->paginate(20);

        return $this->view('modules.procurements.all',[
            'resources' => $resources,
            'accounting' => $this->accounting,
            'today' => Carbon::now()->format('Y-m-d')
        ]);
    }
}
