<?php

namespace Revlv\Controllers\Library;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use PDF;
use Auth;
use Carbon\Carbon;
use \App\Support\Breadcrumb;
use Validator;

use Revlv\Library\Catalogs\CatalogRequest;
use Revlv\Library\Catalogs\CatalogRepository;

class CatalogController extends Controller
{

    /**
     * [Base Route of Controller]
     *
     * @var string
     */
    protected $baseUrl  =   "library.catalogs.";

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
    public function getDatatable(CatalogRepository $model)
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
        return $this->view('modules.library.catalog.index',[
            'createRoute'   =>  $this->baseUrl."create",
            'breadcrumbs' => [
                new Breadcrumb('Library'),
                new Breadcrumb('Catalog')
            ]
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return $this->view('modules.library.catalog.create',[
            'indexRoute'    =>  $this->baseUrl."index",
            'modelConfig'   =>  [
                'store' =>  [
                    'route'     =>  $this->baseUrl.'store'
                ]
            ],
            'breadcrumbs' => [
                new Breadcrumb('Library'),
                new Breadcrumb('Catalog')
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
        CatalogRequest $request,
        CatalogRepository $model)
    {
        $result = $model->save($request->getData());

        return redirect()->route($this->baseUrl."index")->with([
            'success'  => "New record has been successfully added."
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(
        $id,
        CatalogRepository $model)
    {
        $result      =   $model->findById($id);

        return $this->view('modules.library.catalog.show',[
            'result' =>  $result,
            'breadcrumbs' => [
                new Breadcrumb('Library'),
                new Breadcrumb('Catalog')
            ]
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id,
        CatalogRepository $model)
    {
        $result =   $model->findById($id);

        return $this->view('modules.library.catalog.edit',[
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
            ],
            'breadcrumbs' => [
                new Breadcrumb('Library'),
                new Breadcrumb('Catalog')
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
        CatalogRequest $request,
        CatalogRepository $model)
    {

        $model->update($request->getData(), $id);

        return redirect()->route($this->baseUrl."index")->with([
            'success'  => "Record has been successfully updated."
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, CatalogRepository $model)
    {
        $model->delete($id);

        return redirect()->route($this->baseUrl.'index')->with([
            'success'  => "Record has been successfully deleted."
        ]);
    }

}
