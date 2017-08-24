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

        if(\Sentinel::getUser()->hasRole('Admin'))
        {

            $model  =   $model->whereNull('receiver_id');
        }
        else
        {
            $model  =   $model->where('sender_id', '=', $user);
        }

        $model  =   $model->where('user_id', '!=', $user);
        $model  =   $model->whereNull('is_seen');

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

        if(\Sentinel::getUser()->hasRole('Admin'))
        {
            $model  =   $model->whereNull('receiver_id');
        }
        else
        {
            $model  =   $model->where('sender_id', '=', $user);
        }

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
