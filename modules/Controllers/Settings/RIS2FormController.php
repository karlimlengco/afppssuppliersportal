<?php

namespace Revlv\Controllers\Settings;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;

use \Revlv\Settings\Forms\RIS2\RIS2Repository;
use \Revlv\Settings\CateredUnits\CateredUnitRepository;

class RIS2FormController extends Controller
{

    /**
     * [getDatatable description]
     *
     * @return [type]            [description]
     */
    public function getDatatable(RIS2Repository $model)
    {
        return $model->getDatatable();
    }


    /**
     * [Base Route of Controller]
     *
     * @var string
     */
    protected $baseUrl  =   "maintenance.forms-ris2.";

    /**
     * [$model description]
     *
     * @var [type]
     */
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
    public function index()
    {
        return $this->view('modules.settings.forms.ris2.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(CateredUnitRepository $units)
    {

        $filePath = base_path()."/resources/views/forms/default-ris2.blade.php";
        $contents = \File::get($filePath);

        \JavaScript::put([
          ' rawContents' => json_encode($contents)
        ]);

        $this->view('modules.settings.forms.ris2.create',[
            'unit_lists'    =>  $units->lists('id', 'short_code'),
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
    public function store(Request $request, RIS2Repository $model)
    {
        $this->validate($request, [
            'unit_id'   =>  'required|unique:ris2_forms,unit_id',
            'content'   =>  'required'
        ]);

        $result = $model->save($request->all());
        return redirect()->route($this->baseUrl.'edit', $result->id)->with([
            'success'  => "New record has been successfully added."
        ]);
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
    public function edit($id, RIS2Repository $model)
    {
        $result =   $model->findById($id);

        \JavaScript::put([
          ' rawContents' => json_encode($result->content)
        ]);

        return $this->view('modules.settings.forms.ris2.edit',[
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
    public function update(Request $request, $id, RIS2Repository $model)
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
    public function destroy($id, RIS2Repository $model)
    {
        //
    }
}
