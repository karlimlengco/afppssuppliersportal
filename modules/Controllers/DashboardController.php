<?php

namespace Revlv\Controllers;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;


use \Revlv\Settings\Units\UnitRepository;
use \Revlv\Procurements\BlankRequestForQuotation\BlankRFQRepository;
use \Revlv\Procurements\UnitPurchaseRequests\UnitPurchaseRequestRepository;

class DashboardController extends Controller
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
    public function index(BlankRFQRepository $blankRfq, UnitRepository $units, UnitPurchaseRequestRepository $model)
    {
        // $analytics  =   $model->getAnalytics();

        $result     =   $model->getPSR();
        $result2    =   $model->getPSRUnits();
        // $rfq    =   $blankRfq->getAll();
        // $values =   [];
        $array      =   [];
        $array2     =   [];
        // $test   =   [];
        // $name   =   "";
        $monthranges =   range(0, 11);
        $months =   [
                    'UPR',
                    'RFQ',
                    'RFQ Closed',
                    'PhilGeps',
                    'ISPQ',
                    'Canvass',
                    'NOA',
                    'NOAA',
                    'PO',
                    'MFO OB',
                    'ACCTG OB',
                    'MFO Received',
                    'ACCTG Received',
                    'COA Approved',
                    'NTP',
                    'NTPA',
                    'NOD',
                    'Delivery',
                    'TIAC',
                    'COA Delivery',
                    'DIIR',
                    'Voucher',
                    'End'
                ];

        $unit_list  =   $units->lists('id','name');

        // March

        // for ($i=0; $i < count($monthranges); $i++) {
        //     $monthranges[$i] =   0;
        // }
        foreach($result as  $data)
        {
            $name       =   $data->unit_name;
            $newArray   =   [
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

            $array[]=   [
                'label'                 =>  $name,
                'data'                  =>  $newArray,
                'pointBorderColor'      =>  rgbcode($name),
                'pointBorderWidth'      =>  '2',
                'borderColor'           =>  rgbcode($name)
            ];
        }

        foreach($result2 as  $data)
        {
            $name       =   $data->unit_name;
            $newArray2   =   [
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

            $array2[]=   [
                'label'                 =>  $name,
                'data'                  =>  $newArray2,
                'pointBorderColor'      =>  rgbcode($name),
                'pointBorderWidth'      =>  '2',
                'borderColor'           =>  rgbcode($name)
            ];
        }

        \JavaScript::put([
            'months'        => $months,
            'values'        => $array,
            'values2'        => $array2,
            'description'   => "PCCI Graph"
        ]);

        return $this->view('modules.dashboard',[
            // 'analytics' =>  $analytics
        ]);
    }

    // $rfq    =   $blankRfq->getAll();
    //     $values =   [];
    //     $array  =   [];
    //     $test   =   [];
    //     $name   =   "";
    //     $monthranges =   range(0, 11);
    //     $months =   ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

    //     $unit_list  =   $units->lists('id','name');

    //     // March

    //     for ($i=0; $i < count($monthranges); $i++) {
    //         $monthranges[$i] =   0;
    //     }

    //     foreach($rfq->toArray() as  $item)
    //     {
    //         $name   =   $item['unit_name'];

    //         if(isset($array[$name]) )
    //         {
    //             $test[$name]    =   $test[$name];
    //         }
    //         else
    //         {
    //             $test[$name]    =   $monthranges;
    //         }


    //         for ($i=1; $i < count($test[$name]) + 1; $i++) {
    //             $test[$name][$item['month']]    =   $item['data'];
    //         }

    //         $array[$name]=   [
    //             'label'                 =>  $name,
    //             'data'                  =>  $test[$name],
    //             'pointBorderColor'      =>  rgbcode($name),
    //             'pointBorderWidth'      =>  '2',
    //             'borderColor'           =>  rgbcode($name)
    //         ];
    //     }

    //     $newArray   =   [];
    //     foreach($array as $key => $val)
    //     {
    //         $newArray[]  = $val;
    //     }

    //     \JavaScript::put([
    //         'months'        => $months,
    //         'values'        => $newArray,
    //         'description'   => "Unit Graph"
    //     ]);

    //     return $this->view('modules.dashboard');

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function settings()
    {
        return $this->view('modules.settings.dashboard');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function procurements()
    {
        return $this->view('modules.procurements.dashboard');
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
