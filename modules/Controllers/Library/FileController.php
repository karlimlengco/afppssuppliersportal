<?php

namespace Revlv\Controllers\Library;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use PDF;
use Auth;
use Carbon\Carbon;
use \App\Support\Breadcrumb;
use Validator;

use Revlv\Library\Library\LibraryRequest;
use Revlv\Library\Library\LibraryRepository;
use Revlv\Library\Catalogs\CatalogRepository;

class FileController extends Controller
{

    /**
     * [Base Route of Controller]
     *
     * @var string
     */
    protected $baseUrl  =   "library.files.";

    /**
     * [$model description]
     *
     * @var [type]
     */
    protected $model;
    protected $catalogs;

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
    public function getDatatable(LibraryRepository $model)
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
        return $this->view('modules.library.files.index',[
            'createRoute'   =>  $this->baseUrl."create",
            'breadcrumbs' => [
                new Breadcrumb('Library'),
                new Breadcrumb('Files')
            ]
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(CatalogRepository $catalogs)
    {

        $catalog_lists  =   $catalogs->lists();
        return $this->view('modules.library.files.create',[
            'catalogs'      =>  $catalog_lists,
            'indexRoute'    =>  $this->baseUrl."index",
            'modelConfig'   =>  [
                'store' =>  [
                    'route'     =>  $this->baseUrl.'store',
                    'files'     =>  true,
                ]
            ],
            'breadcrumbs' => [
                new Breadcrumb('Library'),
                new Breadcrumb('Files')
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
        LibraryRequest $request,
        LibraryRepository $model)
    {
        $file       = md5_file($request->file_name);
        $file       = $file.".".$request->file_name->getClientOriginalExtension();

        $result         = $model->save([
            'name'          =>  $request->name,
            'catalog_id'    =>  $request->catalog_id,
            'tags'          =>  $request->tags,
            'file_name'     =>  $file,
            'uploaded_by'   =>  \Sentinel::getUser()->first_name ." ". \Sentinel::getUser()->surname,
        ]);

        if($result)
        {
            $path       = $request->file_name->storeAs('library', $file);
        }

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
        LibraryRepository $model)
    {
        $result      =   $model->findById($id);

        return $this->view('modules.library.files.show',[
            'result' =>  $result,
            'breadcrumbs' => [
                new Breadcrumb('Library'),
                new Breadcrumb('Files')
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
        CatalogRepository $catalogs,
        LibraryRepository $model)
    {
        $result =   $model->findById($id);

        $catalog_lists  =   $catalogs->lists();
        return $this->view('modules.library.files.edit',[
            'data'          =>  $result,
            'catalogs'      =>  $catalog_lists,
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
                new Breadcrumb('Files')
            ]
        ]);
    }

    /**
     * [downloadAttachment description]
     *
     * @param  Request $request [description]
     * @param  [type]  $id      [description]
     * @return [type]           [description]
     */
    public function downloadFile(
        Request $request,
        $id,
        LibraryRepository $models)
    {
        $result     = $models->findById($id);

        $directory      =   storage_path("app/library/".$result->file_name);

        if(!file_exists($directory))
        {
            return 'Sorry. File does not exists.';
        }

        return response()->download($directory);
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
        Request $request,
        LibraryRepository $model)
    {

        $this->validate($request, [
            'name'  =>  'required',
            'catalog_id'  =>  'required',
            'tags'  =>  'required',
        ]);

        $model->update([
            'name'          =>  $request->name,
            'catalog_id'    =>  $request->catalog_id,
            'tags'          =>  $request->tags,
        ], $id);

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
    public function destroy($id, LibraryRepository $model)
    {
        $model->delete($id);

        return redirect()->route($this->baseUrl.'index')->with([
            'success'  => "Record has been successfully deleted."
        ]);
    }

}
