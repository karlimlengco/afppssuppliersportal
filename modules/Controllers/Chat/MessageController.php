<?php

namespace Revlv\Controllers\Chat;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;

use \Revlv\Chats\Message\MessageRepository;
use \Revlv\Chats\ChatRepository;
use \Revlv\Chats\ChatMemberRepository;
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
    protected $chatMembers;
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
        $chatId     = null;
        $message    = collect([]);

        $messages->markAsSeen($user->id);
        if($chat)
        {
            $chatId =   $chat->id;
        }

        if($chatId != null)
        {
          $message=   $messages->findByChatId($chatId);
        }

        return $message;
    }

    /**
     * [getMessage description]
     *
     * @param  MessageRepository $messages [description]
     * @param  ChatRepository    $chats    [description]
     * @return [type]                      [description]
     */
    public function getChatMessageBySender($sender, MessageRepository $messages, ChatRepository $chats)
    {
        $chat       =   $chats->findBySenderAndReceiver(\Sentinel::getUser()->id, $sender);
        $chatId     = 0;
        if($chat)
        {
            $chatId =   $chat->id;
        }

        return $chatId;
    }

    /**
     * [getMessage description]
     *
     * @param  MessageRepository $messages [description]
     * @param  ChatRepository    $chats    [description]
     * @return [type]                      [description]
     */
    public function getChatMessageByUPR($upr, MessageRepository $messages, ChatRepository $chats)
    {
        $chat       =   $chats->findByUPR($upr);

        $chatId     = 0;
        if($chat)
        {
            $chatId =   $chat->id;
        }

        return $chatId;
    }

    /**
     * [showChat description]
     *
     * @param  [type]            $senderId [description]
     * @param  ChatRepository    $chats    [description]
     * @param  MessageRepository $messages [description]
     * @return [type]                      [description]
     */
    public function showChat($senderId, $receiverId = null, ChatRepository $chats, MessageRepository $messages)
    {

        if($receiverId == null)
        {
            $chat       =   $chats->findBySender($senderId);
        }
        else
        {
            $chat       =   $chats->findBySenderAndReceiver($senderId, $receiverId);
        }

        $messages->markAsSeen(\Sentinel::getUser()->id);

        if($chat)
        {
            return $chat->messages;
        }
        return [];
    }

    /**
     * [showUPRChat description]
     *
     * @param  [type]            $senderId [description]
     * @param  ChatRepository    $chats    [description]
     * @param  MessageRepository $messages [description]
     * @return [type]                      [description]
     */
    public function showUPRChat($upr, ChatRepository $chats, MessageRepository $messages)
    {

        $chat       =   $chats->findByUPR($upr);

        $messages->markAsSeen(\Sentinel::getUser()->id);

        if($chat)
        {
            return $chat->messages;
        }
        return [];
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

    /**
     * [getAdminMessageAPI description]
     *
     * @param  Request        $request [description]
     * @param  ChatRepository $chats   [description]
     * @return [type]                  [description]
     */
    public function getAdminMessageAPI(Request $request,  ChatRepository $chats)
    {

        $items   =   $chats->getAllAdmin();
        if(! \Sentinel::getUser()->hasRole('Admin') )
        {
            $items   =   $chats->getMyChats(20, \Sentinel::getUser()->id);
        }
        else
        {
            $items   =   $chats->getAllAdmin();
        }


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
    public function store(Request $request,
        ChatRepository $chats,
        MessageRepository $messages,
        \Revlv\Users\UserRepository $users,
        UnitPurchaseRequestRepository $upr,
        ChatMemberRepository $chatMembers)
    {
        $user   =   \Sentinel::getUser();
        $chat   = null;
        if($request->has("uprId") && $request->get('uprId') != null)
        {
            $chat   =   $chats->findByUPR($request->get('uprId'));

            if($chat == null)
            {
                $uprModel   =   $upr->findById($request->get('uprId'));
                $unt        =   $uprModel->units;
                $unitUsers  =   $users->getAllAdmins();
                $userIds    =   [];

                foreach($unitUsers as $userU)
                {
                    if($userU->hasRole('PCCO Operation') && $userU->unit_id == $unt || $userU->hasRole('BAC Operation') && $userU->unit_id == $unt || $userU->hasRole('Unit Operation') && $userU->unit_id == $unt)
                    {
                        $userIds[] = $userU->id;
                    }
                }

                $userIds[] = $user->id;

                $chat = $chats->save(['sender_id' => $user->id, 'upr_id' => $request->get('uprId'), 'title' => 'UPR-'.$uprModel->upr_number]);

                foreach($userIds as $id)
                {
                    $chatMembers->save(['chat_id' => $chat->id, 'user_id' => $id]);
                }
            }
        }
        else
        {
            if($request->has('chatId') && $request->chatId != null)
            {
                $chat   =   $chats->findById($request->chatId);
            }
            else
            {
                if(!$user->hasRole('Admin'))
                {
                    $chat   =   $chats->findBySender($user->id);
                }
            }

            if($chat == null)
            {
                if(!$user->hasRole('Admin'))
                {
                    $userAdmins =   $users->getAllAdmins();
                    $userIds    =   [];

                    foreach($userAdmins as $admin)
                    {
                        if($admin->hasRole('Admin'))
                        {
                            $userIds[] = $admin->id;
                        }
                    }
                    $userIds[] = $user->id;

                    $chat = $chats->save(['sender_id' => $user->id, 'Title' => 'Admin Inquiry']);

                    foreach($userIds as $id)
                    {
                        $chatMembers->save(['chat_id' => $chat->id, 'user_id' => $id]);
                    }
                }
                else
                {
                    $chat = $chats->save(['sender_id' => $user->id, 'receiver_id' => $request->receiverId]);

                    $chatMembers->save(['chat_id' => $chat->id, 'user_id' => $request->receiverId]);
                    $chatMembers->save(['chat_id' => $chat->id, 'user_id' => $user->id]);
                }
            }
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
            $presence_data = array('id' => $user->id ,'name' => $user->first_name." ".$user->surname);
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
