<?php

namespace Revlv\Controllers\Settings;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;

use \Revlv\Settings\Forms\RFQ\RFQRepository;
use \Revlv\Settings\ProcurementCenters\ProcurementCenterRepository;

class FormsController extends Controller
{


    /**
     * [getDatatable description]
     *
     * @return [type]            [description]
     */
    public function getDatatable(RFQRepository $model)
    {
        return $model->getDatatable();
    }


    /**
     * [Base Route of Controller]
     *
     * @var string
     */
    protected $baseUrl  =   "maintenance.forms.";

    /**
     * [$model description]
     *
     * @var [type]
     */
    protected $model;
    protected $pccos;

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
    public function index()
    {
        return $this->view('modules.settings.forms.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(ProcurementCenterRepository $pccos)
    {
        $this->view('modules.settings.forms.create',[
            'pcco_list'     =>  $pccos->lists('id', 'name'),
            'indexRoute'    =>  $this->baseUrl.'index',
            'modelConfig'   =>  [
                'store' =>  [
                    'route'     =>  $this->baseUrl.'store'
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
    public function store(Request $request, RFQRepository $model)
    {
        $this->validate($request, [
            'pcco_id'   =>  'required|unique:forms_rfq,pcco_id',
            'content'   =>  'required'
        ]);

        $result = $model->save($request->all());

        return redirect()->route($this->baseUrl.'edit', $result->id)->with([
            'success'  => "New record has been successfully added."
        ]);        //
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
    public function edit($id, RFQRepository $model)
    {
        $result =   $model->findById($id);

        return $this->view('modules.settings.forms.edit',[
            'data'          =>  $result,
            'indexRoute'    =>  $this->baseUrl.'index',
            'modelConfig'   =>  [
                'update' =>  [
                    'route'     =>  [$this->baseUrl.'update', $id],
                    'method'    =>  'PUT'
                ],
                'destroy'   => [
                    'route' => [$this->baseUrl.'destroy',$id],
                    'method'=> 'DELETE'
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
    public function update(Request $request, $id, RFQRepository $model)
    {
        $this->validate($request,[
            'content'   =>  'required'
        ]);
        $model->update(['content' => $request->content], $id);

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
    public function destroy($id, RFQRepository $model)
    {
        //
    }
}
