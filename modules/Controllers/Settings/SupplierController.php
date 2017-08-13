<?php

namespace Revlv\Controllers\Settings;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;

use \Revlv\Settings\Suppliers\SupplierRepository;
use \Revlv\Settings\Suppliers\AttachmentTrait;
use \Revlv\Settings\Banks\BankRepository;
use \Revlv\Settings\Suppliers\SupplierRequest;

class SupplierController extends Controller
{

    use AttachmentTrait;

    /**
     * [Base Route of Controller]
     *
     * @var string
     */
    protected $baseUrl  =   "settings.suppliers.";

    /**
     * [$model description]
     *
     * @var [type]
     */
    protected $model;

    /**
     * [$banks description]
     *
     * @var [type]
     */
    protected $banks;

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
    public function getDatatable(SupplierRepository $model)
    {
        return $model->getDatatable();
    }

    /**
     * [getDraftDatatable description]
     *
     * @param  SupplierRepository $model [description]
     * @return [type]                    [description]
     */
    public function getDraftDatatable(SupplierRepository $model)
    {
        return $model->getDatatable('draft');
    }

    /**
     * [drafts description]
     *
     * @return [type] [description]
     */
    public function drafts()
    {
        return $this->view('modules.settings.suppliers.drafts',[
            'indexRoute'    =>  $this->baseUrl."index",
            'createRoute'   =>  $this->baseUrl.'create'
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->view('modules.settings.suppliers.index',[
            'createRoute'   =>  $this->baseUrl."create",
            'draftRoute'    =>  $this->baseUrl."drafts"
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(BankRepository $banks)
    {
        $bank_lists =   $banks->lists('id', 'code');

        $this->view('modules.settings.suppliers.create',[
            'bank_lists'    =>  $bank_lists,
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
    public function store(SupplierRequest $request, SupplierRepository $model)
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
    public function edit($id, SupplierRepository $model, BankRepository $banks)
    {
        $bank_lists =   $banks->lists('id', 'code');

        $result =   $model->findById($id);

        return $this->view('modules.settings.suppliers.edit',[
            'data'          =>  $result,
            'bank_lists'    =>  $bank_lists,
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
    public function update(SupplierRequest $request, $id, SupplierRepository $model)
    {
        $model->update($request->getData(), $id);

        return redirect()->route($this->baseUrl.'edit', $id)->with([
            'success'  => "Record has been successfully updated."
        ]);
    }

    /**
     * [accepts description]
     *
     * @param  [type]             $id [description]
     * @param  SupplierRepository $id [description]
     * @return [type]                 [description]
     */
    public function acceptSupplier($id, SupplierRepository $model)
    {
        $model->update(['status' => 'accepted'], $id);

        return redirect()->route($this->baseUrl.'edit', $id)->with([
            'success'  => "Record has been successfully updated."
        ]);
    }

    /**
     * [accepts description]
     *
     * @param  [type]             $id [description]
     * @param  SupplierRepository $id [description]
     * @return [type]                 [description]
     */
    public function blockedSupplier($id, Request $request, SupplierRepository $model)
    {
        $this->validate($request, [
            'date_blocked'  => 'required',
            'blocked_remarks'  => 'required',
        ]);
        $model->update(['is_blocked' => '1', 'date_blocked' => $request->date_blocked, 'blocked_remarks' => $request->blocked_remarks], $id);

        return redirect()->route($this->baseUrl.'edit', $id)->with([
            'success'  => "Record has been successfully updated."
        ]);
    }

    /**
     * [accepts description]
     *
     * @param  [type]             $id [description]
     * @param  SupplierRepository $id [description]
     * @return [type]                 [description]
     */
    public function unblockedSupplier($id,  SupplierRepository $model)
    {
        $model->update(['is_blocked' => '0'], $id);

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
    public function destroy($id, SupplierRepository $model)
    {
        $model->delete($id);

        return redirect()->route($this->baseUrl.'index')->with([
            'success'  => "Record has been successfully deleted."
        ]);
    }
}
