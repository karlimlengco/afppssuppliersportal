<?php

namespace Revlv\Controllers;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;

use \Revlv\Chats\Message\MessageRepository;
use \Revlv\Settings\Units\UnitRepository;
use \Revlv\Procurements\BlankRequestForQuotation\BlankRFQRepository;
use \Revlv\Procurements\UnitPurchaseRequests\UnitPurchaseRequestRepository;

class ChatController extends Controller
{

    /**
     * [$blankRfq description]
     *
     * @var [type]
     */
    protected $blankRfq;
    protected $model;
    protected $message;
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
        return view('chat');
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = \Sentinel::getUser();

        $message = $user->messages()->create([
            'message' => $request->get('message')
        ]);
        // Announce that a new message has been posted
        broadcast(new \App\Events\MessagePosted($message, $user))->toOthers();

        return ['status' => 'OK'];
    }

    /**
     * [authenticate description]
     *
     * @return [type] [description]
     */
    public function authenticate(Request $request)
    {
        $pusher = new \Pusher(env('PUSHER_KEY'), env('PUSHER_SECRET'), env('PUSHER_APP_ID'));

        if(\Sentinel::check())
        {
            //Fetch User Object
            $user =  \Sentinel::getUser();
            //Presence Channel information. Usually contains personal user information.
            //See: https://pusher.com/docs/client_api_guide/client_presence_channels
            $presence_data = array('name' => $user->first_name." ".$user->last_name);
            //Registers users' presence channel.
            return $pusher->presence_auth($request->get('channel_name'), $request->get('socket_id'), $user->id, $presence_data);
        }
        else
        {
            return \Response::make('Forbidden',403);
        }
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
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
