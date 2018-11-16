<?php

namespace Revlv\Controllers\Settings;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Excel;

use \Revlv\Settings\Suppliers\SupplierRepository;
use \Revlv\Settings\Suppliers\AttachmentTrait;
use \Revlv\Settings\Banks\BankRepository;
use \Revlv\Settings\Suppliers\SupplierRequest;
use \Revlv\Settings\Suppliers\SupplierEloquent;
use \Revlv\Settings\Suppliers\Attachments\AttachmentEloquent;

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

    public function getFilesDatatable(SupplierRepository $model)
    {
        return $model->getFileDatatable();
    }

    /**
     * [getInfo description]
     *
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function getInfo($id, SupplierRepository $model)
    {
        $requirements = ['dti' => 'dti', 'mayors_permit' => 'mayors_permit', 'tax_clearance' => 'tax_clearance', 'philgeps_registration' => 'philgeps_registration'];

        $today =  \Carbon\Carbon::now()->format('Y-m-d');
        $model  = AttachmentEloquent::select([
          \DB::raw('MAX(supplier_attachments.validity_date) as valid_date'),
          'supplier_attachments.type'
          ])
        ->where('supplier_id', '=', $id)
        ->where('validity_date', '>=', $today)
        ->groupBy('supplier_attachments.type')
        ->get()->toArray();

        foreach($model as $data)
        {
            unset($requirements[$data['type']]);
        }

        if(count($requirements) != 0){
          return 'not eligible';
        }

        return 'eligible';
    }

    /**
     * [drafts description]
     *
     * @return [type] [description]
     */
    public function files()
    {
        return $this->view('modules.settings.suppliers.files');
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
        $result = $model->save($request->getData()+['status' => 'accepted']);

        return redirect()->route($this->baseUrl.'edit', $result->id)->with([
            'success'  => "New record has been successfully added."
        ]);        //
    }

    /**
     * [storeNew description]
     *
     * @param  Request            $request [description]
     * @param  SupplierRepository $model   [description]
     * @return [type]                      [description]
     */
    public function storeNew(Request $request, SupplierRepository $model)
    {
        $result = $model->save($request->all()+['status' => 'accepted']);

        return $result;

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, SupplierRepository $model, BankRepository $banks)
    {
        $bank_lists =   $banks->lists('id', 'code');
        $result = $model->findById($id);

        return $this->view('modules.settings.suppliers.show', [
          'data'          =>  $result,
          'bank_lists'    =>  $bank_lists,
          'indexRoute'    =>  $this->baseUrl.'index',
          'modelConfig'   =>  [
              'add_attachment' =>  [
                  'route'     =>  [$this->baseUrl.'attachments.store', $id],
              ]
          ]
        ]);
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

    public function printView( SupplierEloquent $model)
    {
        $result = $model->select([
            'suppliers.*',
            \DB::raw(" (select supplier_attachments.validity_date from supplier_attachments where supplier_attachments.supplier_id = suppliers.id AND type = 'dti' order by supplier_attachments.created_at desc limit 1) as dti_validity_date "),
            \DB::raw(" (select supplier_attachments.validity_date from supplier_attachments where supplier_attachments.supplier_id = suppliers.id AND type = 'mayors_permit' order by supplier_attachments.created_at desc limit 1) as mayors_validity_date "),
            \DB::raw(" (select supplier_attachments.validity_date from supplier_attachments where supplier_attachments.supplier_id = suppliers.id AND type = 'tax_clearance' order by supplier_attachments.created_at desc limit 1) as tax_validity_date "),
            \DB::raw(" (select supplier_attachments.validity_date from supplier_attachments where supplier_attachments.supplier_id = suppliers.id AND type = 'philgeps_registraion' order by supplier_attachments.created_at desc limit 1) as philgeps_validity_date ")

        ])->get();
        
        Excel::create("Suppliers", function($excel)use ($result) {
            $excel->sheet('Page1', function($sheet)use ($result) {
                $count = 6;
                $sheet->row(1, ['SUPPLPIERS']);
                $sheet->row(2, [""]);

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
                        'Company Name',
                        'Owner',
                        'Address',
                        'Line of Business',
                        'TIN',
                        'Address',
                        'DTI',
                        'MAYORS PERMIT',
                        'TAX CLEARANCE',
                        'PHILGEPS POSTING',
                    ]);
                }

                foreach($result as $data)
                {

                    $count ++;
                    $newdata    =   [
                        $data->name,
                        $data->owner,
                        $data->address,
                        $data->line_of_business,
                        $data->tin,
                        $data->address,
                        $data->dti_validity_date,
                        $data->mayors_validity_date,
                        $data->tax_validity_date,
                        $data->philgeps_validity_date,
                    ];
                    $sheet->row($count, $newdata);

                }

            });

        })->export('xlsx');
    }
}
