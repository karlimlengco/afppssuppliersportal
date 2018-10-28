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
    protected $blankRfq;
    protected $center;
    protected $units;
    protected $model;


    /**
     * @param center $center
     */
    public function __construct(ProcurementCenterRepository $center, UnitPurchaseRequestRepository $model)
    {
        $this->center  =   $center;
        $this->model  =   $model;
        parent::__construct();
    }

    public function viewPrint($type , Request $request)
    {
        $result =  $this->center->getPrograms(null, $type, $request);

        $from   = $request->start;
        $to     = $request->end;

        Excel::create("Overview", function($excel)use ($result, $from ,$to, $type) {
            $excel->sheet('Page1', function($sheet)use ($result, $from ,$to, $type) {
                if($from != null)
                $from = \Carbon\Carbon::createFromFormat('!Y-m-d', $from)->format('d F Y');

                if($to != null)
                $to = \Carbon\Carbon::createFromFormat('!Y-m-d', $to)->format('d F Y');

                $count = 6;
                $sheet->row(1, ['OVERVIEW SUMMARY OF PROGRAMS']);
                $sheet->row(2, [$type ." METHOD OF PROCUREMENT"]);
                $sheet->row(3, ["(Period Covered: $from to $to)"]);

                $sheet->cells('A3:AA3', function($cells) {
                    $cells->setAlignment('center');
                    $cells->setBorder('thin', 'thin', 'thin', 'thin');
                });

                $sheet->cells('A2:AA2', function($cells) {
                    $cells->setAlignment('center');
                    $cells->setBorder('thin', 'thin', 'thin', 'thin');
                });

                $sheet->cells('A1:AA1', function($cells) {
                    $cells->setAlignment('center');
                    $cells->setBorder('thin', 'thin', 'thin', 'thin');
                });

                $sheet->mergeCells('A1:AA1');
                $sheet->mergeCells('A2:AA2');
                $sheet->mergeCells('A3:AA3');
                if($result->last() != null)
                {
                    $sheet->row(6, [
                        'Program',
                        'Total UPR',
                        'Completed',
                        'Ongoing',
                        'Cancelled',
                        'Delayed',
                        'Total ABC',
                        'ABC for completed projects',
                        'Total bid price for completed projects',
                        'Residual of completed projects '
                        
                    ]);
                }

                foreach($result as $data)
                {

                    $count ++;
                    $newdata    =   [
                        $data->programs,
                        $data->upr_count,
                        $data->completed_count,
                        $data->ongoing_count,
                        $data->cancelled_count,
                        $data->delay_count,
                        number_format($data->total_abc,2),
                        number_format($data->total_approved_abc,2),
                        number_format($data->total_bid,2),
                        number_format($data->total_complete_residual,2),
                    ];
                    $sheet->row($count, $newdata);

                }

            });

        })->export('xlsx');
    }

    /**
     * 
     * 
     */
    public function viewPrintUpr($short_code, $center, $type, Request $request)
    {

        $result      =   $this->model->getUprs($center, $short_code,  $type, $request);
        $from   = $request->start;
        $to     = $request->end;

        Excel::create("Overview", function($excel)use ($result, $from ,$to, $type,  $short_code) {
            $excel->sheet('Page1', function($sheet)use ($result, $from ,$to, $type,  $short_code) {
                if($from != null)
                $from = \Carbon\Carbon::createFromFormat('!Y-m-d', $from)->format('d F Y');

                if($to != null)
                $to = \Carbon\Carbon::createFromFormat('!Y-m-d', $to)->format('d F Y');

                $count = 6;
                $sheet->row(1, ['OVERVIEW SUMMARY OF Catered Unit '. $short_code]);
                $sheet->row(2, [$type ." METHOD OF PROCUREMENT"]);
                $sheet->row(3, ["(Period Covered: $from to $to)"]);

                $sheet->cells('A3:AA3', function($cells) {
                    $cells->setAlignment('center');
                    $cells->setBorder('thin', 'thin', 'thin', 'thin');
                });

                $sheet->cells('A2:AA2', function($cells) {
                    $cells->setAlignment('center');
                    $cells->setBorder('thin', 'thin', 'thin', 'thin');
                });

                $sheet->cells('A1:AA1', function($cells) {
                    $cells->setAlignment('center');
                    $cells->setBorder('thin', 'thin', 'thin', 'thin');
                });

                $sheet->mergeCells('A1:AA1');
                $sheet->mergeCells('A2:AA2');
                $sheet->mergeCells('A3:AA3');
                if($result->last() != null)
                {
                    $sheet->row(6, [
                        'UPR',
                        'Status',
                        'Total ABC',
                        'ABC for completed projects',
                        'Total bid price for completed projects',
                        'Residual of completed projects '
                        
                    ]);
                }

                foreach($result as $data)
                {

                    $count ++;
                    $newdata    =   [
                        $data->project_name ."(". $data->upr_number .")"   ,
                        $data->status,
                        number_format($data->total_abc,2),
                        number_format($data->total_approved_abc,2),
                        number_format($data->total_bid,2),
                        number_format($data->total_complete_residual,2),
                    ];
                    $sheet->row($count, $newdata);

                }

            });

        })->export('xlsx');
    }

    /**
     * 
     */
    public function viewPrintProgramCenter($program, $center, $type, Request $request)
    {
        $result      =   $this->model->getUnits($center, $program,  $type, $request);

        $from   = $request->start;
        $to     = $request->end;

        Excel::create("Overview", function($excel)use ($result, $from ,$to, $type, $program, $center) {
            $excel->sheet('Page1', function($sheet)use ($result, $from ,$to, $type, $program, $center) {
                if($from != null)
                $from = \Carbon\Carbon::createFromFormat('!Y-m-d', $from)->format('d F Y');

                if($to != null)
                $to = \Carbon\Carbon::createFromFormat('!Y-m-d', $to)->format('d F Y');

                $count = 6;
                $sheet->row(1, ['OVERVIEW SUMMARY OF PC/CO '. $center]);
                $sheet->row(2, [$type ." METHOD OF PROCUREMENT"]);
                $sheet->row(3, ["(Period Covered: $from to $to)"]);

                $sheet->cells('A3:AA3', function($cells) {
                    $cells->setAlignment('center');
                    $cells->setBorder('thin', 'thin', 'thin', 'thin');
                });

                $sheet->cells('A2:AA2', function($cells) {
                    $cells->setAlignment('center');
                    $cells->setBorder('thin', 'thin', 'thin', 'thin');
                });

                $sheet->cells('A1:AA1', function($cells) {
                    $cells->setAlignment('center');
                    $cells->setBorder('thin', 'thin', 'thin', 'thin');
                });

                $sheet->mergeCells('A1:AA1');
                $sheet->mergeCells('A2:AA2');
                $sheet->mergeCells('A3:AA3');
                if($result->last() != null)
                {
                    $sheet->row(6, [
                        'Catered Unit',
                        'Total UPR',
                        'Completed',
                        'Ongoing',
                        'Cancelled',
                        'Delayed',
                        'Total ABC',
                        'ABC for completed projects',
                        'Total bid price for completed projects',
                        'Residual of completed projects '
                        
                    ]);
                }

                foreach($result as $data)
                {

                    $count ++;
                    $newdata    =   [
                        $data->short_code   ,
                        $data->upr_count,
                        $data->completed_count,
                        $data->ongoing_count,
                        $data->cancelled_count,
                        $data->delay_count,
                        number_format($data->total_abc,2),
                        number_format($data->total_approved_abc,2),
                        number_format($data->total_bid,2),
                        number_format($data->total_complete_residual,2),
                    ];
                    $sheet->row($count, $newdata);

                }

            });

        })->export('xlsx');
    }

    /**
     * 
     */
    public function viewPrintProgram($program, $type, Request $request)
    {
        $result      =   $this->model->getUprCenters($program,  $type, $request);

        
        $from   = $request->start;
        $to     = $request->end;

        Excel::create("Overview", function($excel)use ($result, $from ,$to, $type, $program) {
            $excel->sheet('Page1', function($sheet)use ($result, $from ,$to, $type, $program) {
                if($from != null)
                $from = \Carbon\Carbon::createFromFormat('!Y-m-d', $from)->format('d F Y');

                if($to != null)
                $to = \Carbon\Carbon::createFromFormat('!Y-m-d', $to)->format('d F Y');

                $count = 6;
                $sheet->row(1, ['OVERVIEW SUMMARY OF PROGRAM '. $program]);
                $sheet->row(2, [$type ." METHOD OF PROCUREMENT"]);
                $sheet->row(3, ["(Period Covered: $from to $to)"]);

                $sheet->cells('A3:AA3', function($cells) {
                    $cells->setAlignment('center');
                    $cells->setBorder('thin', 'thin', 'thin', 'thin');
                });

                $sheet->cells('A2:AA2', function($cells) {
                    $cells->setAlignment('center');
                    $cells->setBorder('thin', 'thin', 'thin', 'thin');
                });

                $sheet->cells('A1:AA1', function($cells) {
                    $cells->setAlignment('center');
                    $cells->setBorder('thin', 'thin', 'thin', 'thin');
                });

                $sheet->mergeCells('A1:AA1');
                $sheet->mergeCells('A2:AA2');
                $sheet->mergeCells('A3:AA3');
                if($result->last() != null)
                {
                    $sheet->row(6, [
                        'PC/CO',
                        'Total UPR',
                        'Completed',
                        'Ongoing',
                        'Cancelled',
                        'Delayed',
                        'Total ABC',
                        'ABC for completed projects',
                        'Total bid price for completed projects',
                        'Residual of completed projects '
                        
                    ]);
                }

                foreach($result as $data)
                {

                    $count ++;
                    $newdata    =   [
                        $data->name,
                        $data->upr_count,
                        $data->completed_count,
                        $data->ongoing_count,
                        $data->cancelled_count,
                        $data->delay_count,
                        number_format($data->total_abc,2),
                        number_format($data->total_approved_abc,2),
                        number_format($data->total_bid,2),
                        number_format($data->total_complete_residual,2),
                    ];
                    $sheet->row($count, $newdata);

                }

            });

        })->export('xlsx');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(BlankRFQRepository $blankRfq, UnitRepository $units, UnitPurchaseRequestRepository $model)
    {

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
        dd($request->all());
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
