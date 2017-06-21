<?php

namespace Revlv\Controllers;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;


use \Revlv\Procurements\BlankRequestForQuotation\BlankRFQRepository;

class DashboardController extends Controller
{

    /**
     * [$blankRfq description]
     *
     * @var [type]
     */
    protected $blankRfq;


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
    public function index(BlankRFQRepository $blankRfq)
    {
        $rfq    =   $blankRfq->getAll();
        $values =   [];
        $array  =   [];
        $name   =   "";
        $monthranges =   range(0, 11);
        $months =   ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

         // March

        for ($i=0; $i < count($monthranges); $i++) {
            $monthranges[$i] =   0;
        }

        foreach($rfq->toArray() as  $item)
        {
            $name   =   $item['unit_name'];


            for ($i=1; $i < count($monthranges) + 1; $i++) {
                $monthranges[$item['month']]    =   $item['data'];
            }

            $array[]=   [
                'label'                 =>  $name,
                'data'                  =>  $monthranges,
                'pointBorderColor'      =>  rgbcode($name),
                'pointBorderWidth'      =>  '2',
                'borderColor'           =>  rgbcode($name)
            ];
        }

        \JavaScript::put([
            'months'        => $months,
            'values'        => $array,
            'description'   => "Unit Graph"
        ]);

        return $this->view('modules.dashboard');
    }

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
