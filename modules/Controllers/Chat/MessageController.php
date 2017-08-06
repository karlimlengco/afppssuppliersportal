<?php

namespace Revlv\Controllers\Chat;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;

use \Revlv\Chats\Message\MessageRepository;
use \Revlv\Chats\ChatRepository;
use \Revlv\Settings\Units\UnitRepository;
use \Revlv\Procurements\BlankRequestForQuotation\BlankRFQRepository;
use \Revlv\Procurements\UnitPurchaseRequests\UnitPurchaseRequestRepository;

class MessageController extends Controller
{

    /**
     * [$blankRfq description]
     *
     * @var [type]
     */
    protected $blankRfq;
    protected $model;
    protected $messages;
    protected $chats;
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
     * [getMessage description]
     *
     * @param  MessageRepository $messages [description]
     * @param  ChatRepository    $chats    [description]
     * @return [type]                      [description]
     */
    public function getMessage(MessageRepository $messages, ChatRepository $chats)
    {
        $user       =   \Sentinel::getUser();
        $chat       =   $chats->findBySender($user->id);
        $chatId     = 0;
        if($chat)
        {
            $chatId =   $chat->id;
        }

        $message=   $messages->findByChatId($chatId);

        return $message;
    }

    /**
     * [showChat description]
     *
     * @param  [type]            $senderId [description]
     * @param  ChatRepository    $chats    [description]
     * @param  MessageRepository $messages [description]
     * @return [type]                      [description]
     */
    public function showChat($senderId, ChatRepository $chats, MessageRepository $messages)
    {
        $chat       =   $chats->findBySender($senderId);

        return $chat->messages;
    }

    /**
     * [getAdminMessage description]
     *
     * @param  MessageRepository $messages [description]
     * @param  ChatRepository    $chats    [description]
     * @return [type]                      [description]
     */
    public function getAdminMessage(MessageRepository $messages, ChatRepository $chats)
    {
        return $this->view('modules.chat.admin-messages');
    }

    public function getAdminMessageAPI(Request $request,  ChatRepository $chats)
    {
        $items   =   $chats->getAllAdmin();

        $response = [
            'pagination' => [
                'total' => $items->total(),
                'per_page' => $items->perPage(),
                'current_page' => $items->currentPage(),
                'last_page' => $items->lastPage(),
                'from' => $items->firstItem(),
                'to' => $items->lastItem()
            ],
            'data' => $items
        ];

        return  $response;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, ChatRepository $chats, MessageRepository $messages)
    {
        $user   =   \Sentinel::getUser();

        if($request->has('chatId') && $request->chatId != null)
        {
            $chat   =   $chats->findById($request->chatId);
        }
        else
        {
            $chat   =   $chats->findBySender($user->id);
        }

        if($chat == null)
        {
            $chat = $chats->save(['sender_id' => $user->id]);
        }

        $message = $user->messages()->create([
            'message' => $request->get('message'),
            'chat_id' => $chat->id
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