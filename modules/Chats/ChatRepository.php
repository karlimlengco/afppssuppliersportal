<?php

namespace Revlv\Chats;

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
        ]);

        $model  =   $model->leftJoin('chat_members', 'chat_members.chat_id', '=','chats.id');

        $model  =   $model->whereNull('receiver_id');
        $model  =   $model->where('chat_members.user_id', '=', \Sentinel::getUser()->id);

        $model  =   $model->orWhere(function($query) {
                 $query->where('chat_members.user_id', '=', \Sentinel::getUser()->id);
             });



        $model  =   $model->groupBy([
            'chats.title',
            'chats.id',
            'chats.sender_id',
        ]);

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

        $model  =   $model->groupBy([
            'chats.title',
            'chats.id',
            'chats.sender_id',
        ]);

        // dd($receiver);
        return $model->paginate($pagintate);
    }
}
