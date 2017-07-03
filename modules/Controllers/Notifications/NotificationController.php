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

            if($item->rfq_created_at == null && $today->gte($upr_created) )
            {
                // Count working days
                $days = $today->diffInDaysFiltered(function (Carbon $date) use ($h_lists) {
                    return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists);
                }, $upr_created);

                $data   =   [
                    'id'            =>  $item->id,
                    'project_name'  =>  $item->project_name,
                    'upr_number'    =>  $item->upr_number,
                    'ref_number'    =>  $item->ref_number,
                    'total_amount'  =>  $item->total_amount,
                    'date_prepared' =>  $item->date_prepared,
                    'state'         =>  $item->state,
                    'event'         =>  "UPR to RFQ",
                    'status'        =>  "Delay",
                    'days'          =>  $days,
                ];

                if($days <= 1)
                {
                    $newCollection->push($data);
                }
            }
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
