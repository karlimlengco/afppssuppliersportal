<?php

namespace Revlv\Controllers\Procurements;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;

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
        $canvass_lists      =   $canvass->lists('id', 'rfq_number');

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

        $result = $model->save($request->getData());

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
                    'method'=>  'PUT'
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
}
