<?php

namespace Revlv\Chats\Message;

use Revlv\BaseRepository;
use Illuminate\Database\Eloquent\Model;

class MessageRepository extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return MessageEloquent::class;
    }

    /**
     * [findByChatId description]
     * @return [type] [description]
     */
    public function findByChatId($chat)
    {
        $model  =   $this->model;

        $model  =   $model->where('chat_id', '=', $chat);

        return $model->get();
    }

    /**
     * [getUnseenByUser description]
     *
     * @param  [type] $user [description]
     * @return [type]       [description]
     */
    public function getUnseenByUser($user)
    {

        $model  =   $this->model;

        $model  =   $model->select([
            'messages.*',
            'chats.sender_id',
            'chats.receiver_id',
        ]);

        $model  =   $model->leftJoin('chats','chats.id', '=', 'messages.chat_id');

        $model  =   $model->leftJoin('chat_members', 'chat_members.chat_id', '=','messages.chat_id');

        if(\Sentinel::getUser()->hasRole('Admin'))
        {


            $model  =   $model->Where(function($query) {
                     $query->where('chat_members.user_id', '=', \Sentinel::getUser()->id);
                 });
        }
        else
        {
            $model  =   $model->where('receiver_id', '=', $user);
            $model  =   $model->where('chat_members.user_id', '=', \Sentinel::getUser()->id);


            $model  =   $model->orWhere(function($query) use ($user){
                     $query->whereNull('receiver_id');
                     $query->where('sender_id', '=', $user);
                 });

            $model  =   $model->orwhere('chat_members.user_id', '=', \Sentinel::getUser()->id);
        }

        $model  =   $model->where('messages.user_id', '!=', $user);
        $model  =   $model->whereNull('is_seen');

        $model->groupBy([
            'messages.id',
            'messages.message',
            'messages.user_id',
            'messages.created_at',
            'messages.updated_at',
            'messages.chat_id',
            'messages.status',
            'messages.seen_by',
            'messages.is_seen',
            'chats.sender_id',
            'chats.receiver_id',
        ]);

        return $model->get();
    }

    /**
     *
     *
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function markAsSeen($user)
    {
        // dd('www');
        $model  =   $this->model;
        $model  =   $model->select([
            'messages.*',
            'chats.sender_id',
            'chats.receiver_id',
            'chats.updated_at as update',
        ]);

        $model  =   $model->leftJoin('chats','chats.id', '=', 'messages.chat_id');

        // if(\Sentinel::getUser()->hasRole('Admin'))
        // {
        //     $model  =   $model->whereNull('receiver_id');
        // }
        // else
        // {
            $model  =   $model->where('sender_id', '=', $user);
        // }

        $model  =   $model->where('user_id', '!=', $user);
        $model  =   $model->whereNull('is_seen');
        $result =   $model->get();

        foreach($result as $data)
        {
            $data->update(['is_seen' => 1]);
        }
        // $model  =   $model->update(['is_seen' => 1]);

        return true;
    }

}
