<?php

namespace Revlv\Controllers\Settings;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;

use \Revlv\Settings\BacSec\BacSecRepository;
use \Revlv\Settings\BacSec\BacSecRequest;
use \Revlv\Settings\ProcurementCenters\ProcurementCenterRepository;

class BacSecController extends Controller
{

    /**
     * [Base Route of Controller]
     *
     * @var string
     */
    protected $baseUrl  =   "maintenance.bacsec.";

    /**
     *
     *
     * @var [type]
     */
    protected $centers;

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
    public function getDatatable(BacSecRepository $model)
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
        return $this->view('modules.settings.bacsec.index',[
            'createRoute'   =>  $this->baseUrl."create"
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(ProcurementCenterRepository $centers)
    {
        $center_list    =   $centers->lists("id","name");
        $this->view('modules.settings.bacsec.create',[
            'pcco'          =>  $center_list,
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
    public function store(BacSecRequest $request, BacSecRepository $model)
    {
        $result = $model->save($request->getData());

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
    public function edit($id, BacSecRepository $model, ProcurementCenterRepository $centers)
    {

        $center_list    =   $centers->lists("id","name");
        $result =   $model->findById($id);

        return $this->view('modules.settings.bacsec.edit',[
            'pcco'          =>  $center_list,
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
    public function update(BacSecRequest $request, $id, BacSecRepository $model)
    {
        $model->update($request->getData(), $id);

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
    public function destroy($id, BacSecRepository $model)
    {
        $model->delete($id);

        return redirect()->route($this->baseUrl.'index')->with([
            'success'  => "Record has been successfully deleted."
        ]);
    }
}
