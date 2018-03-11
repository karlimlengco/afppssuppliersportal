<?php

namespace Revlv\Controllers\Settings;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;

use \Revlv\Settings\CateredUnits\CateredUnitRepository;
use \Revlv\Settings\CateredUnits\CateredUnitRequest;
use \Revlv\Settings\CateredUnits\AttachmentTrait;
use \Revlv\Settings\ProcurementCenters\ProcurementCenterRepository;

class CateredUnitController extends Controller
{
    use AttachmentTrait;
    /**
     * [Base Route of Controller]
     *
     * @var string
     */
    protected $baseUrl  =   "maintenance.catered-units.";

    /**
     * [$model description]
     *
     * @var [type]
     */
    protected $model;

    /**
     *
     *
     * @var [type]
     */
    protected $centers;

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
    public function getDatatable(CateredUnitRepository $model)
    {
        return $model->getDatatable();
    }

    public function getFilesDatatable(CateredUnitRepository $model)
    {
        return $model->getFileDatatable();
    }

    /**
     * [drafts description]
     *
     * @return [type] [description]
     */
    public function files()
    {
        return $this->view('modules.settings.catered-units.files');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->view('modules.settings.catered-units.index',[
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
        $this->view('modules.settings.catered-units.create',[
            'center_list'   =>  $center_list,
            'indexRoute'    =>  $this->baseUrl.'index',
            'modelConfig'   =>  [
                'store' =>  [
                    'route'     =>  $this->baseUrl.'store'
                ]
            ]
        ]);
    }

    /**
     *
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CateredUnitRequest $request, CateredUnitRepository $model)
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
    public function edit($id, CateredUnitRepository $model,ProcurementCenterRepository $centers)
    {
        $result =   $model->findById($id);
        $center_list    =   $centers->lists("id","name");
        return $this->view('modules.settings.catered-units.edit',[
            'data'          =>  $result,
            'center_list'   =>  $center_list,
            'indexRoute'    =>  $this->baseUrl.'index',
            'modelConfig'   =>  [
                'update' =>  [
                    'route'     =>  [$this->baseUrl.'update', $id],
                    'method'    =>  'PUT'
                ],
                'destroy'   => [
                    'route' => [$this->baseUrl.'destroy',$id],
                    'method'=> 'DELETE'
                ],
                'add_attachment' =>  [
                    'route'     =>  [$this->baseUrl.'attachments.store', $id],
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
    public function update(CateredUnitRequest $request, $id, CateredUnitRepository $model)
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
    public function destroy($id, CateredUnitRepository $model)
    {
        $model->delete($id);

        return redirect()->route($this->baseUrl.'index')->with([
            'success'  => "Record has been successfully deleted."
        ]);
    }
}
