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
}
