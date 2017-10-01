<?php

namespace Revlv\Controllers\Procurements;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use PDF;

use \Revlv\Procurements\Minutes\MinuteRepository;
use \Revlv\Procurements\Minutes\Members\MemberRepository;
use \Revlv\Procurements\Minutes\Canvass\CanvassRepository as MinuteCanvass;
use \Revlv\Procurements\Canvassing\CanvassingRepository;
use \Revlv\Settings\Signatories\SignatoryRepository;
use \Revlv\Procurements\Minutes\MinuteRequest;

class MinutesController extends Controller
{

    /**
     * [Base Route of Controller]
     *
     * @var string
     */
    protected $baseUrl  =   "procurements.minutes.";

    /**
     * [$model description]
     *
     * @var [type]
     */
    protected $model;
    protected $canvass;
    protected $members;
    protected $signatories;
    protected $minute_canvass;

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
    public function getDatatable(MinuteRepository $model)
    {
        return $model->getDatatable();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->view('modules.procurements.minutes.index',[
            'createRoute'   =>  $this->baseUrl."create"
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(
        SignatoryRepository $signatories,
        CanvassingRepository $canvass
        )
    {

        $signatory_lists    =   $signatories->lists('id', 'name');
        $canvass_lists      =   $canvass->listCompleted('id', 'rfq_number');

        $this->view('modules.procurements.minutes.create',[
            'signatory_lists'   =>  $signatory_lists,
            'canvass_lists'     =>  $canvass_lists,
            'indexRoute'        =>  $this->baseUrl.'index',
            'modelConfig'       =>  [
                'store' =>  [
                    'route' =>  $this->baseUrl.'store'
                ]
            ]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(
        MinuteRequest $request,
        MemberRepository $members,
        MinuteCanvass $minute_canvass,
        MinuteRepository $model)
    {

        $data   =   $request->getData();
        $data['prepared_by']    = \Sentinel::getUser()->id;

        $result = $model->save($data);

        foreach($request->members as $member)
        {
            $members->save(['meeting_id' => $result->id, 'signatory_id' => $member]);
        }

        foreach($request->canvass as $canv)
        {
            $minute_canvass->save(['meeting_id' => $result->id, 'canvass_id' => $canv]);
        }

        return redirect()->route($this->baseUrl.'show', $result->id)->with([
            'success'  => "New record has been successfully added."
        ]);        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, MinuteRepository $model)
    {
        $result =   $model->findById($id);

        return $this->view('modules.procurements.minutes.show',[
            'data'          =>  $result,
            'indexRoute'    =>  $this->baseUrl.'index',
            'printRoute'    =>  $this->baseUrl.'print',
            'editRoute'     =>  $this->baseUrl.'edit',

        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id,
        MinuteRepository $model,
        SignatoryRepository $signatories,
        CanvassingRepository $canvass
        )
    {
        $result             =   $model->findById($id);
        $signatory_lists    =   $signatories->lists('id', 'name');
        $canvass_lists      =   $canvass->lists('id', 'rfq_number');
        $members_current    =   $result->members->pluck('signatory_id')->toArray();
        $canvass_current    =   $result->canvass->pluck('canvass_id')->toArray();


        return $this->view('modules.procurements.minutes.edit',[
            'data'              =>  $result,
            'indexRoute'        =>  $this->baseUrl.'show',
            'signatory_lists'   =>  $signatory_lists,
            'canvass_lists'     =>  $canvass_lists,
            'canvass'           =>  $canvass_current,
            'members'           =>  $members_current,
            'modelConfig'       =>  [
                'update' =>  [
                    'route' =>  [$this->baseUrl.'update', $id],
                    'method'=>  'PUT',
                    'novalidate'=>  'novalidate'
                ]
            ]
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(
        $id,
        MinuteRequest $request,
        MemberRepository $members,
        MinuteCanvass $minute_canvass,
        MinuteRepository $model)
    {
        $result =   $model->update($request->getData(), $id);

        $members->deleteAll($id);
        $minute_canvass->deleteAll($id);

        foreach($request->members as $member)
        {
            $members->save(['meeting_id' => $id, 'signatory_id' => $member]);
        }

        foreach($request->canvass as $canv)
        {
            $minute_canvass->save(['meeting_id' => $id, 'canvass_id' => $canv]);
        }

        return redirect()->route($this->baseUrl.'edit', $id)->with([
            'success'  => "Record has been successfully updated."
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, MinuteRepository $model)
    {
        $model->delete($id);

        return redirect()->route($this->baseUrl.'index')->with([
            'success'  => "Record has been successfully deleted."
        ]);
    }

    /**
     * [viewPrint description]
     *
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function viewPrint(
        $id,
        MinuteRepository $model)
    {
        $mom                    =   $model->with(['members','canvass'])->findById($id);

        $data['date_opened']    =   $mom->date_opened;
        $data['time_opened']    =   $mom->time_opened;
        $data['venue']          =   $mom->venue;
        $data['time_closed']    =   $mom->time_closed;
        $data['members']        =   $mom->members;
        $data['canvass']        =   $mom->canvass;
        $data['officer']        =   $mom->officer;

        $pdf = PDF::loadView('forms.mom', ['data' => $data])->setOption('margin-left', 13)->setOption('margin-right', 13)->setOption('margin-bottom', 10)->setPaper('a4');

        return $pdf->setOption('page-width', '8.27in')->setOption('page-height', '11.69in')->inline('mom.pdf');
    }

    /**
     * [viewResolution description]
     *
     * @param  [type]           $id    [description]
     * @param  MinuteRepository $model [description]
     * @return [type]                  [description]
     */
    public function viewResolution($id,
        MinuteRepository $model)
    {
        $mom                    =   $model->with(['members','canvass'])->findById($id);

        $data['date_opened']    =   $mom->date_opened;
        $data['time_opened']    =   $mom->time_opened;
        $data['venue']          =   $mom->venue;
        $data['time_closed']    =   $mom->time_closed;
        $data['members']        =   $mom->members;
        $data['canvass']        =   $mom->canvass;
        $data['officer']        =   $mom->officer;

        $pdf = PDF::loadView('forms.resolution', ['data' => $data])->setOption('margin-left', 13)->setOption('margin-right', 13)->setOption('margin-bottom', 10)->setPaper('a4');

        return $pdf->setOption('page-width', '8.27in')->setOption('page-height', '11.69in')->inline('resolutions.pdf');
    }
}
