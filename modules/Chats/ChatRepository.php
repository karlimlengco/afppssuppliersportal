<?php

namespace Revlv\Chats;

use DB;
use Revlv\BaseRepository;
use Illuminate\Database\Eloquent\Model;

class ChatRepository extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return ChatEloquent::class;
    }


    /**
     * [findBySender description]
     *
     * @return [type] [description]
     */
    public function findBySender($sender)
    {
        $model  =   $this->model;
        $model  =   $model->where('sender_id', '=', $sender);

        return $model->first();
    }

    /**
     * [findByReceiver description]
     *
     * @param  [type] $receiver [description]
     * @return [type]           [description]
     */
    public function findByReceiver($receiver)
    {
        $model  =   $this->model;

        $model  =   $model->where('receiver_id', '=', $receiver);

        return $model->first();
    }

    /**
     * [findByUPR description]
     *
     * @param  [type] $receiver [description]
     * @return [type]           [description]
     */
    public function findByUPR($upr)
    {
        $model  =   $this->model;

        $model  =   $model->where('upr_id', '=', $upr);

        return $model->first();
    }


    /**
     * [findBySenderAndReceiver description]
     *
     * @return [type] [description]
     */
    public function findBySenderAndReceiver($sender, $receiver)
    {
        $model  =   $this->model;

        $model  =   $model->where('sender_id', '=', $sender);
        $model  =   $model->where('receiver_id', '=', $receiver);

        return $model->first();
    }

    /**
     * [getAllAdmin description]
     *
     * @return [type] [description]
     */
    public function getAllAdmin($pagintate = 20)
    {
        $model  =   $this->model;

        $model  =   $model->select([
            'chats.title',
            'chats.id',
            'chats.sender_id',
            DB::raw(" (select messages.created_at from messages left join chats as chat on messages.chat_id  = chat.id  limit 1  ) as last_message "),
        ]);

        $model  =   $model->leftJoin('chat_members', 'chat_members.chat_id', '=','chats.id');

        // $model  =   $model->whereNull('receiver_id');
        // $model  =   $model->where('chat_members.user_id', '=', \Sentinel::getUser()->id);

        $model  =   $model->Where(function($query) {
                 $query->where('chat_members.user_id', '=', \Sentinel::getUser()->id);
             });

        $model  =   $model->groupBy([
            'chats.title',
            'chats.id',
            'chats.sender_id',
        ]);
        $model  =   $model->orderBy('last_message');

        return $model->paginate($pagintate);
    }



    /**
     * [getMyChats description]
     *
     * @return [type] [description]
     */
    public function getMyChats($pagintate = 20, $receiver)
    {
        $model  =   $this->model;

        $model  =   $model->select([
            'chats.title',
            'chats.id',
            'chats.sender_id',
        ]);

        $model  =   $model->leftJoin('chat_members', 'chat_members.chat_id', '=','chats.id');

        $model  =   $model->where('receiver_id', '=', $receiver);
        $model  =   $model->where('chat_members.user_id', '=', \Sentinel::getUser()->id);

        $model  =   $model->orWhere(function($query) use ($receiver){
                 $query->whereNull('receiver_id');
                 $query->where('sender_id', '=', $receiver);
            });

        $model  =   $model->orwhere('chat_members.user_id', '=', \Sentinel::getUser()->id);

        $model  =   $model->groupBy([
            'chats.title',
            'chats.id',
            'chats.sender_id',
        ]);

        // dd($receiver);
        return $model->paginate($pagintate);
    }
}
