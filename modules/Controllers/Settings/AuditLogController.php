<?php

namespace Revlv\Controllers\Settings;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Sentinel;

use \Revlv\Settings\AuditLogs\AuditLogRepository;
use Revlv\Users\UserRepository;

class AuditLogController extends Controller
{

    /**
     * [Base Route of Controller]
     *
     * @var string
     */
    protected $baseUrl  =   "settings.audit-logs.";

    /**
     * [$model description]
     *
     * @var [type]
     */
    protected $model;

    /**
     * [$user description]
     *
     * @var [type]
     */
    protected $user;

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
    public function getDatatable(AuditLogRepository $model)
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
        $this->view('modules.settings.audit-logs.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, AuditLogRepository $model)
    {
        $logs   =   $model->findById($id);

        return $this->view('modules.settings.audit-logs.show',[
            'data'      =>  $logs
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
